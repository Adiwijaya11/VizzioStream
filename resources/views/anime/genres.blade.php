@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-[#090D16] relative">
    <!-- Ambient glow accents -->
    <div class="fixed top-24 left-1/4 w-[400px] h-[400px] bg-sky-600/10 blur-[130px] rounded-full pointer-events-none z-0"></div>
    <div class="fixed bottom-0 right-10 w-[350px] h-[350px] bg-blue-700/10 blur-[120px] rounded-full pointer-events-none z-0"></div>

    <div class="relative z-10 max-w-7xl mx-auto px-6 md:px-10 py-10 space-y-10">

        {{-- Header --}}
        <header class="space-y-6">
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 border-b border-sky-950/60 pb-6">
                <div>
                    <span class="inline-flex items-center gap-2 text-xs md:text-sm font-semibold uppercase tracking-widest text-sky-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                        </svg>
                        Kategori Anime
                    </span>
                    <h1 class="text-3xl md:text-5xl font-black tracking-tight text-white bg-gradient-to-r from-sky-400 via-sky-300 to-blue-500 bg-clip-text text-transparent drop-shadow-lg mt-2">
                        Jelajahi Semua Genre
                    </h1>
                    <p class="text-slate-300 mt-3 max-w-xl leading-relaxed text-sm md:text-base">
                        Pilih genre favoritmu untuk menemukan anime yang paling sesuai dengan selera.
                    </p>
                </div>

                <a href="{{ route('anime.index') }}"
                   class="inline-flex items-center gap-2 text-xs font-semibold bg-[#0F192E] border border-sky-800/60 hover:border-sky-500 text-sky-300 hover:text-white px-4 py-2.5 rounded-xl transition-all duration-300 hover:scale-105 active:scale-95 shadow-lg shadow-sky-900/20 shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                    Semua Anime
                </a>
            </div>
        </header>

        {{-- Genre grid --}}
        @if($error)
            <div class="flex flex-col items-center justify-center text-center py-24 space-y-4">
                <div class="w-16 h-16 rounded-2xl bg-rose-500/10 border border-rose-500/30 flex items-center justify-center">
                    <svg class="w-8 h-8 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M5 12h14"/>
                    </svg>
                </div>
                <div>
                    <p class="text-slate-200 font-semibold">Server Anime Tidak Terhubung</p>
                    <p class="text-slate-400 text-sm mt-1 max-w-md mx-auto">{{ $error }}</p>
                </div>
            </div>
        @else
            <section class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4 md:gap-5">
                @forelse($genres as $genre)
                    <a href="{{ route('genre.show', ['genreId' => $genre['genreId'] ?? $genre['slug'] ?? '#']) }}"
                       class="group relative bg-[#0F192E] rounded-2xl border border-sky-950/60 hover:border-sky-400/50 p-5 overflow-hidden transition-all duration-300 hover:scale-[1.03] hover:shadow-2xl hover:shadow-sky-500/20">
                        {{-- Glow accent on hover --}}
                        <div class="absolute -top-8 -right-8 w-24 h-24 bg-sky-500/0 group-hover:bg-sky-500/20 blur-2xl rounded-full transition-all duration-500"></div>

                        <div class="relative flex items-start justify-between">
                            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-sky-500/20 to-blue-600/20 border border-sky-500/30 flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5 text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                </svg>
                            </div>
                            <svg class="w-4 h-4 text-slate-600 group-hover:text-sky-400 transition-all duration-300 group-hover:translate-x-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </div>

                        <h3 class="relative mt-4 text-sm md:text-base font-bold text-white group-hover:text-sky-400 transition duration-300 capitalize leading-snug">
                            {{ $genre['title'] ?? 'Genre' }}
                        </h3>
                        <p class="relative mt-1 text-[11px] text-slate-500 group-hover:text-slate-400 transition">
                            Jelajahi koleksi
                        </p>
                    </a>
                @empty
                    <div class="col-span-full flex flex-col items-center justify-center text-center py-24 space-y-4">
                        <div class="w-16 h-16 rounded-2xl bg-sky-500/10 border border-sky-500/30 flex items-center justify-center">
                            <svg class="w-8 h-8 text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </div>
                        <p class="text-slate-400">Belum ada genre yang tersedia.</p>
                    </div>
                @endforelse
            </section>
        @endif
    </div>
</div>
@endsection
