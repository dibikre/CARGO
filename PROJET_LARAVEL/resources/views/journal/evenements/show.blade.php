@extends('layouts.journal')

@section('titre', $evenement->titre . ' — Agenda CARGO')

@section('contenu')
<div style="background:var(--dark); color:#fff; padding:48px 0 36px;">
    <div class="wrap" style="max-width:860px;">
        <span class="kick" style="color:var(--yellow);">Agenda logistique · {{ strtoupper($evenement->type_evenement) }}</span>
        <h1 class="dsp" style="margin:16px 0; font-size:44px; font-weight:900;">{{ $evenement->titre }}</h1>
        <div style="font-size:16px; color:#C9CCD6;">
            📅 Du {{ $evenement->date_debut->translatedFormat('d F Y') }} au {{ $evenement->date_fin->translatedFormat('d F Y') }}
        </div>
    </div>
</div>

<div class="wrap" style="max-width:860px; padding-top:40px;">
    <div style="background:#fff; padding:36px; display:flex; flex-direction:column; gap:24px;">
        <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(200px, 1fr)); gap:20px; padding-bottom:20px; border-bottom:2px dashed var(--dashed-border);">
            <div>
                <span class="kick" style="color:var(--text-muted); font-size:11px;">Lieu & Ville</span>
                <p style="margin:4px 0 0; font-weight:700; font-size:16px;">{{ $evenement->lieu ?? $evenement->ville }}, {{ $evenement->pays }}</p>
            </div>
            <div>
                <span class="kick" style="color:var(--text-muted); font-size:11px;">Organisateur</span>
                <p style="margin:4px 0 0; font-weight:700; font-size:16px;">{{ $evenement->organisateur ?? 'Comité organisateur' }}</p>
            </div>
            @if($evenement->url_site_officiel)
                <div>
                    <span class="kick" style="color:var(--text-muted); font-size:11px;">Site officiel</span>
                    <p style="margin:4px 0 0;">
                        <a href="{{ $evenement->url_site_officiel }}" target="_blank" rel="noopener" class="btn-yellow" style="padding:8px 16px; font-size:13px;">
                            Accéder au site ↗
                        </a>
                    </p>
                </div>
            @endif
        </div>

        @if($evenement->description)
            <div>
                <h3 class="dsp" style="font-size:24px; margin:0 0 12px;">Présentation de l'événement</h3>
                <div style="font-size:16px; line-height:1.7; color:#2B2E3A;">
                    {!! nl2br(e($evenement->description)) !!}
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
