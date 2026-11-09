{{-- 
  Composant Blade : Graphiques d'évolution quotidienne des vues et du rythme de publication
  Design sobre avec typographie noire et badges neutres alignés avec /redaction/articles
--}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    {{-- Graphique 1 : Courbe d'évolution des vues uniques (2 colonnes) --}}
    <div class="lg:col-span-2 bg-white rounded border border-gray-200 p-5 shadow-xs space-y-4">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-base font-heading font-black text-gray-900">Daily Unique Views Trend</h2>
                <p class="text-xs text-gray-500">Readership dynamics over the selected timeframe</p>
            </div>
            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded text-xs font-bold bg-gray-100 text-gray-800 border border-gray-200">
                <span class="w-2 h-2 rounded-full bg-cargo-dark"></span>
                <span>Views</span>
            </span>
        </div>

        <div class="h-64 w-full relative">
            <canvas id="graphiqueVues" class="w-full h-full"></canvas>
        </div>
    </div>

    {{-- Graphique 2 : Fréquence de publication (1 colonne) --}}
    <div class="bg-white rounded border border-gray-200 p-5 shadow-xs space-y-4">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-base font-heading font-black text-gray-900">Publication Rhythm</h2>
                <p class="text-xs text-gray-500">Daily published articles count</p>
            </div>
            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded text-xs font-bold bg-gray-100 text-gray-800 border border-gray-200">
                <span class="w-2 h-2 rounded-full bg-cargo-accent"></span>
                <span>Articles</span>
            </span>
        </div>

        <div class="h-64 w-full relative">
            <canvas id="graphiquePublications" class="w-full h-full"></canvas>
        </div>
    </div>
</div>
