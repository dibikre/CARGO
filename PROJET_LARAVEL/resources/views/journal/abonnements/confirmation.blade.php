@extends('layouts.journal')

@section('titre', 'Confirmation d’abonnement — CARGO Pro')

@section('contenu')
<div class="wrap" style="max-width:680px; padding:64px 32px;">
    <div style="background:#fff; padding:48px; border-top:6px solid var(--accent); text-align:center;">
        <span class="kick" style="color:var(--accent-dark);">Abonnement actif</span>
        <h1 class="dsp" style="margin:16px 0; font-size:38px; font-weight:900;">Merci pour votre confiance</h1>
        <p style="font-size:17px; line-height:1.6; color:var(--text-muted);">
            Votre formule <strong>{{ $abonnement->formule->nom }}</strong> est désormais activée sur votre compte.
        </p>

        <div style="background:var(--bg-light); padding:20px; margin:24px 0; text-align:left; font-size:15px; display:flex; flex-direction:column; gap:8px;">
            <div><strong>Référence :</strong> {{ $abonnement->reference_commande }}</div>
            <div><strong>Date d'échéance :</strong> {{ $abonnement->date_fin->format('d/m/Y') }}</div>
            <div><strong>Montant réglé :</strong> {{ number_format($abonnement->montant_paye_ttc, 2, ',', ' ') }} € TTC</div>
        </div>

        <a href="{{ route('accueil') }}" class="btn-yellow">
            Accéder aux articles PRO
        </a>
    </div>
</div>
@endsection
