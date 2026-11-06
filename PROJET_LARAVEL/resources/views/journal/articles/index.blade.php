@extends('layouts.journal')

@section('titre', 'Tous les articles — CARGO Logistique')

@section('contenu')
<div style="background:var(--dark); color:#fff; padding:48px 0 36px;">
    <div class="wrap">
        <span class="kick" style="color:var(--yellow);">Actualités & Décryptages</span>
        <h1 class="dsp" style="margin:12px 0 0; font-size:48px; font-weight:900;">Toutes les publications</h1>
        <p style="color:#C9CCD6; font-size:18px; margin-top:8px;">Retrouvez l’ensemble des analyses, reportages et enquêtes logistiques.</p>
    </div>
</div>

<div class="wrap" style="padding-top:40px;">
    {{-- Filtres de modes de transport --}}
    <div style="display:flex; flex-wrap:wrap; gap:10px; margin-bottom:32px; padding-bottom:20px; border-bottom:2px dashed var(--dashed-border);">
        <a href="{{ route('articles.index') }}" style="padding:10px 18px; font-weight:600; font-size:14px; {{ empty($modeTransport) ? 'background:var(--dark); color:#fff;' : 'background:#fff; color:var(--dark);' }}">
            Tous les modes
        </a>
        @foreach(['routier' => 'Routier', 'maritime' => 'Maritime', 'ferroviaire' => 'Ferroviaire', 'aerien' => 'Aérien', 'multimodal' => 'Multimodal', 'entreposage' => 'Entreposage', 'douanes' => 'Douane'] as $cle => $libelle)
            <a href="{{ route('articles.index', ['mode' => $cle]) }}" style="padding:10px 18px; font-weight:600; font-size:14px; {{ $modeTransport === $cle ? 'background:var(--accent); color:#fff;' : 'background:#fff; color:var(--dark);' }}">
                {{ $libelle }}
            </a>
        @endforeach
    </div>

    {{-- Grille d'articles --}}
    <div style="display:grid; grid-template-columns:repeat(auto-fill, minmax(280px, 1fr)); gap:28px;">
        @forelse($articles as $article)
            @include('journal.composants.carte-article', ['article' => $article, 'theme' => $loop->iteration % 2 === 0 ? 'sombre' : 'clair'])
        @empty
            <div style="grid-column: 1 / -1; background:#fff; padding:48px; text-align:center;">
                <h3 class="dsp" style="margin:0;">Aucun article trouvé pour ces critères</h3>
                <p style="color:var(--text-muted); margin-top:8px;">Essayez de réinitialiser vos filtres ou effectuez une recherche.</p>
                <a href="{{ route('articles.index') }}" class="btn-yellow" style="margin-top:16px;">Voir tous les articles</a>
            </div>
        @endforelse
    </div>

    {{-- Pagination personnalisée --}}
    <div style="margin-top:48px; display:flex; justify-content:center;">
        {{ $articles->links() }}
    </div>
</div>
@endsection
