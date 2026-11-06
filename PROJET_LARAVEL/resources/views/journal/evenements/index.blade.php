@extends('layouts.journal')

@section('titre', 'Agenda des Salons & Conférences Logistiques — CARGO')

@section('contenu')
<div style="background:var(--dark); color:#fff; padding:48px 0 36px;">
    <div class="wrap">
        <span class="kick" style="color:var(--yellow);">Agenda professionnel</span>
        <h1 class="dsp" style="margin:12px 0 0; font-size:48px; font-weight:900;">Salons, Conférences & Événements</h1>
        <p style="color:#C9CCD6; font-size:18px; margin-top:8px;">Le calendrier des grands rendez-vous de la filière transport et logistique.</p>
    </div>
</div>

<div class="wrap" style="padding-top:40px;">
    {{-- Filtre par type --}}
    <div style="display:flex; gap:10px; margin-bottom:32px;">
        <a href="{{ route('evenements.index') }}" style="padding:10px 18px; font-weight:600; font-size:14px; {{ empty($type) ? 'background:var(--dark); color:#fff;' : 'background:#fff; color:var(--dark);' }}">
            Tous
        </a>
        <a href="{{ route('evenements.index', ['type' => 'salon']) }}" style="padding:10px 18px; font-weight:600; font-size:14px; {{ $type === 'salon' ? 'background:var(--accent); color:#fff;' : 'background:#fff; color:var(--dark);' }}">
            Salons
        </a>
        <a href="{{ route('evenements.index', ['type' => 'conference']) }}" style="padding:10px 18px; font-weight:600; font-size:14px; {{ $type === 'conference' ? 'background:var(--accent); color:#fff;' : 'background:#fff; color:var(--dark);' }}">
            Conférences
        </a>
        <a href="{{ route('evenements.index', ['type' => 'webinaire']) }}" style="padding:10px 18px; font-weight:600; font-size:14px; {{ $type === 'webinaire' ? 'background:var(--accent); color:#fff;' : 'background:#fff; color:var(--dark);' }}">
            Webinaires
        </a>
    </div>

    <div style="display:grid; grid-template-columns:repeat(auto-fill, minmax(320px, 1fr)); gap:28px;">
        @forelse($evenements as $event)
            <div style="background:#fff; padding:28px; display:flex; flex-direction:column; gap:14px; border-top:4px solid {{ $event->est_partenaire ? 'var(--yellow)' : 'var(--dark)' }};">
                <span class="kick" style="color:var(--accent-dark);">
                    {{ strtoupper($event->type_evenement) }}
                    @if($event->est_partenaire) · <strong style="color:#14151C;">PARTENAIRE CARGO</strong> @endif
                </span>
                <h2 class="dsp" style="margin:0; font-size:24px; line-height:1.2;">
                    <a href="{{ route('evenements.show', $event->slug) }}">{{ $event->titre }}</a>
                </h2>
                <div style="font-size:14px; color:var(--text-muted);">
                    📍 {{ $event->ville }} @if($event->pays) ({{ $event->pays }}) @endif<br>
                    📅 Du {{ $event->date_debut->format('d/m/Y') }} au {{ $event->date_fin->format('d/m/Y') }}
                </div>
                <div style="margin-top:auto; padding-top:14px; border-top:1px dashed var(--dashed-border);">
                    <a href="{{ route('evenements.show', $event->slug) }}" style="font-weight:700; color:var(--dark);">
                        Détails & Inscription →
                    </a>
                </div>
            </div>
        @empty
            <div style="grid-column: 1 / -1; background:#fff; padding:48px; text-align:center;">
                <h3 class="dsp" style="margin:0;">Aucun événement programmé dans cette catégorie</h3>
            </div>
        @endforelse
    </div>

    <div style="margin-top:48px; display:flex; justify-content:center;">
        {{ $evenements->links() }}
    </div>
</div>
@endsection
