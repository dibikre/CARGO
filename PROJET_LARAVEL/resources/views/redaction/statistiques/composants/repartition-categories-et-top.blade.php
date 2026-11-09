{{-- 
  Composant Blade : Répartition des publications par catégorie et top 10 des articles les plus consultés
  Design épuré reprenant fidèlement les styles de la table /redaction/articles
--}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    {{-- Répartition par section --}}
    <div class="bg-white rounded border border-gray-200 p-5 shadow-xs space-y-4">
        <div>
            <h2 class="text-base font-heading font-black text-gray-900">Publications by Section</h2>
            <p class="text-xs text-gray-500">Distribution of articles across logistics verticals</p>
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
                        <span class="text-gray-500 font-mono">{{ $catStat->articles_publies }} published ({{ $catStat->total_articles }} total)</span>
                    </div>
                    <div class="w-full h-2 bg-gray-100 rounded-full overflow-hidden">
                        <div class="h-full bg-cargo-accent rounded-full" style="width: {{ $pourcentage }}%;"></div>
                    </div>
                </div>
            @empty
                <p class="text-xs text-gray-400 italic py-4">No published articles yet to calculate section ratios.</p>
            @endforelse
        </div>
    </div>

    {{-- Top 10 des articles les plus consultés avec en-tête sombre identique à /redaction/articles --}}
    <div class="lg:col-span-2 bg-white rounded border border-gray-200 shadow-xs space-y-0 overflow-hidden">
        <div class="p-5 pb-3 flex items-center justify-between border-b border-gray-100">
            <div>
                <h2 class="text-base font-heading font-black text-gray-900">Top 10 Most-Read Articles</h2>
                <p class="text-xs text-gray-500">Ranked by real IP-verified reading counter</p>
            </div>
            <a href="{{ route('redaction.articles.index', ['tri' => 'vues', 'direction' => 'desc']) }}" class="text-xs text-gray-900 hover:text-cargo-accent underline font-bold uppercase tracking-wider">
                Full list &rarr;
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="bg-cargo-dark text-white font-bold uppercase tracking-wider text-[11px]">
                        <th class="py-3 px-3">#</th>
                        <th class="py-3 px-3">Article Title</th>
                        <th class="py-3 px-3">Section</th>
                        <th class="py-3 px-3">Author</th>
                        <th class="py-3 px-3 text-right">Views</th>
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
                            <td class="py-2.5 px-3 text-gray-500">{{ $topArt->categorie->nom ?? 'General' }}</td>
                            <td class="py-2.5 px-3 text-gray-900 font-semibold">{{ $topArt->auteur->name ?? 'Staff' }}</td>
                            <td class="py-2.5 px-3 text-right font-mono font-bold text-gray-900">
                                {{ number_format($topArt->vues_compteur) }}
                            </td>
                            <td class="py-2.5 px-3 text-right space-x-2">
                                <a href="{{ route('redaction.articles.edit', $topArt->id) }}" class="text-gray-900 font-bold hover:underline">
                                    Edit HTML
                                </a>
                                <a href="{{ route('articles.show', $topArt->slug) }}" target="_blank" class="text-gray-600 hover:text-gray-900 font-semibold hover:underline">
                                    View
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-6 text-gray-400 italic">No published articles with view records yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
