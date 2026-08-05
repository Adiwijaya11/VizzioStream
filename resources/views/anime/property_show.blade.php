@extends('layouts.app')

@php
    $typeIcon = match($type) {
        'year'    => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>',
        'country' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21l1.65-3.8a9 9 0 1 1 3.4 2.9L3 21"/><circle cx="12" cy="11" r="3" stroke="currentColor" stroke-width="2"/>',
        'studio'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>',
        'season'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707m12.728 0l-.707-.707M6.343 6.343l-.707-.707M12 8a4 4 0 100 8 4 4 0 000-8z"/>',
        default   => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V7a4 4 0 014-4z"/>',
    };

    $typeLabel = match($type) {
        'year'    => 'Tahun Rilis',
        'country' => 'Negara Asal',
        'studio'  => 'Studio Animasi',
        'season'  => 'Season',
        default   => ucfirst($type),
    };

    $typeAccent = match($type) {
        'year'    => 'from-amber-400 to-orange-500',
        'country' => 'from-emerald-400 to-teal-500',
        'studio'  => 'from-violet-400 to-purple-500',
        'season'  => 'from-sky-400 to-blue-500',
        default   => 'from-sky-400 to-blue-500',
    };

    $typeBorder = match($type) {
        'year'    => 'border-amber-500/40',
        'country' => 'border-emerald-500/40',
        'studio'  => 'border-violet-500/40',
        'season'  => 'border-sky-500/40',
        default   => 'border-sky-500/40',
    };

    $typeText = match($type) {
        'year'    => 'text-amber-400',
        'country' => 'text-emerald-400',
        'studio'  => 'text-violet-400',
        'season'  => 'text-sky-400',
        default   => 'text-sky-400',
    };

    $typeBg = match($type) {
        'year'    => 'bg-amber-500/10',
        'country' => 'bg-emerald-500/10',
        'studio'  => 'bg-violet-500/10',
        'season'  => 'bg-sky-500/10',
        default   => 'bg-sky-500/10',
    };

    $typeGlow = match($type) {
        'year'    => 'bg-amber-600/8',
        'country' => 'bg-emerald-600/8',
        'studio'  => 'bg-violet-600/8',
        'season'  => 'bg-sky-600/8',
        default   => 'bg-sky-600/8',
    };

    $totalAnime  = count($animeData);
    $currentPage = $paginator->currentPage();
    $lastPage    = $paginator->lastPage();
@endphp

@section('title', $title . ' – ' . $typeLabel . ' | VizzioStream')

@section('content')
<div class="min-h-screen bg-[#090D16] relative overflow-hidden">

    {{-- Dynamic ambient glow that matches the property type --}}
    <div class="fixed top-16 left-1/3 w-[600px] h-[600px] {{ $typeGlow }} blur-[160px] rounded-full pointer-events-none z-0 animate-pulse" style="animation-duration: 6s;"></div>
    <div class="fixed bottom-0 right-1/4 w-[400px] h-[400px] bg-blue-900/8 blur-[130px] rounded-full pointer-events-none z-0"></div>

    <div class="relative z-10 max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-10">

        {{-- ═══════════════════════════════════════════════════════════════ --}}
        {{-- HERO HEADER                                                     --}}
        {{-- ═══════════════════════════════════════════════════════════════ --}}
        <header class="pt-10 pb-8">
            {{-- Breadcrumb --}}
            <nav class="flex items-center gap-2 text-xs text-slate-500 mb-6">
                <a href="{{ route('welcome') }}" class="hover:text-sky-400 transition-colors">Beranda</a>
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                <a href="{{ route('anime.index') }}" class="hover:text-sky-400 transition-colors">Anime</a>
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                <span class="{{ $typeText }} font-semibold">{{ $typeLabel }}</span>
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                <span class="text-white truncate max-w-[120px]">{{ $title }}</span>
            </nav>

            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                {{-- Left: Icon + Title block --}}
                <div class="flex items-start gap-5">
                    {{-- Property type icon badge --}}
                    <div class="shrink-0 relative">
                        <div class="w-16 h-16 rounded-2xl {{ $typeBg }} border {{ $typeBorder }} flex items-center justify-center shadow-lg">
                            <svg class="w-8 h-8 {{ $typeText }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                {!! $typeIcon !!}
                            </svg>
                        </div>
                        <div class="absolute -inset-1 rounded-2xl {{ $typeBg }} blur-md -z-10 opacity-60"></div>
                    </div>

                    {{-- Title & description --}}
                    <div class="min-w-0">
                        <p class="text-xs font-bold uppercase tracking-widest {{ $typeText }} mb-1">{{ $typeLabel }}</p>
                        <h1 class="text-3xl sm:text-4xl lg:text-5xl font-black tracking-tight text-white bg-gradient-to-r {{ $typeAccent }} bg-clip-text text-transparent leading-none">
                            {{ $title }}
                        </h1>
                        <p class="text-slate-400 mt-2 text-sm md:text-base leading-relaxed max-w-lg">
                            @if($type === 'year')
                                Koleksi anime yang tayang pada tahun <span class="{{ $typeText }} font-semibold">{{ $title }}</span>, dari berbagai studio dan genre.
                            @elseif($type === 'country')
                                Anime produksi <span class="{{ $typeText }} font-semibold">{{ $title }}</span>, menampilkan karya terbaik dari negara tersebut.
                            @elseif($type === 'studio')
                                Seluruh karya animasi dari studio <span class="{{ $typeText }} font-semibold">{{ $title }}</span>.
                            @elseif($type === 'season')
                                Anime yang tayang pada musim <span class="{{ $typeText }} font-semibold">{{ $title }}</span>.
                            @else
                                Koleksi anime dengan properti <span class="{{ $typeText }} font-semibold">{{ $title }}</span>.
                            @endif
                        </p>
                    </div>
                </div>

                {{-- Right: Actions --}}
                <div class="flex items-center gap-3 shrink-0">
                    <a href="{{ route('anime.index') }}"
                       class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-semibold bg-slate-800/60 border border-slate-700/60 text-slate-300 hover:text-white hover:border-slate-500 transition-all duration-300 backdrop-blur-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                        Semua Anime
                    </a>
                    <a href="{{ url()->current() }}"
                       class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-semibold {{ $typeBg }} border {{ $typeBorder }} {{ $typeText }} hover:opacity-80 transition-all duration-300 backdrop-blur-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        Refresh
                    </a>
                </div>
            </div>

            {{-- Stats bar --}}
            @if(!$error && !$paginator->isEmpty())
                <div class="mt-8 flex flex-wrap items-center gap-3">
                    {{-- Total anime chip --}}
                    <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-[#0F192E] border border-sky-950/70 text-sm">
                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                        <span class="text-slate-400">Total:</span>
                        <span class="font-bold text-white">{{ number_format($paginator->total()) }} anime</span>
                    </div>
                    {{-- Page info chip --}}
                    <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-[#0F192E] border border-sky-950/70 text-sm">
                        <svg class="w-4 h-4 {{ $typeText }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <span class="text-slate-400">Halaman</span>
                        <span class="font-bold {{ $typeText }}">{{ $currentPage }}</span>
                        <span class="text-slate-500">dari</span>
                        <span class="font-bold text-white">{{ $lastPage }}</span>
                    </div>
                    {{-- Per page chip --}}
                    <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-[#0F192E] border border-sky-950/70 text-sm text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                        </svg>
                        <span>{{ $totalAnime }} per halaman</span>
                    </div>
                    {{-- Kuramanime source badge --}}
                    <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-violet-500/10 border border-violet-500/30 text-sm text-violet-300 font-semibold">
                        <span class="w-1.5 h-1.5 rounded-full bg-violet-400"></span>
                        Sumber: Kuramanime
                    </div>
                </div>
            @endif
        </header>

        {{-- ═══════════════════════════════════════════════════════════════ --}}
        {{-- DIVIDER                                                         --}}
        {{-- ═══════════════════════════════════════════════════════════════ --}}
        <div class="h-px bg-gradient-to-r from-transparent via-sky-900/60 to-transparent mb-8"></div>

        {{-- ═══════════════════════════════════════════════════════════════ --}}
        {{-- ERROR STATE                                                     --}}
        {{-- ═══════════════════════════════════════════════════════════════ --}}
        @if($error)
            <div class="flex flex-col items-center justify-center text-center py-32 space-y-6">
                <div class="relative">
                    <div class="w-24 h-24 rounded-3xl bg-rose-500/10 border border-rose-500/30 flex items-center justify-center">
                        <svg class="w-12 h-12 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                    <div class="absolute -inset-3 rounded-3xl bg-rose-500/5 blur-xl -z-10"></div>
                </div>
                <div class="space-y-2 max-w-md">
                    <h2 class="text-2xl font-bold text-white">Server Tidak Terhubung</h2>
                    <p class="text-slate-400 text-sm leading-relaxed">{{ $error }}</p>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ url()->current() }}"
                       class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-semibold text-white bg-gradient-to-r from-sky-500 to-blue-600 hover:from-sky-400 hover:to-blue-500 shadow-lg shadow-sky-500/25 transition-all duration-300 active:scale-95">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        Coba Lagi
                    </a>
                    <a href="{{ route('anime.index') }}"
                       class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-semibold bg-slate-800/60 border border-slate-700 text-slate-300 hover:text-white transition-all duration-300 active:scale-95">
                        Kembali
                    </a>
                </div>
            </div>

        @else

            {{-- ═══════════════════════════════════════════════════════════ --}}
            {{-- ANIME GRID                                                  --}}
            {{-- ═══════════════════════════════════════════════════════════ --}}
            @if($paginator->isEmpty())
                <div class="flex flex-col items-center justify-center text-center py-32 space-y-5">
                    <div class="w-20 h-20 rounded-3xl {{ $typeBg }} border {{ $typeBorder }} flex items-center justify-center">
                        <svg class="w-10 h-10 {{ $typeText }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="space-y-1">
                        <h2 class="text-xl font-bold text-white">Tidak Ada Anime</h2>
                        <p class="text-slate-400 text-sm">Belum ada anime yang tersedia untuk <span class="{{ $typeText }} font-semibold">{{ $title }}</span>.</p>
                    </div>
                    <a href="{{ route('anime.index') }}"
                       class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-semibold {{ $typeBg }} border {{ $typeBorder }} {{ $typeText }} hover:opacity-80 transition-all duration-300">
                        Jelajahi Semua Anime
                    </a>
                </div>
            @else
                <section class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-4 md:gap-5 pb-10">
                    @foreach($animeData as $index => $anime)
                        @php
                            $animeId = $anime['animeId'] ?? '#';
                            $hasScore = isset($anime['score']) && $anime['score'] > 0;
                            $hasEps   = isset($anime['episodes']) && $anime['episodes'] > 0;
                            $season   = $anime['season'] ?? null;
                            $studios  = $anime['studios'] ?? null;
                        @endphp
                        <a href="{{ $animeId !== '#' ? route('anime.show', ['animeId' => $animeId, 'provider' => 'kuramanime']) : '#' }}"
                           id="anime-card-{{ $index }}"
                           class="group relative bg-gradient-to-b from-[#0F1929] to-[#0A1120] rounded-2xl overflow-hidden border border-sky-950/50 hover:border-[#38bdf8]/60 hover:shadow-2xl hover:shadow-sky-900/30 transition-all duration-300 cursor-pointer flex flex-col hover:-translate-y-1">

                            {{-- Poster area --}}
                            <div class="relative overflow-hidden aspect-[2/3]">
                                {{-- Score badge --}}
                                @if($hasScore)
                                    <div class="absolute top-2 left-2 z-10 flex items-center gap-1 bg-black/70 backdrop-blur-sm px-2 py-1 rounded-lg border border-amber-500/30">
                                        <svg class="w-3 h-3 text-amber-400 fill-current shrink-0" viewBox="0 0 24 24">
                                            <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/>
                                        </svg>
                                        <span class="text-[11px] font-bold text-amber-300">{{ $anime['score'] }}</span>
                                    </div>
                                @endif

                                {{-- Episode count badge --}}
                                @if($hasEps)
                                    <div class="absolute top-2 right-2 z-10 bg-black/70 backdrop-blur-sm px-2 py-1 rounded-lg border border-sky-700/40">
                                        <span class="text-[10px] font-bold text-sky-300">{{ $anime['episodes'] }} EP</span>
                                    </div>
                                @endif

                                {{-- Poster image --}}
                                @if(!empty($anime['poster']))
                                    <img src="{{ $anime['poster'] }}"
                                         alt="{{ $anime['title'] }}"
                                         loading="lazy"
                                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 ease-out">
                                @else
                                    <div class="w-full h-full bg-gradient-to-br from-sky-900/40 via-slate-900 to-blue-900/30 flex flex-col items-center justify-center gap-2">
                                        <svg class="w-10 h-10 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        <span class="text-slate-600 text-[10px] uppercase tracking-widest">No Poster</span>
                                    </div>
                                @endif

                                {{-- Hover play overlay --}}
                                <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end justify-center pb-4">
                                    <div class="flex items-center gap-2 bg-white/10 backdrop-blur-md border border-white/20 px-3 py-1.5 rounded-full">
                                        <svg class="w-4 h-4 text-white fill-current" viewBox="0 0 24 24">
                                            <path d="M8 5v14l11-7z"/>
                                        </svg>
                                        <span class="text-white text-xs font-bold">Tonton</span>
                                    </div>
                                </div>
                            </div>

                            {{-- Card body --}}
                            <div class="p-3 flex flex-col gap-1.5 flex-1">
                                <h3 class="text-xs sm:text-sm font-bold text-slate-100 group-hover:{{ $typeText }} transition-colors duration-300 leading-snug line-clamp-2">
                                    {{ $anime['title'] }}
                                </h3>

                                {{-- Meta row --}}
                                <div class="flex flex-wrap items-center gap-1 mt-auto pt-1.5 border-t border-white/5">
                                    @if($season)
                                        <span class="text-[10px] font-semibold {{ $typeText }} {{ $typeBg }} px-1.5 py-0.5 rounded-md truncate max-w-[90px]">
                                            {{ $season }}
                                        </span>
                                    @endif
                                    @if($studios && !$season)
                                        <span class="text-[10px] text-slate-500 truncate max-w-[100px]">{{ $studios }}</span>
                                    @endif
                                    @if(!$season && !$studios)
                                        <span class="text-[10px] font-semibold {{ $typeText }}">{{ $title }}</span>
                                    @endif
                                </div>
                            </div>
                        </a>
                    @endforeach
                </section>

                {{-- ═══════════════════════════════════════════════════════ --}}
                {{-- PAGINATION                                              --}}
                {{-- ═══════════════════════════════════════════════════════ --}}
                @if($paginator->hasPages())
                    <div class="pb-12">
                        {{-- Progress bar --}}
                        <div class="max-w-sm mx-auto mb-5">
                            <div class="flex items-center justify-between text-xs text-slate-500 mb-2">
                                <span>Halaman {{ $currentPage }} dari {{ $lastPage }}</span>
                                <span>{{ number_format(($currentPage / max($lastPage, 1)) * 100, 0) }}%</span>
                            </div>
                            <div class="h-1 bg-slate-800 rounded-full overflow-hidden">
                                <div class="h-full bg-gradient-to-r {{ $typeAccent }} rounded-full transition-all duration-500"
                                     style="width: {{ ($currentPage / max($lastPage, 1)) * 100 }}%"></div>
                            </div>
                        </div>

                        <nav aria-label="Paginasi" class="flex items-center justify-center gap-2 flex-wrap">
                            {{-- Prev --}}
                            @if($paginator->onFirstPage())
                                <span class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-xl text-sm font-semibold bg-slate-900/50 border border-slate-800 text-slate-600 cursor-not-allowed select-none">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                                    Sebelumnya
                                </span>
                            @else
                                <a href="{{ $paginator->previousPageUrl() }}"
                                   class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-xl text-sm font-semibold bg-[#0F192E] border border-sky-950/70 text-slate-300 hover:border-sky-500/70 hover:text-sky-300 hover:bg-sky-500/5 transition-all duration-300">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                                    Sebelumnya
                                </a>
                            @endif

                            {{-- Page numbers (smart window) --}}
                            @php
                                $window  = 2;
                                $allPages = range(1, $lastPage);
                                $pageUrls = $paginator->getUrlRange(1, $lastPage);
                                $shown    = [];
                                foreach ($allPages as $p) {
                                    if ($p === 1 || $p === $lastPage || abs($p - $currentPage) <= $window) {
                                        $shown[] = $p;
                                    }
                                }
                                $shown = array_unique($shown);
                                sort($shown);
                            @endphp

                            @php $prev = null; @endphp
                            @foreach ($shown as $page)
                                @if ($prev !== null && $page - $prev > 1)
                                    <span class="w-9 h-10 flex items-center justify-center text-slate-600 text-sm select-none">…</span>
                                @endif

                                @if ($page == $currentPage)
                                    <span class="min-w-[2.25rem] h-10 px-3 flex items-center justify-center rounded-xl text-sm font-bold bg-gradient-to-br {{ $typeAccent }} text-white shadow-lg">
                                        {{ $page }}
                                    </span>
                                @else
                                    <a href="{{ $pageUrls[$page] ?? '#' }}"
                                       class="min-w-[2.25rem] h-10 px-3 flex items-center justify-center rounded-xl text-sm font-semibold bg-[#0F192E] border border-sky-950/70 text-slate-400 hover:border-sky-500/60 hover:text-sky-300 hover:bg-sky-500/5 transition-all duration-200">
                                        {{ $page }}
                                    </a>
                                @endif

                                @php $prev = $page; @endphp
                            @endforeach

                            {{-- Next --}}
                            @if($paginator->hasMorePages())
                                <a href="{{ $paginator->nextPageUrl() }}"
                                   class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-xl text-sm font-semibold bg-[#0F192E] border border-sky-950/70 text-slate-300 hover:border-sky-500/70 hover:text-sky-300 hover:bg-sky-500/5 transition-all duration-300">
                                    Berikutnya
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                </a>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-xl text-sm font-semibold bg-slate-900/50 border border-slate-800 text-slate-600 cursor-not-allowed select-none">
                                    Berikutnya
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                </span>
                            @endif
                        </nav>
                    </div>
                @else
                    <div class="text-center py-6 text-xs text-slate-600">
                        Menampilkan {{ $totalAnime }} anime • {{ $typeLabel }}: {{ $title }}
                    </div>
                @endif
            @endif
        @endif

    </div>
</div>
@endsection
