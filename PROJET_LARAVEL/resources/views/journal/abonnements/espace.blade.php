@extends('layouts.journal')

@section('titre', 'Mon Espace Abonné — CARGO Pro')

@section('contenu')
<div style="background:var(--dark); color:#fff; padding:48px 0 36px;">
    <div class="wrap">
        <span class="kick" style="color:var(--yellow);">Espace lecteur</span>
        <h1 class="dsp" style="margin:12px 0 0; font-size:44px; font-weight:900;">Mon compte CARGO</h1>
        <p style="color:#C9CCD6; font-size:16px; margin-top:8px;">Bonjour {{ $utilisateur->name }} ({{ $utilisateur->email }})</p>
    </div>
</div>

<div class="wrap" style="max-width:960px; padding-top:40px;">
    {{-- État actuel de l'abonnement --}}
    <div style="background:#fff; padding:32px; margin-bottom:32px; border-top:4px solid var(--accent);">
        <h2 class="dsp" style="margin:0 0 16px; font-size:26px;">Statut de votre abonnement</h2>
        @if($abonnementActif && $abonnementActif->estValide())
            <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:16px;">
                <div>
                    <span style="background:#22C55E; color:#fff; padding:4px 10px; font-weight:700; font-size:13px;">ACTIF</span>
                    <h3 class="dsp" style="margin:8px 0 4px; font-size:22px;">{{ $abonnementActif->formule->nom }}</h3>
                    <p style="margin:0; color:var(--text-muted); font-size:14px;">
                        Valide jusqu'au <strong>{{ $abonnementActif->date_fin->format('d/m/Y') }}</strong>
                    </p>
                </div>
                <a href="{{ route('abonnements.index') }}" class="btn-yellow" style="font-size:14px;">
                    Changer ou renouveler
                </a>
            </div>
        @else
            <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:16px;">
                <div>
                    <span style="background:var(--accent); color:#fff; padding:4px 10px; font-weight:700; font-size:13px;">NON ABONNÉ</span>
                    <p style="margin:8px 0 0; font-size:15px; color:var(--text-muted);">
                        Vous n'avez pas d'abonnement actif. Abonnez-vous pour débloquer les analyses PRO.
                    </p>
                </div>
                <a href="{{ route('abonnements.index') }}" class="btn-yellow">
                    Découvrir les offres PRO
                </a>
            </div>
        @endif
    </div>

    {{-- Historique des souscriptions --}}
    @if($historique->isNotEmpty())
        <div style="background:#fff; padding:32px;">
            <h2 class="dsp" style="margin:0 0 16px; font-size:24px;">Historique de vos commandes</h2>
            <div style="display:flex; flex-direction:column; gap:12px;">
                @foreach($historique as $abo)
                    <div style="display:flex; justify-content:space-between; align-items:center; padding:12px 0; border-bottom:1px dashed var(--dashed-border); font-size:14px;">
                        <div>
                            <strong>{{ $abo->formule->nom }}</strong> ({{ $abo->reference_commande }})<br>
                            <span style="color:var(--text-muted);">Du {{ $abo->date_debut->format('d/m/Y') }} au {{ $abo->date_fin->format('d/m/Y') }}</span>
                        </div>
                        <div>
                            <strong>{{ number_format($abo->montant_paye_ttc, 2, ',', ' ') }} € TTC</strong>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection
