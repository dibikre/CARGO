@props(['meilleursTags' => []])

<div class="bloc-meilleurs-tags" style="background:#fff; border:1px solid #E2E5EB; border-top:4px solid var(--accent); padding:24px 20px; box-sizing:border-box;">
    {{-- Header du Bloc --}}
    <div style="display:flex; justify-content:space-between; align-items:flex-end; padding-bottom:12px; border-bottom:2px solid var(--dark); margin-bottom:16px;">
        <div>
            <span class="kick" style="color:var(--accent); font-size:calc(11.5px * var(--echelle-texte)); display:block; font-weight:700;">
                Industry Topics &amp; Brands
            </span>
            <h3 class="dsp" style="margin:3px 0 0; font-size:calc(21px * var(--echelle-texte)); line-height:1.15; color:var(--dark); font-weight:900;">
                Top 10 Freight Tags
            </h3>
        </div>
        <span style="font-size:calc(10.5px * var(--echelle-texte)); font-weight:700; text-transform:uppercase; color:var(--text-muted); letter-spacing:0.04em;">
            By Articles
        </span>
    </div>

    {{-- Liste ordonnée des 10 meilleurs tags --}}
    <div style="display:flex; flex-direction:column; gap:8px;">
        @forelse($meilleursTags as $index => $tag)
            @php $nombreArticles = $tag->articles_count ?? $tag->compteur_articles ?? $tag->articles()->count(); @endphp
            <a href="{{ route('tags.articles', $tag->slug) }}" 
               class="tag-item-link"
               style="display:flex; justify-content:space-between; align-items:center; padding:9px 12px; background:#F8FAFC; border:1px solid #E2E8F0; text-decoration:none; transition:all 0.15s ease; border-radius:3px;">
                <div style="display:flex; align-items:center; gap:10px; min-width:0;">
                    {{-- Badge Numéroté de 1 à 10 --}}
                    <span style="display:inline-flex; align-items:center; justify-content:center; width:22px; height:22px; border-radius:2px; font-size:11px; font-weight:900; font-family:'Archivo',sans-serif; flex-shrink:0; {{ $index < 3 ? 'background:var(--accent); color:#fff;' : 'background:#14151C; color:#fff;' }}">
                        {{ $index + 1 }}
                    </span>
                    <span style="font-weight:700; font-size:calc(13px * var(--echelle-texte)); color:var(--dark); white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                        {{ $tag->nom }}
                    </span>
                </div>
                {{-- Nombre d'articles associés --}}
                <span style="font-size:calc(11px * var(--echelle-texte)); font-family:monospace; font-weight:700; color:var(--text-muted); background:#fff; border:1px solid #CBD5E1; padding:2px 7px; border-radius:2px; flex-shrink:0;">
                    {{ $nombreArticles }} {{ Str::plural('art.', $nombreArticles) }}
                </span>
            </a>
        @empty
            <div style="padding:16px; text-align:center; background:#F8FAFC; border:1px dashed #CBD5E1; color:var(--text-muted); font-size:12px;">
                No tags recorded yet.
            </div>
        @endforelse
    </div>
</div>

<style>
    .tag-item-link:hover {
        background: #fff !important;
        border-color: var(--accent) !important;
        transform: translateX(3px);
        box-shadow: 0 2px 6px rgba(0,0,0,0.06);
    }
    .tag-item-link:hover span {
        color: var(--accent);
    }
</style>
