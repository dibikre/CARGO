@extends('layouts.journal')

@section('titre', 'Confirmation d’inscription à la newsletter — CARGO')

@section('contenu')
<div class="wrap" style="max-width:680px; padding:64px 32px; text-align:center;">
    <div style="background:#fff; padding:48px; border-top:6px solid var(--accent);">
        <div style="width:64px; height:64px; background:#DCFCE7; color:#16A34A; border-radius:50%; display:flex; align-items:center; justify-content:center; margin:0 auto 20px; font-size:32px;">
            ✓
        </div>
        <span class="kick" style="color:var(--accent-dark);">Inscription confirmée</span>
        <h1 class="dsp" style="margin:16px 0; font-size:38px; font-weight:900;">Bienvenue sur CARGO !</h1>
        <p style="font-size:17px; line-height:1.6; color:var(--text-muted); margin-bottom:28px;">
            Votre adresse e-mail <strong>{{ $abonne->email }}</strong> est désormais validée. Vous recevrez nos analyses hebdomadaires et veilles stratégiques.
        </p>
        <a href="{{ route('accueil') }}" class="btn-yellow">
            Retourner à l’accueil
        </a>
    </div>
</div>
@endsection
