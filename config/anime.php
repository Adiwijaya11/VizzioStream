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

    'per_page' => env('ANIME_API_PER_PAGE', 30),

    /* Number of API pages to fetch per status for the poster catalog */

    'catalog_pages' => env('ANIME_CATALOG_PAGES', 40),
];
