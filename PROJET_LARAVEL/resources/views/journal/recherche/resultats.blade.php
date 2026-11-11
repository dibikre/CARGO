@extends('layouts.journal')

@section('titre', 'Résultats de recherche — CARGO')
@section('robots', 'noindex, nofollow, noarchive')

@section('contenu')
<div style="background:var(--dark); color:#fff; padding:44px 0 32px; border-bottom:1px solid var(--dark-border);">
    <div class="wrap" style="max-width:880px;">
        <div style="display:flex; align-items:center; gap:8px; margin-bottom:8px;">
            <span class="kick" style="color:var(--yellow);">Archives &bull; Moteur de recherche</span>
            <span style="font-size:11px; background:#20222B; color:#9CA3AF; padding:2px 8px; border:1px solid #374151; font-family:monospace;">Jeton sécurisé à usage unique</span>
        </div>
        <h1 class="dsp" style="margin:8px 0 14px; font-size:38px; font-weight:900;">
            Résultats pour : &laquo; {{ $motsCles }} &raquo;
        </h1>
        <p style="font-size:15px; color:#C9CCD6; margin:0;">
            {{ $articles->total() }} publication(s) répertoriée(s) &bull; Affichage des {{ min(10, $articles->total()) }} résultats les plus pertinents.
        </p>
    </div>
</div>

<div class="wrap" style="max-width:880px; padding-top:36px; padding-bottom:60px;">
    {{-- Liste des publications trouvées --}}
    <div style="display:flex; flex-direction:column; gap:18px;">
        @forelse($articles as $article)
            <article style="background:#fff; padding:24px 28px; border:1px solid #E2E5EB; border-left:4px solid var(--accent); transition:box-shadow 0.2s;">
                <div style="display:flex; justify-content:space-between; align-items:baseline; margin-bottom:6px; flex-wrap:wrap; gap:8px;">
                    <span class="kick" style="color:var(--accent); font-size:12px;">
                        {{ $article->categorie->nom }} &bull; {{ $article->date_publication?->translatedFormat('d M Y') }}
                    </span>
                    <span style="font-size:12px; color:var(--text-muted);">
                        {{ $article->temps_lecture_minutes }} min de lecture
                    </span>
                </div>
                <h2 class="dsp" style="margin:6px 0 10px; font-size:22px; line-height:1.25;">
                    <a href="{{ route('articles.show', $article->slug) }}" target="_blank" rel="noopener noreferrer" style="color:var(--dark); text-decoration:none;" title="Ouvrir l'article dans un nouvel onglet">
                        {{ $article->titre }}
                    </a>
                </h2>
                <p style="margin:0 0 14px; font-size:15px; color:#4B505C; line-height:1.55;">
                    {{ Str::limit($article->chapeau ?? strip_tags($article->contenu), 190) }}
                </p>
                <a href="{{ route('articles.show', $article->slug) }}" target="_blank" rel="noopener noreferrer" style="font-weight:700; font-size:13px; color:var(--dark); text-transform:uppercase; letter-spacing:0.04em; display:inline-flex; align-items:center; gap:6px;" title="Ouvrir l'article dans un nouvel onglet">
                    Consulter l'article &nearr;
                </a>
            </article>
        @empty
            <div style="background:#fff; padding:48px 32px; text-align:center; border:1px dashed var(--dashed-border);">
                <h3 class="dsp" style="margin:0 0 8px; font-size:22px; color:var(--dark);">Aucun article ne correspond à ces termes</h3>
                <p style="color:var(--text-muted); margin:0 0 20px; font-size:15px;">
                    Vérifiez l'orthographe ou formulez une requête plus générale avec des termes sectoriels (ports, multimodal, supply chain...).
                </p>
                <button
                    type="button"
                    onclick="const b = document.getElementById('bouton-declencheur-recherche'); if(b){ b.click(); window.scrollTo({top:0, behavior:'smooth'}); }"
                    style="background:var(--accent); color:#fff; font-weight:700; padding:11px 22px; border:none; cursor:pointer; font-family:var(--police-titre); text-transform:uppercase; font-size:12px;"
                >
                    Effectuer une autre recherche
                </button>
            </div>
        @endforelse
    </div>

    @if($articles->hasPages())
        <div style="margin-top:36px; display:flex; justify-content:center;">
            {{ $articles->links() }}
        </div>
    @endif
</div>
@endsection
