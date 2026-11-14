@extends('redaction.layouts.redaction')

@section('titre', 'Gestion des vidéos embarquées — Rédaction CARGO')

@section('contenu')
<div class="space-y-6">
    {{-- En-tête éditorial aligné sur la charte CARGO --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 border-b border-gray-200">
        <div>
            <div class="flex items-center gap-2">
                <span class="inline-block w-2.5 h-2.5 rounded-full bg-cargo-red"></span>
                <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">Salle de rédaction &bull; Pôle Multimédia</span>
            </div>
            <h1 class="text-2xl font-heading font-black text-gray-900 tracking-tight mt-1">Catalogue des vidéos embarquées</h1>
        </div>

        <div class="flex items-center gap-2.5">
            <a href="{{ route('redaction.articles.index') }}" class="bg-white hover:bg-gray-50 text-gray-700 font-bold text-xs uppercase tracking-wider px-3 py-2 rounded border border-gray-300 shadow-sm transition flex items-center gap-1.5">
                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                <span>Articles</span>
            </a>
            <a href="{{ route('redaction.statistiques.index') }}" class="bg-white hover:bg-gray-50 text-gray-700 font-bold text-xs uppercase tracking-wider px-3 py-2 rounded border border-gray-300 shadow-sm transition flex items-center gap-1.5">
                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                <span>Statistiques éditoriales</span>
            </a>
        </div>
    </div>

    {{-- 1. Cartes de synthèse et KPI --}}
    @include('redaction.videos.composants.kpis-videos')

    {{-- 2. Barre d'outils, recherche instantanée et filtres --}}
    @include('redaction.videos.composants.barre-actions-et-filtres')

    {{-- 3. Vue tableau dense (par défaut) pour gérer des centaines de vidéos --}}
    @include('redaction.videos.composants.tableau-videos')

    {{-- 4. Vue grille visuelle de cartes (alternative) --}}
    @include('redaction.videos.composants.grille-videos')

    {{-- 5. Pagination dynamique sans rechargement --}}
    @include('redaction.videos.composants.pagination-videos')

    {{-- 6. Modale d'ajout rapide --}}
    @include('redaction.videos.composants.modale-ajout-video')

    {{-- 7. Modale d'aperçu du lecteur à la demande --}}
    @include('redaction.videos.composants.modale-apercu-video')

    {{-- 8. Modale de confirmation de suppression unitaire / par lot --}}
    @include('redaction.videos.composants.modale-confirmation-suppression')
</div>

{{-- Scripts modulaires de navigation dynamique et sécurité JWT --}}
@include('redaction.videos.composants.script-rendu-videos')
@include('redaction.videos.composants.script-modales-videos')
@include('redaction.videos.composants.script-navigation-dynamique')
@endsection
