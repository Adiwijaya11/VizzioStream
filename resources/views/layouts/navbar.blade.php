<div x-data="{ mobileOpen: false }">
<nav class="fixed top-0 left-0 right-0 h-16 bg-[#050914]/90 backdrop-blur-md border-b border-sky-950/50 z-50 px-4 sm:px-6 md:px-10 lg:px-16 flex items-center justify-between">
    <!-- Left Section: Logo -->
    <div class="flex items-center space-x-4">
        <!-- Brand Logo -->
        <a href="/" class="text-2xl font-bold tracking-wider text-white transition duration-300 hover:opacity-90 flex items-center space-x-2">
            <img src="{{ asset('logo.svg') }}" alt="VizzioStream Logo" class="h-8 w-8 drop-shadow-[0_0_10px_rgba(56,189,248,0.45)]">
            <span>Vizzio</span><span class="text-sky-400 drop-shadow-[0_0_12px_rgba(56,189,248,0.5)]">Stream</span>
        </a>
    </div>

    <!-- Navigation Links -->
    <div class="hidden lg:flex items-center lg:space-x-3 xl:space-x-5 2xl:space-x-6 text-xs xl:text-sm font-medium">
        <!-- Home Link -->
        <a href="/" class="text-sky-400 font-semibold transition duration-300">Home</a>

        <!-- Genre Dropdown -->
        <div class="relative group">
            <button class="text-slate-300 group-hover:text-sky-400 py-2 transition duration-300 flex items-center space-x-1 focus:outline-none">
                <span>Genre</span>
                <svg class="w-4 h-4 text-slate-400 group-hover:text-sky-400 transition-transform duration-200 group-hover:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>
            <!-- Dropdown Menu (wide grid, top 12 genres + link to all) -->
            <div class="absolute left-0 top-full hidden group-hover:block group-focus-within:block pt-3 z-50">
                @php $navbarGenresLimited = collect($navbarGenres ?? [])->take(12); @endphp
                <div class="w-[29rem] max-w-[90vw] bg-[#0F192E]/95 border border-sky-950/80 rounded-2xl shadow-2xl p-4 backdrop-blur-xl">
                    <div class="flex items-center justify-between px-1 pb-3 mb-2 border-b border-sky-950/70">
                        <span class="text-[10px] font-bold uppercase tracking-widest text-sky-400">Jelajahi Genre</span>
                        <span class="text-[10px] text-slate-500">{{ count($navbarGenres ?? []) }} genre</span>
                    </div>
                    <div class="grid grid-cols-3 gap-1">
                        @forelse($navbarGenresLimited as $genre)
                            <a href="{{ route('genre.show', ['genreId' => $genre['slug']]) }}"
                               class="block px-3 py-2 rounded-lg text-xs text-slate-300 hover:bg-sky-500/10 hover:text-sky-400 transition duration-200 truncate">
                                {{ $genre['title'] }}
                            </a>
                        @empty
                            <p class="col-span-3 px-3 py-4 text-xs text-slate-500 text-center">Genre sedang tidak tersedia.</p>
                        @endforelse
                    </div>
                    <a href="{{ route('genre.index') }}"
                       class="mt-3 flex items-center justify-center gap-1.5 w-full px-4 py-2.5 rounded-xl text-xs font-semibold text-sky-300 bg-sky-500/10 border border-sky-500/30 hover:bg-sky-500/20 hover:text-white transition-all duration-300 group/more">
                        Lihat Semua Genre
                        <svg class="w-3.5 h-3.5 transition-transform duration-300 group-hover/more:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                </div>
            </div>
        </div>

        <!-- Series Dropdown -->
        <div class="relative group">
            <button class="text-slate-300 group-hover:text-sky-400 py-2 transition duration-300 flex items-center space-x-1 focus:outline-none">
                <span>Series</span>
                <svg class="w-4 h-4 text-slate-400 group-hover:text-sky-400 transition-transform duration-200 group-hover:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>
            <!-- Dropdown Menu (wide 2-column: Ongoing | Completed) -->
            <div class="absolute left-0 top-full hidden group-hover:block group-focus-within:block pt-3 z-50">
                <div class="w-[34rem] max-w-[90vw] bg-[#0F192E]/95 border border-sky-950/80 rounded-2xl shadow-2xl p-4 backdrop-blur-xl">
                    <div class="grid grid-cols-2 gap-4">
                        {{-- Ongoing --}}
                        <div>
                            <p class="text-[10px] font-bold uppercase tracking-widest text-sky-400 pb-2 mb-1 border-b border-sky-950/70 flex items-center gap-1.5">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                                Ongoing
                            </p>
                            @forelse($navbarOngoing ?? [] as $anime)
                                <a href="{{ route('anime.show', ['animeId' => $anime['slug'] ?? '#']) }}"
                                   class="block px-2 py-1.5 rounded-lg text-xs text-slate-300 hover:bg-sky-500/10 hover:text-sky-400 transition duration-200 truncate">
                                    {{ $anime['title'] ?? 'Anime' }}
                                </a>
                            @empty
                                <p class="px-2 py-3 text-xs text-slate-500">Belum tersedia.</p>
                            @endforelse
                            <a href="{{ route('anime.feed', ['type' => 'ongoing']) }}"
                               class="mt-2 inline-flex items-center gap-1 text-[11px] font-semibold text-sky-400 hover:text-sky-300 transition">
                                Lihat Semua
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                        </div>
                        {{-- Completed --}}
                        <div class="border-l border-sky-950/70 pl-4">
                            <p class="text-[10px] font-bold uppercase tracking-widest text-sky-400 pb-2 mb-1 border-b border-sky-950/70 flex items-center gap-1.5">
                                <span class="w-1.5 h-1.5 rounded-full bg-amber-400"></span>
                                Completed
                            </p>
                            @forelse($navbarCompleted ?? [] as $anime)
                                <a href="{{ route('anime.show', ['animeId' => $anime['slug'] ?? '#']) }}"
                                   class="block px-2 py-1.5 rounded-lg text-xs text-slate-300 hover:bg-sky-500/10 hover:text-sky-400 transition duration-200 truncate">
                                    {{ $anime['title'] ?? 'Anime' }}
                                </a>
                            @empty
                                <p class="px-2 py-3 text-xs text-slate-500">Belum tersedia.</p>
                            @endforelse
                            <a href="{{ route('anime.feed', ['type' => 'complete']) }}"
                               class="mt-2 inline-flex items-center gap-1 text-[11px] font-semibold text-sky-400 hover:text-sky-300 transition">
                                Lihat Semua
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Populer Link -->
        <a href="#" class="text-slate-300 hover:text-sky-400 transition duration-300">Populer</a>

        <!-- Negara Dropdown -->
        <div class="relative group">
            <button class="text-slate-300 group-hover:text-sky-400 py-2 transition duration-300 flex items-center space-x-1 focus:outline-none">
                <span>Negara</span>
                <svg class="w-4 h-4 text-slate-400 group-hover:text-sky-400 transition-transform duration-200 group-hover:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>
            <!-- Dropdown Menu (wide grid, right-aligned to avoid overflow) -->
            <div class="absolute right-0 top-full hidden group-hover:block group-focus-within:block pt-3 z-50">
                @php $navbarCountriesLimited = collect($navbarCountries ?? [])->take(12); @endphp
                <div class="w-[26rem] max-w-[90vw] bg-[#0F192E]/95 border border-sky-950/80 rounded-2xl shadow-2xl p-4 backdrop-blur-xl">
                    <div class="flex items-center justify-between px-1 pb-3 mb-2 border-b border-sky-950/70">
                        <span class="text-[10px] font-bold uppercase tracking-widest text-sky-400">Negara Asal</span>
                        <span class="text-[10px] text-slate-500">{{ count($navbarCountries ?? []) }} negara</span>
                    </div>
                    <div class="grid grid-cols-3 gap-1">
                        @forelse($navbarCountriesLimited as $country)
                            <a href="{{ route('property.show', ['type' => 'country', 'propertyId' => $country['slug']]) }}"
                               class="block px-3 py-2 rounded-lg text-xs text-slate-300 hover:bg-sky-500/10 hover:text-sky-400 transition duration-200 truncate">
                                {{ $country['title'] }}
                            </a>
                        @empty
                            <p class="col-span-3 px-3 py-4 text-xs text-slate-500 text-center">Negara sedang tidak tersedia.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- Tahun Dropdown -->
        <div class="relative group">
            <button class="text-slate-300 group-hover:text-sky-400 py-2 transition duration-300 flex items-center space-x-1 focus:outline-none">
                <span>Tahun</span>
                <svg class="w-4 h-4 text-slate-400 group-hover:text-sky-400 transition-transform duration-200 group-hover:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>
            <!-- Dropdown Menu (wide grid, right-aligned to avoid overflow) -->
            <div class="absolute right-0 top-full hidden group-hover:block group-focus-within:block pt-3 z-50">
                @php $navbarYearsLimited = collect($navbarYears ?? [])->take(12); @endphp
                <div class="w-[26rem] max-w-[90vw] bg-[#0F192E]/95 border border-sky-950/80 rounded-2xl shadow-2xl p-4 backdrop-blur-xl">
                    <div class="flex items-center justify-between px-1 pb-3 mb-2 border-b border-sky-950/70">
                        <span class="text-[10px] font-bold uppercase tracking-widest text-sky-400">Tahun Rilis</span>
                        <span class="text-[10px] text-slate-500">{{ count($navbarYears ?? []) }} tahun</span>
                    </div>
                    <div class="grid grid-cols-4 gap-1">
                        @forelse($navbarYearsLimited as $year)
                            <a href="{{ route('property.show', ['type' => 'year', 'propertyId' => $year['slug']]) }}"
                               class="block px-3 py-2 rounded-lg text-xs text-slate-300 hover:bg-sky-500/10 hover:text-sky-400 transition duration-200 truncate text-center">
                                {{ $year['title'] }}
                            </a>
                        @empty
                            <p class="col-span-4 px-3 py-4 text-xs text-slate-500 text-center">Tahun sedang tidak tersedia.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- Anime Link -->
        <a href="{{ route('anime.index') }}" class="text-slate-300 hover:text-sky-400 transition duration-300">Anime</a>

        {{-- Favorit (Watchlist Ribbon Icon) --}}
        <a href="{{ route('anime.favorites') }}" class="text-slate-300 hover:text-rose-400 transition duration-300 flex items-center space-x-1.5 group relative">
            <svg class="w-4 h-4 text-rose-400 fill-current group-hover:scale-110 transition-transform drop-shadow-[0_0_8px_rgba(244,63,94,0.4)]" viewBox="0 0 24 24">
                <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
            </svg>
            <span>Favorit</span>
            @php $favCount = count(session('favorites', [])); @endphp
            @if($favCount > 0)
                <span class="absolute -top-2 -right-3 min-w-[18px] h-[18px] px-1 rounded-full bg-rose-500 text-white text-[10px] font-bold flex items-center justify-center shadow-lg shadow-rose-500/40">
                    {{ $favCount > 99 ? '99+' : $favCount }}
                </span>
            @endif
        </a>
    </div>

    <!-- Right Section: Search Bar & Account Action -->
    <div class="flex items-center gap-2 sm:gap-4">
        <div class="relative hidden sm:block">
            <form action="{{ route('search') }}" method="GET" class="relative" role="search">
                <input type="text" name="q" value="{{ request('q') ?? '' }}" placeholder="Cari film, anime..."
                       class="bg-[#0B1220] border border-sky-950/70 text-xs text-white rounded-full py-2 pl-9 pr-4 focus:outline-none focus:border-sky-500 focus:ring-1 focus:ring-sky-500 transition duration-300 w-36 md:w-48 lg:w-56 placeholder-slate-500" id="search-input">
                <button type="submit" aria-label="Cari" class="absolute left-3 top-2.5 text-slate-500 hover:text-sky-400 transition duration-300">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </button>
                <div class="search-suggestions absolute z-20 w-full bg-[#0F192E] border border-sky-950/60 rounded-lg shadow-lg hidden overflow-hidden top-full mt-2"></div>
            </form>
        </div>



        <!-- Hamburger: visible below lg -->
        <button type="button" @click="mobileOpen = !mobileOpen"
                aria-label="Menu" aria-expanded="false"
                class="lg:hidden flex items-center justify-center w-10 h-10 rounded-xl border border-sky-950/70 bg-[#0F192E]/70 text-sky-300 hover:border-sky-500/60 hover:text-sky-400 transition-all duration-300 active:scale-95 relative z-[80]">
            <svg x-show="!mobileOpen" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                <path d="M3 6h18M3 12h18M3 18h18"/>
            </svg>
            <svg x-show="mobileOpen" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                <path d="M6 6l12 12M18 6L6 18"/>
            </svg>
        </button>
    </div>
</nav>

<!-- ===== Mobile / Tablet Navigation Drawer ===== -->
<div x-cloak @keydown.escape.window="mobileOpen = false"
     class="fixed inset-0 z-[70] lg:hidden pointer-events-none">
    <!-- Backdrop -->
    <div x-show="mobileOpen" x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
         @click="mobileOpen = false"
         class="absolute inset-0 bg-[#050914]/80 backdrop-blur-sm pointer-events-auto">
    </div>

    <!-- Panel -->
    <div x-show="mobileOpen"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full"
         @click.away="mobileOpen = false"
         class="absolute top-0 right-0 h-full w-[19rem] max-w-[87vw] bg-[#0B1220]/98 border-l border-sky-950/60 flex flex-col pointer-events-auto shadow-2xl shadow-sky-950/50">

        <!-- Drawer header -->
        <div class="flex items-center justify-between px-5 py-4 border-b border-sky-950/70">
            <span class="text-lg font-bold tracking-wider text-white">
                <span>Vizzio</span><span class="text-sky-400">Stream</span>
            </span>
            <button @click="mobileOpen = false" aria-label="Tutup menu"
                    class="w-9 h-9 flex items-center justify-center rounded-xl border border-sky-950/70 text-slate-400 hover:text-white hover:border-sky-500/60 transition duration-300">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-linecap="round" stroke-width="2">
                    <path d="M6 6l12 12M18 6L6 18"/>
                </svg>
            </button>
        </div>

        <!-- Mobile Search (top) -->
        <div class="px-4 pt-4 pb-1 border-b border-sky-950/60">
            <form action="{{ route('search') }}" method="GET" class="relative" role="search">
                <input type="text" name="q" value="{{ request('q') ?? '' }}" placeholder="Cari film, anime..."
                       autocomplete="off"
                       class="w-full bg-[#0F192E] border border-sky-950/70 text-sm text-white rounded-xl py-2.5 pl-10 pr-3 focus:outline-none focus:border-sky-500 transition duration-300 placeholder-slate-500">
                <button type="submit" aria-label="Cari" class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-500 hover:text-sky-400 transition duration-300">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </button>
                <div class="search-suggestions absolute z-30 w-full bg-[#0F192E] border border-sky-950/60 rounded-lg shadow-lg hidden overflow-hidden top-full mt-2"></div>
            </form>
        </div>

        <!-- Scrollable nav list -->
        <div class="flex-1 overflow-y-auto px-3 py-3 space-y-1">
            <!-- Home -->
            <a href="/" @click="mobileOpen=false" class="flex items-center gap-3 px-3 py-3 rounded-xl text-sm text-slate-200 hover:text-white hover:bg-sky-500/10 border border-transparent hover:border-sky-500/30 transition duration-300">
                <svg class="w-5 h-5 text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"><path d="M3 10.5L12 3l9 7.5V21a1 1 0 01-1 1h-5v-7h-6v7H4a1 1 0 01-1-1z"/></svg>
                Home
            </a>

            <!-- Genre (collapsible) -->
            <div x-data="{ open: false }">
                <button @click="open = !open" class="w-full flex items-center justify-between px-3 py-3 rounded-xl text-sm text-slate-200 hover:text-white hover:bg-sky-500/10 border border-transparent hover:border-sky-500/30 transition duration-300">
                    <span class="flex items-center gap-3">
                        <svg class="w-5 h-5 text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"><path d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h11a2 2 0 012 2v12a4 4 0 01-4 4zM7 3v18M7 3h4"/></svg>
                        Genre
                    </span>
                    <svg class="w-4 h-4 transition-transform duration-300" :class="open ? 'rotate-180 text-sky-400' : 'text-slate-400'" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div x-show="open" x-collapse class="pl-4 pt-1">
                    <div class="grid grid-cols-2 gap-1 pb-2">
                        @forelse($navbarGenres ?? [] as $genre)
                            <a href="{{ route('genre.show', ['genreId' => $genre['slug']]) }}" @click="mobileOpen=false" class="block px-3 py-2 rounded-lg text-xs text-slate-400 hover:text-sky-400 hover:bg-sky-500/10 transition duration-200 truncate">{{ $genre['title'] }}</a>
                        @empty
                            <p class="col-span-2 px-3 py-2 text-xs text-slate-500">Genre tidak tersedia.</p>
                        @endforelse
                    </div>
                    <a href="{{ route('genre.index') }}" @click="mobileOpen=false" class="inline-flex items-center gap-1 text-[11px] font-semibold text-sky-400 hover:text-sky-300 px-3 pb-2">Lihat Semua Genre &rarr;</a>
                </div>
            </div>

            <!-- Series (collapsible) -->
            <div x-data="{ open: false }">
                <button @click="open = !open" class="w-full flex items-center justify-between px-3 py-3 rounded-xl text-sm text-slate-200 hover:text-white hover:bg-sky-500/10 border border-transparent hover:border-sky-500/30 transition duration-300">
                    <span class="flex items-center gap-3">
                        <svg class="w-5 h-5 text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"><path d="M20 7H4a1 1 0 00-1 1v11a1 1 0 001 1h16a1 1 0 001-1V8a1 1 0 00-1-1zM8 3h8M12 3v4"/></svg>
                        Series
                    </span>
                    <svg class="w-4 h-4 transition-transform duration-300" :class="open ? 'rotate-180 text-sky-400' : 'text-slate-400'" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div x-show="open" x-collapse class="pl-4 pt-1 pb-2 space-y-0.5">
                    <p class="px-3 pt-1 text-[10px] font-bold uppercase tracking-widest text-emerald-400 flex items-center gap-1.5"><span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>Ongoing</p>
                    @forelse($navbarOngoing ?? [] as $anime)
                        <a href="{{ route('anime.show', ['animeId' => $anime['slug'] ?? '#']) }}" @click="mobileOpen=false" class="block px-3 py-2 rounded-lg text-xs text-slate-400 hover:text-sky-400 hover:bg-sky-500/10 transition duration-200 truncate">{{ $anime['title'] ?? 'Anime' }}</a>
                    @empty
                        <p class="px-3 py-1 text-xs text-slate-500">Belum tersedia.</p>
                    @endforelse
                    <p class="px-3 pt-2 text-[10px] font-bold uppercase tracking-widest text-amber-400">Completed</p>
                    @forelse($navbarCompleted ?? [] as $anime)
                        <a href="{{ route('anime.show', ['animeId' => $anime['slug'] ?? '#']) }}" @click="mobileOpen=false" class="block px-3 py-2 rounded-lg text-xs text-slate-400 hover:text-sky-400 hover:bg-sky-500/10 transition duration-200 truncate">{{ $anime['title'] ?? 'Anime' }}</a>
                    @empty
                        <p class="px-3 py-1 text-xs text-slate-500">Belum tersedia.</p>
                    @endforelse
                    <a href="/anime" @click="mobileOpen=false" class="inline-flex items-center gap-1 text-[11px] font-semibold text-sky-400 hover:text-sky-300 px-3 pt-1">Lihat Semua Series &rarr;</a>
                </div>
            </div>

            <!-- Favorit -->
            <a href="{{ route('anime.favorites') }}" @click="mobileOpen=false"
               class="flex items-center gap-3 px-3 py-3 rounded-xl text-sm text-slate-200 hover:text-white hover:bg-rose-500/10 border border-transparent hover:border-rose-500/30 transition duration-300 relative">
                <svg class="w-5 h-5 text-rose-400 fill-current" viewBox="0 0 24 24">
                    <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                </svg>
                <span class="flex-1">Favorit</span>
                @php $favCount = count(session('favorites', [])); @endphp
                @if($favCount > 0)
                    <span class="min-w-[20px] h-5 px-1.5 rounded-full bg-rose-500 text-white text-[10px] font-bold flex items-center justify-center">
                        {{ $favCount > 99 ? '99+' : $favCount }}
                    </span>
                @endif
            </a>

            <!-- Negara (collapsible) -->
            <div x-data="{ open: false }">
                <button @click="open = !open" class="w-full flex items-center justify-between px-3 py-3 rounded-xl text-sm text-slate-200 hover:text-white hover:bg-sky-500/10 border border-transparent hover:border-sky-500/30 transition duration-300">
                    <span class="flex items-center gap-3">
                        <svg class="w-5 h-5 text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"><path d="M12 21s-7-5.2-7-11a7 7 0 0114 0c0 5.8-7 11-7 11z"/><circle cx="12" cy="10" r="2.5"/></svg>
                        Negara
                    </span>
                    <svg class="w-4 h-4 transition-transform duration-300" :class="open ? 'rotate-180 text-sky-400' : 'text-slate-400'" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div x-show="open" x-collapse class="pl-4 pt-1">
                    <div class="grid grid-cols-2 gap-1 pb-2">
                        @forelse($navbarCountries ?? [] as $country)
                            <a href="{{ route('property.show', ['type' => 'country', 'propertyId' => $country['slug']]) }}" @click="mobileOpen=false" class="block px-3 py-2 rounded-lg text-xs text-slate-400 hover:text-sky-400 hover:bg-sky-500/10 transition duration-200 truncate">{{ $country['title'] }}</a>
                        @empty
                            <p class="col-span-2 px-3 py-2 text-xs text-slate-500">Negara tidak tersedia.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Tahun (collapsible) -->
            <div x-data="{ open: false }">
                <button @click="open = !open" class="w-full flex items-center justify-between px-3 py-3 rounded-xl text-sm text-slate-200 hover:text-white hover:bg-sky-500/10 border border-transparent hover:border-sky-500/30 transition duration-300">
                    <span class="flex items-center gap-3">
                        <svg class="w-5 h-5 text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"><rect x="3" y="5" width="18" height="16" rx="2"/><path d="M8 3v4M16 3v4M3 10h18"/></svg>
                        Tahun
                    </span>
                    <svg class="w-4 h-4 transition-transform duration-300" :class="open ? 'rotate-180 text-sky-400' : 'text-slate-400'" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div x-show="open" x-collapse class="pl-4 pt-1">
                    <div class="grid grid-cols-3 gap-1 pb-2">
                        @forelse($navbarYears ?? [] as $year)
                            <a href="{{ route('property.show', ['type' => 'year', 'propertyId' => $year['slug']]) }}" @click="mobileOpen=false" class="block px-3 py-2 rounded-lg text-center text-xs text-slate-400 hover:text-sky-400 hover:bg-sky-500/10 transition duration-200 truncate">{{ $year['title'] }}</a>
                        @empty
                            <p class="col-span-3 px-3 py-2 text-xs text-slate-500 text-center">Tahun tidak tersedia.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Anime -->
            <a href="{{ route('anime.index') }}" @click="mobileOpen=false" class="flex items-center gap-3 px-3 py-3 rounded-xl text-sm text-slate-200 hover:text-white hover:bg-sky-500/10 border border-transparent hover:border-sky-500/30 transition duration-300">
                <svg class="w-5 h-5 text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"><rect x="3" y="5" width="6" height="14" rx="1" transform="rotate(0)"/><rect x="15" y="5" width="6" height="14" rx="1"/><rect x="10.5" y="3" width="3" height="18"/></svg>
                Anime
            </a>
        </div>


    </div>
</div>
</div>
