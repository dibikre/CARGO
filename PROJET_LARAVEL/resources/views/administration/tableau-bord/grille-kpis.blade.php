{{-- Grille d'indicateurs clés (KPIs) avec états vides expressifs & actions (Point 3) --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
    {{-- Articles publiés --}}
    <div class="bg-white p-5 rounded-lg border border-gray-200 shadow-sm flex flex-col justify-between">
        <div>
            <div class="text-xs font-semibold uppercase tracking-wider text-gray-500 mb-1">Publications & Articles</div>
            <div class="text-3xl font-heading font-black text-gray-900">{{ $totalArticles }}</div>
        </div>
        <div class="text-xs text-gray-500 mt-3 pt-3 border-t border-gray-100 flex items-center justify-between">
            <span><strong class="text-emerald-700">{{ $articlesPublies }}</strong> publiés &bull; <strong class="text-amber-700">{{ $articlesBrouillons }}</strong> brouillons</span>
            <span class="text-gray-400 font-mono">{{ $articlesCeMois }} ce mois</span>
        </div>
    </div>

    {{-- Abonnés PRO (avec état vide expressif) --}}
    <div class="bg-white p-5 rounded-lg border border-gray-200 shadow-sm flex flex-col justify-between">
        <div>
            <div class="text-xs font-semibold uppercase tracking-wider text-gray-500 mb-1">Abonnés CARGO PRO</div>
            @if ($totalAbonnesActifs > 0)
                <div class="text-3xl font-heading font-black text-gray-900">{{ $totalAbonnesActifs }}</div>
                <p class="text-xs text-gray-500 mt-1">Actifs sur {{ $totalUtilisateurs }} comptes inscrits</p>
            @else
                <div class="text-sm font-bold text-gray-700 mt-1">Aucun abonné actif</div>
                <p class="text-xs text-gray-500 mt-1">{{ $totalFormules > 0 ? $totalFormules . ' formule(s) configurée(s)' : 'Aucune formule créée' }}</p>
            @endif
        </div>
        <div class="mt-3 pt-3 border-t border-gray-100">
            <a href="{{ route('admin.abonnements.index') }}" class="text-xs text-cargo-red font-semibold hover:underline flex items-center justify-between">
                <span>{{ $totalAbonnesActifs > 0 ? 'Gérer les abonnés' : 'Configurer les formules PRO' }}</span>
                <span>&rarr;</span>
            </a>
        </div>
    </div>

    {{-- Audience & Lectures réelles --}}
    <div class="bg-white p-5 rounded-lg border border-gray-200 shadow-sm flex flex-col justify-between">
        <div>
            <div class="text-xs font-semibold uppercase tracking-wider text-gray-500 mb-1">Audience & Lectures (30j)</div>
            <div class="text-3xl font-heading font-black text-gray-900">{{ number_format($consultationsMois, 0, ',', ' ') }}</div>
            <p class="text-xs text-gray-500 mt-1">Cumul certifié des consultations uniques</p>
        </div>
        <div class="text-xs text-gray-500 mt-3 pt-3 border-t border-gray-100 flex items-center justify-between">
            <span>Total général : <strong>{{ number_format($totalLecturesCumulees, 0, ',', ' ') }}</strong></span>
            <span class="text-emerald-700 font-semibold">Suivi actif</span>
        </div>
    </div>

    {{-- Modération des commentaires (avec état vide valorisant) --}}
    <div class="bg-white p-5 rounded-lg border border-gray-200 shadow-sm flex flex-col justify-between">
        <div>
            <div class="text-xs font-semibold uppercase tracking-wider text-gray-500 mb-1">Modération des Retours</div>
            @if ($commentairesEnAttente > 0)
                <div class="text-3xl font-heading font-black text-amber-600">{{ $commentairesEnAttente }}</div>
                <p class="text-xs text-amber-700 font-medium mt-1">Commentaires en attente d'approbation</p>
            @else
                <div class="flex items-center gap-2 mt-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                    <span class="text-sm font-bold text-emerald-800">File à jour</span>
                </div>
                <p class="text-xs text-gray-500 mt-1">Tous les avis soumis ont été traités</p>
            @endif
        </div>
        <div class="mt-3 pt-3 border-t border-gray-100">
            <a href="{{ route('admin.commentaires.index') }}" class="text-xs {{ $commentairesEnAttente > 0 ? 'text-cargo-red font-bold' : 'text-gray-600' }} hover:underline flex items-center justify-between">
                <span>{{ $commentairesEnAttente > 0 ? 'Traiter la modération' : 'Consulter les retours' }}</span>
                <span>&rarr;</span>
            </a>
        </div>
    </div>
</div>
