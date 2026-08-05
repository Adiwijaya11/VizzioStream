<?php

use App\Http\Controllers\AnimeController;
use App\Http\Controllers\WelcomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', [WelcomeController::class, 'index'])->name('welcome');
Route::get('/search', [WelcomeController::class, 'search'])->name('search');
Route::get('/api/search/suggestions', [WelcomeController::class, 'searchSuggestions'])->name('api.search.suggestions');
Route::get('/genres', [AnimeController::class, 'genres'])->name('genre.index');

// Public anime browsing (wajik-anime-api local server)
Route::get('/anime', [AnimeController::class, 'index'])->name('anime.index');
Route::get('/anime/type/{type}', [AnimeController::class, 'feed'])->name('anime.feed');
Route::get('/anime/{animeId}', [WelcomeController::class, 'show'])->name('anime.show');
Route::get('/anime/{animeId}/episode/{episodeId}', [WelcomeController::class, 'episode'])->name('anime.episode');
Route::get('/genre/{genreId}', [AnimeController::class, 'showByGenre'])->name('genre.show');
Route::get('/property/{type}/{propertyId}', [AnimeController::class, 'showByProperty'])->name('property.show');

// Actions
Route::get('/api/server/{serverId}', [WelcomeController::class, 'serverUrl'])->name('api.server.url');
Route::post('/anime/{animeId}/favorite', [AnimeController::class, 'favorite'])->name('anime.favorite');
Route::post('/anime/refresh', [AnimeController::class, 'refresh'])->name('anime.refresh');
Route::get('/favorites', [AnimeController::class, 'favorites'])->name('anime.favorites');
Route::delete('/favorites/clear', [AnimeController::class, 'favoritesClear'])->name('anime.favorites.clear');
