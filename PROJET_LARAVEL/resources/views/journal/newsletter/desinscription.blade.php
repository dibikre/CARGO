@extends('layouts.journal')

@section('titre', 'Désinscription confirmée — CARGO')

@section('contenu')
<div class="wrap" style="max-width:680px; padding:64px 32px; text-align:center;">
    <div style="background:#fff; padding:48px; border-top:6px solid var(--dark);">
        <span class="kick" style="color:var(--text-muted);">Désinscription prise en compte</span>
        <h1 class="dsp" style="margin:16px 0; font-size:36px; font-weight:900;">Vous êtes désinscrit</h1>
        <p style="font-size:16px; line-height:1.6; color:var(--text-muted); margin-bottom:28px;">
            L'adresse <strong>{{ $abonne->email }}</strong> a bien été retirée de nos listes de diffusion. Vous ne recevrez plus nos lettres d'information.
        </p>
        <a href="{{ route('accueil') }}" class="btn-yellow">
            Retourner au journal
        </a>
    </div>
</div>
@endsection
