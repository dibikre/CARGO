@extends('layouts.journal')

@section('titre', 'Session de recherche expirée — CARGO')
@section('robots', 'noindex, nofollow, noarchive')

@section('contenu')
<div style="background:var(--dark); color:#fff; padding:56px 0 44px; border-bottom:1px solid var(--dark-border);">
    <div class="wrap" style="max-width:760px; text-align:center;">
        <span class="kick" style="color:var(--yellow);">Recherche documentaire</span>
        <h1 class="dsp" style="margin:14px 0 16px; font-size:38px; font-weight:900; line-height:1.15;">
            Session de recherche expirée
        </h1>
        <p style="font-size:16px; color:#C9CCD6; max-width:620px; margin:0 auto; line-height:1.6;">
            {{ $message ?? 'Votre session de recherche a expiré. Veuillez relancer une nouvelle recherche.' }}
        </p>
    </div>
</div>

<div class="wrap" style="max-width:760px; padding-top:48px; padding-bottom:64px;">
    <div style="background:#fff; border:1px solid #E2E5EB; border-left:4px solid var(--accent); padding:32px; box-shadow:0 2px 8px rgba(0,0,0,0.04);">
        <p style="font-size:15px; line-height:1.7; color:#4B505C; margin:0 0 24px;">
            Pour accéder aux articles et analyses du journal, veuillez relancer une nouvelle recherche depuis la barre de recherche située en haut de page ou retourner à l'accueil du site.
        </p>

        <div style="display:flex; flex-wrap:wrap; gap:12px; align-items:center;">
            <button
                type="button"
                onclick="const btn = document.getElementById('bouton-declencheur-recherche'); if(btn){ btn.click(); window.scrollTo({top:0, behavior:'smooth'}); }"
                style="background:var(--accent); color:#fff; font-weight:700; padding:12px 24px; border:none; cursor:pointer; font-family:var(--police-titre); text-transform:uppercase; font-size:13px; letter-spacing:0.04em;"
            >
                Nouvelle recherche
            </button>
            <a
                href="{{ route('accueil') }}"
                style="border:1px solid #D5D8E0; background:#F4F1EA; color:var(--dark); font-weight:700; padding:11px 20px; text-decoration:none; font-family:var(--police-titre); text-transform:uppercase; font-size:13px;"
            >
                Retour à l'accueil
            </a>
        </div>
    </div>
</div>
@endsection
