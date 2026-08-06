<?php

namespace App\Providers;

use App\Services\AnimeApiService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Throwable;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Behind Vercel TLS, force https so @vite/asset/route never emit http://
        // (browser Mixed Content blocks CSS/JS and breaks the whole UI).
        if (! $this->app->environment('local')) {
            URL::forceScheme('https');
        }

        View::composer('layouts.navbar', function ($view) {
            /** @var \App\Services\AnimeApiService $animeService */
            $animeService = app(AnimeApiService::class);

            $genresData = [];
            $ongoingData = [];
            $completedData = [];

            // Years + countries are pure static — zero HTTP on every page.
            $yearsData = Cache::remember('navbar_years_static', now()->addDay(), function () {
                $years = [];
                for ($y = (int) date('Y'); $y >= 2015; $y--) {
                    $years[] = ['title' => (string) $y, 'slug' => (string) $y];
                }

                return $years;
            });

            $countriesData = Cache::remember('navbar_countries_static', now()->addDay(), function () use ($animeService) {
                return collect(['JP', 'US', 'CN', 'KR', 'GB', 'AU', 'TW', 'TH', 'VN', 'ID'])
                    ->map(fn ($code) => [
                        'title' => $animeService->countryName($code),
                        'slug' => $code,
                    ])
                    ->toArray();
            });

            try {
                // Genres + series previews share the already-cached home/list endpoints.
                $genresData = Cache::remember('navbar_genres', now()->addMinutes(60), function () use ($animeService) {
                    return collect($animeService->getAllGenres() ?? [])->map(fn ($genre) => [
                        'title' => $genre['title'],
                        'slug' => $genre['genreId'],
                    ])->toArray();
                });

                $ongoingData = Cache::remember('navbar_ongoing', now()->addMinutes(30), function () use ($animeService) {
                    return collect($animeService->ongoing(1)['items'] ?? [])
                        ->take(3)
                        ->map(fn ($anime) => [
                            'title' => $anime['title'],
                            'slug' => $anime['animeId'],
                        ])
                        ->toArray();
                });

                $completedData = Cache::remember('navbar_completed', now()->addMinutes(30), function () use ($animeService) {
                    return collect($animeService->complete(1)['items'] ?? [])
                        ->take(3)
                        ->map(fn ($anime) => [
                            'title' => $anime['title'],
                            'slug' => $anime['animeId'],
                        ])
                        ->toArray();
                });
            } catch (Throwable $e) {
                report($e);
            }

            $view->with('navbarGenres', $genresData);
            $view->with('navbarOngoing', $ongoingData);
            $view->with('navbarCompleted', $completedData);
            $view->with('navbarYears', $yearsData);
            $view->with('navbarCountries', $countriesData);
        });
    }
}
