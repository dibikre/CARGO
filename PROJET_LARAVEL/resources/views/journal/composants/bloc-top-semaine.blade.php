@props(['articlesTopSemaine' => []])

<div class="bloc-top-semaine" style="background:#fff; border:1px solid #E2E5EB; border-top:4px solid var(--accent); padding:28px 24px; box-sizing:border-box;">
    <div style="display:flex; justify-content:space-between; align-items:center; padding-bottom:12px; border-bottom:2px solid var(--dark); margin-bottom:18px;">
        <div>
            <span class="kick" style="color:var(--accent); font-size:calc(11.5px * var(--echelle-texte)); display:block;">Most Read This Week</span>
            <h3 class="dsp" style="margin:2px 0 0; font-size:calc(22px * var(--echelle-texte)); line-height:1.15;">Top 6 Weekly Briefings</h3>
        </div>
        <span style="background:var(--yellow); color:#14151C; font-weight:900; font-size:calc(11px * var(--echelle-texte)); padding:3px 8px; text-transform:uppercase; letter-spacing:0.06em;">
            7 Days
        </span>
    </div>

    <div style="display:flex; flex-direction:column; gap:16px;">
        @forelse($articlesTopSemaine as $index => $article)
            <article style="display:flex; gap:14px; align-items:flex-start; padding-bottom:14px; {{ !$loop->last ? 'border-bottom:1px dashed var(--dashed-border);' : '' }}">
                <b style="font-family:var(--police-titre); font-weight:900; font-size:calc(24px * var(--echelle-texte)); color:var(--accent); line-height:1; min-width:32px;">
                    {{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}
                </b>
                <div style="display:flex; flex-direction:column; gap:4px; min-width:0;">
                    <div style="display:flex; align-items:center; gap:8px;">
                        <span class="kick" style="color:var(--accent-dark); font-size:calc(10.5px * var(--echelle-texte));">
                            {{ $article->categorie->nom ?? 'Logistics' }}
                        </span>
                        @if($article->est_reserve_abonnes)
                            @if(isset($abonnementsActifs) && !$abonnementsActifs)
                                <span style="background:#0F766E; color:#fff; font-size:calc(9.5px * var(--echelle-texte)); font-weight:800; padding:1px 5px; text-transform:uppercase;">FREE</span>
                            @else
                                <span style="background:var(--yellow); color:#14151C; font-size:calc(9.5px * var(--echelle-texte)); font-weight:800; padding:1px 5px; text-transform:uppercase;">PRO</span>
                            @endif
                        @endif
                    </div>

                    <h4 class="dsp" style="margin:0; font-size:calc(15px * var(--echelle-texte)); line-height:1.25; font-weight:700;">
                        <a href="{{ route('articles.show', $article->slug) }}" style="color:var(--dark); text-decoration:none;">
                            {{ $article->titre }}
                        </a>
                    </h4>

                    <div style="display:flex; align-items:center; gap:8px; font-size:calc(11.5px * var(--echelle-texte)); color:var(--text-muted); margin-top:2px;">
                        <span>{{ number_format($article->vues_compteur) }} reads</span>
                        <span>·</span>
                        <span>{{ $article->temps_lecture_minutes }}m</span>
                    </div>
                </div>
            </article>
        @empty
            <p style="color:var(--text-muted); font-size:13px; font-style:italic; margin:0;">
                Weekly ranking is being consolidated from reader metrics.
            </p>
        @endforelse
    </div>
</div>
