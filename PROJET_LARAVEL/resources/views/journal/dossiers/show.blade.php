@extends('layouts.journal')

@section('titre', $dossier->meta_titre ?? 'Dossier : ' . $dossier->titre . ' — CARGO')
@section('meta_description', $dossier->meta_description ?? Str::limit(strip_tags($dossier->chapeau), 160))

@section('contenu')
<div style="background:var(--accent); color:#fff; padding:56px 0 44px;">
    <div class="wrap" style="max-width:960px;">
        <span class="kick badge-alert">Grand dossier de la rédaction</span>
        <h1 class="dsp" style="margin:16px 0 16px; font-size:52px; line-height:1.05; font-weight:900;">
            {{ $dossier->titre }}
        </h1>
        @if($dossier->chapeau)
            <p style="font-size:20px; line-height:1.55; margin:0; opacity:.95;">{{ $dossier->chapeau }}</p>
        @endif
    </div>
</div>

<div class="wrap" style="max-width:960px; padding-top:48px;">
    @if($dossier->description_complete)
        <div style="background:#fff; padding:36px; margin-bottom:48px; font-size:17px; line-height:1.7; border-left:4px solid var(--dark);">
            {!! nl2br(e($dossier->description_complete)) !!}
        </div>
    @endif

    <h2 class="dsp" style="font-size:32px; margin-bottom:24px; padding-bottom:12px; border-bottom:3px solid var(--dark);">
        Sommaire du dossier ({{ $articles->count() }} volets)
    </h2>

    <div style="display:flex; flex-direction:column; gap:20px;">
        @forelse($articles as $index => $article)
            <div style="background:#fff; padding:24px 28px; display:flex; flex-wrap:wrap; align-items:center; justify-content:space-between; gap:16px;">
                <div style="display:flex; gap:20px; align-items:baseline;">
                    <b style="font:900 32px/1 'Archivo',sans-serif; color:var(--yellow); min-width:35px;">
                        {{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}
                    </b>
                    <div>
                        <span class="kick" style="color:var(--accent-dark); font-size:12px;">{{ $article->categorie->nom }}</span>
                        <h3 class="dsp" style="margin:4px 0 0; font-size:22px; line-height:1.2;">
                            <a href="{{ route('articles.show', $article->slug) }}">{{ $article->titre }}</a>
                        </h3>
                    </div>
                </div>
                <a href="{{ route('articles.show', $article->slug) }}" class="btn-yellow" style="padding:10px 20px; font-size:14px;">
                    Lire ce volet →
                </a>
            </div>
        @empty
            <p style="color:var(--text-muted);">Les volets de ce dossier sont en cours de finalisation par la rédaction.</p>
        @endforelse
    </div>
</div>
@endsection
