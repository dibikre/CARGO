{{-- 
  Composant Blade : Deux diagrammes circulaires pour la semaine courante (depuis lundi)
  Design sobre, typographie noire et métadonnées grises alignées avec /redaction/articles
--}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    {{-- Diagramme 1 : Volume de publication de la semaine depuis lundi --}}
    <div class="bg-white rounded border border-gray-200 p-5 shadow-xs space-y-4">
        <div class="flex items-start justify-between gap-3 pb-3 border-b border-gray-100">
            <div>
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-cargo-accent"></span>
                    <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">Production hebdomadaire</span>
                </div>
                <h2 class="text-base font-heading font-black text-gray-900 mt-0.5">Volume de publications par jour</h2>
                <p class="text-xs text-gray-500">Articles publiés depuis lundi ({{ $statistiquesSemaine['date_debut']->locale('fr')->translatedFormat('d M') }} – {{ $statistiquesSemaine['date_fin']->locale('fr')->translatedFormat('d M') }})</p>
            </div>
            <div class="text-right shrink-0">
                <span class="text-xs text-gray-500 font-semibold block uppercase">Total publiés</span>
                <span class="text-2xl font-heading font-black text-gray-900">{{ $statistiquesSemaine['total_publications'] }}</span>
            </div>
        </div>

        {{-- Graphique Donut & Légende sobre --}}
        <div class="flex flex-col sm:flex-row items-center gap-6 pt-2">
            <div class="relative w-44 h-44 shrink-0 flex items-center justify-center">
                <canvas id="graphiqueCirculairePublications" class="w-full h-full"></canvas>
                <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none text-center">
                    <span class="text-xl font-heading font-black text-gray-900 leading-none">{{ $statistiquesSemaine['total_publications'] }}</span>
                    <span class="text-[10px] font-bold uppercase tracking-wider text-gray-400 mt-0.5">Articles</span>
                </div>
            </div>

            {{-- Liste détaillée des jours avec volume et pourcentage --}}
            <div class="flex-1 w-full space-y-1">
                @php
                    $totalPubs = max(1, $statistiquesSemaine['total_publications']);
                @endphp
                @foreach($statistiquesSemaine['details_jours'] as $jour)
                    @php
                        $pourcentage = round(($jour['publications'] / $totalPubs) * 100, 1);
                    @endphp
                    <div class="flex items-center justify-between text-xs py-1.5 px-2 rounded {{ $jour['est_aujourdhui'] ? 'bg-gray-100 font-bold' : 'hover:bg-gray-50' }}">
                        <div class="flex items-center gap-2 min-w-0">
                            <span class="w-2.5 h-2.5 rounded-full shrink-0" style="background-color: {{ $jour['couleur'] }};"></span>
                            <span class="text-gray-900 truncate">
                                {{ $jour['nom_francais'] ?? $jour['nom_anglais'] }}
                                @if($jour['est_aujourdhui'])
                                    <span class="ml-1 text-[9px] font-bold text-white bg-cargo-dark px-1.5 py-0.5 rounded uppercase">Aujourd'hui</span>
                                @endif
                            </span>
                        </div>
                        <div class="flex items-center gap-3 shrink-0">
                            <span class="font-mono font-bold text-gray-900">{{ $jour['publications'] }} <span class="text-gray-400 font-normal">art.</span></span>
                            <span class="font-mono text-xs text-gray-500 w-10 text-right">{{ $pourcentage }}%</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Diagramme 2 : Nombre de vues de la semaine depuis lundi --}}
    <div class="bg-white rounded border border-gray-200 p-5 shadow-xs space-y-4">
        <div class="flex items-start justify-between gap-3 pb-3 border-b border-gray-100">
            <div>
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-gray-900"></span>
                    <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">Audience hebdomadaire</span>
                </div>
                <h2 class="text-base font-heading font-black text-gray-900 mt-0.5">Répartition des vues par jour</h2>
                <p class="text-xs text-gray-500">Part d'audience enregistrée depuis lundi sur tous les articles actifs</p>
            </div>
            <div class="text-right shrink-0">
                <span class="text-xs text-gray-500 font-semibold block uppercase">Total des vues</span>
                <span class="text-2xl font-heading font-black text-gray-900">{{ number_format($statistiquesSemaine['total_vues']) }}</span>
            </div>
        </div>

        {{-- Graphique Donut & Légende sobre --}}
        <div class="flex flex-col sm:flex-row items-center gap-6 pt-2">
            <div class="relative w-44 h-44 shrink-0 flex items-center justify-center">
                <canvas id="graphiqueCirculaireVues" class="w-full h-full"></canvas>
                <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none text-center">
                    <span class="text-xl font-heading font-black text-gray-900 leading-none">{{ number_format($statistiquesSemaine['total_vues']) }}</span>
                    <span class="text-[10px] font-bold uppercase tracking-wider text-gray-400 mt-0.5">Vues</span>
                </div>
            </div>

            {{-- Liste détaillée des jours avec décompte de vues et pourcentage --}}
            <div class="flex-1 w-full space-y-1">
                @php
                    $totalVuesSem = max(1, $statistiquesSemaine['total_vues']);
                @endphp
                @foreach($statistiquesSemaine['details_jours'] as $jour)
                    @php
                        $pourcentageVues = round(($jour['vues'] / $totalVuesSem) * 100, 1);
                    @endphp
                    <div class="flex items-center justify-between text-xs py-1.5 px-2 rounded {{ $jour['est_aujourdhui'] ? 'bg-gray-100 font-bold' : 'hover:bg-gray-50' }}">
                        <div class="flex items-center gap-2 min-w-0">
                            <span class="w-2.5 h-2.5 rounded-full shrink-0" style="background-color: {{ $jour['couleur'] }};"></span>
                            <span class="text-gray-900 truncate">
                                {{ $jour['nom_francais'] ?? $jour['nom_anglais'] }}
                                @if($jour['est_aujourdhui'])
                                    <span class="ml-1 text-[9px] font-bold text-white bg-cargo-dark px-1.5 py-0.5 rounded uppercase">Aujourd'hui</span>
                                @endif
                            </span>
                        </div>
                        <div class="flex items-center gap-3 shrink-0">
                            <span class="font-mono font-bold text-gray-900">{{ number_format($jour['vues']) }} <span class="text-gray-400 font-normal">vues</span></span>
                            <span class="font-mono text-xs text-gray-500 w-10 text-right">{{ $pourcentageVues }}%</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
