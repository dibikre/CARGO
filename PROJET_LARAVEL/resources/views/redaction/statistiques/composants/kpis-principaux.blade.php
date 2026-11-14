{{-- 
  Composant Blade : Cartes des métriques clés (KPIs) en haut du tableau de bord rédaction
  Style épuré aligné avec /redaction/articles (typographie noire & métadonnées grises sobres)
--}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
    {{-- Carte 1 : Vues totales --}}
    <div class="bg-white p-5 rounded border border-gray-200 shadow-xs relative overflow-hidden">
        <div class="text-xs font-bold text-gray-500 uppercase tracking-wider">Vues uniques cumulées</div>
        <div id="kpiTotalVues" class="text-3xl font-heading font-black text-gray-900 mt-2">{{ number_format($totalVuesCumulees) }}</div>
        <div class="text-xs text-gray-500 font-semibold mt-1 flex items-center gap-1.5">
            <span class="w-1.5 h-1.5 rounded-full bg-cargo-accent"></span>
            <span>Lectures vérifiées par IP en direct</span>
        </div>
        <div class="absolute right-3 top-3 text-gray-100 pointer-events-none">
            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
        </div>
    </div>

    {{-- Carte 2 : Publications de la période --}}
    <div class="bg-white p-5 rounded border border-gray-200 shadow-xs relative overflow-hidden">
        <div class="text-xs font-bold text-gray-500 uppercase tracking-wider">Publiés sur la période</div>
        <div id="kpiPubsPeriode" class="text-3xl font-heading font-black text-gray-900 mt-2">{{ $publicationsPeriode }}</div>
        <div id="kpiJoursLibelle" class="text-xs text-gray-500 font-semibold mt-1">Articles publiés sur les {{ $jours }} derniers jours</div>
        <div class="absolute right-3 top-3 text-gray-100 pointer-events-none">
            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
        </div>
    </div>

    {{-- Carte 3 : Total des articles publiés --}}
    <div class="bg-white p-5 rounded border border-gray-200 shadow-xs relative overflow-hidden">
        <div class="text-xs font-bold text-gray-500 uppercase tracking-wider">Total publiés</div>
        <div id="kpiTotalPublies" class="text-3xl font-heading font-black text-gray-900 mt-2">{{ $totalPublies }}</div>
        <div class="text-xs text-gray-500 font-semibold mt-1">{{ $totalArticles }} articles dans la base rédaction</div>
        <div class="absolute right-3 top-3 text-gray-100 pointer-events-none">
            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
    </div>

    {{-- Carte 4 : Flux éditorial --}}
    <div class="bg-white p-5 rounded border border-gray-200 shadow-xs relative overflow-hidden">
        <div class="text-xs font-bold text-gray-500 uppercase tracking-wider">Flux éditorial</div>
        <div id="kpiPipeline" class="text-3xl font-heading font-black text-gray-900 mt-2">{{ $totalRelecture + $totalBrouillons }}</div>
        <div class="text-xs text-gray-500 font-semibold mt-1">{{ $totalRelecture }} en relecture &bull; {{ $totalBrouillons }} brouillons</div>
        <div class="absolute right-3 top-3 text-gray-100 pointer-events-none">
            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
        </div>
    </div>
</div>
