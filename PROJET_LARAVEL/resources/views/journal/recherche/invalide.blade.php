@extends('layouts.journal')

@section('titre', 'Lien de recherche expiré — CARGO')

@section('contenu')
<div style="background:var(--dark); color:#fff; padding:56px 0 44px; border-bottom:1px solid var(--dark-border);">
    <div class="wrap" style="max-width:760px; text-align:center;">
        <span class="kick" style="color:var(--accent);">Sécurité du journal &bull; Session temporaire</span>
        <h1 class="dsp" style="margin:14px 0 16px; font-size:38px; font-weight:900; line-height:1.15;">
            Ce lien de recherche n'est plus accessible
        </h1>
        <p style="font-size:16px; color:#C9CCD6; max-width:620px; margin:0 auto; line-height:1.6;">
            {{ $message ?? 'Le lien temporaire généré a expiré (validité stricte de 10 secondes) ou a déjà été consommé pour protéger vos données de navigation.' }}
        </p>
    </div>
</div>

<div class="wrap" style="max-width:760px; padding-top:48px; padding-bottom:64px;">
    <div style="background:#fff; border:1px solid #E2E5EB; border-left:4px solid var(--accent); padding:32px; box-shadow:0 2px 8px rgba(0,0,0,0.04);">
        <h2 class="dsp" style="font-size:20px; margin:0 0 12px; color:var(--dark);">
            Règles d'accès et protection cryptographique
        </h2>
        <ul style="margin:0 0 24px; padding-left:20px; font-size:14px; line-height:1.7; color:#4B505C;">
            <li>Chaque URL de recherche est à <strong>usage strictement unique</strong> (consommation atomique).</li>
            <li>Le lien expire automatiquement <strong>10 secondes</strong> après sa génération côté serveur.</li>
            <li>L'accès est restreint à la session du navigateur ayant initié la requête initiale.</li>
        </ul>

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
