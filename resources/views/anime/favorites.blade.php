@extends('layouts.app')

@section('title', 'Favorit Saya | VizzioStream')
@section('meta_description', 'Daftar anime favorit yang telah kamu simpan di VizzioStream.')

@section('content')
<div class="min-h-screen bg-[#090D16] relative overflow-hidden">

    {{-- Ambient glows --}}
    <div class="fixed top-20 left-1/3 w-[500px] h-[500px] bg-sky-600/8 blur-[150px] rounded-full pointer-events-none z-0"></div>
    <div class="fixed bottom-10 right-10 w-[400px] h-[400px] bg-rose-700/6 blur-[130px] rounded-full pointer-events-none z-0"></div>

    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-10 py-10 space-y-8">

        {{-- ── HEADER ─────────────────────────────────────────────── --}}
        <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4">
            <div class="space-y-2">
                {{-- Breadcrumb --}}
                <div class="flex items-center gap-2 text-xs text-slate-500">
                    <a href="{{ route('welcome') }}" class="hover:text-sky-400 transition-colors">Home</a>
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    <span class="text-slate-400">Favorit Saya</span>
                </div>

                <h1 class="text-3xl sm:text-4xl font-black tracking-tight text-white flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-rose-500/15 border border-rose-500/30 flex items-center justify-center shadow-lg shadow-rose-500/10">
                        <svg class="w-5 h-5 text-rose-400 fill-current" viewBox="0 0 24 24">
                            <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                        </svg>
                    </div>
                    <span class="bg-gradient-to-r from-white via-rose-200 to-sky-300 bg-clip-text text-transparent">
                        Favorit Saya
                    </span>
                </h1>

                <p class="text-slate-400 text-sm">
                    @if(count($favorites) > 0)
                        <span class="text-rose-400 font-bold">{{ count($favorites) }}</span> anime tersimpan dalam daftar favoritmu
                    @else
                        Belum ada anime yang kamu simpan
                    @endif
                </p>
            </div>

            @if(count($favorites) > 0)
                {{-- Clear all form --}}
                <form method="POST" action="{{ route('anime.favorites.clear') }}"
                      onsubmit="return confirm('Hapus semua favorit?')"
                      class="shrink-0">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs font-semibold bg-rose-500/10 border border-rose-500/30 text-rose-400 hover:bg-rose-500/20 hover:text-rose-300 transition-all duration-300 active:scale-95">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        Hapus Semua
                    </button>
                </form>
            @endif
        </div>

        {{-- ── FLASH STATUS ──────────────────────────────────────── --}}
        @if(session('status'))
            <div class="flex items-center gap-3 bg-emerald-500/10 border border-emerald-500/30 text-emerald-300 text-sm px-5 py-3.5 rounded-2xl">
                <svg class="w-5 h-5 shrink-0 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>{{ session('status') }}</span>
            </div>
        @endif

        {{-- ── EMPTY STATE ────────────────────────────────────────── --}}
        @if(count($favorites) === 0)
            <div class="flex flex-col items-center justify-center text-center py-24 space-y-6">
                <div class="relative">
                    <div class="w-28 h-28 rounded-3xl bg-rose-500/8 border border-rose-500/20 flex items-center justify-center shadow-2xl">
                        <svg class="w-14 h-14 text-rose-500/40" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                        </svg>
                    </div>
                    <div class="absolute -top-1 -right-1 w-5 h-5 rounded-full bg-rose-500/20 border border-rose-500/40 flex items-center justify-center">
                        <svg class="w-2.5 h-2.5 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 5v14M5 12h14"/>
                        </svg>
                    </div>
                </div>

                <div class="space-y-2 max-w-sm">
                    <h2 class="text-2xl font-bold text-white">Belum Ada Favorit</h2>
                    <p class="text-slate-400 text-sm leading-relaxed">
                        Kamu belum menyimpan anime apapun. Jelajahi koleksi kami dan klik tombol
                        <span class="text-rose-400 font-semibold">♥ Tambah ke Favorit</span> pada halaman detail anime.
                    </p>
                </div>

                <div class="flex flex-wrap items-center justify-center gap-3 pt-2">
                    <a href="{{ route('welcome') }}"
                       class="inline-flex items-center gap-2 px-6 py-3 rounded-2xl text-sm font-bold text-white bg-gradient-to-r from-sky-500 to-blue-600 hover:from-sky-400 hover:to-blue-500 shadow-lg shadow-sky-500/25 transition-all duration-300 hover:-translate-y-0.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10.5L12 3l9 7.5V21a1 1 0 01-1 1h-5v-7h-6v7H4a1 1 0 01-1-1z"/>
                        </svg>
                        Ke Beranda
                    </a>
                    <a href="{{ route('anime.index') }}"
                       class="inline-flex items-center gap-2 px-6 py-3 rounded-2xl text-sm font-semibold bg-[#0F192E] border border-sky-950/70 text-slate-300 hover:text-sky-300 hover:border-sky-500/50 transition-all duration-300">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        Jelajahi Anime
                    </a>
                </div>
            </div>

        @else
            {{-- ── FAVORITES GRID ──────────────────────────────────── --}}
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-4">
                @foreach($favorites as $fav)
                    @php
                        $animeId = $fav['anime_id'] ?? '';
                        $title   = $fav['title']    ?? 'Anime';
                        $poster  = $fav['poster']   ?? null;
                    @endphp

                    <div class="group relative flex flex-col">
                        {{-- Card --}}
                        <a href="{{ route('anime.show', ['animeId' => $animeId]) }}"
                           class="block relative aspect-[3/4] rounded-2xl overflow-hidden border border-sky-950/60 hover:border-sky-400/50 shadow-lg hover:shadow-xl hover:shadow-sky-500/10 transition-all duration-300 hover:-translate-y-1">

                            {{-- Poster --}}
                            @if($poster)
                                <img src="{{ $poster }}" alt="{{ $title }}"
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            @else
                                <div class="w-full h-full bg-gradient-to-br from-sky-900/30 to-slate-900 flex items-center justify-center">
                                    <svg class="w-10 h-10 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 10l4.55-2.27A1 1 0 0121 8.63v6.74a1 1 0 01-1.45.9L15 14M5 17h10a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                            @endif

                            {{-- Hover overlay --}}
                            <div class="absolute inset-0 bg-gradient-to-t from-[#050914]/95 via-[#050914]/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex flex-col justify-end p-3 gap-2">
                                <span class="inline-flex items-center justify-center gap-1.5 w-full py-2 rounded-xl text-xs font-bold text-white bg-gradient-to-r from-sky-500 to-blue-600 shadow-lg shadow-sky-500/30">
                                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd"/>
                                    </svg>
                                    Tonton
                                </span>
                            </div>

                            {{-- Heart badge --}}
                            <div class="absolute top-2 right-2 w-7 h-7 rounded-xl bg-rose-500/20 border border-rose-500/40 backdrop-blur-sm flex items-center justify-center">
                                <svg class="w-3.5 h-3.5 text-rose-400 fill-current" viewBox="0 0 24 24">
                                    <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                                </svg>
                            </div>
                        </a>

                        {{-- Title + remove button --}}
                        <div class="mt-2.5 flex items-start justify-between gap-1.5">
                            <a href="{{ route('anime.show', ['animeId' => $animeId]) }}"
                               class="flex-1 min-w-0 text-xs font-semibold text-slate-200 hover:text-sky-300 transition-colors leading-tight line-clamp-2">
                                {{ $title }}
                            </a>

                            {{-- Remove button --}}
                            <form method="POST" action="{{ route('anime.favorite', ['animeId' => $animeId]) }}" class="shrink-0 mt-0.5">
                                @csrf
                                <input type="hidden" name="title" value="{{ $title }}">
                                <input type="hidden" name="poster" value="{{ $poster }}">
                                <button type="submit" title="Hapus dari favorit"
                                        class="w-6 h-6 rounded-lg bg-slate-800/80 border border-slate-700/60 hover:bg-rose-500/20 hover:border-rose-500/50 flex items-center justify-center transition-all duration-200 active:scale-90">
                                    <svg class="w-3 h-3 text-slate-500 hover:text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- ── BOTTOM ACTION BAR ──────────────────────────────── --}}
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4 bg-[#0F192E]/60 border border-sky-950/60 rounded-2xl p-4 sm:px-6">
                <div class="flex items-center gap-3 text-sm text-slate-400">
                    <div class="w-8 h-8 rounded-xl bg-rose-500/10 border border-rose-500/30 flex items-center justify-center">
                        <svg class="w-4 h-4 text-rose-400 fill-current" viewBox="0 0 24 24">
                            <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                        </svg>
                    </div>
                    <span>
                        Kamu memiliki <span class="font-bold text-white">{{ count($favorites) }}</span> anime favorit
                        <span class="text-slate-600 text-xs ml-1">(tersimpan di session browser)</span>
                    </span>
                </div>
                <a href="{{ route('anime.index') }}"
                   class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-semibold bg-sky-500/10 border border-sky-500/30 text-sky-300 hover:bg-sky-500/20 hover:text-white transition-all duration-300">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    Tambah Lebih Banyak
                </a>
            </div>
        @endif

    </div>
</div>
@endsection
