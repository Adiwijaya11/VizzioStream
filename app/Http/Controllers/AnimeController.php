<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Services\AnimeApiService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Throwable;

class AnimeController extends Controller
{
    public function __construct(protected AnimeApiService $anime)
    {
        //
    }

    /**
     * Show the anime browsing page (Ongoing feed by default).
     */
    public function index(): View
    {
        return $this->renderFeed('ongoing', 1);
    }

    /**
     * Show a paginated feed for a given type (ongoing | complete).
     */
    public function feed(Request $request, string $type): View
    {
        $type = in_array($type, ['ongoing', 'complete'], true) ? $type : 'ongoing';

        $page = max(1, (int) $request->query('page', 1));

        return $this->renderFeed($type, $page);
    }

    /**
     * Force a cache eviction for the anime feeds and return to the index.
     */
    public function refresh(): RedirectResponse
    {
        $this->anime->flush();

        return back()->with('status', 'Cache anime telah diperbarui.');
    }

    /**
     * Show anime by genre.
     */
    public function showByGenre(string $genreId): View
    {
        $currentPage = Paginator::resolveCurrentPage('page');
        $animePerPage = 30;

        $response = [];
        $error = null;

        try {
            $response = $this->anime->genre($genreId, $currentPage);
        } catch (Throwable $e) {
            Log::error("Failed to fetch anime by genre in AnimeController@showByGenre: {$e->getMessage()}");
            $error = 'Sedang ada kendala menghubungi server anime. Silakan coba lagi nanti.';
        }

        $animeData = $response['items'] ?? [];
        $totalItems = $response['totalItems'] ?? 0;
        $perPage = config('anime.per_page', 30); // Use config for per_page

        $paginator = new LengthAwarePaginator(
            $animeData,
            $totalItems,
            $perPage,
            $currentPage,
            ['path' => route('anime.genre.show', ['genreId' => $genreId])]
        );

        return view('anime.genre_show', compact('animeData', 'paginator', 'genreId', 'error'));
    }

    /**
     * Show anime filtered by a kuramanime property (e.g. year, country, studio).
     *
     * @param  string  $type  one of "year" | "country" | "studio" | "season"
     */
    public function showByProperty(string $type, string $propertyId): View
    {
        $propertyTypes = ['year', 'country', 'studio', 'season'];
        $type = in_array($type, $propertyTypes, true) ? $type : 'season';

        $currentPage = Paginator::resolveCurrentPage('page');
        $animePerPage = 30;

        $error = null;
        $animeData = [];
        $totalItems = 0;
        $perPage = $animePerPage;

        try {
            if ($type === 'country') {
                // The oploverz API does not support country filtering.
                // Fall back to the full merged catalog so the page works.
                $response = $this->anime->merged($currentPage);
            } elseif ($type === 'year') {
                $response = $this->anime->animesByYear((int) $propertyId, $currentPage);
            } else {
                $response = $this->anime->animesByProperty($type, $propertyId, $currentPage);
            }
        } catch (Throwable $e) {
            Log::error("Failed to fetch anime by property in AnimeController@showByProperty: {$e->getMessage()}");
            $error = 'Sedang ada kendala menghubungi server anime. Silakan coba lagi nanti.';
        }
        $animeData = $response['items'] ?? [];
        $totalItems = $response['totalItems'] ?? 0;
        $perPage = config('anime.per_page', 30); // Use config for per_page
        $paginator = new LengthAwarePaginator(
            $animeData,
            $totalItems,
            $perPage,
            $currentPage,
            ['path' => route('property.show', ['type' => $type, 'propertyId' => $propertyId])]
        );

        $title = $type === 'country'
            ? $this->anime->countryName(strtoupper($propertyId))
            : $propertyId;

        return view('anime.property_show', compact('animeData', 'paginator', 'type', 'propertyId', 'title', 'error'));
    }

    /**
     * Show all available genres.
     */
    public function genres(): View
    {
        $genres = [];
        $error  = null;

        try {
            $genres = $this->anime->getAllGenres();
        } catch (Throwable $e) {
            $error = 'Sedang ada kendala menghubungi server anime. Silakan coba lagi nanti.';
        }

        return view('anime.genres', compact('genres', 'error'));
    }

    /**
     * Build the Anime index view for one feed + its pagination state.
     */
    protected function renderFeed(string $feed, int $page): View
    {
        $error = null;
        $items = [];
        $pagination = [
            'currentPage' => $page,
            'hasPrevPage' => false,
            'hasNextPage' => false,
            'prevPage'    => null,
            'nextPage'    => null,
            'totalPages'  => 1,
        ];

        try {
            // $feed is either 'ongoing' or 'complete' from the guard in feed()/index().
            $data = $feed === 'complete'
                ? $this->anime->complete($page)
                : $this->anime->ongoing($page);

            $items = $data['items'] ?? [];
            $pagination = $data['pagination'] ?? $this->buildFallbackPagination($page);
        } catch (Throwable $e) {
            $error = 'Sedang ada kendala menghubungi server anime. Silakan coba lagi nanti, atau periksa apakah server wajik-anime-api aktif di port 3001.';
        }

        $pagination['page'] = $page;

        // Build the query strings used by Prev/Next/links while preserving the type.
        $pagination['prevUrl'] = $pagination['hasPrevPage']
            ? route('anime.feed', ['type' => $feed, 'page' => $pagination['prevPage'] ?: 1])
            : null;

        $pagination['nextUrl'] = $pagination['hasNextPage']
            ? route('anime.feed', ['type' => $feed, 'page' => $pagination['nextPage']])
            : null;

        // Simple numbered links window around the current page.
        $pagination['pages'] = $this->paginationLinks(
            (int) $pagination['currentPage'],
            (int) $pagination['totalPages'],
            $feed
        );

        return view('anime.index', [
            'feed'       => $feed,
            'items'      => $items,
            'error'      => $error,
            'pagination' => $pagination,
        ]);
    }

    /**
     * Build a small array of numbered page links to render.
     *
     * @return array<int, array{page: int, url: string, current: bool}>
     */
    protected function paginationLinks(int $current, int $total, string $feed): array
    {
        $links = [];

        $start = max(1, $current - 2);
        $end   = min($total, $current + 2);

        for ($i = $start; $i <= $end; $i++) {
            $links[] = [
                'page'    => $i,
                'url'     => route('anime.feed', ['type' => $feed, 'page' => $i]),
                'current' => $i === $current,
            ];
        }

        return $links;
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

    /**
     * Show the user's session-based favorites list.
     */
    public function favorites(): View
    {
        $favorites = array_values(session('favorites', []));
        return view('anime.favorites', compact('favorites'));
    }

    /**
     * Clear all session favorites.
     */
    public function favoritesClear(): RedirectResponse
    {
        session()->forget('favorites');
        return redirect()->route('anime.favorites')->with('status', 'Semua favorit telah dihapus.');
    }

    /**
     * Add or remove an anime from the user's favorites (session-based, no auth required).
     */
    public function favorite(Request $request, string $animeId): RedirectResponse
    {
        $favorites = session('favorites', []);

        if (isset($favorites[$animeId])) {
            unset($favorites[$animeId]);
            $message = 'Anime telah dihapus dari daftar favorit.';
        } else {
            $favorites[$animeId] = [
                'anime_id' => $animeId,
                'title'    => $request->input('title'),
                'poster'   => $request->input('poster'),
            ];
            $message = 'Anime telah ditambahkan ke daftar favorit.';
        }

        session(['favorites' => $favorites]);

        return back()->with('status', $message);
    }
}
