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
                        Genre Anime
                    </span>
                    <h1 class="text-3xl md:text-5xl font-black tracking-tight text-white bg-gradient-to-r from-sky-400 via-sky-300 to-blue-500 bg-clip-text text-transparent drop-shadow-lg mt-2 capitalize">
                        {{ ucwords(str_replace('-', ' ', $genreId)) }}
                    </h1>
                    <p class="text-slate-300 mt-3 max-w-xl leading-relaxed text-sm md:text-base">
                        Kumpulan anime dengan genre <span class="text-sky-400 font-semibold">{{ ucwords(str_replace('-', ' ', $genreId)) }}</span>,
                        ditampilkan langsung dari koleksi terlengkap.
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

        {{-- Card grid --}}
        <section class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4 md:gap-6">
            @forelse($animeData as $anime)
                <a href="{{ route('anime.show', $anime['animeId'] ?? '#') }}"
                   class="group bg-[#0F192E] rounded-2xl overflow-hidden border border-sky-950/60 hover:border-sky-400/50 hover:shadow-2xl hover:shadow-sky-500/20 transition-all duration-300 cursor-pointer flex flex-col">

                    {{-- Poster --}}
                    <div class="relative overflow-hidden aspect-[3/4]">
                        {{-- Badge --}}
                        <span class="absolute top-3 left-3 bg-sky-950/80 backdrop-blur-md px-2.5 py-1 rounded-lg border border-sky-700/50 text-xs font-bold text-sky-300">
                            {{ $anime['episodes'] > 0 ? $anime['episodes'].' Episode' : 'HD' }}
                        </span>

                        @if(isset($anime['score']) && $anime['score'] > 0)
                            <span class="absolute top-3 right-3 bg-slate-950/80 backdrop-blur-md px-2.5 py-1 rounded-lg border border-slate-700/50 text-xs font-bold text-amber-400 flex items-center space-x-1">
                                <svg class="w-3.5 h-3.5 text-amber-400 fill-current" viewBox="0 0 24 24">
                                    <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/>
                                </svg>
                                <span>{{ $anime['score'] }}</span>
                            </span>
                        @endif

                        @if($anime['poster'])
                            <img src="{{ $anime['poster'] }}" alt="{{ $anime['title'] }}"
                                 loading="lazy"
                                 class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                        @else
                            <div class="w-full h-full bg-gradient-to-br from-sky-900/40 to-slate-900 flex items-center justify-center">
                                <span class="text-slate-500 text-xs uppercase tracking-widest">No Poster</span>
                            </div>
                        @endif

                        {{-- Hover play overlay --}}
                        <div class="absolute inset-0 bg-slate-950/50 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">
                            <div class="w-12 h-12 rounded-full bg-sky-500 text-white flex items-center justify-center shadow-lg transform group-hover:scale-110 transition-transform">
                                <svg class="w-6 h-6 fill-current ml-1" viewBox="0 0 24 24">
                                    <path d="M8 5v14l11-7z"/>
                                </svg>
                            </div>
                        </div>
                    </div>

                    {{-- Body --}}
                    <div class="p-3 md:p-4 flex flex-col justify-between flex-1 space-y-2">
                        <div>
                            <h3 class="text-sm md:text-base font-bold text-white group-hover:text-sky-400 transition duration-300 leading-snug">
                                {{ $anime['title'] }}
                            </h3>
                        </div>
                        <div class="flex items-center justify-between text-[11px] md:text-xs text-slate-400 pt-2 border-t border-sky-950/40">
                            <span class="inline-flex items-center text-sky-400 font-semibold">
                                @if(isset($anime['season']))
                                    {{ $anime['season'] }}
                                @else
                                    {{ ucfirst(str_replace('-', ' ', $genreId)) }}
                                @endif
                            </span>
                        </div>
                    </div>
                </a>
            @empty
                <div class="col-span-full flex flex-col items-center justify-center text-center py-24 space-y-4">
                    <div class="w-16 h-16 rounded-2xl bg-sky-500/10 border border-sky-500/30 flex items-center justify-center">
                        <svg class="w-8 h-8 text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </div>
                    <p class="text-slate-400">Belum ada anime yang tersedia pada genre ini.</p>
                </div>
            @endforelse
        </section>

        {{-- Pagination --}}
        @if($paginator->hasPages())
            <nav aria-label="Paginasi genre" class="flex items-center justify-center gap-2 pt-4">
                @if($paginator->onFirstPage())
                    <span class="inline-flex items-center gap-1 px-4 py-2 rounded-xl text-sm font-semibold bg-[#0F192E] border border-sky-950/70 text-slate-600 cursor-not-allowed">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                        Sebelumnya
                    </span>
                @else
                    <a href="{{ $paginator->previousPageUrl() }}"
                       class="inline-flex items-center gap-1 px-4 py-2 rounded-xl text-sm font-semibold bg-[#0F192E] border border-sky-950/70 text-slate-300 hover:border-sky-500 hover:text-sky-300 transition-all duration-300">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                        Sebelumnya
                    </a>
                @endif

                @foreach ($paginator->getUrlRange(1, $paginator->lastPage()) as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="min-w-[2.25rem] h-9 px-2 flex items-center justify-center rounded-lg text-sm font-semibold bg-gradient-to-r from-sky-500 to-blue-600 text-white shadow-lg shadow-sky-500/30">
                            {{ $page }}
                        </span>
                    @else
                        <a href="{{ $url }}"
                           class="min-w-[2.25rem] h-9 px-2 flex items-center justify-center rounded-lg text-sm font-semibold bg-[#0F192E] border border-sky-950/70 text-slate-400 hover:border-sky-500 hover:text-sky-300 transition-all duration-300">
                            {{ $page }}
                        </a>
                    @endif
                @endforeach

                @if($paginator->hasMorePages())
                    <a href="{{ $paginator->nextPageUrl() }}"
                       class="inline-flex items-center gap-1 px-4 py-2 rounded-xl text-sm font-semibold bg-[#0F192E] border border-sky-950/70 text-slate-300 hover:border-sky-500 hover:text-sky-300 transition-all duration-300">
                        Berikutnya
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                @else
                    <span class="inline-flex items-center gap-1 px-4 py-2 rounded-xl text-sm font-semibold bg-[#0F192E] border border-sky-950/70 text-slate-600 cursor-not-allowed">
                        Berikutnya
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </span>
                @endif
            </nav>
        @else
            <div class="text-center text-xs text-slate-500 pt-4">
                Halaman {{ $paginator->currentPage() ?? 1 }}
            </div>
        @endif
    </div>
</div>
@endsection
