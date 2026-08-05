@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-[#090D16] relative">
    <!-- Ambient glow accents -->
    <div class="fixed top-24 left-1/4 w-[400px] h-[400px] bg-sky-600/10 blur-[130px] rounded-full pointer-events-none z-0"></div>
    <div class="fixed bottom-0 right-10 w-[350px] h-[350px] bg-blue-700/10 blur-[120px] rounded-full pointer-events-none z-0"></div>

    <div class="relative z-10 max-w-7xl mx-auto px-6 md:px-10 py-10 space-y-10">

        @if($error)
            {{-- API unreachable --}}
            <div class="flex flex-col items-center justify-center text-center py-24 space-y-4">
                <div class="w-16 h-16 rounded-2xl bg-red-500/10 border border-red-500/30 flex items-center justify-center">
                    <svg class="w-8 h-8 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <h2 class="text-xl font-bold text-white">Anime Tidak Ditemukan</h2>
                <p class="text-slate-400 max-w-md text-sm leading-relaxed">{{ $error }}</p>
                <a href="{{ route('welcome') }}" class="mt-2 inline-flex items-center gap-2 text-sm font-semibold bg-sky-500/10 border border-sky-500/40 text-sky-300 px-5 py-2.5 rounded-xl hover:bg-sky-500/20 transition-all duration-300">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Kembali ke Beranda
                </a>
            </div>
        @else
            {{-- Back + breadcrumb --}}
            <div class="flex items-center gap-3 text-sm">
                <a href="{{ route('welcome') }}" class="inline-flex items-center gap-1.5 text-slate-400 hover:text-sky-300 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Kembali
                </a>
            </div>

            {{-- Hero detail block --}}
            <section class="flex flex-col md:flex-row gap-8 md:gap-12 items-start">
                {{-- Poster --}}
                <div class="relative shrink-0 mx-auto md:mx-0">
                    <div class="w-56 md:w-72 aspect-[3/4] rounded-3xl overflow-hidden border border-sky-800/60 shadow-2xl shadow-sky-900/40 relative group">
                        @if($anime['poster'])
                            <img src="{{ $anime['poster'] ?? '' }}" alt="{{ $anime['title'] ?? 'Anime' }}"
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        @else
                            <div class="w-full h-full bg-gradient-to-br from-sky-900/40 to-slate-900 flex items-center justify-center">
                                <span class="text-slate-500 text-xs uppercase tracking-widest">No Poster</span>
                            </div>
                        @endif
                    </div>

                    @if(!empty($anime['status']))
                        <span class="absolute -bottom-3 left-1/2 -translate-x-1/2 bg-gradient-to-r from-sky-500 to-blue-600 text-white text-xs font-bold px-4 py-1.5 rounded-full shadow-lg shadow-sky-500/30 border border-sky-400/50 uppercase tracking-wider">
                            {{ $anime['status'] ?? '' }}
                        </span>
                    @endif
                </div>

                {{-- Meta --}}
                <div class="flex-1 min-w-0 space-y-6">
                    <div class="space-y-3">
                        <h1 class="text-3xl md:text-5xl font-black tracking-tight text-white bg-gradient-to-r from-sky-400 via-sky-300 to-blue-500 bg-clip-text text-transparent drop-shadow-lg">
                            {{ $anime['title'] ?? 'Anime' }}
                        </h1>
                        @if(!empty($anime['japanese']))
                            <p class="text-slate-400 text-sm md:text-base">{{ $anime['japanese'] ?? '' }}</p>
                        @endif

                        {{-- Quick badges --}}
                        <div class="flex flex-wrap items-center gap-2 pt-1">
                            @if(!empty($anime['type']))
                                <span class="inline-flex items-center gap-1 text-[11px] font-bold uppercase tracking-wider bg-sky-500/10 border border-sky-500/40 text-sky-300 px-3 py-1 rounded-lg">TV</span>
                            @endif
                            @if($anime['score'] > 0)
                                <span class="inline-flex items-center gap-1 text-sm font-bold bg-[#0F192E] border border-slate-700/60 text-amber-400 px-3 py-1 rounded-lg">
                                    <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                                        <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/>
                                    </svg>
                                    {{ $anime['score'] ?? 0 }}
                                </span>
                            @endif
                            @if($anime['episodes'] > 0)
                                <span class="inline-flex items-center gap-1 text-sm font-bold bg-[#0F192E] border border-slate-700/60 text-sky-300 px-3 py-1 rounded-lg">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.55-2.27A1 1 0 0121 8.63v6.74a1 1 0 01-1.45.9L15 14M5 17h10a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                    </svg>
                                    {{ $anime['episodes'] ?? 0 }} Episode
                                </span>
                            @endif
                        </div>

                        {{-- Server Switcher --}}
                        <div class="flex items-center gap-2 pt-2">
                            <span class="text-xs text-slate-400 font-semibold uppercase tracking-wider mr-1">Server:</span>
                            <a href="{{ route('anime.show', ['animeId' => $animeId, 'provider' => 'otakudesu']) }}"
                               class="inline-flex items-center gap-1.5 text-xs font-bold px-3 py-1.5 rounded-xl border transition-all duration-300 {{ ($provider ?? 'otakudesu') === 'otakudesu' ? 'bg-sky-500/20 border-sky-400 text-sky-300 shadow-md shadow-sky-500/20' : 'bg-slate-800/40 border-slate-700/60 text-slate-400 hover:text-white hover:border-slate-500' }}">
                                <span class="w-2 h-2 rounded-full {{ ($provider ?? 'otakudesu') === 'otakudesu' ? 'bg-emerald-400 animate-pulse' : 'bg-slate-500' }}"></span>
                                Server 1 (Otakudesu)
                            </a>
                            <a href="{{ route('anime.show', ['animeId' => $animeId, 'provider' => 'kuramanime']) }}"
                               class="inline-flex items-center gap-1.5 text-xs font-bold px-3 py-1.5 rounded-xl border transition-all duration-300 {{ ($provider ?? 'otakudesu') === 'kuramanime' ? 'bg-purple-500/20 border-purple-400 text-purple-300 shadow-md shadow-purple-500/20' : 'bg-slate-800/40 border-slate-700/60 text-slate-400 hover:text-white hover:border-slate-500' }}">
                                <span class="w-2 h-2 rounded-full {{ ($provider ?? 'otakudesu') === 'kuramanime' ? 'bg-emerald-400 animate-pulse' : 'bg-slate-500' }}"></span>
                                Server 2 (Kuramanime)
                            </a>
                        </div>
                    </div>

                    {{-- Favorite Button --}}
                    <div class="pt-4">
                        <form method="POST" action="{{ route('anime.favorite', ['animeId' => $animeId]) }}" class="inline-block">
                            @csrf
                            <input type="hidden" name="title" value="{{ $anime['title'] ?? 'Anime' }}">
                            <input type="hidden" name="poster" value="{{ $anime['poster'] ?? '' }}">
                            @if($isFavorited ?? false)
                                <button id="favorite-button" class="inline-flex items-center gap-2 text-sm font-semibold bg-sky-500/10 border border-sky-500/40 text-sky-300 px-5 py-2.5 rounded-xl hover:bg-sky-500/20 transition-all duration-300">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"/>
                                    </svg>
                                    <span id="favorite-label">Hapus dari Favorit</span>
                                </button>
                            @else
                                <button id="favorite-button" class="inline-flex items-center gap-2 text-sm font-semibold bg-red-500/10 border border-red-500/40 text-red-300 px-5 py-2.5 rounded-xl hover:bg-red-500/20 transition-all duration-300">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"/>
                                    </svg>
                                    <span id="favorite-label">Tambah ke Favorit</span>
                                </button>
                            @endif
                        </form>
                    </div>

                    {{-- Info grid --}}
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                        @if(!empty($anime['status']))
                            <div class="bg-[#0F192E] border border-sky-950/60 rounded-xl p-3">
                                <p class="text-[10px] uppercase tracking-widest text-slate-500 font-semibold">Status</p>
                                <p class="text-sm font-bold text-white mt-1">{{ $anime['status'] ?? '' }}</p>
                            </div>
                        @endif
                        @if(!empty($anime['aired']))
                            <div class="bg-[#0F192E] border border-sky-950/60 rounded-xl p-3">
                                <p class="text-[10px] uppercase tracking-widest text-slate-500 font-semibold">Tayang</p>
                                <p class="text-sm font-bold text-white mt-1">{{ $anime['aired'] ?? '' }}</p>
                            </div>
                        @endif
                        @if(!empty($anime['studios']))
                            <div class="bg-[#0F192E] border border-sky-950/60 rounded-xl p-3">
                                <p class="text-[10px] uppercase tracking-widest text-slate-500 font-semibold">Studio</p>
                                <p class="text-sm font-bold text-white mt-1">{{ is_array($anime['studios'] ?? null) ? implode(', ', $anime['studios']) : ($anime['studios'] ?? '') }}</p>
                            </div>
                        @endif
                        @if(!empty($anime['duration']))
                            <div class="bg-[#0F192E] border border-sky-950/60 rounded-xl p-3">
                                <p class="text-[10px] uppercase tracking-widest text-slate-500 font-semibold">Durasi</p>
                                <p class="text-sm font-bold text-white mt-1">{{ $anime['duration'] ?? '' }}</p>
                            </div>
                        @endif
                        @if(!empty($anime['producers']))
                            <div class="bg-[#0F192E] border border-sky-950/60 rounded-xl p-3 col-span-2 sm:col-span-1">
                                <p class="text-[10px] uppercase tracking-widest text-slate-500 font-semibold">Produser</p>
                                <p class="text-sm font-bold text-white mt-1 line-clamp-2">{{ is_array($anime['producers'] ?? null) ? implode(', ', array_map(fn($p) => is_array($p) ? ($p['name'] ?? json_encode($p)) : $p, $anime['producers'])) : ($anime['producers'] ?? '') }}</p>
                            </div>
                        @endif
                    </div>

                    {{-- Genre chips --}}
                    @if(!empty($anime['genres']))
                        <div class="flex flex-wrap gap-2">
                            @foreach($anime['genres'] as $genre)
                                <span class="text-xs font-semibold bg-slate-800/60 border border-slate-700/60 text-slate-300 px-3 py-1 rounded-full hover:border-sky-500 hover:text-sky-300 transition-colors">{{ $genre }}</span>
                            @endforeach
                        </div>
                    @endif
                </div>
            </section>

            {{-- Synopsis --}}
            @if(!empty($anime['synopsis']))
                <section class="space-y-4">
                    <h2 class="text-2xl font-extrabold tracking-tight text-white flex items-center gap-3">
                        <span class="w-1.5 h-6 bg-gradient-to-b from-sky-400 to-blue-600 rounded-full"></span>
                        Sinopsis
                    </h2>
                    <div class="bg-[#0F192E] border border-sky-950/60 rounded-2xl p-6">
                        <p class="text-slate-300 leading-relaxed whitespace-pre-line text-sm md:text-base">{{ $anime['synopsis'] ?? '' }}</p>
                    </div>
                </section>
            @endif

            {{-- Episode list --}}
                <section id="episodes" class="space-y-6">
                    <div class="flex items-center justify-between">
                        <h2 class="text-2xl font-extrabold tracking-tight text-white flex items-center gap-3">
                            <span class="w-1.5 h-10 bg-gradient-to-b from-sky-400 to-blue-600 rounded-full"></span>
                            Daftar Episode
                            <span class="text-sm font-bold text-sky-400 bg-sky-500/10 border border-sky-500/30 px-3 py-1 rounded-full">{{ count($episodes) }}</span>
                        </h2>
                        <span class="hidden sm:inline-flex items-center gap-2 text-xs text-slate-500">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.55-2.27A1 1 0 0121 8.63v6.74a1 1 0 01-1.45.9L15 14M5 17h10a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                            </svg>
                            Klik episode untuk menonton
                        </span>
                    </div>

                    @if(count($episodes) > 0)
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                            @foreach($episodes as $episode)
                                <a href="{{ route('anime.episode', ['animeId' => $animeId, 'episodeId' => $episode['episodeId'], 'provider' => $provider ?? 'otakudesu']) }}"
                                   class="group relative flex items-center gap-4 bg-gradient-to-br from-[#0F192E] to-[#0B1424] border border-sky-950/60 hover:border-sky-400/60 rounded-2xl p-4 overflow-hidden transition-all duration-300 hover:-translate-y-0.5 hover:shadow-xl hover:shadow-sky-500/10">
                                    {{-- Hover glow --}}
                                    <div class="absolute inset-0 bg-gradient-to-br from-sky-500/0 via-sky-500/0 to-sky-500/10 opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none"></div>

                                    {{-- Episode number badge --}}
                                    <div class="relative w-12 h-12 shrink-0 rounded-xl bg-gradient-to-br from-sky-500 to-blue-600 flex items-center justify-center shadow-lg shadow-sky-500/20 group-hover:shadow-sky-500/40 group-hover:scale-105 transition-all duration-300">
                                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>

                                    <div class="relative min-w-0 flex-1">
                                        <p class="text-sm font-bold text-white group-hover:text-sky-300 transition-colors">
                                            Episode {{ $episode['title'] ?? $loop->iteration }}
                                        </p>
                                        <p class="text-xs text-slate-500 mt-0.5 flex items-center gap-1">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                            </svg>
                                            Putar sekarang
                                        </p>
                                    </div>

                                    <svg class="relative w-5 h-5 text-slate-600 group-hover:text-sky-400 shrink-0 transition-all duration-300 group-hover:translate-x-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </a>
                            @endforeach
                        </div>
                    @else
                        <div class="bg-[#0F192E] border border-sky-950/60 rounded-2xl p-10 text-center">
                            <p class="text-slate-400 text-sm">Belum ada episode yang tersedia untuk anime ini.</p>
                        </div>
                    @endif
                </section>
        @endif
    </div>
</div>
@endsection
