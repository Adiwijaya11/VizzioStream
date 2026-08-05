{{-- Dynamic anime grid fed by AnimeApiService (wajik-anime-api) --}}
@if($error)
    {{-- API unreachable error --}}
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
    {{-- Movie Grid (dynamic cards) --}}
    <div id="anime-grid" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-3 sm:gap-5 lg:gap-6">
        @forelse(($gridItems ?? $items ?? []) as $anime)
            <a href="{{ route('anime.show', $anime['animeId'] ?? '#') }}"
               class="group bg-[#0F192E] rounded-xl sm:rounded-2xl overflow-hidden border border-sky-950/60 hover:border-sky-400/50 hover:shadow-2xl hover:shadow-sky-500/20 transition-all duration-300 cursor-pointer flex flex-col">

                {{-- Poster --}}
                <div class="relative overflow-hidden aspect-[3/4]">
                    {{-- Episode badge --}}
                    <span class="absolute top-2 left-2 sm:top-3 sm:left-3 bg-sky-950/80 backdrop-blur-md px-1.5 py-0.5 sm:px-2.5 sm:py-1 rounded-md sm:rounded-lg border border-sky-700/50 text-[10px] sm:text-xs font-bold text-sky-300">
                        {{ !empty($anime['episodeLabel']) ? $anime['episodeLabel'] : (($anime['episodes'] ?? 0) > 0 ? ($anime['episodes'] ?? 0).' Episode' : 'HD') }}
                    </span>

                    {{-- Release day badge --}}
                    @if(!empty($anime['releaseDay']))
                        <span class="absolute top-2 right-2 sm:top-3 sm:right-3 bg-slate-950/80 backdrop-blur-md px-1.5 py-0.5 sm:px-2.5 sm:py-1 rounded-md sm:rounded-lg border border-slate-700/50 text-[10px] sm:text-xs font-bold text-amber-400">
                            {{ $anime['releaseDay'] ?? '' }}
                        </span>
                    @endif

                    @if($anime['poster'])
                        <img src="{{ $anime['poster'] ?? '' }}" alt="{{ $anime['title'] ?? 'Anime' }}"
                             loading="lazy"
                             class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                    @else
                        <div class="w-full h-full bg-gradient-to-br from-sky-900/40 via-slate-900 to-slate-950 flex flex-col items-center justify-center gap-2">
                            <span class="w-14 h-14 sm:w-16 sm:h-16 rounded-2xl bg-sky-500/15 border border-sky-500/30 flex items-center justify-center text-2xl sm:text-3xl font-black text-sky-400 uppercase">{{ mb_strtoupper(mb_substr($anime['title'] ?? 'A', 0, 1)) }}</span>
                            <span class="text-slate-500 text-[10px] uppercase tracking-widest">Anime</span>
                        </div>
                    @endif

                    {{-- Hover play overlay --}}
                    <div class="absolute inset-0 bg-slate-950/50 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">
                        <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-full bg-sky-500 text-white flex items-center justify-center shadow-lg transform group-hover:scale-110 transition-transform">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 fill-current ml-1" viewBox="0 0 24 24">
                                <path d="M8 5v14l11-7z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                {{-- Info --}}
                <div class="p-3 sm:p-4 flex flex-col justify-between flex-1 space-y-2">
                    <div>
                    @if(in_array($anime['status'] ?? null, ['Ongoing', 'Completed', 'Completed Airing'], true))
                        <span class="text-[10px] sm:text-xs text-sky-400 font-semibold uppercase tracking-wider block flex items-center gap-1.5">
                            @if(($anime['status'] ?? 'Ongoing') === 'Completed' || ($anime['status'] ?? '') === 'Completed Airing')
                                <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                Tamat
                            @else
                                <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4v16M17 4v16M3 8h18M3 12h18M3 16h18"></path></svg>
                                Ongoing
                            @endif
                        </span>
                    @endif
                        <h3 class="text-sm sm:text-base md:text-lg font-bold text-white group-hover:text-sky-400 transition duration-300 line-clamp-1 mt-1">{{ $anime['title'] ?? 'Anime' }}</h3>
                    </div>
                    <div class="flex items-center justify-between text-[10px] sm:text-xs text-slate-400 pt-2 border-t border-sky-950/40">
                        @if($anime['hasDate'])
                            <span class="flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                {{ $anime['date'] ?? '' }}
                            </span>
                        @endif
                        <span class="flex items-center gap-1 {{ $anime['hasDate'] ? '' : 'ml-auto' }}">
                            <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            {{ !empty($anime['episodeLabel']) ? $anime['episodeLabel'] : (($anime['episodes'] ?? 0) > 0 ? ($anime['episodes'] ?? 0).' Eps' : (($anime['status'] ?? '') === 'Completed' ? 'Tamat' : 'Ongoing')) }}
                        </span>
                    </div>
                </div>
            </a>
        @empty
            {{-- Empty state --}}
            <div class="col-span-full flex flex-col items-center justify-center text-center py-20 space-y-4">
                <div class="w-16 h-16 rounded-2xl bg-sky-500/10 border border-sky-500/30 flex items-center justify-center">
                    <svg class="w-8 h-8 text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4v16M17 4v16M3 8h18M3 12h18M3 16h18"/>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-white">Belum ada anime</h3>
                <p class="text-slate-400 text-sm max-w-md">Tidak ada data anime yang tersedia saat ini. Coba segarkan halaman atau periksa server anime.</p>
            </div>
        @endforelse
    </div>
@endif
