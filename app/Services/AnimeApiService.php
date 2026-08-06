<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

class AnimeApiService
{
    private string $apiBaseUrl;

    public function __construct()
    {
        $this->apiBaseUrl = rtrim((string) config('anime.api_base_urls.' . config('anime.provider', 'oploverz')), '/');
    }

    /**
     * Single GET request with cache. Oploverz endpoints return full lists,
     * so no page merging is needed.
     */
    private function request(string $endpoint, array $params = [], ?int $cacheTtl = null): array
    {
        $url = $this->apiBaseUrl . '/' . ltrim($endpoint, '/');
        $cacheKey = 'anime_api_' . md5($url . json_encode($params));
        $cacheTtl = $cacheTtl ?? (int) config('anime.cache_ttl', 900);

        $cached = Cache::get($cacheKey);
        if (is_array($cached) && empty($cached['_failed'])) {
            return $cached;
        }

        try {
            $response = Http::timeout((int) config('anime.timeout', 10))
                ->connectTimeout(5)
                ->retry(1, 150)
                ->get($url, $params);

            $response->throw();

            $json = $response->json() ?? [];
            Cache::put($cacheKey, $json, $cacheTtl);

            return $json;
        } catch (Throwable $e) {
            Log::error("Anime API call failed: $endpoint", [
                'params' => $params,
                'error' => $e->getMessage(),
            ]);

            $empty = ['data' => null, 'pagination' => null, '_failed' => true];
            // Brief negative cache only — don't lock out the endpoint for 15 min.
            Cache::put($cacheKey, $empty, 30);

            return $empty;
        }
    }

    /**
     * Normalize an oploverz list item (anime card or episode release)
     * to the application's anime card shape.
     */
    private function mapAnime(array $item): array
    {
        $aired = $item['releasedOn'] ?? $item['releaseTime'] ?? null;
        $href = $item['href'] ?? null;

        return [
            'title' => is_string($item['title'] ?? $item['seriesName'] ?? null)
                ? preg_replace('/\t+/', ' ', trim($item['title'] ?? $item['seriesName']))
                : (is_string($item['seriesName'] ?? null) ? preg_replace('/\t+/', ' ', trim($item['seriesName'])) : 'Tanpa Judul'),
            'poster' => $item['poster'] ?? null,
            'episodes' => (int) preg_replace('/\D/', '', $item['episode'] ?? $item['episodes'] ?? ''),
            'episodeLabel' => $item['episode'] ?? null,
            'animeId' => (string) ($item['slug'] ?? $item['id'] ?? ''),
            'score' => $item['score'] ?? $item['rating'] ?? 'N/A',
            'date' => $aired ? Carbon::parse($aired)->format('d M Y') : '-',
            'latestReleaseDate' => $aired ?? '-',
            'lastReleaseDate' => $item['aired_to'] ?? $item['updatedOn'] ?? '-',
            'releaseDay' => $item['releaseTime'] ?? $item['releaseDay'] ?? '-',
            'hasDate' => ! empty($aired),
            'otakudesuUrl' => $href ?? $item['url'] ?? '#',
            'status' => $item['status'] ?? $item['type'] ?? 'Unknown',
            'type' => $item['type'] ?? null,
        ];
    }

    /**
     * Map a directory (list-mode) item: title/slug/href only, no poster.
     */
    private function mapDirectoryItem(array $item): array
    {
        return [
            'title' => trim($item['title'] ?? 'Tanpa Judul'),
            'poster' => null,
            'episodes' => 0,
            'animeId' => (string) ($item['slug'] ?? ''),
            'score' => 'N/A',
            'date' => '-',
            'latestReleaseDate' => '-',
            'lastReleaseDate' => '-',
            'releaseDay' => '-',
            'hasDate' => false,
            'otakudesuUrl' => $item['href'] ?? '#',
            'status' => 'Unknown',
            'type' => null,
        ];
    }

    private function paginate(array $items, int $page = 1, int $perPage = 30): array
    {
        $perPage = $perPage ?: (int) config('anime.per_page', 30);
        $total = count($items);
        $totalPages = max(1, (int) ceil($total / $perPage));
        $page = max(1, min($page, $totalPages));

        return [
            'items' => array_slice($items, ($page - 1) * $perPage, $perPage),
            'totalItems' => $total,
            'pagination' => [
                'currentPage' => $page,
                'perPage' => $perPage,
                'totalItems' => $total,
                'totalPages' => $totalPages,
                'hasPrevPage' => $page > 1,
                'hasNextPage' => $page < $totalPages,
                'prevPage' => $page > 1 ? $page - 1 : null,
                'nextPage' => $page < $totalPages ? $page + 1 : null,
            ],
        ];
    }

    private function animeListFrom(string $endpoint, array $params, int $page = 1, int $perPage = 30): array
    {
        $response = $this->request($endpoint, $params);
        $items = array_map([$this, 'mapAnime'], $response['data']['animeList'] ?? []);

        return $this->paginate($items, $page, $perPage);
    }

    /**
     * Poster-bearing "banyak data" feed: fetches multiple pages of ongoing +
     * completed from the API (which now passes ?page=N through to oploverz),
     * plus the popular list from home. The page is resolved against the full
     * poster catalog, so every card has a poster.
     */
    public function merged(int $page = 1, int $perPage = 30): array
    {
        $perPage = (int) config('anime.per_page', 30);

        $items = $this->posterCatalog();

        $result = $this->paginate($items, $page, $perPage);
        $result['feed'] = 'merged';

        return $result;
    }

    /**
     * Merge rich fields (episode/status/score/genres) from the home feed into
     * a catalog card so badges show real data instead of the 'HD' fallback.
     */
    private function mapHomeEnrich(array $card, array $home): array
    {
        $cardKey = $this->normalizeKey($card['title']);
        if ($cardKey === '') {
            return $card;
        }

        $hits = [];
        foreach (['popularToday', 'latestRelease'] as $section) {
            foreach ($home['data'][$section]['animeList'] ?? [] as $item) {
                foreach (array_filter([$item['seriesName'] ?? null, $item['title'] ?? null], 'is_string') as $candidate) {
                    // seriesName often holds "<Name>\t<Episode Title>"
                    $series = trim(explode("\t", $candidate)[0]);
                    $key = $this->normalizeKey($series);
                    if ($key === '') {
                        continue;
                    }
                    if ($key === $cardKey || (mb_strlen($key) >= 5 && (str_contains($key, $cardKey) || str_contains($cardKey, $key)))) {
                        $hits[] = $item;
                        break 2;
                    }
                }
            }
        }
        if (empty($hits)) {
            return $card;
        }

        $hit = $hits[0];
        $episodes = (int) preg_replace('/\D/', '', $hit['episode'] ?? $hit['episodes'] ?? '');
        $status = $hit['status'] ?? $card['status'];

        return array_merge($card, [
            'episodes' => $episodes ?: $card['episodes'],
            'episodeLabel' => $hit['episode'] ?? $card['episodeLabel'],
            'status' => $status,
            'score' => $hit['score'] ?? $card['score'],
            'releaseDay' => $hit['releaseTime'] ?? $card['releaseDay'],
            'genres' => $hit['genres'] ?? $card['genres'] ?? [],
        ]);
    }

    /**
     * Lowercase alphanumeric key for fuzzy title matching (e.g.
     * "Grand Blue Season 3" vs "Grand Blue S3 Episode 05 ...").
     */
    private function normalizeKey(string $value): string
    {
        return preg_replace('/[^a-z0-9]+/', '', mb_strtolower($value));
    }

    /**
     * Best catalog card for the hero: match the most recent home release so
     * the banner shows real episode/update data with a working detail link.
     *
     * @param  array<int, array<string, mixed>>  $cards
     * @return array<string, mixed>|null
     */
    public function heroCard(array $cards): ?array
    {
        // Reuses request() cache — no extra HTTP if posterCatalog already ran.
        $home = $this->request('oploverz/home');
        $latest = $home['data']['latestRelease']['animeList'][0] ?? null;
        if (! $latest) {
            return $cards[0] ?? null;
        }

        $base = $this->mapAnime($latest);
        if (is_string($latest['href'] ?? null)) {
            $base['animeId'] = $this->episodeSlugToAnimeSlug(
                basename(rtrim(parse_url($latest['href'], PHP_URL_PATH) ?: $latest['href'], '/'))
            ) ?: $base['animeId'];
        }

        // Prefer a catalog card with a real series slug when titles match.
        $key = $this->normalizeKey(trim(explode("\t", (string) ($latest['seriesName'] ?? $latest['title'] ?? ''))[0]));
        if ($key !== '') {
            foreach ($cards as $c) {
                if ($this->normalizeKey($c['title'] ?? '') === $key) {
                    $base = array_merge($base, [
                        'animeId' => $c['animeId'] ?: $base['animeId'],
                        'poster' => $c['poster'] ?? $base['poster'],
                        'title' => $c['title'] ?? $base['title'],
                    ]);
                    break;
                }
            }
        }

        $episodes = (int) preg_replace('/\D/', '', $latest['episode'] ?? '');

        return array_merge($base, [
            'episodes' => $episodes ?: ($base['episodes'] ?? 0),
            'episodeLabel' => $latest['episode'] ?? $base['episodeLabel'] ?? null,
            'status' => $latest['status'] ?? $base['status'] ?? null,
            'score' => $latest['score'] ?? $base['score'] ?? 'N/A',
            'releaseDay' => $latest['releaseTime'] ?? $base['releaseDay'] ?? null,
            'genres' => $latest['genres'] ?? $base['genres'] ?? [],
            'date' => $latest['releaseTime'] ?? $base['date'] ?? null,
            'title' => trim(explode("\t", $latest['seriesName'] ?? $latest['title'] ?? '')[0]) ?: $base['title'],
        ]);
    }

    /**
     * Fetch pages 1..N of ongoing + completed (poster cards) and the home
     * popular list. Concurrent pool + catalog-level cache so home isn't 80
     * sequential upstream round-trips on every cold request.
     */
    private function posterCatalog(): array
    {
        $pages = max(1, (int) config('anime.catalog_pages', 3));
        $ttl = (int) config('anime.cache_ttl', 900);
        $cacheKey = 'anime_poster_catalog_v2_p' . $pages;

        return Cache::remember($cacheKey, $ttl, function () use ($pages) {
            $home = $this->request('oploverz/home');
            $merged = [];

            foreach ($home['data']['popularToday']['animeList'] ?? [] as $item) {
                $card = $this->mapAnime($item);
                if ($card['animeId'] !== '') {
                    $merged[$card['animeId']] = $card;
                }
            }

            // Build all page URLs first, then fire them in one concurrent pool.
            $jobs = [];
            foreach (['ongoing', 'completed'] as $status) {
                for ($p = 1; $p <= $pages; $p++) {
                    $jobs[] = ['status' => $status, 'page' => $p];
                }
            }

            $base = $this->apiBaseUrl;
            $timeout = (int) config('anime.timeout', 10);

            $responses = Http::pool(function ($pool) use ($jobs, $base, $timeout) {
                foreach ($jobs as $i => $job) {
                    $pool->as((string) $i)
                        ->timeout($timeout)
                        ->connectTimeout(5)
                        ->get($base . '/oploverz/anime', [
                            'status' => $job['status'],
                            'page' => $job['page'],
                        ]);
                }
            });

            foreach ($responses as $i => $response) {
                try {
                    if (! $response || ! method_exists($response, 'successful') || ! $response->successful()) {
                        continue;
                    }
                    $json = $response->json() ?? [];
                    // Seed per-page cache so ongoing()/complete() reuse the same data.
                    $job = $jobs[(int) $i];
                    $pageUrl = $base . '/oploverz/anime';
                    $pageKey = 'anime_api_' . md5($pageUrl . json_encode([
                        'status' => $job['status'],
                        'page' => $job['page'],
                    ]));
                    Cache::put($pageKey, $json, (int) config('anime.cache_ttl', 900));

                    foreach ($json['data']['animeList'] ?? [] as $item) {
                        $card = $this->mapAnime($item);
                        if ($card['animeId'] !== '') {
                            $merged[$card['animeId']] = $card;
                        }
                    }
                } catch (Throwable $e) {
                    // Skip bad page; keep whatever we already have.
                }
            }

            $enriched = array_map(fn ($card) => $this->mapHomeEnrich($card, $home), $merged);

            return array_values($enriched);
        });
    }

    /**
     * "https://oploverz.am/one-piece-episode-1172-subtitle-indonesia/"
     * -> "one-piece"
     */
    private function episodeSlugToAnimeSlug(string $episodeSlug): string
    {
        return preg_replace('/-episode-\d+.*$/', '', $episodeSlug);
    }

    public function ongoing(int $page = 1): array
    {
        $result = $this->animeListFrom('oploverz/anime', ['status' => 'ongoing'], $page, (int) config('anime.per_page', 30));
        $result['feed'] = 'ongoing';

        return $result;
    }

    public function complete(int $page = 1): array
    {
        $result = $this->animeListFrom('oploverz/anime', ['status' => 'completed'], $page, (int) config('anime.per_page', 30));
        $result['feed'] = 'completed';

        return $result;
    }

    public function search(string $query, int $page = 1): array
    {
        $result = $this->animeListFrom('oploverz/search', ['q' => $query], $page, (int) config('anime.per_page', 30));

        return $result + ['query' => $query];
    }

    public function searchSuggestions(string $query): array
    {
        $response = $this->request('oploverz/search', ['q' => $query]);
        $items = $response['data']['animeList'] ?? [];

        return array_map(fn ($item) => [
            'title' => $item['title'] ?? 'N/A',
            'animeId' => (string) ($item['slug'] ?? ''),
        ], array_slice($items, 0, 5));
    }

    public function details(string $animeId): array
    {
        $response = $this->request('oploverz/anime/' . $animeId);
        $anime = $response['data']['details'] ?? [];

        if (empty($anime)) {
            return [];
        }

        $synopsis = implode("\n\n", $anime['synopsis']['paragraphList'] ?? []);

        return [
            'animeId' => (string) ($anime['slug'] ?? $animeId),
            'title' => is_string($anime['title'] ?? null) ? preg_replace('/\t+/', ' ', trim($anime['title'])) : 'Tanpa Judul',
            'poster' => $anime['poster'] ?? null,
            'banner' => $anime['banner'] ?? $anime['poster'] ?? null,
            'synopsis' => $synopsis ?: 'Sinopsis tidak tersedia.',
            'episodes' => count($anime['episodeList'] ?? []),
            'score' => $anime['rating'] ?? 'N/A',
            'status' => $anime['status'] ?? 'Unknown',
            'genres' => $anime['genres'] ?? [],
            'studios' => $anime['studio'] ? [$anime['studio']] : [],
            'airedFrom' => $anime['releasedOn'] ?? null,
            'airedTo' => $anime['updatedOn'] ?? null,
            'trailer' => null,
            'url' => $anime['seriesHref'] ?? '#',
            'date' => isset($anime['releasedOn'])
                ? Carbon::parse($anime['releasedOn'])->format('d M Y')
                : '-',
            'latestReleaseDate' => $anime['releasedOn'] ?? '-',
            'lastReleaseDate' => $anime['updatedOn'] ?? '-',
            'releaseDay' => '-',
            'hasDate' => ! empty($anime['releasedOn']),
            'otakudesuUrl' => $anime['seriesHref'] ?? '#',
            'episodeList' => array_map(fn ($ep) => [
                'episodeId' => basename(rtrim($ep['href'] ?? '', '/')),
                'episode' => $ep['episode'] ?? '',
                'title' => $ep['title'] ?? '',
                'date' => $ep['date'] ?? '',
                'href' => $ep['href'] ?? '#',
            ], $anime['episodeList'] ?? []),
        ];
    }

    public function episode(string $animeId, string $episodeId, string $provider = null): array
    {
        $response = $this->request('oploverz/episode/' . $episodeId);
        $d = $response['data']['details'] ?? [];

        if (empty($d)) {
            return [];
        }

        $prev = $d['prevEpisode'] ?? null;
        $next = $d['nextEpisode'] ?? null;

        // Oploverz "download" is [{title, qualityList:[{title, urlList:[{title,url}]}]}]
        $downloads = [];
        foreach ($d['download'] ?? [] as $group) {
            foreach ($group['qualityList'] ?? [] as $q) {
                $downloads[] = [
                    'title' => ($group['title'] ?? '') . ' ' . ($q['title'] ?? ''),
                    'size' => null,
                    'urlList' => $q['urlList'] ?? [],
                ];
            }
        }

        return [
            'title' => $d['title'] ?? 'Episode Anime',
            'releaseTime' => $d['releasedOn'] ?? null,
            'defaultStreamingUrl' => $d['streamingUrl'] ?? null,
            'hasPrevEpisode' => (bool) ($d['hasPrevEpisode'] ?? ! empty($prev)),
            'prevEpisode' => $prev ? basename(rtrim($prev['href'] ?? '', '/')) : null,
            'hasNextEpisode' => (bool) ($d['hasNextEpisode'] ?? ! empty($next)),
            'nextEpisode' => $next ? basename(rtrim($next['href'] ?? '', '/')) : null,
            'seriesName' => $d['seriesName'] ?? null,
            'seriesSlug' => $d['seriesSlug'] ?? $animeId,
            'seriesHref' => $d['seriesHref'] ?? null,
            'server' => [],
            'download' => $downloads,
            'info' => [
                'Episode' => $d['episodeNumber'] ?? null,
                'Rilis' => $d['releasedOn'] ?? null,
            ],
        ];
    }

    public function serverUrl(string $url): string
    {
        // No mirror lookup available; the streaming URL is passed directly.
        return $url;
    }

    public function genre(string $genre, int $page = 1): array
    {
        $result = $this->animeListFrom('oploverz/anime', ['genre' => $genre], $page, (int) config('anime.per_page', 30));
        $result['feed'] = 'genre';

        return $result;
    }

    public function countryName(string $countryCode): string
    {
        $map = [
            'JP' => 'Jepang', 'US' => 'Amerika', 'CN' => 'China',
            'KR' => 'Korea', 'GB' => 'Inggris', 'AU' => 'Australia',
            'TW' => 'Taiwan', 'TH' => 'Thailand', 'VN' => 'Vietnam', 'ID' => 'Indonesia',
        ];

        return $map[$countryCode] ?? $countryCode;
    }

    public function animesByYear(string $year, int $page = 1): array
    {
        // The oploverz API filters by season code (e.g. "summer-2026"),
        // not by a bare year, so merge the year's four seasons.
        $items = [];
        foreach (['winter', 'spring', 'summer', 'fall'] as $season) {
            $response = $this->request('oploverz/anime', ['season' => $season . '-' . $year]);
            foreach ($response['data']['animeList'] ?? [] as $item) {
                $card = $this->mapAnime($item);
                if ($card['animeId'] !== '') {
                    $items[$card['animeId']] = $card;
                }
            }
        }

        $result = $this->paginate(array_values($items), $page, (int) config('anime.per_page', 30));
        $result['feed'] = 'by_year';

        return $result;
    }

    public function animesByProperty(string $property, string $propertyId, int $page = 1): array
    {
        $result = $this->animeListFrom('oploverz/anime', [$property => $propertyId], $page, (int) config('anime.per_page', 30));
        $result['feed'] = 'by_property';

        return $result;
    }

    public function getAllGenres(): array
    {
        $response = $this->request('oploverz/home');
        $releases = $response['data']['latestRelease']['animeList'] ?? [];

        // Collect unique genre names across the home feed.
        $genres = [];
        foreach ($releases as $item) {
            foreach ($item['genres'] ?? [] as $g) {
                $genres[$g] = [
                    'title' => $g,
                    'genreId' => strtolower($g),
                ];
            }
        }

        return array_values($genres);
    }

    /**
     * Property options for the navbar (Tahun / Negara dropdowns).
     *
     * The oploverz API has no dedicated properties endpoint, so values are
     * derived from real data: seasons/years come from anime detail pages
     * ("Fall 1999" => 1999) and countries are a curated static list because
     * the API does not expose a country field at all.
     */
    public function getProperties(string $type): array
    {
        if ($type === 'country') {
            return array_map(fn ($code) => [
                'title' => $this->countryName($code),
                'propertyId' => $code,
            ], ['JP', 'US', 'CN', 'KR', 'GB', 'AU', 'TW', 'TH', 'VN', 'ID']);
        }

        if ($type === 'season') {
            // No cheap API source for years; derive a reasonable static list
            // (current year back to 1999). Keeps the navbar instant.
            $years = [];
            for ($y = (int) date('Y'); $y >= 1999; $y--) {
                $years[] = [ 'title' => (string) $y, 'propertyId' => (string) $y ];
            }

            return $years;
        }

        return [];
    }

    public function flush(): void
    {
        Cache::flush();
        Log::info('Anime API cache flushed.');
    }
}
