@extends('layouts.journal')

@section('titre', $categorie->meta_titre ?? 'Rubrique ' . $categorie->nom . ' — CARGO')
@section('meta_description', $categorie->meta_description ?? 'Toutes les actualités sur la rubrique ' . $categorie->nom)

@section('contenu')
<div style="background:var(--dark); color:#fff; padding:48px 0 36px;">
    <div class="wrap">
        <span class="kick" style="color:var(--yellow);">Industry Section</span>
        <h1 class="dsp" style="margin:12px 0 0; font-size:48px; font-weight:900;">{{ $categorie->nom }}</h1>
        @if($categorie->description)
            <p style="color:#C9CCD6; font-size:18px; margin-top:8px; max-width:700px;">{{ $categorie->description }}</p>
        @endif
    </div>
</div>

<div class="wrap" style="padding-top:40px;">
    <div style="display:grid; grid-template-columns:repeat(auto-fill, minmax(280px, 1fr)); gap:28px;">
        @forelse($articles as $article)
            @include('journal.composants.carte-article', ['article' => $article, 'theme' => $loop->iteration % 2 === 0 ? 'sombre' : 'clair'])
        @empty
            <div style="grid-column: 1 / -1; background:#fff; padding:48px; text-align:center; border:1px solid #E2E5EB;">
                <h3 class="dsp" style="margin:0; font-size:24px;">No briefings published in this section yet</h3>
                <p style="color:var(--text-muted); margin:8px 0 20px;">Check back soon or explore our comprehensive archive of logistics intelligence.</p>
                <a href="{{ route('articles.index') }}" style="background:var(--accent); color:#fff; font-weight:700; padding:12px 24px; display:inline-block; font-family:'Archivo',sans-serif; text-transform:uppercase; font-size:13px; letter-spacing:0.05em; text-decoration:none;">Browse All Intelligence</a>
            </div>
        @endforelse
    </div>

    <div style="margin-top:48px; display:flex; justify-content:center;">
        {{ $articles->links() }}
    </div>
</div>
@endsection
