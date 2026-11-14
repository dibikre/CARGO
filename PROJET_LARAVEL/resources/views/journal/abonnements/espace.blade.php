@extends('layouts.journal')

@section('titre', 'Mon Espace Abonné — CARGO Pro')

@section('contenu')
<div style="background:var(--dark); color:#fff; padding:48px 0 36px;">
    <div class="wrap" style="display:flex; flex-wrap:wrap; justify-content:space-between; align-items:flex-end; gap:16px;">
        <div>
            <span class="kick" style="color:var(--yellow);">Espace lecteur</span>
            <h1 class="dsp" style="margin:12px 0 0; font-size:44px; font-weight:900;">Mon compte CARGO</h1>
            <p style="color:#C9CCD6; font-size:16px; margin-top:8px;">Bonjour {{ $utilisateur->name }} ({{ $utilisateur->email }})</p>
        </div>
        <form action="{{ route('logout') }}" method="POST" style="margin:0;">
            @csrf
            <button type="submit" style="background:#262936; color:#fff; border:1px solid #4A4E5C; padding:10px 18px; font-weight:700; font-size:13px; cursor:pointer; text-transform:uppercase;">
                Se déconnecter
            </button>
        </form>
    </div>
</div>

<div class="wrap" style="max-width:960px; padding-top:40px;">
    @if(isset($abonnementsActifs) && !$abonnementsActifs)
        <div style="background:#ECFDF5; border:2px solid #10B981; padding:24px 28px; margin-bottom:28px;">
            <span class="kick" style="color:#047857; font-size:12px;">CARGO ACCÈS LIBRE</span>
            <h2 class="dsp" style="margin:6px 0 8px; font-size:24px; color:#064E3B;">Les abonnements PRO sont actuellement désactivés par l'administration</h2>
            <p style="margin:0; color:#065F46; font-size:15px;">Tous les articles et dossiers PRO sont accessibles gratuitement sans abonnement actif.</p>
        </div>
    @endif

    {{-- État actuel de l'abonnement --}}
    <div style="background:#fff; padding:32px; margin-bottom:32px; border-top:4px solid var(--accent);">
        <h2 class="dsp" style="margin:0 0 16px; font-size:26px;">Statut de votre abonnement PRO</h2>
        @if($abonnementActif && $abonnementActif->estValide())
            <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:16px;">
                <div>
                    <span style="background:#16A34A; color:#fff; padding:5px 12px; font-weight:800; font-size:12px; text-transform:uppercase; letter-spacing:0.06em;">ABONNEMENT PRO ACTIF</span>
                    <h3 class="dsp" style="margin:10px 0 4px; font-size:24px;">{{ $abonnementActif->formule->nom }}</h3>
                    <p style="margin:0; color:var(--text-muted); font-size:14px;">
                        Accès complet aux articles PRO valide jusqu'au <strong>{{ $abonnementActif->date_fin->format('d/m/Y') }}</strong> (Réf. {{ $abonnementActif->reference_commande }})
                    </p>
                </div>
                @if($abonnementsActifs ?? true)
                    <a href="{{ route('abonnements.index') }}" class="btn-yellow" style="font-size:14px;">
                        Changer ou renouveler
                    </a>
                @endif
            </div>
        @else
            <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:16px;">
                <div>
                    <span style="background:var(--accent); color:#fff; padding:5px 12px; font-weight:800; font-size:12px; text-transform:uppercase; letter-spacing:0.06em;">NON ABONNÉ PRO</span>
                    <p style="margin:10px 0 0; font-size:15px; color:var(--text-muted);">
                        Vous n'avez pas d'abonnement PRO actif actuellement.
                        @if($abonnementsActifs ?? true)
                            Souscrivez à une formule ci-dessous pour débloquer l'intégralité des articles PRO.
                        @endif
                    </p>
                </div>
                @if(($abonnementsActifs ?? true) && ($souscriptionsActives ?? true))
                    <a href="{{ route('abonnements.index') }}" style="background:var(--accent); color:#fff; font-weight:700; padding:12px 22px; font-family:var(--police-titre); text-transform:uppercase; font-size:13px;">
                        Voir toutes les offres PRO
                    </a>
                @endif
            </div>
        @endif
    </div>

    {{-- Souscription directe lorsque l'utilisateur n'a pas encore d'abonnement PRO actif --}}
    @if((! $abonnementActif || ! $abonnementActif->estValide()) && ($abonnementsActifs ?? true))
        <div style="background:#fff; padding:32px; margin-bottom:32px; border-top:4px solid var(--yellow);">
            <h2 class="dsp" style="margin:0 0 8px; font-size:24px;">Souscrire à un abonnement CARGO PRO</h2>
            <p style="margin:0 0 20px; color:var(--text-muted); font-size:14px;">
                Activez votre accès complet aux articles et enquêtes réservés PRO.
            </p>

            @if(!($souscriptionsActives ?? true))
                <div style="background:#FFFBEB; border:1px solid #F59E0B; padding:16px; color:#92400E; font-size:14px; font-weight:600;">
                    Les nouvelles souscriptions sont temporairement suspendues par l'administration.
                </div>
            @elseif(isset($formulesDisponibles) && $formulesDisponibles->isNotEmpty())
                <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(260px, 1fr)); gap:20px;">
                    @foreach($formulesDisponibles as $formule)
                        <div style="border:1px solid #D1D5DB; padding:22px; display:flex; flex-direction:column; gap:12px;">
                            <div>
                                <h3 class="dsp" style="margin:0; font-size:20px;">{{ $formule->nom }}</h3>
                                <p style="margin:6px 0 0; font-size:13px; color:var(--text-muted);">{{ $formule->description }}</p>
                            </div>
                            <div style="font-size:26px; font-weight:900; font-family:var(--police-titre);">
                                {{ number_format($formule->prix_ht, 0, ',', ' ') }} € <span style="font-size:13px; font-weight:500; color:var(--text-muted);">HT / {{ $formule->duree_en_jours }} jours</span>
                            </div>
                            <form action="{{ route('abonnements.souscrire') }}" method="POST" style="margin-top:auto; display:flex; flex-direction:column; gap:10px;">
                                @csrf
                                <input type="hidden" name="formule_id" value="{{ $formule->id }}">
                                <select name="mode_paiement" class="form-input" style="height:38px; font-size:13px;">
                                    <option value="carte_bancaire">Carte bancaire</option>
                                    <option value="virement">Virement bancaire</option>
                                    <option value="prelevement">Prélèvement SEPA</option>
                                    <option value="facture_pro">Facture entreprise PRO</option>
                                </select>
                                <button type="submit" style="background:var(--accent); color:#fff; border:none; padding:11px 16px; font-weight:700; font-family:var(--police-titre); text-transform:uppercase; font-size:13px; cursor:pointer;">
                                    Souscrire maintenant
                                </button>
                            </form>
                        </div>
                    @endforeach
                </div>
            @else
                <p style="color:var(--text-muted);">Aucune formule d'abonnement active n'est disponible pour le moment.</p>
            @endif
        </div>
    @endif

    {{-- Historique des souscriptions --}}
    @if($historique->isNotEmpty())
        <div style="background:#fff; padding:32px;">
            <h2 class="dsp" style="margin:0 0 16px; font-size:24px;">Historique de vos commandes</h2>
            <div style="display:flex; flex-direction:column; gap:12px;">
                @foreach($historique as $abo)
                    <div style="display:flex; justify-content:space-between; align-items:center; padding:12px 0; border-bottom:1px dashed var(--dashed-border); font-size:14px;">
                        <div>
                            <strong>{{ $abo->formule->nom }}</strong> ({{ $abo->reference_commande }})<br>
                            <span style="color:var(--text-muted);">Du {{ $abo->date_debut->format('d/m/Y') }} au {{ $abo->date_fin->format('d/m/Y') }} — Statut : {{ strtoupper($abo->statut) }}</span>
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
