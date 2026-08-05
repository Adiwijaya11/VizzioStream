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
            $yearsData = [];
            $countriesData = [];

            try {
                $genresData = Cache::remember('navbar_genres', now()->addMinutes(30), function () use ($animeService) {
                    $response = $animeService->getAllGenres();
                    return collect($response ?? [])->map(function ($genre) {
                        return [
                            'title' => $genre['title'],
                            'slug' => $genre['genreId'],
                        ];
                    })->toArray();
                });

                $ongoingData = Cache::remember('navbar_ongoing', now()->addMinutes(30), function () use ($animeService) {
                    $response = $animeService->ongoing(1);
                    return collect($response['items'] ?? [])->map(function ($anime) {
                        return [
                            'title' => $anime['title'],
                            'slug' => $anime['animeId'],
                        ];
                    })->take(5)->toArray();
                });

                $completedData = Cache::remember('navbar_completed', now()->addMinutes(30), function () use ($animeService) {
                    $response = $animeService->complete(1);
                    return collect($response['items'] ?? [])->map(function ($anime) {
                        return [
                            'title' => $anime['title'],
                            'slug' => $anime['animeId'],
                        ];
                    })->take(5)->toArray();
                });

                // Real year data derived from kuramanime season properties
                // (e.g. "Summer 2026" => 2026), sorted descending.
                $yearsData = Cache::remember('navbar_years', now()->addMinutes(60), function () use ($animeService) {
                    $seasons = $animeService->getProperties('season');

                    return collect($seasons ?? [])
                        ->map(function ($season) {
                            $year = (int) preg_replace('/\D+/', '', $season['title'] ?? '');

                            return $year > 0 ? $year : null;
                        })
                        ->filter()
                        ->unique()
                        ->sortDesc()
                        ->values()
                        ->map(fn ($year) => ['title' => (string) $year, 'slug' => (string) $year])
                        ->toArray();
                });

                // Real country data from kuramanime country properties (JP).
                $countriesData = Cache::remember('navbar_countries', now()->addMinutes(60), function () use ($animeService) {
                    $countries = $animeService->getProperties('country');
                    $countryNames = [
                        'JP' => 'Jepang',
                        'AU' => 'Australia',
                        'CN' => 'China',
                        'ID' => 'Indonesia',
                        'KR' => 'Korea Selatan',
                        'US' => 'Amerika Serikat',
                        'GB' => 'Inggris',
                        'TW' => 'Taiwan',
                        'TH' => 'Thailand',
                        'VN' => 'Vietnam',
                    ];

                    return collect($countries ?? [])->map(function ($country) use ($countryNames) {
                        $title = $country['title'] ?? '';

                        return [
                            'title' => $countryNames[$title] ?? $title,
                            'slug'  => $country['propertyId'] ?? $title,
                        ];
                    })->toArray();
                });
            } catch (Throwable $e) {
                // Log the error or handle it as needed
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
