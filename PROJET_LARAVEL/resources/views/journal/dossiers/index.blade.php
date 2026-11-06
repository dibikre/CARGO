@extends('layouts.journal')

@section('titre', 'Dossiers Spéciaux & Grandes Enquêtes — CARGO')

@section('contenu')
<div style="background:var(--accent); color:#fff; padding:56px 0 44px;">
    <div class="wrap">
        <span class="kick badge-alert">Enquêtes approfondies</span>
        <h1 class="dsp" style="margin:16px 0 0; font-size:52px; font-weight:900;">Dossiers thématiques</h1>
        <p style="font-size:20px; line-height:1.5; margin-top:12px; max-width:700px;">
            Des analyses de fond, des cartes et des retours d'expérience pour comprendre les transformations structurelles de la filière transport.
        </p>
    </div>
</div>

<div class="wrap" style="padding-top:48px;">
    <div style="display:grid; grid-template-columns:repeat(auto-fill, minmax(320px, 1fr)); gap:32px;">
        @forelse($dossiers as $dossier)
            <div style="background:#fff; padding:32px; display:flex; flex-direction:column; gap:16px; border-top:4px solid var(--accent);">
                <span class="kick" style="color:var(--accent-dark);">Dossier · {{ $dossier->articles_count }} articles</span>
                <h2 class="dsp" style="margin:0; font-size:28px; line-height:1.2;">
                    <a href="{{ route('dossiers.show', $dossier->slug) }}">{{ $dossier->titre }}</a>
                </h2>
                @if($dossier->chapeau)
                    <p style="margin:0; font-size:15px; color:var(--text-muted); line-height:1.5;">{{ Str::limit($dossier->chapeau, 140) }}</p>
                @endif
                <div style="margin-top:auto; padding-top:16px; border-top:1px dashed var(--dashed-border);">
                    <a href="{{ route('dossiers.show', $dossier->slug) }}" style="font-weight:700; color:var(--dark);">
                        Explorer le dossier →
                    </a>
                </div>
            </div>
        @empty
            <div style="grid-column: 1 / -1; background:#fff; padding:48px; text-align:center;">
                <h3 class="dsp" style="margin:0;">Aucun dossier thématique pour le moment</h3>
            </div>
        @endforelse
    </div>

    <div style="margin-top:48px; display:flex; justify-content:center;">
        {{ $dossiers->links() }}
    </div>
</div>
@endsection
