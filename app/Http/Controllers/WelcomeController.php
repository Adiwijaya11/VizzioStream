<?php

namespace App\Http\Controllers;

use App\Services\AnimeApiService;
use App\Models\Favorite;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Throwable;

class WelcomeController extends Controller
{
    public function __construct(protected AnimeApiService $anime)
    {
        //
    }

    /**
     * Show the landing page with the latest ongoing anime from the local API.
     */
    public function index(Request $request): View
    {
        $items = [];
        $featured = null;
        $error = null;
        $sectionItems = [];
        $sectionPagination = [];
        $sectionTotal = 0;

        $page = max(1, (int) $request->query('page', 1));

        try {
            // One catalog load covers list + hero (cached). Avoid a second
            // ongoing() round-trip that doubles cold-start latency.
            $section = $this->anime->merged(
                $page,
                (int) config('anime.per_page', 30)
            );
            $sectionItems = $section['items'] ?? [];
            $sectionPagination = $section['pagination'] ?? $this->buildFallbackPagination($page);
            $sectionTotal = (int) ($section['totalItems'] ?? 0);

            // Top strip reuses the same page of cards (already enriched).
            $items = array_slice($sectionItems, 0, 12);

            $featured = $this->buildFeatured(
                $this->anime->heroCard($sectionItems) ?? ($sectionItems[0] ?? null)
            );
        } catch (Throwable $e) {
            Log::error("Failed to fetch anime data in WelcomeController@index: {$e->getMessage()}");
            $error = 'Sedang ada kendala menghubungi server anime. Silakan coba lagi nanti.';
            // Provide a safe fallback for library to avoid blank pages
            $sectionItems = [];
            $sectionPagination = $this->buildFallbackPagination($page);
            $sectionTotal = 0;
        }

        return view('welcome', [
            'items' => $items,
            'featured' => $featured,
            'error' => $error,
            'sectionItems' => $sectionItems,
            'sectionPagination' => $sectionPagination,
            'sectionTotal' => $sectionTotal,
        ]);
    }

    /**
     * Show the search results page for the navbar search box.
     */
    public function search(Request $request): View
    {
        $q = trim((string) $request->query('q', ''));

        if ($q === '') {
            return view('search', [
                'query'   => '',
                'items'   => [],
                'total'   => 0,
                'error'   => null,
            ]);
        }

        $items = [];
        $total = 0;
        $error = null;

        try {
            $data = $this->anime->search($q, $request->query('page', 1));
            $items = $data['items'] ?? [];
            $total = count($items); // Jikan API pagination provides total results, but for now we count items per page
        } catch (Throwable $e) {
            Log::error("Failed to search anime in WelcomeController@search: {$e->getMessage()}");
            $error = 'Pencarian sedang tidak tersedia. Silakan coba lagi nanti.';
        }

        return view('search', [
            'query' => $q,
            'items' => $items,
            'total' => $total,
            'error' => $error,
        ]);
    }

    public function searchSuggestions(Request $request)
    {
        $q = trim((string) $request->query('q', ''));
        $suggestions = [];

        if (mb_strlen($q) < 2) { // Minimal 2 karakter untuk saran
            return response()->json([]);
        }

        try {
            $data = $this->anime->searchSuggestions($q);
            // Ambil judul + link detail dari hasil pencarian API
            $suggestions = collect($data ?? [])->map(function ($anime) {
                return [
                    'title' => $anime['title'] ?? '',
                    'link'  => route('anime.show', ['animeId' => $anime['animeId'] ?: Str::slug($anime['title'] ?? '')]),
                ];
            })->reject(fn ($s) => $s['title'] === '')->values()->toArray();
        } catch (Throwable $e) {
            Log::error("Failed to fetch search suggestions in WelcomeController@searchSuggestions: {$e->getMessage()}");
        }

        return response()->json($suggestions);
    }
    /**
     * Show the anime detail page (synopsis, meta, and episode list).
     */
    public function show(Request $request, string $animeId): View
    {
        $error = null;
        $anime = null;
        $episodes = [];
        $isFavorited = false;
        $provider = in_array($request->query('provider'), ['otakudesu', 'kuramanime', 'oploverz'], true)
            ? $request->query('provider')
            : config('anime.provider', 'oploverz');

        try {
            $anime = $this->anime->details($animeId); // Removed provider as Jikan is direct
            $episodes = $anime['episodeList'] ?? []; // Jikan does not directly provide episode list in details
            $isFavorited = isset(session('favorites', [])[$animeId]);
        } catch (Throwable $e) {
            Log::error("Failed to fetch anime details in WelcomeController@show for animeId: {$animeId}, error: {$e->getMessage()}");
            $error = 'Anime tidak ditemukan atau server anime sedang bermasalah. Silakan coba lagi nanti.';
        }

        return view('anime.show', [
            'anime'       => $anime,
            'animeId'     => $animeId,
            'episodes'    => $episodes,
            'error'       => $error,
            'isFavorited' => $isFavorited,
            'provider'    => $provider,
        ]);
    }

    /**
     * Show the embedded player for a single episode.
     */
    public function episode(Request $request, string $animeId, string $episodeId): View
    {
        $error = null;
        $episode = [];
        $provider = in_array($request->query('provider'), ['otakudesu', 'kuramanime', 'oploverz'], true)
            ? $request->query('provider')
            : config('anime.provider', 'oploverz');

        try {
            $episode = $this->anime->episode($animeId, $episodeId); // Removed provider as it's not applicable
        } catch (Throwable $e) {
            Log::error("Failed to fetch episode details in WelcomeController@episode for animeId: {$animeId}, episodeId: {$episodeId}, error: {$e->getMessage()}");
            $error = 'Episode tidak ditemukan atau sedang bermasalah. Silakan coba episode lain.';
        }

        return view('anime.episode', [
            'animeId'   => $animeId,
            'episode'   => $episode,
            'episodeId' => $episodeId,
            'error'     => $error,
            'provider'  => $provider,
        ]);
    }

    /**
     * JSON endpoint to resolve a mirror server ID to an iframe URL.
     */
    public function serverUrl(string $serverId): \Illuminate\Http\JsonResponse
    {
        $url = $this->anime->serverUrl($serverId);

        return response()->json([
            'success' => (bool) $url && $url !== '#',
            'url'     => $url,
        ]);
    }

    /**
     * Map one raw API item onto the object the hero section expects.
     *
     * @param  array<string, mixed>|null  $item
     */
    protected function buildFeatured(?array $item): ?object
    {
        if (! $item) {
            return null;
        }

        return (object) [
            'title'       => $item['title'] ?? 'Anime Terbaru',
            'poster'      => $item['poster'] ?? null,
            'episodes'    => (int) ($item['episodes'] ?? 0),
            'episodeLabel' => $item['episodeLabel'] ?? null,
            'status'      => $item['status'] ?? null,
            'score'       => $item['score'] ?? null,
            'genres'      => $item['genres'] ?? [],
            'date'        => $item['date'] ?? $item['latestReleaseDate'] ?? null,
            'releaseDay'  => $item['releaseDay'] ?? null,
            'slug'        => $item['animeId'] ?? null, // Use animeId as slug
            'url'         => $item['otakudesuUrl'] ?? null, // Use otakudesuUrl as the external URL
            'description' => sprintf(
                'Episode %s telah tayang! Anime ini baru saja mengupdate episode terbarunya pada %s. Klik tombol di bawah untuk langsung menuju halaman detail dan menonton episode terbarunya.',
                $item['episodeLabel'] ?? ($item['episodes'] ?? 'terbaru'),
                $item['date'] ?? $item['latestReleaseDate'] ?? 'hari ini'
            ),
        ];
    }

    protected function buildFallbackPagination(int $page = 1): array
    {
        return [
            'currentPage' => $page,
            'prevPage' => null,
            'hasPrevPage' => false,
            'nextPage' => null,
            'hasNextPage' => false,
            'totalPages' => 0,
        ];
    }
}
