@extends('layouts.journal')

@section('titre', 'Corridor de transport ' . $corridor->nom . ' — CARGO')

@section('contenu')
<div style="background:var(--dark); color:#fff; padding:48px 0 36px;">
    <div class="wrap">
        <span class="kick" style="color:var(--yellow);">Strategic Freight Corridor · {{ strtoupper($corridor->type_flux) }}</span>
        <h1 class="dsp" style="margin:12px 0 0; font-size:48px; font-weight:900;">{{ $corridor->nom }}</h1>
        <div style="display:flex; align-items:center; gap:12px; margin-top:16px;">
            <span style="font-size:14px; font-weight:600;">Operational Status:</span>
            @if(in_array($corridor->statut_operationnel, ['normal', 'fluide'], true))
                <span style="background:#22C55E; color:#fff; padding:4px 10px; font-weight:700; font-size:13px;">NORMAL FLOW</span>
            @elseif($corridor->statut_operationnel === 'ralenti')
                <span style="background:var(--yellow); color:#14151C; padding:4px 10px; font-weight:700; font-size:13px;">CONGESTED / SLOW</span>
            @else
                <span style="background:var(--accent); color:#fff; padding:4px 10px; font-weight:700; font-size:13px;">MAJOR DISRUPTION</span>
            @endif
        </div>
        @if($corridor->description)
            <p style="color:#C9CCD6; font-size:17px; margin-top:12px; max-width:750px;">{{ $corridor->description }}</p>
        @endif
    </div>
</div>

<div class="wrap" style="padding-top:40px;">
    <h2 class="dsp" style="font-size:28px; margin-bottom:24px;">Corridor Disruption & Operations Intel</h2>
    <div style="display:grid; grid-template-columns:repeat(auto-fill, minmax(280px, 1fr)); gap:28px;">
        @forelse($articles as $article)
            @include('journal.composants.carte-article', ['article' => $article, 'theme' => $loop->iteration % 2 === 0 ? 'sombre' : 'clair'])
        @empty
            <div style="grid-column: 1 / -1; background:#fff; padding:48px; text-align:center; border:1px solid #E2E5EB;">
                <h3 class="dsp" style="margin:0; font-size:24px;">No recent intelligence on this freight corridor</h3>
                <a href="{{ route('articles.index') }}" style="background:var(--accent); color:#fff; font-weight:700; padding:12px 24px; display:inline-block; font-family:'Archivo',sans-serif; text-transform:uppercase; font-size:13px; letter-spacing:0.05em; text-decoration:none; margin-top:16px;">Browse All Intelligence</a>
            </div>
        @endforelse
    </div>

    <div style="margin-top:48px; display:flex; justify-content:center;">
        {{ $articles->links() }}
    </div>
</div>
@endsection
