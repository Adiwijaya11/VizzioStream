{{-- Skeleton loading grid shown while a pagination navigation is in progress --}}
<div id="pagination-skeleton" class="skeleton-grid hidden" aria-hidden="true">
    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-3 sm:gap-5 lg:gap-6">
        @for($i = 0; $i < 18; $i++)
            <div class="rounded-xl sm:rounded-2xl overflow-hidden border border-sky-950/60 bg-[#0F192E]">
                {{-- Poster shimmer --}}
                <div class="aspect-[3/4] skeleton-shimmer relative overflow-hidden"></div>
                {{-- Body --}}
                <div class="p-3 sm:p-4 flex flex-col gap-2">
                    <div class="h-3 rounded-md skeleton-shimmer w-3/4"></div>
                    <div class="h-5 rounded-md skeleton-shimmer w-full mt-1"></div>
                    <div class="flex items-center justify-between pt-2 mt-auto">
                        <div class="h-2.5 rounded-md skeleton-shimmer w-12"></div>
                        <div class="h-2.5 rounded-md skeleton-shimmer w-8"></div>
                    </div>
                </div>
            </div>
        @endfor
    </div>
</div>
