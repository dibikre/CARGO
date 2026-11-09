{{-- 
  Composant Blade : Statistique des articles les plus vus des différents jours de cette semaine (du lundi au dimanche)
  Design éditorial sobre inspiré de /redaction/articles (textes noirs, métadonnées grises, hiérarchie nette)
--}}
<div class="bg-white rounded border border-gray-200 p-5 shadow-xs space-y-4">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 pb-3 border-b border-gray-100">
        <div>
            <div class="flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-cargo-accent"></span>
                <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">Day-by-Day Editorial Leaderboard</span>
            </div>
            <h2 class="text-base font-heading font-black text-gray-900 mt-0.5">Most-Viewed Articles by Day (This Week)</h2>
            <p class="text-xs text-gray-500">Highest-performing article identified for each individual day from Monday ({{ $statistiquesSemaine['date_debut']->format('d M') }}) to Sunday ({{ $statistiquesSemaine['date_fin']->format('d M') }})</p>
        </div>
        <span class="px-2.5 py-1 text-xs font-bold text-gray-800 bg-gray-100 border border-gray-300 rounded uppercase tracking-wider self-start sm:self-auto">
            Weekly Leaderboard
        </span>
    </div>

    {{-- Grille des 7 jours de la semaine avec l'article leader de chaque jour --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-7 gap-3 pt-2">
        @foreach($statistiquesSemaine['details_jours'] as $jour)
            @php
                $artTop = $jour['article_top'];
            @endphp
            <div class="flex flex-col justify-between rounded border {{ $jour['est_aujourdhui'] ? 'border-2 border-cargo-dark bg-white shadow-xs' : ($jour['est_futur'] ? 'border-gray-200 bg-gray-50/50 opacity-60' : 'border-gray-200 bg-white hover:border-gray-300') }} p-3 transition">
                {{-- En-tête du jour --}}
                <div class="pb-2 border-b border-gray-100 mb-2">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-1.5">
                            <span class="w-2 h-2 rounded-full" style="background-color: {{ $jour['couleur'] }};"></span>
                            <span class="font-heading font-black text-xs text-gray-900">{{ $jour['nom_anglais'] }}</span>
                        </div>
                        @if($jour['est_aujourdhui'])
                            <span class="text-[9px] font-bold uppercase tracking-wider bg-cargo-dark text-white px-1.5 py-0.5 rounded">Today</span>
                        @elseif($jour['est_futur'])
                            <span class="text-[9px] font-semibold text-gray-400 uppercase">Upcoming</span>
                        @else
                            <span class="text-[10px] font-mono text-gray-500 font-bold">{{ \Carbon\Carbon::parse($jour['date'])->format('d M') }}</span>
                        @endif
                    </div>
                    <div class="text-xs text-gray-500 mt-1 flex items-center justify-between">
                        <span>Day Traffic:</span>
                        <span class="font-mono font-bold text-gray-900">{{ number_format($jour['vues']) }} views</span>
                    </div>
                </div>

                {{-- Corps : L'article le plus vu ce jour-là --}}
                <div class="flex-1 flex flex-col justify-between space-y-2">
                    @if($artTop)
                        <div>
                            {{-- Badge Catégorie sobre --}}
                            <span class="inline-block text-[10px] font-bold uppercase tracking-wider px-1.5 py-0.5 rounded bg-gray-100 text-gray-800 mb-1 border border-gray-200">
                                {{ $artTop['categorie'] }}
                            </span>

                            {{-- Titre de l'article --}}
                            <h3 class="text-xs font-bold text-gray-900 line-clamp-3 hover:text-cargo-accent leading-snug">
                                <a href="{{ route('redaction.articles.edit', $artTop['id']) }}" title="{{ $artTop['titre'] }}">
                                    {{ $artTop['titre'] }}
                                </a>
                            </h3>

                            <div class="text-[11px] text-gray-500 mt-1 truncate">
                                By {{ $artTop['auteur'] }}
                            </div>
                        </div>

                        {{-- Statistiques et Liens d'action --}}
                        <div class="pt-2 border-t border-gray-100 mt-2 space-y-1.5">
                            <div class="flex items-center justify-between text-xs">
                                <span class="text-gray-500">Day Views:</span>
                                <span class="font-mono font-bold text-gray-900">
                                    {{ number_format($artTop['vues_jour']) }}
                                </span>
                            </div>
                            <div class="flex items-center justify-between text-[11px]">
                                <span class="text-gray-500">Total Views:</span>
                                <span class="font-mono text-gray-700 font-semibold">
                                    {{ number_format($artTop['vues_totales']) }}
                                </span>
                            </div>

                            <div class="flex items-center justify-between pt-1">
                                <a href="{{ route('redaction.articles.edit', $artTop['id']) }}" class="text-xs font-bold text-gray-900 hover:underline">
                                    Edit HTML
                                </a>
                                @if(!empty($artTop['slug']))
                                    <a href="{{ route('articles.show', $artTop['slug']) }}" target="_blank" class="text-xs font-semibold text-gray-600 hover:text-gray-900 hover:underline">
                                        View &rarr;
                                    </a>
                                @endif
                            </div>
                        </div>
                    @else
                        {{-- Cas où aucun article n'a été consulté ou jour à venir --}}
                        <div class="py-6 text-center flex flex-col items-center justify-center text-gray-400 space-y-1">
                            <svg class="w-6 h-6 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <span class="text-[11px] text-gray-400 italic">
                                {{ $jour['est_futur'] ? 'Day in progress' : 'No reads logged' }}
                            </span>
                        </div>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</div>
