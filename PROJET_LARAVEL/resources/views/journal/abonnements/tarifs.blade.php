@extends('layouts.journal')

@section('titre', 'Abonnements Professionnels — CARGO Pro')

@section('contenu')
<div style="background:var(--dark); color:#fff; padding:56px 0 44px; text-align:center;">
    <div class="wrap" style="max-width:800px;">
        <span class="kick" style="color:var(--yellow);">Offres lecteurs & entreprises</span>
        <h1 class="dsp" style="margin:16px 0; font-size:48px; font-weight:900;">Abonnez-vous à CARGO Pro</h1>
        <p style="color:#C9CCD6; font-size:18px; margin:0;">
            Accédez à toutes les analyses exclusives de la rédaction, aux cours mondiaux du fret et aux dossiers sectoriels sans limite.
        </p>
    </div>
</div>

<div class="wrap" style="padding-top:36px;">
    @if(isset($abonnementsActifs) && !$abonnementsActifs)
        <div style="background:#ECFDF5; border:2px solid #10B981; padding:28px 32px; margin-bottom:36px; display:flex; flex-wrap:wrap; align-items:center; justify-content:space-between; gap:20px;">
            <div style="max-width:680px;">
                <span class="kick" style="color:#047857; font-size:12px; display:block; margin-bottom:6px;">CARGO ACCÈS LIBRE ACTIF</span>
                <h2 class="dsp" style="margin:0 0 8px; font-size:26px; color:#064E3B;">Toutes les formules sont actuellement gratuites et en libre accès</h2>
                <p style="margin:0; color:#065F46; font-size:15px; line-height:1.6;">
                    La rédaction et l'administration ont désactivé la facturation des abonnements. Vous bénéficiez d'un accès libre, intégral et illimité à l'ensemble des analyses fret, données maritimes et dossiers d'investigation sans aucun paiement requis.
                </p>
            </div>
            <a href="{{ route('accueil') }}" style="background:#0F766E; color:#fff; font-weight:700; padding:14px 28px; font-family:var(--police-titre); text-transform:uppercase; font-size:calc(12.5px * var(--echelle-texte)); letter-spacing:0.05em; display:inline-block;">
                Explorer tous les articles
            </a>
        </div>
    @endif

    <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(290px, 1fr)); gap:32px;">
        @forelse($formules as $formule)
            <div style="background:#fff; padding:36px; box-sizing:border-box; display:flex; flex-direction:column; gap:20px; border-top:6px solid {{ $loop->iteration === 2 ? 'var(--yellow)' : 'var(--accent)' }}; position:relative; {{ isset($abonnementsActifs) && !$abonnementsActifs ? 'opacity:0.85;' : '' }}">
                @if($loop->iteration === 2)
                    <span style="position:absolute; top:-16px; right:24px; background:var(--yellow); color:#14151C; padding:4px 12px; font-size:11px; font-weight:800; text-transform:uppercase; letter-spacing:.1em;">
                        Recommandé
                    </span>
                @endif

                <div>
                    <h2 class="dsp" style="margin:0; font-size:26px;">{{ $formule->nom }}</h2>
                    <p style="margin:8px 0 0; color:var(--text-muted); font-size:14px;">{{ $formule->description }}</p>
                </div>

                <div style="padding:16px 0; border-top:2px dashed var(--dashed-border); border-bottom:2px dashed var(--dashed-border);">
                    <span class="dsp" style="font-size:44px; font-weight:900;">{{ number_format($formule->prix_ht, 0, ',', ' ') }} €</span>
                    <span style="font-size:14px; color:var(--text-muted);">HT / {{ $formule->duree_en_jours }} jours</span>
                </div>

                <ul style="list-style:none; padding:0; margin:0; display:flex; flex-direction:column; gap:10px; font-size:15px;">
                    <li>✓ Accès illimité aux articles et dossiers PRO</li>
                    <li>✓ Baromètres et livres blancs téléchargeables</li>
                    <li>✓ Alertes en temps réel sur les corridors de fret</li>
                    @if($formule->nombre_utilisateurs_max > 1)
                        <li>✓ Jusqu'à <strong>{{ $formule->nombre_utilisateurs_max }} accès</strong> simultanés entreprise</li>
                    @endif
                </ul>

                @if(isset($abonnementsActifs) && !$abonnementsActifs)
                    <div style="margin-top:auto; padding-top:16px;">
                        <a href="{{ route('accueil') }}" style="background:#0F766E; color:#fff; display:block; text-align:center; padding:12px 18px; font-weight:700; text-transform:uppercase; font-size:calc(12.5px * var(--echelle-texte)); font-family:var(--police-titre);">
                            Accès Libre (Sans Frais)
                        </a>
                    </div>
                @else
                    @auth
                        <form action="{{ route('abonnements.souscrire') }}" method="POST" style="margin-top:auto; padding-top:16px;">
                            @csrf
                            <input type="hidden" name="formule_id" value="{{ $formule->id }}">
                            <input type="hidden" name="mode_paiement" value="carte_bancaire">
                            <button type="submit" class="btn-yellow" style="width:100%; text-align:center;">
                                Choisir cette formule
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="btn-yellow" style="text-align:center; margin-top:auto;">
                            Se connecter pour s’abonner
                        </a>
                    @endauth
                @endif
            </div>
        @empty
            <div style="grid-column: 1 / -1; background:#fff; padding:48px; text-align:center;">
                <p>Aucune offre d'abonnement n'est disponible pour le moment.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection
