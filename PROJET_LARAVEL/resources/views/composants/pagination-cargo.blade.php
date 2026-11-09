@if ($paginator->hasPages() || $paginator->total() > 0)
    <nav role="navigation" aria-label="Pagination" style="display:flex; flex-direction:column; align-items:center; gap:16px; width:100%;">
        {{-- Compteur d'articles / éléments --}}
        <div style="font-size:13px; color:#4B505C; font-family:var(--police-texte, sans-serif);">
            @if ($paginator->total() > 0)
                Showing <strong style="color:var(--dark);">{{ $paginator->firstItem() }}</strong> to <strong style="color:var(--dark);">{{ $paginator->lastItem() }}</strong> of <strong style="color:var(--dark);">{{ $paginator->total() }}</strong> results
            @else
                No results to display
            @endif
        </div>

        {{-- Barre de navigation avec boutons et icônes SVG contraintes --}}
        @if ($paginator->hasPages())
            <div style="display:inline-flex; align-items:center; gap:4px; background:#fff; padding:6px; border:1px solid #D5D8E0; box-shadow:0 1px 3px rgba(0,0,0,0.05); flex-wrap:wrap; justify-content:center;">
                {{-- Bouton Précédent --}}
                @if ($paginator->onFirstPage())
                    <span aria-disabled="true" style="display:inline-flex; align-items:center; gap:6px; padding:8px 14px; font-size:12px; font-weight:700; text-transform:uppercase; color:#9CA3AF; background:#F3F4F6; cursor:not-allowed; border:1px solid #E5E7EB;">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="display:inline-block;"><path d="M15 18l-6-6 6-6"/></svg>
                        Previous
                    </span>
                @else
                    <a href="{{ $paginator->previousPageUrl() }}" rel="prev" style="display:inline-flex; align-items:center; gap:6px; padding:8px 14px; font-size:12px; font-weight:700; text-transform:uppercase; color:var(--dark); background:#fff; text-decoration:none; border:1px solid #D5D8E0; transition:all 0.15s ease;">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="display:inline-block;"><path d="M15 18l-6-6 6-6"/></svg>
                        Previous
                    </a>
                @endif

                {{-- Pages numérotées --}}
                @foreach ($elements as $element)
                    @if (is_string($element))
                        <span aria-disabled="true" style="padding:8px 12px; font-size:13px; font-weight:700; color:#9CA3AF;">{{ $element }}</span>
                    @endif

                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <span aria-current="page" style="display:inline-flex; align-items:center; justify-content:center; min-width:34px; height:34px; padding:0 8px; font-size:13px; font-weight:800; color:#fff; background:var(--accent, #D4201A); border:1px solid var(--accent, #D4201A);">
                                    {{ $page }}
                                </span>
                            @else
                                <a href="{{ $url }}" style="display:inline-flex; align-items:center; justify-content:center; min-width:34px; height:34px; padding:0 8px; font-size:13px; font-weight:700; color:var(--dark); background:#fff; text-decoration:none; border:1px solid #E5E7EB; transition:all 0.15s ease;">
                                    {{ $page }}
                                </a>
                            @endif
                        @endforeach
                    @endif
                @endforeach

                {{-- Bouton Suivant --}}
                @if ($paginator->hasMorePages())
                    <a href="{{ $paginator->nextPageUrl() }}" rel="next" style="display:inline-flex; align-items:center; gap:6px; padding:8px 14px; font-size:12px; font-weight:700; text-transform:uppercase; color:var(--dark); background:#fff; text-decoration:none; border:1px solid #D5D8E0; transition:all 0.15s ease;">
                        Next
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="display:inline-block;"><path d="M9 18l6-6-6-6"/></svg>
                    </a>
                @else
                    <span aria-disabled="true" style="display:inline-flex; align-items:center; gap:6px; padding:8px 14px; font-size:12px; font-weight:700; text-transform:uppercase; color:#9CA3AF; background:#F3F4F6; cursor:not-allowed; border:1px solid #E5E7EB;">
                        Next
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="display:inline-block;"><path d="M9 18l6-6-6-6"/></svg>
                    </span>
                @endif
            </div>
        @endif
    </nav>
@endif
