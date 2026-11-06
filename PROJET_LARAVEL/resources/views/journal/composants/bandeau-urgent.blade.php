@props(['alertes' => []])

<div style="background: var(--accent); color: #fff;">
    <div class="wrap" style="display:flex; flex-wrap:wrap; gap:12px 32px; align-items:center; min-height:76px; font:600 16px 'IBM Plex Sans',sans-serif;">
        <span class="kick" style="background:#14151C; padding:9px 12px; color:#fff;">En bref</span>
        @forelse($alertes as $alerte)
            <span>
                <a href="{{ route('articles.show', $alerte->slug) }}" style="color:#fff; text-decoration:none;">
                    {{ $alerte->titre }}
                </a>
            </span>
        @empty
            <span>Nouveaux horaires de dédouanement maritime</span>
            <span>Appel d’offres : nouvelle zone logistique multimodale</span>
            <span>Fret ferroviaire : ouverture d'une liaison transfrontalière</span>
        @endforelse
    </div>
</div>
