@props(['articlesPlusLus' => []])

<div style="background:var(--dark); color:#fff; padding:36px 40px; box-sizing:border-box;">
    <h2 class="dsp" style="margin:0 0 12px; font-weight:900; font-size:calc(34px * var(--echelle-texte));">Les plus lus</h2>
    @forelse($articlesPlusLus as $index => $article)
        <a href="{{ route('articles.show', $article->slug) }}" class="rk">
            <b style="font-family:var(--police-titre);">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</b>
            <span style="font:600 calc(17.5px * var(--echelle-texte))/1.35 var(--police-texte);">{{ $article->titre }}</span>
        </a>
    @empty
        <a href="#" class="rk"><b style="font-family:var(--police-titre);">01</b><span style="font:600 calc(17.5px * var(--echelle-texte))/1.35 var(--police-texte);">Incoterms 2026 : les erreurs fréquentes dans les contrats de vente</span></a>
        <a href="#" class="rk"><b style="font-family:var(--police-titre);">02</b><span style="font:600 calc(17.5px * var(--echelle-texte))/1.35 var(--police-texte);">Choisir son WMS : critères comparatifs et pièges à éviter</span></a>
        <a href="#" class="rk"><b style="font-family:var(--police-titre);">03</b><span style="font:600 calc(17.5px * var(--echelle-texte))/1.35 var(--police-texte);">Transport multimodal : bâtir une chaîne d'approvisionnement résiliente</span></a>
        <a href="#" class="rk"><b style="font-family:var(--police-titre);">04</b><span style="font:600 calc(17.5px * var(--echelle-texte))/1.35 var(--police-texte);">Retours e-commerce et logistique inverse : les nouveaux modèles</span></a>
    @endforelse
</div>
