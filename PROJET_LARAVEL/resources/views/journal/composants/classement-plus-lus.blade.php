@props(['articlesPlusLus' => []])

<div style="background:var(--dark); color:#fff; padding:36px 40px; box-sizing:border-box;">
    <h2 class="dsp" style="margin:0 0 12px; font-weight:900; font-size:36px;">Les plus lus</h2>
    @forelse($articlesPlusLus as $index => $article)
        <a href="{{ route('articles.show', $article->slug) }}" class="rk">
            <b>{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</b>
            <span style="font:600 18px/1.35 'IBM Plex Sans',sans-serif;">{{ $article->titre }}</span>
        </a>
    @empty
        <a href="#" class="rk"><b>01</b><span style="font:600 18px/1.35 'IBM Plex Sans',sans-serif;">Incoterms 2026 : les erreurs fréquentes dans les contrats de vente</span></a>
        <a href="#" class="rk"><b>02</b><span style="font:600 18px/1.35 'IBM Plex Sans',sans-serif;">Choisir son WMS : critères comparatifs et pièges à éviter</span></a>
        <a href="#" class="rk"><b>03</b><span style="font:600 18px/1.35 'IBM Plex Sans',sans-serif;">Transport multimodal : bâtir une chaîne d'approvisionnement résiliente</span></a>
        <a href="#" class="rk"><b>04</b><span style="font:600 18px/1.35 'IBM Plex Sans',sans-serif;">Retours e-commerce et logistique inverse : les nouveaux modèles</span></a>
    @endforelse
</div>
