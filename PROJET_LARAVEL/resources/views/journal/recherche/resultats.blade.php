@extends('layouts.journal')

@section('titre', 'Recherche dans les archives — CARGO')

@section('contenu')
<div style="background:var(--dark); color:#fff; padding:48px 0 36px;">
    <div class="wrap" style="max-width:860px;">
        <span class="kick" style="color:var(--yellow);">Moteur de recherche</span>
        <h1 class="dsp" style="margin:12px 0 20px; font-size:44px; font-weight:900;">Rechercher dans CARGO</h1>

        <form action="{{ route('recherche') }}" method="GET" style="display:flex; gap:12px; flex-wrap:wrap;">
            <input type="text" name="q" value="{{ $requete }}" placeholder="Mots-clés, nom d'armateur, réglementation, WMS..." class="form-input" style="flex:1 1 300px; height:54px; font-size:17px;">
            <button type="submit" class="btn-yellow" style="height:54px; padding:0 28px; font-size:16px;">Rechercher</button>
        </form>
    </div>
</div>

<div class="wrap" style="max-width:860px; padding-top:40px;">
    @if($requete !== '')
        <p style="font-size:16px; color:var(--text-muted); margin-bottom:28px;">
            Résultats pour la recherche : <strong>"{{ $requete }}"</strong> ({{ $articles->total() }} publications trouvées)
        </p>
    @endif

    <div style="display:flex; flex-direction:column; gap:20px;">
        @forelse($articles as $article)
            <div style="background:#fff; padding:24px 28px; border-left:4px solid var(--accent);">
                <span class="kick" style="color:var(--accent-dark); font-size:12px;">
                    {{ $article->categorie->nom }} · {{ $article->date_publication?->format('d/m/Y') }}
                </span>
                <h2 class="dsp" style="margin:6px 0 8px; font-size:24px; line-height:1.2;">
                    <a href="{{ route('articles.show', $article->slug) }}">{{ $article->titre }}</a>
                </h2>
                <p style="margin:0 0 12px; font-size:15px; color:#4B505C; line-height:1.5;">
                    {{ Str::limit($article->chapeau ?? strip_tags($article->contenu), 180) }}
                </p>
                <a href="{{ route('articles.show', $article->slug) }}" style="font-weight:700; font-size:14px; color:var(--dark);">
                    Lire l'article →
                </a>
            </div>
        @empty
            <div style="background:#fff; padding:48px; text-align:center;">
                <h3 class="dsp" style="margin:0;">Aucun résultat ne correspond à votre requête</h3>
                <p style="color:var(--text-muted); margin-top:8px;">Vérifiez l'orthographe ou tentez une recherche plus large.</p>
            </div>
        @endforelse
    </div>

    <div style="margin-top:40px; display:flex; justify-content:center;">
        {{ $articles->links() }}
    </div>
</div>
@endsection
