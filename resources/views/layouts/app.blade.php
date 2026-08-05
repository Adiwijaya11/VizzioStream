<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="VizzioStream - Platform streaming film, anime, dan serial modern.">
    <title>VizzioStream</title>

    <!-- Favicon Logo -->
    <link rel="icon" type="image/svg+xml" href="{{ asset('logo.svg') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Scripts and Styles via Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Lenis Smooth Scroll --}}
    <link rel="stylesheet" href="https://unpkg.com/lenis@1.1.18/dist/lenis.css">
    <script src="https://unpkg.com/lenis@1.1.18/dist/lenis.min.js"></script>
    <style>
        html.lenis, html.lenis body { height: auto; }
        .lenis.lenis-smooth { scroll-behavior: auto !important; }
        .lenis.lenis-stopped { overflow: hidden; }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-[#090D16] text-white min-h-screen flex flex-col font-sans antialiased selection:bg-sky-500 selection:text-white relative">

    <!-- Ambient Dark Blue Canvas Glows -->
    <div class="fixed top-0 left-1/4 w-[500px] h-[500px] bg-sky-600/10 blur-[140px] rounded-full pointer-events-none z-0"></div>
    <div class="fixed bottom-0 right-10 w-[400px] h-[400px] bg-blue-700/10 blur-[130px] rounded-full pointer-events-none z-0"></div>

    <!-- Navbar -->
    @include('layouts.navbar')

    <!-- Layout Container -->
    <div class="flex-1 pt-16 relative z-10 flex flex-col">
        <!-- Main Content Area -->
        <main class="flex-1 flex flex-col min-w-0">
            <div class="flex-1">
                @yield('content')
            </div>

            <!-- Footer -->
            @include('layouts.footer')
        </main>
    </div>

    {{-- Page-specific scripts --}}
    @stack('scripts')
    {{-- Alpine.js CDN --}}
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    {{-- Lenis Smooth Scroll Init --}}
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const lenis = new Lenis({
                duration: 1.1,
                easing: (t) => Math.min(1, 1.001 - Math.pow(2, -10 * t)),
                smoothWheel: true,
                wheelMultiplier: 1,
                touchMultiplier: 1.5,
            });

            function raf(time) {
                lenis.raf(time);
                requestAnimationFrame(raf);
            }
            requestAnimationFrame(raf);

            // Sync with anchor links
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', (e) => {
                    const target = document.querySelector(anchor.getAttribute('href'));
                    if (target) {
                        e.preventDefault();
                        lenis.scrollTo(target, { offset: -80, duration: 1.2 });
                    }
                });
            });

            window.lenis = lenis;
        });
    </script>
</body>
</html>
