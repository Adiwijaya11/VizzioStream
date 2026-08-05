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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 3v4a1 1 0 001 1h4M9 3v4M9 17v4m0-4H5m4 0h4m0 0v4m0-4V9m0 4H9m4 0h6M6 9h8a1 1 0 011 1v5"/>
                        </svg>
                        Anime
                    </span>
                    <h1 class="text-3xl md:text-5xl font-black tracking-tight text-white bg-gradient-to-r from-sky-400 via-sky-300 to-blue-500 bg-clip-text text-transparent drop-shadow-lg mt-2">
                        Jelajahi Dunia Anime
                    </h1>
                    <p class="text-slate-300 mt-3 max-w-xl leading-relaxed text-sm md:text-base">
                        Stream ribuan judul anime ongoing dan complete dalam kualitas terbaik.
                    </p>
                </div>

                {{-- Refresh cache --}}
                <form method="POST" action="{{ route('anime.refresh') }}">
                    @csrf
                    <button type="submit"
                        class="inline-flex items-center gap-2 text-xs font-semibold bg-[#0F192E] border border-sky-800/60 hover:border-sky-500 text-sky-300 hover:text-white px-4 py-2.5 rounded-xl transition-all duration-300 hover:scale-105 active:scale-95 shadow-lg shadow-sky-900/20">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h5M20 20v-5h-5M4.93 9a8 8 0 0114.5-4M19.07 15a8 8 0 01-14.5 4"/>
                        </svg>
                        Segarkan Data
                    </button>
                </form>
            </div>

            {{-- Feed toggle tabs --}}
            <div class="inline-flex items-center gap-2 bg-[#0F192E] border border-sky-950/70 rounded-2xl p-1.5">
                <a href="{{ route('anime.feed', ['type' => 'ongoing']) }}"
                   class="px-5 py-2 rounded-xl text-sm font-semibold transition-all duration-300 {{ $feed === 'ongoing' ? 'bg-gradient-to-r from-sky-500 to-blue-600 text-white shadow-lg shadow-sky-500/30' : 'text-slate-400 hover:text-sky-300 hover:bg-sky-500/10' }}">
                    Ongoing
                </a>
                <a href="{{ route('anime.feed', ['type' => 'complete']) }}"
                   class="px-5 py-2 rounded-xl text-sm font-semibold transition-all duration-300 {{ $feed === 'complete' ? 'bg-gradient-to-r from-sky-500 to-blue-600 text-white shadow-lg shadow-sky-500/30' : 'text-slate-400 hover:text-sky-300 hover:bg-sky-500/10' }}">
                    Complete
                </a>
            </div>
        </header>

        {{-- Flash status --}}
        @if(session('status'))
            <div class="flex items-center gap-3 bg-emerald-500/10 border border-emerald-500/30 text-emerald-300 px-4 py-3 rounded-xl text-sm">
                <svg class="w-5 h-5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                {{ session('status') }}
            </div>
        @endif

        {{-- API unreachable error --}}
        @if($error)
            <div class="flex flex-col items-center justify-center text-center py-20 space-y-4">
                <div class="w-16 h-16 rounded-2xl bg-red-500/10 border border-red-500/30 flex items-center justify-center">
                    <svg class="w-8 h-8 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <h2 class="text-xl font-bold text-white">Server Anime Tidak Terhubung</h2>
                <p class="text-slate-400 max-w-md text-sm leading-relaxed">{{ $error }}</p>
            </div>
        @else
            {{-- Card grid --}}
            <section class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4 md:gap-6">
                @forelse($items as $anime)
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
                                @if($anime['hasDate'])
                                    <span class="flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        {{ $anime['date'] }}
                                    </span>
                                @endif
                                <span class="inline-flex items-center gap-1 text-sky-400 font-semibold">
                                    {{ $feed === 'ongoing' ? 'Ongoing' : 'Complete' }}
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
                        <p class="text-slate-400">Belum ada anime yang tersedia pada feed ini.</p>
                    </div>
                @endforelse
            </section>

            {{-- Pagination --}}
            @if(!empty($pagination['pages']) && count($pagination['pages']) > 1)
                <nav aria-label="Paginasi anime" class="flex items-center justify-center gap-2 pt-4">
                    @if($pagination['prevUrl'])
                        <a href="{{ $pagination['prevUrl'] }}"
                           class="inline-flex items-center gap-1 px-4 py-2 rounded-xl text-sm font-semibold bg-[#0F192E] border border-sky-950/70 text-slate-300 hover:border-sky-500 hover:text-sky-300 transition-all duration-300">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                            </svg>
                            Sebelumnya
                        </a>
                    @endif

                    <div class="flex items-center gap-1.5">
                        @foreach ($pagination['pages'] as $pageLink)
                            <a href="{{ $pageLink['url'] }}"
                               class="min-w-[2.25rem] h-9 px-2 flex items-center justify-center rounded-lg text-sm font-semibold transition-all duration-300
                                      {{ $pageLink['current'] ? 'bg-gradient-to-r from-sky-500 to-blue-600 text-white shadow-lg shadow-sky-500/30' : 'bg-[#0F192E] border border-sky-950/70 text-slate-400 hover:border-sky-500 hover:text-sky-300' }}">
                                {{ $pageLink['page'] }}
                            </a>
                        @endforeach
                    </div>

                    @if($pagination['nextUrl'])
                        <a href="{{ $pagination['nextUrl'] }}"
                           class="inline-flex items-center gap-1 px-4 py-2 rounded-xl text-sm font-semibold bg-[#0F192E] border border-sky-950/70 text-slate-300 hover:border-sky-500 hover:text-sky-300 transition-all duration-300">
                            Berikutnya
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    @endif
                </nav>
            @else
                <div class="text-center text-xs text-slate-500 pt-4">
                    Halaman {{ $pagination['currentPage'] ?? 1 }}
                </div>
            @endif
        @endif
    </div>
</div>
@endsection
