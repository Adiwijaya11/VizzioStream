@extends('layouts.app')

@section('content')

    <!-- HERO SECTION: Full Width -->
    <section id="hero-section" class="relative min-h-[82vh] flex items-center overflow-hidden border-b border-sky-950/40 shadow-2xl">

        <!-- Background Hero Image (blurred/upscaled so the low-res poster stays smooth) -->
        @if(!empty($featured->poster))
            <img src="{{ $featured->poster }}"
                 alt="{{ $featured->title }}"
                 class="absolute inset-0 w-full h-full object-cover object-center z-[1] blur-3xl scale-125 brightness-[0.55] saturate-[1.3]">
        @else
            <img src="https://images.unsplash.com/photo-1578632767115-351597cf2477?w=1600&auto=format&fit=crop&q=80"
                 alt="Anime terbaru"
                 class="absolute inset-0 w-full h-full object-cover object-center z-[1]">
        @endif

        <!-- Dark Gradient Overlay for Readability -->
        <div class="absolute inset-0 z-[2] bg-gradient-to-t from-[#090D16] via-[#090D16]/80 to-transparent"></div>
        <div class="absolute inset-0 z-[2] bg-gradient-to-r from-[#090D16] via-[#090D16]/70 to-transparent max-w-3xl"></div>

        <!-- GSAP Animated Gradient Blobs (above overlay, mix-blend overlay) -->
        <div id="hero-gradient-bg" class="absolute inset-0 z-[3] overflow-hidden" aria-hidden="true">
            <!-- Blob 1: Sky Blue -->
            <div id="blob1" class="absolute w-[700px] h-[700px] rounded-full pointer-events-none" style="background: radial-gradient(circle, rgba(14,165,233,0.22), transparent 70%); top: -180px; left: -120px; filter: blur(110px);"></div>
            <!-- Blob 2: Indigo/Purple -->
            <div id="blob2" class="absolute w-[550px] h-[550px] rounded-full pointer-events-none" style="background: radial-gradient(circle, rgba(99,102,241,0.22), transparent 70%); bottom: -100px; right: 5%; filter: blur(130px);"></div>
            <!-- Blob 3: Cyan -->
            <div id="blob3" class="absolute w-[420px] h-[420px] rounded-full pointer-events-none" style="background: radial-gradient(circle, rgba(34,211,238,0.15), transparent 70%); top: 25%; right: 28%; filter: blur(100px);"></div>
        </div>

        <!-- GSAP Floating Geometric Shapes (premium outlined SVGs, animated below) -->
        <svg width="0" height="0" class="absolute" aria-hidden="true">
            <defs>
                <linearGradient id="shape-grad-sky" x1="0" y1="0" x2="1" y2="1">
                    <stop stop-color="#38BDF8"/>
                    <stop offset="1" stop-color="#6366F1"/>
                </linearGradient>
                <linearGradient id="shape-grad-cyan" x1="0" y1="0" x2="1" y2="1">
                    <stop stop-color="#22D3EE"/>
                    <stop offset="1" stop-color="#3B82F6"/>
                </linearGradient>
                <linearGradient id="shape-grad-indigo" x1="0" y1="0" x2="1" y2="1">
                    <stop stop-color="#818CF8"/>
                    <stop offset="1" stop-color="#38BDF8"/>
                </linearGradient>
            </defs>
        </svg>
        <div id="hero-shapes" class="absolute inset-0 z-[3] pointer-events-none overflow-hidden" aria-hidden="true">

            <!-- 1. Outlined square (double frame) -->
            <svg class="shape" width="64" height="64" viewBox="0 0 64 64" fill="none" style="top: 14%; right: 8%; opacity: 0.7;">
                <rect x="1.5" y="1.5" width="61" height="61" rx="14" stroke="url(#shape-grad-sky)" stroke-width="1.5"/>
                <rect x="12" y="12" width="40" height="40" rx="10" stroke="url(#shape-grad-sky)" stroke-width="0.8" opacity="0.45"/>
            </svg>

            <!-- 2. Diamond (rotated square with center dot) -->
            <svg class="shape" width="46" height="46" viewBox="0 0 46 46" fill="none" style="top: 8%; right: 26%; opacity: 0.6;">
                <rect x="1.5" y="1.5" width="43" height="43" rx="6" stroke="url(#shape-grad-cyan)" stroke-width="1.5" transform="rotate(45 23 23)"/>
                <circle cx="23" cy="23" r="5" fill="url(#shape-grad-cyan)" opacity="0.7"/>
            </svg>

            <!-- 3. Ring circle with inner dot -->
            <svg class="shape" width="40" height="40" viewBox="0 0 40 40" fill="none" style="top: 38%; right: 12%; opacity: 0.5;">
                <circle cx="20" cy="20" r="17" stroke="url(#shape-grad-indigo)" stroke-width="1.5"/>
                <circle cx="20" cy="20" r="5" fill="url(#shape-grad-indigo)" opacity="0.5"/>
            </svg>

            <!-- 4. Triangle outline -->
            <svg class="shape" width="52" height="52" viewBox="0 0 52 52" fill="none" style="top: 58%; right: 6%; opacity: 0.55;">
                <path d="M26 4 L48 46 L4 46 Z" stroke="url(#shape-grad-sky)" stroke-width="1.5" stroke-linejoin="round"/>
                <circle cx="26" cy="20" r="3" fill="url(#shape-grad-sky)" opacity="0.6"/>
            </svg>

            <!-- 5. Dashed ring (premium detail) -->
            <svg class="shape" width="70" height="70" viewBox="0 0 70 70" fill="none" style="top: 22%; right: 42%; opacity: 0.35;">
                <circle cx="35" cy="35" r="30" stroke="url(#shape-grad-cyan)" stroke-width="1.2" stroke-dasharray="4 8"/>
                <circle cx="35" cy="35" r="30" stroke="url(#shape-grad-cyan)" stroke-width="0.5" opacity="0.4"/>
            </svg>

            <!-- 6. Small solid square with glow -->
            <svg class="shape" width="14" height="14" viewBox="0 0 14 14" fill="none" style="top: 72%; right: 24%; opacity: 0.8;">
                <rect x="1" y="1" width="12" height="12" rx="3" fill="url(#shape-grad-sky)"/>
            </svg>

            <!-- 7. Plus / cross -->
            <svg class="shape" width="34" height="34" viewBox="0 0 34 34" fill="none" style="top: 30%; right: 58%; opacity: 0.4;">
                <path d="M17 4 V30 M4 17 H30" stroke="url(#shape-grad-indigo)" stroke-width="1.5" stroke-linecap="round"/>
            </svg>

            <!-- 8. Overlapping squares -->
            <svg class="shape" width="58" height="58" viewBox="0 0 58 58" fill="none" style="top: 48%; right: 36%; opacity: 0.4;">
                <rect x="2" y="2" width="40" height="40" rx="8" stroke="url(#shape-grad-sky)" stroke-width="1.2"/>
                <rect x="16" y="16" width="40" height="40" rx="8" stroke="url(#shape-grad-cyan)" stroke-width="1.2" opacity="0.8"/>
            </svg>

            <!-- 9. Small diamond outline -->
            <svg class="shape" width="20" height="20" viewBox="0 0 20 20" fill="none" style="top: 64%; right: 50%; opacity: 0.5;">
                <rect x="3" y="3" width="14" height="14" rx="2" stroke="url(#shape-grad-cyan)" stroke-width="1.2" transform="rotate(45 10 10)"/>
            </svg>

            <!-- 10. Tiny dot trio -->
            <svg class="shape" width="30" height="30" viewBox="0 0 30 30" fill="none" style="top: 12%; right: 64%; opacity: 0.45;">
                <circle cx="6" cy="6" r="2.5" fill="url(#shape-grad-indigo)"/>
                <circle cx="22" cy="10" r="2" fill="url(#shape-grad-sky)"/>
                <circle cx="14" cy="24" r="2.5" fill="url(#shape-grad-cyan)"/>
            </svg>

        </div>

        <!-- GSAP Particle Float Field Canvas (above blobs) -->
        <canvas id="hero-particles" class="absolute inset-0 w-full h-full z-[4] pointer-events-none" aria-hidden="true"></canvas>

        <!-- Featured Banner Content (above all layers) -->
        <div class="relative z-[10] px-5 sm:px-10 lg:px-20 pb-12 md:pb-16 pt-8 md:pt-0 max-w-7xl mx-auto w-full flex flex-col md:flex-row items-center md:items-center justify-center md:justify-between gap-8 md:gap-10">
            <div class="space-y-5 max-w-4xl w-full">
                <!-- Popular Badge with Fire SVG -->
                <div class="inline-flex items-center space-x-2 bg-gradient-to-r from-amber-500/20 to-sky-500/20 border border-amber-500/40 text-amber-300 px-4 py-1.5 rounded-full text-[11px] sm:text-xs md:text-sm font-semibold tracking-wide uppercase backdrop-blur-md shadow-lg">
                    <svg class="w-4 h-4 shrink-0" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M15.362 5.214A8.252 8.252 0 0 1 12 21 8.25 8.25 0 0 1 6.038 7.047 8.287 8.287 0 0 0 9 9.601a8.983 8.983 0 0 1 3.361-6.867 8.21 8.21 0 0 0 3 2.48Z" fill="url(#flame-grad)"/>
                        <path d="M12 18a3.75 3.75 0 0 0 .495-7.468 5.99 5.99 0 0 0-1.925 3.547A3.75 3.75 0 0 0 12 18Z" fill="#FDE047"/>
                        <defs>
                            <linearGradient id="flame-grad" x1="6" y1="2.7" x2="15.4" y2="21" gradientUnits="userSpaceOnUse">
                                <stop stop-color="#F97316"/>
                                <stop offset="1" stop-color="#EF4444"/>
                            </linearGradient>
                        </defs>
                    </svg>
                    <span>
                        @if(!empty($featured->date))
                            #1 Update Terbaru Hari Ini
                        @else
                            #1 Anime Populer Minggu Ini
                        @endif
                    </span>
                </div>

                <!-- Big Film Title -->
                <h1 class="text-3xl xs:text-4xl sm:text-5xl md:text-6xl lg:text-7xl font-black tracking-tight text-white leading-[1.05] sm:leading-none drop-shadow-lg">
                    {{ $featured->title ?? 'Cyberpunk: Neon City 2099' }}
                </h1>

                <!-- Meta Stats & Categories -->
                <div class="flex flex-wrap items-center gap-2.5 md:gap-3 text-xs md:text-sm text-slate-300">
                    <span class="bg-slate-800/80 border border-slate-700 px-2.5 py-1 rounded-md font-medium text-slate-200 flex items-center space-x-1.5">
                        <svg class="w-3.5 h-3.5 text-sky-400 fill-current" viewBox="0 0 24 24">
                            <path d="M8 5v14l11-7z"/>
                        </svg>
                        <span>Episode {{ $featured->episodes ?? 0 }}</span>
                    </span>
                    @if(!empty($featured->episodeLabel))
                        <span class="bg-sky-950/80 border border-sky-900/60 px-2.5 py-1 rounded-md text-sky-300 font-medium">Update {{ $featured->episodeLabel }}</span>
                    @elseif(!empty($featured->date))
                        <span class="bg-sky-950/80 border border-sky-900/60 px-2.5 py-1 rounded-md text-sky-300 font-medium">Update {{ $featured->date }}</span>
                    @endif
                    @if(!empty($featured->releaseDay) && $featured->releaseDay !== '-')
                        <span class="bg-sky-500/10 border border-sky-500/30 px-2.5 py-1 rounded-md text-sky-400 font-semibold">Update {{ $featured->releaseDay }}</span>
                    @endif
                    @if(!empty($featured->url))
                        <span class="text-slate-400">&bull; <a href="{{ $featured->url }}" target="_blank" rel="noopener" class="text-sky-400 hover:text-sky-300 hover:underline">Sumber: Otakudesu</a></span>
                    @endif
                </div>

                <!-- Synopsis -->
                <p class="text-slate-300 text-sm md:text-base leading-relaxed line-clamp-3 sm:line-clamp-4 md:line-clamp-none max-w-2xl">
                    {{ $featured->description ?? 'Di dunia dystopian masa depan di mana teknologi dan kejahatan bersatu, seorang mercenary cybernetic berjuang mengungkap konspirasi terbesar di Neon City untuk menyelamatkan umat manusia.' }}
                </p>

                <!-- Buttons -->
                <div class="flex flex-wrap items-center gap-3 md:gap-4 pt-2">
                    <a href="{{ $featured->slug ? route('anime.show', $featured->slug) : ($featured->url ?? '#') }}" class="bg-gradient-to-r from-sky-500 to-blue-600 hover:from-sky-400 hover:to-blue-500 text-white px-6 sm:px-8 py-3 md:py-3.5 rounded-xl font-bold shadow-lg shadow-sky-500/30 hover:shadow-sky-500/50 transition-all duration-300 hover:scale-105 active:scale-95 flex items-center space-x-2 grow sm:grow-0 justify-center">
                        <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24">
                            <path d="M8 5v14l11-7z"/>
                        </svg>
                        <span>Putar Sekarang</span>
                    </a>
                    <form method="POST" action="{{ $featured->slug ? route('anime.favorite', ['animeId' => $featured->slug]) : '#' }}" class="grow sm:grow-0">
                        @csrf
                        <input type="hidden" name="title" value="{{ $featured->title ?? 'Anime' }}">
                        <input type="hidden" name="poster" value="{{ $featured->poster ?? '' }}">
                        <button type="submit" id="hero-favorite-button" class="w-full sm:w-auto bg-slate-900/80 hover:bg-slate-800 border border-sky-900/60 hover:border-sky-400 text-white px-6 py-3 md:py-3.5 rounded-xl font-semibold backdrop-blur-md transition-all duration-300 hover:scale-105 flex items-center space-x-2 grow sm:grow-0 justify-center {{ ($isFavorited ?? false) ? '!border-rose-500/60 !text-rose-300 !bg-rose-500/10' : '' }}">
                            <svg id="hero-favorite-icon" class="w-5 h-5 {{ ($isFavorited ?? false) ? 'text-rose-400' : 'text-sky-400' }}" fill="{{ ($isFavorited ?? false) ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            <span>{{ ($isFavorited ?? false) ? 'Di Daftar Saya' : 'Daftar Saya' }}</span>
                        </button>
                    </form>
                </div>
            </div>
            <!-- Sharp Poster Panel (responsive: compact on mobile, crisp on the right on desktop) -->
            @if(!empty($featured->poster))
                <div class="shrink-0 w-32 sm:w-40 md:w-56 lg:w-64 xl:w-80">
                    <div class="relative rounded-xl md:rounded-2xl overflow-hidden shadow-[0_25px_60px_-15px_rgba(2,132,199,0.5)] ring-1 ring-white/15 rotate-2 hover:rotate-0 transition-all duration-500 hover:scale-[1.03] group">
                        <img src="{{ $featured->poster }}" alt="Poster {{ $featured->title }}" class="w-full object-cover" loading="eager">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/10 to-transparent"></div>
                        <div class="absolute bottom-3 left-3 right-3 flex items-center justify-between gap-2">
                            <span class="bg-sky-500/90 text-white text-[11px] font-bold px-2.5 py-1 rounded-md backdrop-blur-sm shadow-lg">
                                {{ $featured->episodeLabel ?? ('EP '.($featured->episodes ?? '?')) }}
                            </span>
                            <span class="bg-black/60 text-sky-300 text-[11px] font-semibold px-2.5 py-1 rounded-md backdrop-blur-sm border border-sky-500/30">
                                {{ $featured->releaseDay ?? 'Terbaru' }}
                            </span>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </section>

    <!-- DYNAMIC ANIME SECTION (replaces static "Keunggulan") -->
    <div class="space-y-16 py-8 px-6 md:px-10 lg:px-16">
        <section id="anime-section" class="space-y-8 scroll-mt-24">
            <!-- Header & Category Filter Tabs -->
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 border-b border-sky-950/60 pb-6">
                <div>
                    @php($total = (int) ($sectionTotal ?? 0))
                    <div class="flex items-center gap-3">
                        <h2 class="text-3xl md:text-4xl font-extrabold text-white tracking-tight leading-tight bg-gradient-to-r from-sky-400 via-sky-300 to-blue-500 text-transparent bg-clip-text drop-shadow-lg">Daftar Lengkap Anime</h2>
                        @if($total > 0)
                            <span class="inline-flex items-center gap-1 rounded-full border border-sky-500/30 bg-sky-500/10 px-3 py-1 text-xs font-semibold text-sky-300 whitespace-nowrap mt-1">
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"></path></svg>
                                {{ number_format($total) }} Judul
                            </span>
                        @endif
                    </div>
                    <p class="text-base text-slate-300 mt-2 max-w-lg leading-relaxed">Seluruh koleksi anime, mulai dari yang sedang tayang (ongoing) hingga yang sudah tamat (completed).</p>
                </div>

                <!-- Filter Dropdown -->
                <div class="relative inline-block text-left" x-data="{ open: false }" @click.outside="open = false">
                    <div>
                        <button type="button" @click="open = !open" class="inline-flex justify-center w-full rounded-md border border-sky-950/60 shadow-sm px-4 py-2 bg-[#0F192E] text-sm font-medium text-slate-300 hover:bg-sky-500/10 hover:text-sky-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-[#090D16] focus:ring-sky-500 transition duration-300">
                            Filter
                            <svg class="-mr-1 ml-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </div>

                    <div x-show="open" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100" x-transition:leave-end="transform opacity-0 scale-95" class="origin-top-right absolute right-0 mt-2 w-56 rounded-md shadow-lg bg-[#0F192E] ring-1 ring-black ring-opacity-5 focus:outline-none z-20">
                        <div class="py-1" role="menu" aria-orientation="vertical" aria-labelledby="options-menu">
                            <a href="#" class="block px-4 py-2 text-sm text-slate-300 hover:bg-sky-500/10 hover:text-sky-400" role="menuitem">Semua</a>
                            <a href="#" class="block px-4 py-2 text-sm text-slate-300 hover:bg-sky-500/10 hover:text-sky-400" role="menuitem">Action</a>
                            <a href="#" class="block px-4 py-2 text-sm text-slate-300 hover:bg-sky-500/10 hover:text-sky-400" role="menuitem">Petualangan</a>
                            <a href="#" class="block px-4 py-2 text-sm text-slate-300 hover:bg-sky-500/10 hover:text-sky-400" role="menuitem">Animasi</a>
                            <a href="#" class="block px-4 py-2 text-sm text-slate-300 hover:bg-sky-500/10 hover:text-sky-400" role="menuitem">Komedi</a>
                            <a href="#" class="block px-4 py-2 text-sm text-slate-300 hover:bg-sky-500/10 hover:text-sky-400" role="menuitem">Drama</a>
                            <a href="#" class="block px-4 py-2 text-sm text-slate-300 hover:bg-sky-500/10 hover:text-sky-400" role="menuitem">Fantasi</a>
                            <a href="#" class="block px-4 py-2 text-sm text-slate-300 hover:bg-sky-500/10 hover:text-sky-400" role="menuitem">Horor</a>
                            <a href="#" class="block px-4 py-2 text-sm text-slate-300 hover:bg-sky-500/10 hover:text-sky-400" role="menuitem">Misteri</a>
                            <a href="#" class="block px-4 py-2 text-sm text-slate-300 hover:bg-sky-500/10 hover:text-sky-400" role="menuitem">Romansa</a>
                            <a href="#" class="block px-4 py-2 text-sm text-slate-300 hover:bg-sky-500/10 hover:text-sky-400" role="menuitem">Sci-Fi</a>
                            <a href="#" class="block px-4 py-2 text-sm text-slate-300 hover:bg-sky-500/10 hover:text-sky-400" role="menuitem">Thriller</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Dynamic anime grid (30 per page) -->

            {{-- Skeleton loading shown during pagination navigation --}}
            @include('partials.skeleton-grid')
            @include('partials.anime-grid', [
                'gridItems' => $sectionItems,
                'error'     => $error,
            ])

            <!-- Pagination (30 per page) -->
            @if(count($sectionItems) && ($sectionPagination['totalPages'] ?? 1) > 1)
                @include('partials.pagination', [
                    'baseRoute'  => 'welcome',
                    'pagination' => $sectionPagination,
                    'anchor'     => '#anime-section',
                ])
            @endif
        </section>
    </div>

@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    /* =============================================
       1. GSAP ANIMATED GRADIENT BACKGROUND
          Blob positions shift and flow continuously
       ============================================= */
    const blob1 = document.getElementById('blob1');
    const blob2 = document.getElementById('blob2');
    const blob3 = document.getElementById('blob3');

    if (blob1 && blob2 && blob3) {
        // Blob 1: very slow drift — sky blue
        gsap.to(blob1, {
            x: 150,
            y: 100,
            duration: 18,
            repeat: -1,
            yoyo: true,
            ease: 'sine.inOut',
        });

        // Blob 2: very slow drift — indigo
        gsap.to(blob2, {
            x: -120,
            y: -90,
            duration: 22,
            repeat: -1,
            yoyo: true,
            ease: 'sine.inOut',
            delay: 2,
        });

        // Blob 3: gentle pulse & drift — cyan
        gsap.to(blob3, {
            x: 70,
            y: -50,
            scale: 1.15,
            duration: 16,
            repeat: -1,
            yoyo: true,
            ease: 'sine.inOut',
            delay: 1,
        });
    }

    /* =============================================
       1b. FLOATING GEOMETRIC SHAPES
          Premium SVG shapes float, rotate & drift
          upward with randomized timing
          (fewer shapes on mobile for clarity/perf)
       ============================================= */
    const isMobile = window.matchMedia('(max-width: 767px)').matches;
    let shapes = Array.from(document.querySelectorAll('#hero-shapes .shape'));
    if (isMobile) {
        // Keep only the 5 largest/most visible shapes on small screens
        shapes.slice(5).forEach(function (s) { s.classList.add('hidden'); });
    }
    if (shapes.length) {
        shapes.forEach(function (shape) {
            const rand = (min, max) => min + Math.random() * (max - min);
            const pick = (arr) => arr[Math.floor(Math.random() * arr.length)];

            const dur = rand(7, 14);          // total drift duration
            const floatY = rand(30, 90);      // vertical bounce distance
            const swayX = rand(-40, 40);      // horizontal sway
            const spin = pick([-140, -90, -50, 50, 90, 140, 180]); // rotation degrees
            const scalePulse = rand(1.05, 1.22);

            // Randomised entrance (fade + rise from below)
            gsap.fromTo(shape, {
                y: 40,
                opacity: 0,
                scale: 0.7,
            }, {
                y: 0,
                opacity: parseFloat(shape.style.opacity || '0.6'),
                scale: 1,
                duration: rand(1.6, 3),
                ease: 'power2.out',
                delay: rand(0, 1.5),
            });

            // Continuous float, drift & rotate
            gsap.to(shape, {
                y: floatY,
                x: swayX,
                rotation: spin,
                scale: scalePulse,
                duration: dur,
                repeat: -1,
                yoyo: true,
                ease: 'sine.inOut',
                delay: rand(0, 1),
                onRepeat: function () {
                    gsap.to(shape, { x: rand(-40, 40), duration: rand(3, 6), overwrite: true });
                },
            });
        });
    }


    /* =============================================
       2. GSAP PARTICLE FLOAT FIELD
          Subtle floating particles drift upward
          with randomized paths
          (fewer particles on mobile for perf)
       ============================================= */
    const canvas = document.getElementById('hero-particles');
    const section = document.getElementById('hero-section');
    if (!canvas || !section) return;

    const ctx = canvas.getContext('2d');

    // Very soft, low-opacity colour palette — subtle glow only
    const colors = [
        'rgba(14, 165, 233, 0.22)',   // sky-500 soft
        'rgba(56, 189, 248, 0.18)',   // sky-400 soft
        'rgba(34, 211, 238, 0.16)',   // cyan-400 soft
        'rgba(99, 102, 241, 0.14)',   // indigo-500 soft
        'rgba(186, 230, 253, 0.12)',  // sky-200 very soft
    ];

    function randomBetween(min, max) {
        return min + Math.random() * (max - min);
    }

    const PARTICLE_COUNT = isMobile ? 18 : 45;
    let particles = [];
    let frame = 0;
    let dpr = 1;

    // Re-seed particles to fill the CURRENT canvas size.
    // This keeps the field covering the whole hero on any viewport.
    // Particles are smaller & dimmer on mobile for a subtler, cleaner look.
    const R_MIN = isMobile ? 0.6 : 0.8;
    const R_MAX = isMobile ? 1.4 : 2.5;
    const OP_MAX = isMobile ? 0.28 : 0.35;
    function seedParticles() {
        const w = canvas.width / dpr;
        const h = canvas.height / dpr;
        particles = [];
        for (let i = 0; i < PARTICLE_COUNT; i++) {
            const p = {
                x: randomBetween(0, w),
                y: randomBetween(0, h),
                radius: randomBetween(R_MIN, R_MAX),
                color: colors[Math.floor(Math.random() * colors.length)],
                opacity: randomBetween(0.08, OP_MAX),    // much softer max opacity
                speedY: randomBetween(-0.08, -0.28),   // 5x slower upward drift
                speedX: randomBetween(-0.05, 0.05),    // barely any horizontal movement
                sway: randomBetween(0.2, 0.7),
                swayOffset: randomBetween(0, Math.PI * 2),
                swaySpeed: randomBetween(0.002, 0.008), // very slow sway oscillation
                // fade-in/out lifecycle
                life: randomBetween(0, 1),
                lifeSpeed: randomBetween(0.0008, 0.003),
            };
            particles.push(p);
        }
    }

    // Size the canvas backing store to the device pixel ratio so particles
    // stay crisp (not blurry) on high-DPI/mobile screens, and match the
    // hero's actual size (its height can change after images/fonts load).
    function resizeCanvas() {
        dpr = window.devicePixelRatio || 1;
        const w = section.clientWidth;
        const h = section.clientHeight;
        // Guard: skip redundant work when dimensions are unchanged.
        if (Math.round(w * dpr) === canvas.width && Math.round(h * dpr) === canvas.height) return;
        canvas.width = Math.round(w * dpr);
        canvas.height = Math.round(h * dpr);
        seedParticles(); // keep particles covering the new size
    }
    resizeCanvas();
    seedParticles();

    // React to ANY size change of the hero — window resizes, orientation
    // changes, and layout shifts from images/fonts loading on mobile.
    if ('ResizeObserver' in window) {
        new ResizeObserver(resizeCanvas).observe(section);
    } else {
        window.addEventListener('resize', resizeCanvas);
    }

    function drawParticles() {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        frame++;

        particles.forEach(function(p) {
            // Sinusoidal horizontal sway
            const swayCurrent = Math.sin(frame * p.swaySpeed + p.swayOffset) * p.sway;

            p.x += p.speedX + swayCurrent * 0.04;
            p.y += p.speedY;

            // Lifecycle opacity fade: breathe in/out gently
            p.life += p.lifeSpeed;
            const lifeFactor = Math.sin(p.life * Math.PI); // 0→1→0 fade
            const currentOpacity = p.opacity * Math.max(0.1, lifeFactor);

            // Reset particle when it floats off the top
            const w = canvas.width / dpr;
            const h = canvas.height / dpr;
            if (p.y < -10) {
                p.y = h + randomBetween(0, 40);
                p.x = randomBetween(0, w);
                p.life = 0;
                p.lifeSpeed = randomBetween(0.0008, 0.003);
            }

            // Draw soft glowing particle (scaled to device pixel ratio)
            const r = p.radius * dpr;
            ctx.save();
            ctx.globalAlpha = Math.min(currentOpacity, 0.4); // hard cap at 0.4
            ctx.beginPath();
            ctx.arc(p.x * dpr, p.y * dpr, r, 0, Math.PI * 2);
            ctx.fillStyle = p.color;
            ctx.shadowColor = p.color;
            ctx.shadowBlur = r * 6; // larger, softer glow halo
            ctx.fill();
            ctx.restore();
        });

        requestAnimationFrame(drawParticles);
    }

    drawParticles();
});
</script>
@endpush
