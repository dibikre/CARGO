{{-- Barre de pagination dynamique pour navigation sans rechargement --}}
<div id="barre-pagination-videos" class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pt-4 border-t border-gray-200">
    <div class="text-xs text-gray-500">
        Showing <span id="pagination-de" class="font-bold text-gray-900">{{ $videos->firstItem() ?? 0 }}</span>
        to <span id="pagination-a" class="font-bold text-gray-900">{{ $videos->lastItem() ?? 0 }}</span>
        of <span id="pagination-total" class="font-bold text-gray-900">{{ $videos->total() }}</span> embedded videos
    </div>

    <div class="flex items-center gap-1.5" id="conteneur-liens-pagination">
        @if($videos->hasPages())
            {{-- Bouton Page Précédente --}}
            <button 
                type="button" 
                class="bouton-page-nav px-3 py-1.5 rounded border text-xs font-bold uppercase transition flex items-center gap-1 {{ $videos->onFirstPage() ? 'border-gray-200 text-gray-300 cursor-not-allowed' : 'border-gray-300 text-gray-700 hover:bg-gray-50' }}"
                data-page="{{ $videos->currentPage() - 1 }}"
                {{ $videos->onFirstPage() ? 'disabled' : '' }}
            >
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                <span>Prev</span>
            </button>

            {{-- Numéros de page --}}
            @for($i = 1; $i <= $videos->lastPage(); $i++)
                @if($i == $videos->currentPage())
                    <button type="button" class="bouton-page-nav px-3 py-1.5 rounded border text-xs font-bold uppercase bg-cargo-dark text-white border-cargo-dark shadow-sm" data-page="{{ $i }}">
                        {{ $i }}
                    </button>
                @elseif($i <= 3 || $i >= $videos->lastPage() - 1 || abs($i - $videos->currentPage()) <= 1)
                    <button type="button" class="bouton-page-nav px-3 py-1.5 rounded border text-xs font-bold uppercase bg-white text-gray-700 border-gray-300 hover:bg-gray-50 transition" data-page="{{ $i }}">
                        {{ $i }}
                    </button>
                @elseif($i == 4 && $videos->lastPage() > 5)
                    <span class="px-1 text-gray-400 text-xs">...</span>
                @endif
            @endfor

            {{-- Bouton Page Suivante --}}
            <button 
                type="button" 
                class="bouton-page-nav px-3 py-1.5 rounded border text-xs font-bold uppercase transition flex items-center gap-1 {{ $videos->hasMorePages() ? 'border-gray-300 text-gray-700 hover:bg-gray-50' : 'border-gray-200 text-gray-300 cursor-not-allowed' }}"
                data-page="{{ $videos->currentPage() + 1 }}"
                {{ $videos->hasMorePages() ? '' : 'disabled' }}
            >
                <span>Next</span>
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </button>
        @endif
    </div>
</div>
