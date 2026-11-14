@extends('redaction.layouts.redaction')

@section('titre', 'Audience & Publications — Analytique — Rédaction CARGO')

@section('contenu')
<div id="conteneurStatistiques" class="space-y-6" data-jeton="{{ $jetonSecurite }}" data-url-api="{{ route('redaction.statistiques.api') }}">
    {{-- Bannière d'alerte sécurité épurée --}}
    <div id="banniereAlerteSecurite" class="hidden bg-white border-2 border-cargo-dark text-gray-900 p-4 rounded text-xs flex items-center justify-between shadow-xs">
        <div class="flex items-center gap-2">
            <svg class="w-4 h-4 text-cargo-accent shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            <span id="messageAlerteSecurite" class="font-bold">Le jeton de sécurité est invalide, expiré ou associé à un autre appareil.</span>
        </div>
        <button type="button" onclick="document.getElementById('banniereAlerteSecurite').classList.add('hidden')" class="text-gray-500 hover:text-gray-900 font-bold text-sm">&times;</button>
    </div>

    {{-- En-tête avec sélecteur de période dynamique (style identique à /redaction/articles) --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 border-b border-gray-200">
        <div>
            <div class="flex items-center gap-2">
                <span class="inline-block w-2.5 h-2.5 rounded-full bg-cargo-accent"></span>
                <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">Salle de rédaction &bull; Audience &amp; Publications</span>
            </div>
            <h1 class="text-2xl font-heading font-black text-gray-900 tracking-tight mt-1 flex items-center gap-3">
                <span>Audience &amp; Publications — Analytique</span>
                {{-- Indicateur de chargement sobre --}}
                <span id="indicateurChargement" class="hidden inline-flex items-center gap-1.5 px-2.5 py-0.5 bg-gray-100 text-gray-800 border border-gray-300 text-xs font-bold rounded">
                    <svg class="w-3 h-3 animate-spin text-gray-700" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
                    <span>Actualisation...</span>
                </span>
            </h1>
        </div>

        {{-- Filtres de période calqués sur les filtres de /redaction/articles --}}
        <div class="flex items-center gap-2 flex-wrap">
            <a href="{{ route('redaction.statistiques.index', ['periode' => '7']) }}" data-periode="7" class="bouton-periode px-4 py-2 font-bold text-xs uppercase tracking-wider transition {{ $periode === '7' ? 'bg-cargo-dark text-white' : 'bg-white text-gray-800 border border-gray-300 hover:bg-gray-50' }}">7 derniers jours</a>
            <a href="{{ route('redaction.statistiques.index', ['periode' => '30']) }}" data-periode="30" class="bouton-periode px-4 py-2 font-bold text-xs uppercase tracking-wider transition {{ $periode === '30' ? 'bg-cargo-dark text-white' : 'bg-white text-gray-800 border border-gray-300 hover:bg-gray-50' }}">30 derniers jours</a>
            <a href="{{ route('redaction.statistiques.index', ['periode' => '90']) }}" data-periode="90" class="bouton-periode px-4 py-2 font-bold text-xs uppercase tracking-wider transition {{ $periode === '90' ? 'bg-cargo-dark text-white' : 'bg-white text-gray-800 border border-gray-300 hover:bg-gray-50' }}">90 derniers jours</a>
        </div>
    </div>

    {{-- 1. Cartes des métriques clés --}}
    @include('redaction.statistiques.composants.kpis-principaux')

    {{-- 2. Diagrammes circulaires hebdomadaires (depuis lundi) : Volume de publication & Nombre de vues --}}
    @include('redaction.statistiques.composants.diagrammes-circulaires-hebdomadaires')

    {{-- 3. Statistique des articles les plus vus de chaque jour de la semaine --}}
    @include('redaction.statistiques.composants.tableau-plus-vus-par-jour')

    {{-- 4. Évolution quotidienne (courbe des vues et histogramme des publications) --}}
    @include('redaction.statistiques.composants.graphiques-evolution')

    {{-- 5. Répartition par catégorie et Top 10 des articles --}}
    @include('redaction.statistiques.composants.repartition-categories-et-top')
</div>
@endsection

@push('scripts')
    {{-- Script de navigation dynamique sans rechargement de page sécurisée par JWT --}}
    @include('redaction.statistiques.composants.script-navigation-dynamique')
@endpush
