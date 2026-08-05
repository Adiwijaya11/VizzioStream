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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        Pencarian
                    </span>
                    <h1 class="text-4xl md:text-6xl font-black tracking-tighter text-white drop-shadow-2xl mt-2">
                        @if($query)
                            Hasil <span class="text-sky-400">Pencarian</span>
                        @else
                            Cari Anime
                        @endif
                    </h1>
                    
                    @if($query)
                        <div class="mt-6 flex items-center gap-3">
                            <span class="inline-flex items-center gap-2 bg-gradient-to-r from-sky-500 to-blue-600 text-white font-bold px-4 py-1.5 rounded-full shadow-lg shadow-sky-500/20 text-sm">
                                "{{ $query }}"
                            </span>
                            <span class="text-slate-400 text-sm font-medium">
                                {{ $total }} judul ditemukan
                            </span>
                        </div>
                    @else
                        <p class="text-slate-400 mt-4 max-w-xl leading-relaxed text-sm md:text-base">
                            Temukan dunia anime favoritmu. Gunakan kolom pencarian di navbar untuk memulai penjelajahan.
                        </p>
                    @endif
                </div>
            </div>
        </header>

        {{-- Results --}}
        <section>
            @if($query)
                @include('partials.anime-grid', [
                    'gridItems' => $items,
                    'error'     => $error,
                ])
            @else
                <div class="flex flex-col items-center justify-center text-center py-24 space-y-6 group">
                    <div class="relative w-24 h-24 flex items-center justify-center">
                        <div class="absolute inset-0 bg-sky-500/10 rounded-full animate-pulse group-hover:scale-110 transition-transform duration-500"></div>
                        <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-sky-500/20 to-blue-600/20 border border-sky-500/30 flex items-center justify-center shadow-xl shadow-sky-900/20">
                            <svg class="w-8 h-8 text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="space-y-1">
                        <h3 class="text-xl font-black text-white tracking-tight">Belum Ada Pencarian</h3>
                        <p class="text-slate-400 text-sm max-w-xs">Gunakan kolom pencarian di bagian atas untuk menemukan anime yang kamu inginkan.</p>
                    </div>
                </div>
            @endif
        </section>
    </div>
</div>
@endsection
