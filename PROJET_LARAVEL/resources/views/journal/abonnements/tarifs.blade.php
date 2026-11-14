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
    @elseif(isset($souscriptionsActives) && !$souscriptionsActives)
        <div style="background:#FFFBEB; border:2px solid #F59E0B; padding:28px 32px; margin-bottom:36px; display:flex; flex-wrap:wrap; align-items:center; justify-content:space-between; gap:20px;">
            <div style="max-width:680px;">
                <span class="kick" style="color:#B45309; font-size:12px; display:block; margin-bottom:6px;">SOUSCRIPTIONS TEMPORAIREMENT FERMÉES</span>
                <h2 class="dsp" style="margin:0 0 8px; font-size:26px; color:#92400E;">Les nouvelles souscriptions sont actuellement désactivées</h2>
                <p style="margin:0; color:#78350F; font-size:15px; line-height:1.6;">
                    L'administration a suspendu la souscription à de nouveaux forfaits d'abonnement. Les abonnés actifs disposent toujours de l'intégralité de leurs accès réservés.
                </p>
            </div>
            <a href="{{ route('accueil') }}" style="background:#B45309; color:#fff; font-weight:700; padding:14px 28px; font-family:var(--police-titre); text-transform:uppercase; font-size:calc(12.5px * var(--echelle-texte)); letter-spacing:0.05em; display:inline-block;">
                Retour à l'accueil
            </a>
        </div>
    @endif

    @if($abonnementsActifs && auth()->check())
        @if(isset($abonnementActif) && $abonnementActif && $abonnementActif->estValide())
            <div style="background:#ECFDF5; border:2px solid #16A34A; padding:22px 28px; margin-bottom:28px; display:flex; flex-wrap:wrap; align-items:center; justify-content:space-between; gap:16px;">
                <div>
                    <span style="background:#16A34A; color:#fff; padding:4px 10px; font-size:11px; font-weight:800; text-transform:uppercase; letter-spacing:0.08em;">Abonnement PRO Actif</span>
                    <h2 class="dsp" style="margin:8px 0 4px; font-size:22px; color:#064E3B;">
                        Vous êtes abonné à la formule {{ $abonnementActif->formule->nom ?? 'CARGO PRO' }}
                    </h2>
                    <p style="margin:0; color:#065F46; font-size:14px;">
                        Votre accès complet aux articles PRO est actif jusqu'au <strong>{{ $abonnementActif->date_fin->format('d/m/Y') }}</strong>. Vous pouvez prolonger votre abonnement à tout moment ci-dessous.
                    </p>
                </div>
                <a href="{{ route('abonnements.espace') }}" style="background:#065F46; color:#fff; font-weight:700; padding:11px 20px; font-size:13px; text-transform:uppercase;">
                    Voir mon compte
                </a>
            </div>
        @else
            <div style="background:#FEF2F2; border:2px solid var(--accent); padding:22px 28px; margin-bottom:28px; display:flex; flex-wrap:wrap; align-items:center; justify-content:space-between; gap:16px;">
                <div>
                    <span style="background:var(--accent); color:#fff; padding:4px 10px; font-size:11px; font-weight:800; text-transform:uppercase; letter-spacing:0.08em;">Aucun abonnement PRO actif</span>
                    <h2 class="dsp" style="margin:8px 0 4px; font-size:22px; color:var(--dark);">
                        Connecté en tant que {{ auth()->user()->name }} — Compte non abonné PRO
                    </h2>
                    <p style="margin:0; color:var(--text-muted); font-size:14px;">
                        Souscrivez à l'une des formules ci-dessous pour activer immédiatement votre abonnement PRO et lire l'intégralité des articles réservés.
                    </p>
                </div>
                <a href="{{ route('abonnements.espace') }}" style="background:var(--dark); color:#fff; font-weight:700; padding:11px 20px; font-size:13px; text-transform:uppercase;">
                    Mon statut
                </a>
            </div>
        @endif
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
                    <li style="display:flex; align-items:center; gap:8px;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#16A34A" stroke-width="2.5" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg>
                        Accès illimité aux articles et dossiers PRO
                    </li>
                    <li style="display:flex; align-items:center; gap:8px;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#16A34A" stroke-width="2.5" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg>
                        Baromètres et livres blancs téléchargeables
                    </li>
                    <li style="display:flex; align-items:center; gap:8px;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#16A34A" stroke-width="2.5" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg>
                        Alertes en temps réel sur les corridors de fret
                    </li>
                    @if($formule->nombre_utilisateurs_max > 1)
                        <li style="display:flex; align-items:center; gap:8px;">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#16A34A" stroke-width="2.5" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg>
                            <span>Jusqu'à <strong>{{ $formule->nombre_utilisateurs_max }} accès</strong> simultanés entreprise</span>
                        </li>
                    @endif
                </ul>

                @if(isset($abonnementsActifs) && !$abonnementsActifs)
                    <div style="margin-top:auto; padding-top:16px;">
                        <a href="{{ route('accueil') }}" style="background:#0F766E; color:#fff; display:block; text-align:center; padding:12px 18px; font-weight:700; text-transform:uppercase; font-size:calc(12.5px * var(--echelle-texte)); font-family:var(--police-titre);">
                            Accès Libre (Sans Frais)
                        </a>
                    </div>
                @elseif(isset($souscriptionsActives) && !$souscriptionsActives)
                    <div style="margin-top:auto; padding-top:16px;">
                        <button disabled style="background:#E5E7EB; color:#9CA3AF; width:100%; display:block; text-align:center; padding:12px 18px; font-weight:700; text-transform:uppercase; font-size:calc(12.5px * var(--echelle-texte)); font-family:var(--police-titre); border:none; cursor:not-allowed;">
                            Souscriptions Suspendues
                        </button>
                    </div>
                @else
                    @auth
                        <form action="{{ route('abonnements.souscrire') }}" method="POST" style="margin-top:auto; padding-top:16px; display:flex; flex-direction:column; gap:10px;">
                            @csrf
                            <input type="hidden" name="formule_id" value="{{ $formule->id }}">
                            <div>
                                <label for="mode_paiement_{{ $formule->id }}" style="display:block; font-size:11px; font-weight:700; text-transform:uppercase; color:var(--text-muted); margin-bottom:4px;">Mode de règlement</label>
                                <select id="mode_paiement_{{ $formule->id }}" name="mode_paiement" class="form-input" style="height:40px; font-size:13.5px;">
                                    <option value="carte_bancaire">Carte bancaire</option>
                                    <option value="virement">Virement bancaire</option>
                                    <option value="prelevement">Prélèvement SEPA</option>
                                    <option value="facture_pro">Facture entreprise PRO</option>
                                </select>
                            </div>
                            <button type="submit" style="width:100%; text-align:center; background:var(--accent); color:#fff; font-weight:700; font-family:var(--police-titre); font-size:calc(13.5px * var(--echelle-texte)); padding:13px 20px; border:0; cursor:pointer; text-transform:uppercase; letter-spacing:0.05em;">
                                {{ isset($abonnementActif) && $abonnementActif && $abonnementActif->estValide() ? 'Prolonger avec cette formule' : 'Souscrire à cette formule' }}
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
