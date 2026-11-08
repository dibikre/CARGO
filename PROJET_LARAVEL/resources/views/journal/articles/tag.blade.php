@extends('layouts.journal')

@section('titre', 'Tag #' . $tag->nom . ' — CARGO')

@section('contenu')
<div style="background:var(--dark); color:#fff; padding:48px 0 36px;">
    <div class="wrap">
        <span class="kick" style="color:var(--yellow);">Mot-clé &amp; Thématique</span>
        <h1 class="dsp" style="margin:12px 0 0; font-size:48px; font-weight:900;">#{{ $tag->nom }}</h1>
        @if($tag->description)
            <p style="color:#C9CCD6; font-size:18px; margin-top:8px;">{{ $tag->description }}</p>
        @endif
    </div>
</div>

<div class="wrap" style="padding-top:40px;">
    <div style="display:grid; grid-template-columns:repeat(auto-fill, minmax(280px, 1fr)); gap:28px;">
        @forelse($articles as $article)
            @include('journal.composants.carte-article', ['article' => $article, 'theme' => $loop->iteration % 2 === 0 ? 'sombre' : 'clair'])
        @empty
            <div style="grid-column: 1 / -1; background:#fff; padding:48px; text-align:center; border:1px solid #E2E5EB;">
                <h3 class="dsp" style="margin:0; font-size:24px;">Aucun article associé à ce tag pour le moment</h3>
                <a href="{{ route('articles.index') }}" style="background:var(--accent); color:#fff; font-weight:700; padding:12px 24px; display:inline-block; font-family:var(--police-titre); text-transform:uppercase; font-size:calc(12.5px * var(--echelle-texte)); letter-spacing:0.05em; text-decoration:none; margin-top:16px;">Voir tous les articles</a>
            </div>
        @endforelse
    </div>

    <div style="margin-top:48px; display:flex; justify-content:center;">
        {{ $articles->links() }}
    </div>
</div>
@endsection
