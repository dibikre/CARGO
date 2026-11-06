@extends('layouts.journal')

@section('titre', 'Rédiger un nouvel article — Espace Rédaction CARGO')

@section('contenu')
<div style="background:var(--dark); color:#fff; padding:40px 0 28px;">
    <div class="wrap">
        <span class="kick" style="color:var(--yellow);">Journalisme & Édition</span>
        <h1 class="dsp" style="margin:8px 0 0; font-size:38px; font-weight:900;">Nouvel Article</h1>
    </div>
</div>

<div class="wrap" style="max-width:860px; padding-top:36px;">
    <form action="{{ route('redaction.articles.store') }}" method="POST" style="background:#fff; padding:36px; display:flex; flex-direction:column; gap:20px;">
        @csrf

        <div>
            <label style="font-weight:700; font-size:14px; display:block; margin-bottom:6px;">Titre de l'article *</label>
            <input type="text" name="titre" required placeholder="Ex: Décarbonation du fret maritime : les armateurs face aux nouvelles normes" class="form-input">
        </div>

        <div style="display:flex; gap:16px; flex-wrap:wrap;">
            <div style="flex:1 1 200px;">
                <label style="font-weight:700; font-size:14px; display:block; margin-bottom:6px;">Rubrique principale *</label>
                <select name="categorie_id" required class="form-input">
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->nom }}</option>
                    @endforeach
                </select>
            </div>
            <div style="flex:1 1 200px;">
                <label style="font-weight:700; font-size:14px; display:block; margin-bottom:6px;">Mode de transport *</label>
                <select name="mode_transport_principal" required class="form-input">
                    <option value="multimodal">Multimodal</option>
                    <option value="maritime">Maritime</option>
                    <option value="aerien">Aérien</option>
                    <option value="routier">Routier</option>
                    <option value="ferroviaire">Ferroviaire</option>
                    <option value="entreposage">Entreposage</option>
                    <option value="douanes">Douanes</option>
                </select>
            </div>
            <div style="flex:1 1 200px;">
                <label style="font-weight:700; font-size:14px; display:block; margin-bottom:6px;">Format éditorial *</label>
                <select name="format_article" required class="form-input">
                    <option value="article">Article standard</option>
                    <option value="breve_flash">Brève Flash</option>
                    <option value="analyse">Analyse de fond</option>
                    <option value="reportage">Grand reportage</option>
                    <option value="interview">Interview</option>
                    <option value="etude_de_cas">Étude de cas</option>
                </select>
            </div>
        </div>

        <div>
            <label style="font-weight:700; font-size:14px; display:block; margin-bottom:6px;">Chapeau (résumé d'accroche)</label>
            <textarea name="chapeau" rows="3" placeholder="Deux à trois phrases pour introduire l'article..." class="form-input" style="height:auto; padding:12px;"></textarea>
        </div>

        <div>
            <label style="font-weight:700; font-size:14px; display:block; margin-bottom:6px;">Corps de l'article *</label>
            <textarea name="contenu" required rows="10" placeholder="Rédigez ici le texte complet de votre article..." class="form-input" style="height:auto; padding:12px;"></textarea>
        </div>

        <div style="display:flex; gap:20px; flex-wrap:wrap; background:var(--bg-light); padding:16px;">
            <label style="font-weight:600; font-size:14px; display:flex; align-items:center; gap:8px;">
                <input type="checkbox" name="est_a_la_une" value="1"> À la une (Hero)
            </label>
            <label style="font-weight:600; font-size:14px; display:flex; align-items:center; gap:8px;">
                <input type="checkbox" name="est_urgent" value="1"> Flash Urgent / En bref
            </label>
            <label style="font-weight:600; font-size:14px; display:flex; align-items:center; gap:8px;">
                <input type="checkbox" name="est_reserve_abonnes" value="1"> Réservé Abonnés (PRO)
            </label>
        </div>

        <div>
            <label style="font-weight:700; font-size:14px; display:block; margin-bottom:6px;">Statut de publication *</label>
            <select name="statut" required class="form-input">
                <option value="brouillon">Brouillon</option>
                <option value="en_relecture">En relecture</option>
                <option value="publie">Publier immédiatement</option>
            </select>
        </div>

        <div style="display:flex; justify-content:space-between; margin-top:12px;">
            <a href="{{ route('redaction.articles.index') }}" style="font-weight:600; color:var(--text-muted); align-self:center;">
                Annuler
            </a>
            <button type="submit" class="btn-yellow" style="font-size:16px; padding:14px 28px;">
                Enregistrer l'article
            </button>
        </div>
    </form>
</div>
@endsection
