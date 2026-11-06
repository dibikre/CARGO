@extends('layouts.journal')

@section('titre', 'Livres Blancs & Baromètres Logistiques — CARGO Ressources')

@section('contenu')
<div style="background:var(--dark); color:#fff; padding:48px 0 36px;">
    <div class="wrap">
        <span class="kick" style="color:var(--yellow);">Centre de ressources & études</span>
        <h1 class="dsp" style="margin:12px 0 0; font-size:48px; font-weight:900;">Livres Blancs & Baromètres</h1>
        <p style="color:#C9CCD6; font-size:18px; margin-top:8px;">Téléchargez nos fiches méthodologiques, comparatifs WMS et bilans annuels de fret.</p>
    </div>
</div>

<div class="wrap" style="padding-top:40px;">
    <div style="display:grid; grid-template-columns:repeat(auto-fill, minmax(320px, 1fr)); gap:28px;">
        @forelse($documents as $doc)
            <div style="background:#fff; padding:28px; display:flex; flex-direction:column; gap:16px; border-top:4px solid var(--accent);">
                <span class="kick" style="color:var(--accent-dark);">
                    Document · {{ strtoupper($doc->type_mime === 'application/pdf' ? 'PDF' : 'Tableur') }}
                    @if($doc->est_reserve_abonnes)
                        · <span style="background:var(--yellow); color:#14151C; padding:2px 6px; font-weight:700;">PRO</span>
                    @endif
                </span>

                <h2 class="dsp" style="margin:0; font-size:24px; line-height:1.2;">{{ $doc->titre }}</h2>

                @if($doc->description)
                    <p style="margin:0; font-size:14px; color:var(--text-muted); line-height:1.5;">{{ $doc->description }}</p>
                @endif

                <div style="margin-top:auto; padding-top:16px; border-top:1px dashed var(--dashed-border); display:flex; justify-content:space-between; align-items:center;">
                    <span style="font-size:13px; color:var(--text-muted);">{{ $doc->nombre_telechargements }} téléchargements</span>
                    <a href="{{ route('ressources.telecharger', $doc->slug) }}" class="btn-yellow" style="padding:8px 16px; font-size:13px;">
                        Télécharger ↓
                    </a>
                </div>
            </div>
        @empty
            <div style="grid-column: 1 / -1; background:#fff; padding:48px; text-align:center;">
                <h3 class="dsp" style="margin:0;">Aucun document disponible actuellement</h3>
            </div>
        @endforelse
    </div>

    <div style="margin-top:48px; display:flex; justify-content:center;">
        {{ $documents->links() }}
    </div>
</div>
@endsection
