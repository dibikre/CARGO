{{-- 
  Composant Blade : Répartition des publications par catégorie et top 10 des articles les plus consultés
  Design épuré reprenant fidèlement les styles de la table /redaction/articles
--}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    {{-- Répartition par section --}}
    <div class="bg-white rounded border border-gray-200 p-5 shadow-xs space-y-4">
        <div>
            <h2 class="text-base font-heading font-black text-gray-900">Publications par rubrique</h2>
            <p class="text-xs text-gray-500">Répartition des articles selon les verticales logistiques</p>
        </div>

        <div class="space-y-3 pt-2">
            @php
                $maxArticlesSection = max(1, $categoriesStats->max('articles_publies') ?? 1);
            @endphp
            @forelse($categoriesStats as $catStat)
                @php
                    $pourcentage = round(($catStat->articles_publies / $maxArticlesSection) * 100);
                @endphp
                <div>
                    <div class="flex items-center justify-between text-xs font-semibold mb-1">
                        <span class="text-gray-900">{{ $catStat->nom }}</span>
                        <span class="text-gray-500 font-mono">{{ $catStat->articles_publies }} publiés ({{ $catStat->total_articles }} au total)</span>
                    </div>
                    <div class="w-full h-2 bg-gray-100 rounded-full overflow-hidden">
                        <div class="h-full bg-cargo-accent rounded-full" style="width: {{ $pourcentage }}%;"></div>
                    </div>
                </div>
            @empty
                <p class="text-xs text-gray-400 italic py-4">Aucun article publié pour calculer la répartition par rubrique.</p>
            @endforelse
        </div>
    </div>

    {{-- Top 10 des articles les plus consultés avec en-tête sombre identique à /redaction/articles --}}
    <div class="lg:col-span-2 bg-white rounded border border-gray-200 shadow-xs space-y-0 overflow-hidden">
        <div class="p-5 pb-3 flex items-center justify-between border-b border-gray-100">
            <div>
                <h2 class="text-base font-heading font-black text-gray-900">Top 10 des articles les plus lus</h2>
                <p class="text-xs text-gray-500">Classés selon le compteur réel de lectures vérifiées par IP</p>
            </div>
            <a href="{{ route('redaction.articles.index', ['tri' => 'vues', 'direction' => 'desc']) }}" class="text-xs text-gray-900 hover:text-cargo-accent underline font-bold uppercase tracking-wider">
                Liste complète &rarr;
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="bg-cargo-dark text-white font-bold uppercase tracking-wider text-[11px]">
                        <th class="py-3 px-3">#</th>
                        <th class="py-3 px-3">Titre de l'article</th>
                        <th class="py-3 px-3">Rubrique</th>
                        <th class="py-3 px-3">Auteur</th>
                        <th class="py-3 px-3 text-right">Vues</th>
                        <th class="py-3 px-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($topArticles as $index => $topArt)
                        <tr class="hover:bg-gray-50">
                            <td class="py-2.5 px-3 font-mono font-bold text-gray-400">{{ $index + 1 }}</td>
                            <td class="py-2.5 px-3 font-semibold text-gray-900 max-w-xs truncate">
                                <a href="{{ route('redaction.articles.edit', $topArt->id) }}" class="text-gray-900 hover:text-cargo-accent font-bold">
                                    {{ $topArt->titre }}
                                </a>
                            </td>
                            <td class="py-2.5 px-3 text-gray-500">{{ $topArt->categorie->nom ?? 'Général' }}</td>
                            <td class="py-2.5 px-3 text-gray-900 font-semibold">{{ $topArt->auteur->name ?? 'Rédaction' }}</td>
                            <td class="py-2.5 px-3 text-right font-mono font-bold text-gray-900">
                                {{ number_format($topArt->vues_compteur) }}
                            </td>
                            <td class="py-2.5 px-3 text-right space-x-2">
                                <a href="{{ route('redaction.articles.edit', $topArt->id) }}" class="text-gray-900 font-bold hover:underline">
                                    Modifier HTML
                                </a>
                                <a href="{{ route('articles.show', $topArt->slug) }}" target="_blank" class="text-gray-600 hover:text-gray-900 font-semibold hover:underline">
                                    Voir
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-6 text-gray-400 italic">Aucun article publié avec des vues enregistrées pour le moment.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
