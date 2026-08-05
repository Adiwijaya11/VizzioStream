@extends('layouts.app')

@php
    $currentProvider = $provider ?? 'otakudesu';
    $title           = $episode['title'] ?? 'Episode Anime';
    $releaseTime     = $episode['releaseTime'] ?? null;
    $streamUrl       = $episode['defaultStreamingUrl'] ?? null;
    $hasPrev         = !empty($episode['hasPrevEpisode']) && !empty($episode['prevEpisode']);
    $hasNext         = !empty($episode['hasNextEpisode']) && !empty($episode['nextEpisode']);
    $prevId          = $episode['prevEpisode'] ?? null;
    $nextId          = $episode['nextEpisode'] ?? null;
    $servers         = $episode['server'] ?? [];
    $downloads       = $episode['download'] ?? [];
    $info            = $episode['info'] ?? [];
@endphp

@section('title', $title . ' | VizzioStream')

@section('content')
<div class="min-h-screen bg-[#090D16] relative overflow-hidden text-slate-100"
     x-data="{
        activeUrl: '{{ $streamUrl }}',
        loadingServer: false,
        activeServerId: null,
        async changeServer(serverId) {
            if (!serverId) return;
            this.loadingServer = true;
            this.activeServerId = serverId;
            try {
                const res = await fetch('/api/server/' + encodeURIComponent(serverId));
                const data = await res.json();
                if (data.success && data.url) {
                    this.activeUrl = data.url;
                }
            } catch(e) {
                console.error('Failed to load server:', e);
            } finally {
                this.loadingServer = false;
            }
        }
     }">

    {{-- Ambient backdrop glows --}}
    <div class="fixed top-12 left-1/4 w-[600px] h-[600px] bg-sky-600/8 blur-[160px] rounded-full pointer-events-none z-0"></div>
    <div class="fixed bottom-0 right-10 w-[450px] h-[450px] bg-blue-700/8 blur-[140px] rounded-full pointer-events-none z-0"></div>

    <div class="relative z-10 max-w-[1350px] mx-auto px-4 sm:px-6 lg:px-10 py-6 sm:py-8 space-y-6 sm:space-y-8">

        {{-- ═══════════════════════════════════════════════════════════════ --}}
        {{-- TOP BAR: Navigation & Provider Switcher                         --}}
        {{-- ═══════════════════════════════════════════════════════════════ --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-[#0F192E]/60 border border-sky-950/70 p-3 sm:px-5 sm:py-3.5 rounded-2xl backdrop-blur-md">
            {{-- Back Link --}}
            <a href="{{ route('anime.show', ['animeId' => $animeId, 'provider' => $currentProvider]) }}"
               class="inline-flex items-center gap-2 text-xs sm:text-sm font-semibold text-slate-300 hover:text-sky-400 transition-colors group">
                <div class="w-8 h-8 rounded-xl bg-slate-800/70 border border-slate-700/60 flex items-center justify-center group-hover:border-sky-500/60 group-hover:bg-sky-500/10 transition-all">
                    <svg class="w-4 h-4 text-sky-400 group-hover:-translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                </div>
                <span>Kembali ke Detail Anime</span>
            </a>

            {{-- Provider Switcher --}}
            <div class="flex items-center gap-2 flex-wrap">
                <span class="text-[11px] text-slate-400 font-semibold uppercase tracking-wider mr-1">Server:</span>

                <a href="{{ route('anime.episode', ['animeId' => $animeId, 'episodeId' => $episodeId, 'provider' => 'otakudesu']) }}"
                   class="inline-flex items-center gap-2 text-xs font-bold px-3.5 py-2 rounded-xl border transition-all duration-300 {{ $currentProvider === 'otakudesu' ? 'bg-sky-500/20 border-sky-400 text-sky-300 shadow-lg shadow-sky-500/20' : 'bg-slate-800/50 border-slate-700/60 text-slate-400 hover:text-white hover:border-slate-500' }}">
                    <span class="w-2 h-2 rounded-full {{ $currentProvider === 'otakudesu' ? 'bg-emerald-400 animate-pulse' : 'bg-slate-500' }}"></span>
                    Server 1 (Otakudesu)
                </a>

                <a href="{{ route('anime.episode', ['animeId' => $animeId, 'episodeId' => $episodeId, 'provider' => 'kuramanime']) }}"
                   class="inline-flex items-center gap-2 text-xs font-bold px-3.5 py-2 rounded-xl border transition-all duration-300 {{ $currentProvider === 'kuramanime' ? 'bg-purple-500/20 border-purple-400 text-purple-300 shadow-lg shadow-purple-500/20' : 'bg-slate-800/50 border-slate-700/60 text-slate-400 hover:text-white hover:border-slate-500' }}">
                    <span class="w-2 h-2 rounded-full {{ $currentProvider === 'kuramanime' ? 'bg-emerald-400 animate-pulse' : 'bg-slate-500' }}"></span>
                    Server 2 (Kuramanime)
                </a>
            </div>
        </div>

        {{-- ═══════════════════════════════════════════════════════════════ --}}
        {{-- ERROR STATE                                                     --}}
        {{-- ═══════════════════════════════════════════════════════════════ --}}
        @if($error)
            <div class="flex flex-col items-center justify-center text-center py-28 space-y-5 bg-[#0F192E]/40 border border-rose-500/20 rounded-3xl p-6">
                <div class="w-20 h-20 rounded-3xl bg-rose-500/10 border border-rose-500/30 flex items-center justify-center">
                    <svg class="w-10 h-10 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <div class="space-y-2 max-w-md">
                    <h2 class="text-xl sm:text-2xl font-bold text-white">Episode Tidak Dapat Dimuat</h2>
                    <p class="text-slate-400 text-sm leading-relaxed">{{ $error }}</p>
                </div>
                <div class="flex items-center gap-3 pt-2">
                    <a href="{{ route('anime.show', ['animeId' => $animeId, 'provider' => $currentProvider]) }}"
                       class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-semibold text-white bg-gradient-to-r from-sky-500 to-blue-600 hover:from-sky-400 hover:to-blue-500 shadow-lg shadow-sky-500/25 transition-all duration-300">
                        Kembali ke Detail
                    </a>
                </div>
            </div>

        @else

            {{-- ═══════════════════════════════════════════════════════════ --}}
            {{-- HEADER: Title & Meta                                        --}}
            {{-- ═══════════════════════════════════════════════════════════ --}}
            <header class="space-y-3">
                <div class="flex flex-wrap items-center gap-2">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-bold uppercase tracking-wider bg-sky-500/10 border border-sky-500/30 text-sky-400">
                        <span class="w-1.5 h-1.5 rounded-full bg-sky-400 animate-ping"></span>
                        Streaming HD
                    </span>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-semibold bg-slate-800/60 border border-slate-700/60 text-slate-300">
                        Provider: {{ ucfirst($currentProvider) }}
                    </span>
                </div>

                <h1 class="text-xl sm:text-2xl md:text-3xl lg:text-4xl font-black tracking-tight text-white leading-tight">
                    {{ $title }}
                </h1>

                @if($releaseTime)
                    <p class="text-xs sm:text-sm text-slate-400 flex items-center gap-2">
                        <svg class="w-4 h-4 text-sky-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>Diupload: {{ $releaseTime }}</span>
                    </p>
                @endif
            </header>

            {{-- ═══════════════════════════════════════════════════════════ --}}
            {{-- VIDEO PLAYER CINEMA FRAME WITH DYNAMIC MIRROR SWITCHING     --}}
            {{-- ═══════════════════════════════════════════════════════════ --}}
            <section class="relative space-y-4">
                <div class="relative w-full aspect-video rounded-2xl sm:rounded-3xl overflow-hidden border border-sky-900/60 bg-[#050914] shadow-2xl shadow-sky-950/80 group">

                    {{-- Loading Overlay when switching mirror --}}
                    <div x-show="loadingServer" x-cloak
                         class="absolute inset-0 bg-[#050914]/90 backdrop-blur-sm z-20 flex flex-col items-center justify-center space-y-3">
                        <div class="w-12 h-12 border-4 border-sky-500 border-t-transparent rounded-full animate-spin"></div>
                        <p class="text-sm font-semibold text-sky-300">Memuat Mirror Server...</p>
                    </div>

                    <template x-if="activeUrl">
                        <iframe
                            :src="activeUrl"
                            class="absolute inset-0 w-full h-full border-0"
                            allowfullscreen
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                            referrerpolicy="strict-origin-when-cross-origin"
                            scrolling="no"
                            loading="eager"></iframe>
                    </template>

                    <template x-if="!activeUrl">
                        <div class="w-full h-full flex flex-col items-center justify-center p-6 text-center space-y-4 bg-gradient-to-b from-[#0A1120] to-[#050914]">
                            <div class="w-16 h-16 rounded-2xl bg-sky-500/10 border border-sky-500/30 flex items-center justify-center">
                                <svg class="w-8 h-8 text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div class="space-y-1">
                                <p class="text-base font-bold text-white">Video Utama Tidak Tersedia</p>
                                <p class="text-xs text-slate-400 max-w-sm">Pilih salah satu server mirror di bawah untuk memutar video ini.</p>
                            </div>
                        </div>
                    </template>
                </div>

                {{-- Player Quick Action Bar (Download & Options) --}}
                <div class="flex flex-wrap items-center justify-between gap-3 bg-[#0F192E]/90 border border-sky-950/80 p-3 sm:px-5 sm:py-3.5 rounded-2xl">
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                        <span class="text-xs sm:text-sm font-bold text-slate-200">Player Mirror Aktif</span>
                    </div>

                    <div class="flex items-center gap-2.5 flex-wrap">
                        {{-- Fast Scroll to Download Section --}}
                        <a href="#download-section"
                           class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs sm:text-sm font-bold text-white bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-400 hover:to-teal-500 shadow-lg shadow-emerald-500/25 transition-all duration-300 active:scale-95">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                            </svg>
                            <span>Download Episode Ini</span>
                        </a>

                        <template x-if="activeUrl">
                            <a :href="activeUrl" target="_blank" rel="noopener noreferrer"
                               class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-semibold bg-slate-800/80 border border-slate-700/70 text-slate-300 hover:text-white hover:border-sky-500 transition-all">
                                <svg class="w-3.5 h-3.5 text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                </svg>
                                <span>Buka Tab Baru</span>
                            </a>
                        </template>
                    </div>
                </div>
            </section>

            {{-- ═══════════════════════════════════════════════════════════ --}}
            {{-- NAVIGATION CONTROLS: Prev / Next                            --}}
            {{-- ═══════════════════════════════════════════════════════════ --}}
            <nav aria-label="Navigasi Episode" class="grid grid-cols-2 sm:grid-cols-3 items-center gap-3">
                {{-- Prev Episode --}}
                <div>
                    @if($hasPrev)
                        <a href="{{ route('anime.episode', ['animeId' => $animeId, 'episodeId' => $prevId, 'provider' => $currentProvider]) }}"
                           class="inline-flex items-center gap-2 px-4 py-3 sm:px-5 sm:py-3 rounded-xl sm:rounded-2xl bg-[#0F192E] border border-sky-950/80 hover:border-sky-500/70 text-slate-300 hover:text-sky-300 transition-all duration-300 text-xs sm:text-sm font-semibold group w-full justify-center sm:justify-start">
                            <svg class="w-4 h-4 group-hover:-translate-x-1 transition-transform shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                            </svg>
                            <span class="truncate">Episode Prev</span>
                        </a>
                    @else
                        <span class="inline-flex items-center gap-2 px-4 py-3 rounded-xl sm:rounded-2xl bg-slate-900/40 border border-slate-800/50 text-slate-600 text-xs sm:text-sm font-semibold cursor-not-allowed select-none w-full justify-center sm:justify-start">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                            Episode Pertama
                        </span>
                    @endif
                </div>

                {{-- Center: Detail Link (Desktop) --}}
                <div class="hidden sm:flex justify-center">
                    <a href="{{ route('anime.show', ['animeId' => $animeId, 'provider' => $currentProvider]) }}"
                       class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-slate-800/50 border border-slate-700/60 hover:border-slate-500 text-slate-400 hover:text-white transition-all text-xs font-semibold">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                        </svg>
                        Daftar Episode
                    </a>
                </div>

                {{-- Next Episode --}}
                <div class="flex justify-end">
                    @if($hasNext)
                        <a href="{{ route('anime.episode', ['animeId' => $animeId, 'episodeId' => $nextId, 'provider' => $currentProvider]) }}"
                           class="inline-flex items-center gap-2 px-4 py-3 sm:px-5 sm:py-3 rounded-xl sm:rounded-2xl bg-gradient-to-r from-sky-500 to-blue-600 hover:from-sky-400 hover:to-blue-500 text-white shadow-lg shadow-sky-500/25 hover:shadow-sky-500/40 transition-all duration-300 text-xs sm:text-sm font-semibold group w-full justify-center sm:justify-end">
                            <span class="truncate">Episode Next</span>
                            <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    @else
                        <span class="inline-flex items-center gap-2 px-4 py-3 rounded-xl sm:rounded-2xl bg-slate-900/40 border border-slate-800/50 text-slate-600 text-xs sm:text-sm font-semibold cursor-not-allowed select-none w-full justify-center sm:justify-end">
                            Episode Terbaru
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </span>
                    @endif
                </div>
            </nav>

            {{-- ═══════════════════════════════════════════════════════════ --}}
            {{-- SERVER MIRROR ALTERNATIF (INTERACTIVE SWITCHER)             --}}
            {{-- ═══════════════════════════════════════════════════════════ --}}
            @if(!empty($servers['qualityList']) && is_array($servers['qualityList']))
                <section class="bg-[#0F192E]/90 border border-sky-950/80 rounded-2xl p-4 sm:p-6 space-y-4">
                    <div class="flex items-center justify-between pb-3 border-b border-sky-950/70">
                        <h2 class="text-base sm:text-lg font-bold text-white flex items-center gap-2.5">
                            <div class="w-7 h-7 rounded-lg bg-purple-500/10 border border-purple-500/30 flex items-center justify-center text-purple-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M12 5l7 7-7 7"/>
                                </svg>
                            </div>
                            <span>Server Mirror Alternatif (Klik untuk Memutar)</span>
                        </h2>
                        <span class="text-xs text-sky-400 font-semibold">Klik server untuk ganti player</span>
                    </div>

                    <div class="space-y-3">
                        @foreach($servers['qualityList'] as $q)
                            <div class="flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-4 p-3.5 bg-[#0B1220] border border-sky-950/60 rounded-xl">
                                <span class="text-xs font-bold text-sky-300 sm:w-28 shrink-0 flex items-center gap-1.5">
                                    <span class="w-1.5 h-1.5 rounded-full bg-sky-400"></span>
                                    {{ trim($q['title'] ?? 'Quality') }}
                                </span>
                                @if(!empty($q['serverList']))
                                    <div class="flex flex-wrap gap-2 flex-1">
                                        @foreach($q['serverList'] as $srv)
                                            @php $srvId = $srv['serverId'] ?? ''; @endphp
                                            <button
                                                type="button"
                                                @click="changeServer('{{ $srvId }}')"
                                                :class="activeServerId === '{{ $srvId }}' ? 'bg-sky-500/20 border-sky-400 text-sky-300 shadow-md shadow-sky-500/20 scale-105' : 'bg-slate-800/80 border-slate-700 text-slate-300 hover:text-white hover:border-sky-500/70 hover:bg-sky-500/10'"
                                                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-semibold border transition-all duration-200 active:scale-95">
                                                <span class="w-1.5 h-1.5 rounded-full" :class="activeServerId === '{{ $srvId }}' ? 'bg-emerald-400 animate-pulse' : 'bg-slate-400'"></span>
                                                {{ ucfirst($srv['title'] ?? 'Server') }}
                                            </button>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </section>
            @endif

            {{-- ═══════════════════════════════════════════════════════════ --}}
            {{-- DOWNLOAD CENTER SECTION                                     --}}
            {{-- ═══════════════════════════════════════════════════════════ --}}
            @php
                $qualityList = $downloads['qualityList'] ?? (is_array($downloads) && isset($downloads[0]) ? $downloads : []);
            @endphp
            @if(!empty($qualityList))
                <section id="download-section" class="bg-[#0F192E]/90 border border-sky-950/80 rounded-2xl p-4 sm:p-6 space-y-4">
                    <div class="flex items-center justify-between pb-3 border-b border-sky-950/70">
                        <h2 class="text-base sm:text-lg font-bold text-white flex items-center gap-2.5">
                            <div class="w-7 h-7 rounded-lg bg-emerald-500/10 border border-emerald-500/30 flex items-center justify-center text-emerald-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                </svg>
                            </div>
                            <span>Download Episode</span>
                        </h2>
                        <span class="text-xs text-emerald-400 font-semibold">Pilih Resolusi & Server Link</span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 sm:gap-4">
                        @foreach($qualityList as $dl)
                            @php
                                $dlTitle = $dl['title'] ?? 'Resolution';
                                $dlSize  = $dl['size'] ?? null;
                                $urls    = $dl['urlList'] ?? [];
                            @endphp
                            <div class="bg-[#0B1220] border border-sky-950/70 rounded-xl p-3.5 flex flex-col gap-3">
                                <div class="flex items-center justify-between border-b border-white/5 pb-2">
                                    <span class="text-xs sm:text-sm font-bold text-sky-300 flex items-center gap-2">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                                        {{ $dlTitle }}
                                    </span>
                                    @if($dlSize)
                                        <span class="text-[11px] font-semibold text-emerald-300 bg-emerald-950/60 px-2 py-0.5 rounded-md border border-emerald-500/30">
                                            {{ $dlSize }}
                                        </span>
                                    @endif
                                </div>

                                @if(!empty($urls) && is_array($urls))
                                    <div class="flex flex-wrap gap-2 pt-1">
                                        @foreach($urls as $link)
                                            @php $dlUrl = $link['url'] ?? '#'; @endphp
                                            <a href="{{ $dlUrl }}"
                                               target="_blank"
                                               rel="noopener noreferrer"
                                               @if($dlUrl !== '#') download @endif
                                               class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-semibold bg-slate-800/80 border border-slate-700/80 text-slate-200 hover:text-white hover:bg-emerald-500/20 hover:border-emerald-500/50 transition-all duration-200 active:scale-95">
                                                <svg class="w-3.5 h-3.5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                                </svg>
                                                <span>{{ $link['title'] ?? 'Download' }}</span>
                                            </a>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </section>
            @endif

            {{-- ═══════════════════════════════════════════════════════════ --}}
            {{-- EPISODE METADATA SECTION                                    --}}
            {{-- ═══════════════════════════════════════════════════════════ --}}
            @if(!empty($info) && is_array($info))
                <section class="bg-[#0F192E]/60 border border-sky-950/70 rounded-2xl p-4 sm:p-6 space-y-4">
                    <h2 class="text-base font-bold text-white flex items-center gap-2">
                        <span class="w-1 h-4 bg-sky-400 rounded-full"></span>
                        Informasi Tambahan
                    </h2>
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3 text-xs">
                        @foreach($info as $key => $val)
                            @if(is_array($val))
                                @php
                                    $val = implode(', ', array_map(fn ($v) => is_array($v) ? ($v['title'] ?? '') : (string) $v, $val));
                                @endphp
                            @endif
                            @if(!empty($val) && is_string($val))
                                <div class="bg-[#0B1220]/70 border border-sky-950/60 rounded-xl p-3">
                                    <p class="text-[10px] uppercase tracking-wider text-slate-500 font-bold mb-1">{{ $key }}</p>
                                    <p class="text-slate-300 font-medium truncate">{{ $val }}</p>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </section>
            @endif

        @endif

    </div>
</div>
@endsection
