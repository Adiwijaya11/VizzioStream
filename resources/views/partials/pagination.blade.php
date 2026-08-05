{{-- Premium pagination controls for section lists --}}
@php
    $current = $pagination['currentPage'] ?? 1;
    $total   = $pagination['totalPages'] ?? 1;
    $anchor  = $anchor ?? '';
@endphp

@if(($pagination['totalPages'] ?? 1) > 1)
    <nav class="flex flex-wrap items-center justify-center gap-2 pt-10 pb-2 px-1" aria-label="Paginasi halaman">
        {{-- Prev --}}
        @if($pagination['hasPrevPage'] ?? false)
            <a href="{{ route($baseRoute, ['page' => $pagination['prevPage']]) }}{{ $anchor }}"
               class="flex items-center gap-1.5 px-3 sm:px-4 py-2 sm:py-2.5 rounded-lg sm:rounded-xl bg-[#0F192E] border border-sky-950/60 text-xs sm:text-sm font-semibold text-slate-300 hover:bg-sky-500/10 hover:text-sky-400 hover:border-sky-500/40 transition-all duration-300">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                <span class="hidden xs:inline">{{ __('Sebelumnya') }}</span>
            </a>
        @else
            <span class="flex items-center gap-1.5 px-3 sm:px-4 py-2 sm:py-2.5 rounded-lg sm:rounded-xl bg-[#0F192E]/50 border border-sky-950/40 text-xs sm:text-sm font-semibold text-slate-600 cursor-not-allowed">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                <span class="hidden xs:inline">{{ __('Sebelumnya') }}</span>
            </span>
        @endif

        {{-- Page numbers (window of 5 around current; narrowed to 3 on the smallest screens) --}}
        @php
            $window = 2; // default: 5 numbers (current ± 2)
            if ($total > 5) {
                // On small screens show a tighter window so it fits without overflow
                $window = 2;
            }
            $start = max(1, $current - $window);
            $end   = min($total, $start + ($window * 2));
            $start = max(1, $end - ($window * 2));
        @endphp

        @if($start > 1)
            <a href="{{ route($baseRoute, ['page' => 1]) }}{{ $anchor }}"
               class="w-9 h-9 sm:w-10 sm:h-10 flex items-center justify-center rounded-lg sm:rounded-xl bg-[#0F192E] border border-sky-950/60 text-xs sm:text-sm font-semibold text-slate-300 hover:bg-sky-500/10 hover:text-sky-400 transition-all duration-300">1</a>
            @if($start > 2)
                <span class="w-9 h-9 sm:w-10 sm:h-10 flex items-center justify-center text-sm text-slate-500">…</span>
            @endif
        @endif

        @for($i = $start; $i <= $end; $i++)
            @if($i === $current)
                <span class="w-9 h-9 sm:w-10 sm:h-10 flex items-center justify-center rounded-lg sm:rounded-xl bg-gradient-to-br from-sky-500 to-blue-600 text-white text-xs sm:text-sm font-bold shadow-lg shadow-sky-500/30 ring-1 ring-sky-400/50">{{ $i }}</span>
            @else
                <a href="{{ route($baseRoute, ['page' => $i]) }}{{ $anchor }}"
                   class="w-9 h-9 sm:w-10 sm:h-10 flex items-center justify-center rounded-lg sm:rounded-xl bg-[#0F192E] border border-sky-950/60 text-xs sm:text-sm font-semibold text-slate-300 hover:bg-sky-500/10 hover:text-sky-400 hover:border-sky-500/40 transition-all duration-300">{{ $i }}</a>
            @endif
        @endfor

        @if($end < $total)
            @if($end < $total - 1)
                <span class="w-9 h-9 sm:w-10 sm:h-10 flex items-center justify-center text-sm text-slate-500">…</span>
            @endif
            <a href="{{ route($baseRoute, ['page' => $total]) }}{{ $anchor }}"
               class="w-9 h-9 sm:w-10 sm:h-10 flex items-center justify-center rounded-lg sm:rounded-xl bg-[#0F192E] border border-sky-950/60 text-xs sm:text-sm font-semibold text-slate-300 hover:bg-sky-500/10 hover:text-sky-400 transition-all duration-300">{{ $total }}</a>
        @endif

        {{-- Next --}}
        @if($pagination['hasNextPage'] ?? false)
            <a href="{{ route($baseRoute, ['page' => $pagination['nextPage']]) }}{{ $anchor }}"
               class="flex items-center gap-1.5 px-3 sm:px-4 py-2 sm:py-2.5 rounded-lg sm:rounded-xl bg-[#0F192E] border border-sky-950/60 text-xs sm:text-sm font-semibold text-slate-300 hover:bg-sky-500/10 hover:text-sky-400 hover:border-sky-500/40 transition-all duration-300">
                <span class="hidden xs:inline">{{ __('Berikutnya') }}</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
        @else
            <span class="flex items-center gap-1.5 px-3 sm:px-4 py-2 sm:py-2.5 rounded-lg sm:rounded-xl bg-[#0F192E]/50 border border-sky-950/40 text-xs sm:text-sm font-semibold text-slate-600 cursor-not-allowed">
                <span class="hidden xs:inline">{{ __('Berikutnya') }}</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </span>
        @endif
    </nav>
@endif
