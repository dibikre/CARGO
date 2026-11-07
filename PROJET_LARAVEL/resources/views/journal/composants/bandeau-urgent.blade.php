@props(['alertes' => []])

<div style="background: var(--accent); color: #fff;">
    <div class="wrap" style="display:flex; flex-wrap:wrap; gap:12px 32px; align-items:center; min-height:72px; font:600 calc(15.5px * var(--echelle-texte)) var(--police-texte);">
        <span class="kick" style="background:#14151C; padding:8px 12px; color:#fff;">En bref</span>
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
