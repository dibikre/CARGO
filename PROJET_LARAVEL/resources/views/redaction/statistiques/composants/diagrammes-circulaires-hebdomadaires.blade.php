{{-- 
  Composant Blade : Deux diagrammes circulaires pour la semaine courante (depuis lundi)
  1. Volume de publication par jour de la semaine
  2. Nombre de vues par jour de la semaine
--}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    {{-- Diagramme 1 : Volume de publication de la semaine depuis lundi --}}
    <div class="bg-white rounded border border-gray-200 p-5 shadow-sm space-y-4">
        <div class="flex items-start justify-between gap-3 pb-3 border-b border-gray-100">
            <div>
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-cargo-accent"></span>
                    <span class="text-[11px] font-bold text-gray-400 uppercase tracking-wider">Weekly Output</span>
                </div>
                <h2 class="text-base font-heading font-black text-cargo-dark mt-0.5">Publication Volume by Day</h2>
                <p class="text-xs text-gray-500">Distribution of articles published since Monday ({{ $statistiquesSemaine['date_debut']->format('d M') }} – {{ $statistiquesSemaine['date_fin']->format('d M') }})</p>
            </div>
            <div class="text-right shrink-0">
                <span class="text-xs text-gray-400 font-semibold block uppercase">Total Published</span>
                <span class="text-2xl font-heading font-black text-cargo-accent">{{ $statistiquesSemaine['total_publications'] }}</span>
            </div>
        </div>

        {{-- Graphique Donut & Légende interactive --}}
        <div class="flex flex-col sm:flex-row items-center gap-6 pt-2">
            <div class="relative w-48 h-48 shrink-0 flex items-center justify-center">
                <canvas id="graphiqueCirculairePublications" class="w-full h-full"></canvas>
                <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none text-center">
                    <span class="text-xl font-heading font-black text-cargo-dark leading-none">{{ $statistiquesSemaine['total_publications'] }}</span>
                    <span class="text-[10px] font-bold uppercase tracking-wider text-gray-400 mt-0.5">Articles</span>
                </div>
            </div>

            {{-- Liste détaillée des jours avec volume et pourcentage --}}
            <div class="flex-1 w-full space-y-1.5">
                @php
                    $totalPubs = max(1, $statistiquesSemaine['total_publications']);
                @endphp
                @foreach($statistiquesSemaine['details_jours'] as $jour)
                    @php
                        $pourcentage = round(($jour['publications'] / $totalPubs) * 100, 1);
                    @endphp
                    <div class="flex items-center justify-between text-xs py-1 px-2 rounded {{ $jour['est_aujourdhui'] ? 'bg-red-50 border border-red-200' : 'hover:bg-gray-50' }}">
                        <div class="flex items-center gap-2 min-w-0">
                            <span class="w-2.5 h-2.5 rounded-full shrink-0" style="background-color: {{ $jour['couleur'] }};"></span>
                            <span class="font-bold text-gray-800 truncate">
                                {{ $jour['nom_anglais'] }}
                                @if($jour['est_aujourdhui'])
                                    <span class="ml-1 text-[10px] font-extrabold text-cargo-accent uppercase bg-white px-1.5 py-0.5 rounded border border-red-200">Today</span>
                                @endif
                            </span>
                        </div>
                        <div class="flex items-center gap-3 shrink-0">
                            <span class="font-mono font-bold text-gray-900">{{ $jour['publications'] }} <span class="text-gray-400 font-normal">arts</span></span>
                            <span class="font-mono text-[11px] text-gray-500 w-10 text-right">{{ $pourcentage }}%</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Diagramme 2 : Nombre de vues de la semaine depuis lundi --}}
    <div class="bg-white rounded border border-gray-200 p-5 shadow-sm space-y-4">
        <div class="flex items-start justify-between gap-3 pb-3 border-b border-gray-100">
            <div>
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-cargo-yellow"></span>
                    <span class="text-[11px] font-bold text-gray-400 uppercase tracking-wider">Weekly Readership</span>
                </div>
                <h2 class="text-base font-heading font-black text-cargo-dark mt-0.5">Views Distribution by Day</h2>
                <p class="text-xs text-gray-500">Readership volume captured since Monday across all active articles</p>
            </div>
            <div class="text-right shrink-0">
                <span class="text-xs text-gray-400 font-semibold block uppercase">Total Views</span>
                <span class="text-2xl font-heading font-black text-amber-600">{{ number_format($statistiquesSemaine['total_vues']) }}</span>
            </div>
        </div>

        {{-- Graphique Donut & Légende interactive --}}
        <div class="flex flex-col sm:flex-row items-center gap-6 pt-2">
            <div class="relative w-48 h-48 shrink-0 flex items-center justify-center">
                <canvas id="graphiqueCirculaireVues" class="w-full h-full"></canvas>
                <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none text-center">
                    <span class="text-xl font-heading font-black text-cargo-dark leading-none">{{ number_format($statistiquesSemaine['total_vues']) }}</span>
                    <span class="text-[10px] font-bold uppercase tracking-wider text-gray-400 mt-0.5">Views</span>
                </div>
            </div>

            {{-- Liste détaillée des jours avec décompte de vues et pourcentage --}}
            <div class="flex-1 w-full space-y-1.5">
                @php
                    $totalVuesSem = max(1, $statistiquesSemaine['total_vues']);
                @endphp
                @foreach($statistiquesSemaine['details_jours'] as $jour)
                    @php
                        $pourcentageVues = round(($jour['vues'] / $totalVuesSem) * 100, 1);
                    @endphp
                    <div class="flex items-center justify-between text-xs py-1 px-2 rounded {{ $jour['est_aujourdhui'] ? 'bg-amber-50 border border-amber-200' : 'hover:bg-gray-50' }}">
                        <div class="flex items-center gap-2 min-w-0">
                            <span class="w-2.5 h-2.5 rounded-full shrink-0" style="background-color: {{ $jour['couleur'] }};"></span>
                            <span class="font-bold text-gray-800 truncate">
                                {{ $jour['nom_anglais'] }}
                                @if($jour['est_aujourdhui'])
                                    <span class="ml-1 text-[10px] font-extrabold text-amber-700 uppercase bg-white px-1.5 py-0.5 rounded border border-amber-200">Today</span>
                                @endif
                            </span>
                        </div>
                        <div class="flex items-center gap-3 shrink-0">
                            <span class="font-mono font-bold text-gray-900">{{ number_format($jour['vues']) }} <span class="text-gray-400 font-normal">views</span></span>
                            <span class="font-mono text-[11px] text-gray-500 w-10 text-right">{{ $pourcentageVues }}%</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
