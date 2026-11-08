@extends('layouts.journal')

@section('titre', $categorie->meta_titre ?? 'Rubrique ' . ($categorie->nom ?? 'Logistique') . ' — CARGO')
@section('meta_description', $categorie->meta_description ?? 'Toutes les actualités sur la rubrique ' . ($categorie->nom ?? 'Logistique'))

@section('contenu')
{{-- En-tête de Rubrique avec Image de Fond et Protection Totale Anti-Blanc sur Blanc --}}
@php
    $urlFond = $categorie?->urlImageFond();
    $opacite = (int) ($categorie?->opacite_overlay ?: 65) / 100;
    $opaciteMax = min(0.96, $opacite + 0.15);
@endphp

<div class="rubrique-hero-banner" style="position:relative; overflow:hidden; background-color:#14151C; @if(!empty($urlFond)) background-image:linear-gradient(to bottom, rgba(14,15,20,{{ $opacite }}) 0%, rgba(20,21,28,{{ $opaciteMax }}) 100%), url('{{ $urlFond }}'); background-position:center; background-size:cover; background-repeat:no-repeat; @endif color:#FFFFFF; padding:56px 0 44px; border-bottom:4px solid var(--accent);">
    {{-- Protective Contrast Scrim: guarantees text legibility on any background image (even pure white) --}}
    <div style="position:absolute; inset:0; background:radial-gradient(circle at 20% 50%, rgba(0,0,0,0.65) 0%, rgba(0,0,0,0.85) 100%); pointer-events:none;"></div>

    <div class="wrap" style="position:relative; z-index:2;">
        <div style="display:flex; align-items:center; gap:10px; margin-bottom:12px;">
            <span class="kick" style="background:#14151C; color:#FFC93C; padding:6px 12px; font-weight:800; font-size:12px; letter-spacing:0.12em; text-transform:uppercase; border-left:3px solid var(--accent); box-shadow:0 2px 4px rgba(0,0,0,0.4);">
                Veille sectorielle logistique
            </span>
            @if(isset($articles) && method_exists($articles, 'total'))
                <span style="font-size:12px; font-weight:600; color:#E2E5EB; background:rgba(20,21,28,0.7); padding:4px 10px; border-radius:2px; text-shadow:0 1px 2px rgba(0,0,0,0.8);">
                    {{ $articles->total() }} Articles
                </span>
            @endif
        </div>

        <h1 class="dsp" style="margin:0; font-size:52px; font-weight:900; line-height:1.05; color:#FFFFFF; text-shadow:0 2px 10px rgba(0,0,0,0.9), 0 1px 2px rgba(0,0,0,0.95); letter-spacing:-0.01em;">
            {{ $categorie->nom ?? 'Rubrique Logistique' }}
        </h1>

        @if(!empty($categorie?->description))
            <p style="color:#F3F4F6; font-size:19px; line-height:1.6; margin:14px 0 0; max-width:760px; font-weight:400; text-shadow:0 1px 6px rgba(0,0,0,0.85);">
                {{ $categorie->description }}
            </p>
        @endif
    </div>
</div>

<div class="wrap" style="padding-top:40px; padding-bottom:64px;">
    <div style="display:grid; grid-template-columns:repeat(auto-fill, minmax(280px, 1fr)); gap:28px;">
        @forelse($articles ?? [] as $article)
            @include('journal.composants.carte-article', ['article' => $article, 'theme' => $loop->iteration % 2 === 0 ? 'sombre' : 'clair'])
        @empty
            <div style="grid-column: 1 / -1; background:#fff; padding:48px; text-align:center; border:1px solid #E2E5EB;">
                <h3 class="dsp" style="margin:0; font-size:24px; color:var(--dark);">Aucun article publié dans cette rubrique pour le moment</h3>
                <p style="color:var(--text-muted); margin:8px 0 20px;">Revenez très prochainement ou explorez l'ensemble des articles du journal.</p>
                <a href="{{ route('articles.index') }}" style="background:var(--accent); color:#fff; font-weight:700; padding:12px 24px; display:inline-block; font-family:var(--police-titre); text-transform:uppercase; font-size:calc(12.5px * var(--echelle-texte)); letter-spacing:0.05em; text-decoration:none;">Voir toutes les publications</a>
            </div>
        @endforelse
    </div>

    @if(isset($articles) && method_exists($articles, 'links'))
        <div style="margin-top:48px; display:flex; justify-content:center;">
            {{ $articles->links() }}
        </div>
    @endif
</div>
@endsection
