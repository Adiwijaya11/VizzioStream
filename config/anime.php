<?php

return [
    'api_base_urls' => [
        'otakudesu' => env('ANIME_API_OTAKUDESU_BASE_URL'),
        'kuramanime' => env('ANIME_API_KURAMANIME_BASE_URL'),
        'oploverz' => env(
            'ANIME_API_OPLOVERZ_BASE_URL',
            'https://wajik-anime-api-red.vercel.app'
        ),
    ],

    'provider' => env('ANIME_PROVIDER', 'oploverz'),

    /* Section per-page items */
    'per_page' => (int) env('ANIME_API_PER_PAGE', 30),

    /* API pages per status for home catalog (each page ~8–10 titles).
       Keep small: every cold request multiplies into N×2 outbound HTTP calls. */
    'catalog_pages' => (int) env('ANIME_CATALOG_PAGES', 3),

    /* Seconds to keep raw API + derived catalog in cache */
    'cache_ttl' => (int) env('ANIME_CACHE_TTL', 900),

    /* Upstream HTTP timeout (seconds). Fail fast on Vercel cold starts. */
    'timeout' => (int) env('ANIME_API_TIMEOUT', 10),
];
