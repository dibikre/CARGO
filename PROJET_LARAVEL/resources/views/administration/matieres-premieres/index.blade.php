@extends('administration.layouts.admin')

@section('titre', 'Bandeau des Matières Premières — Administration')

@section('contenu')
<div class="space-y-6">
    {{-- En-tête avec compteur de quota --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-6 rounded-lg shadow-sm border border-gray-200">
        <div>
            <div class="flex items-center gap-3">
                <h1 class="text-2xl font-heading font-black text-cargo-dark">Bandeau Défilant des Matières Premières</h1>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $quotaAtteint ? 'bg-amber-100 text-amber-800' : 'bg-blue-100 text-blue-800' }}">
                    {{ $totalProduits }} / {{ $maxProduits }} Produits Max
                </span>
            </div>
            <p class="text-sm text-gray-500 mt-1">
                Gestion des prix des commodités brutes, icônes PNG détourées et courbes de tendance sparkline (JSON 5 à 10 nœuds).
            </p>
        </div>

        <div>
            @if ($quotaAtteint)
                <button disabled class="inline-flex items-center gap-2 bg-gray-300 text-gray-600 px-4 py-2 rounded text-sm font-semibold cursor-not-allowed">
                    Limite atteinte (20 max)
                </button>
            @else
                <a href="{{ route('admin.matieres-premieres.create') }}" class="inline-flex items-center gap-2 bg-cargo-red hover:bg-red-700 text-white px-4 py-2 rounded text-sm font-semibold transition shadow">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Ajouter une matière
                </a>
            @endif
        </div>
    </div>

    {{-- Aperçu du bandeau --}}
    <div class="bg-white p-4 rounded-lg border border-gray-200 shadow-sm">
        <div class="text-xs uppercase tracking-wider text-gray-500 font-semibold mb-2 flex items-center justify-between">
            <span>Aperçu Visuel (Bandeau non-cliquable en direct)</span>
            <span class="text-[11px] text-gray-400">Fond blanc 100% neutre</span>
        </div>
        <div class="flex items-center gap-6 overflow-x-auto py-2 bg-white">
            @forelse ($matieres as $m)
                <div class="flex-shrink-0 flex items-center gap-3 bg-white border border-gray-200 rounded px-3 py-1.5 shadow-sm select-none">
                    <img src="{{ asset($m->image_png) }}" alt="{{ $m->nom }}" class="w-7 h-7 object-contain" />
                    <div>
                        <div class="text-[11px] font-bold text-gray-700">{{ $m->nom }}</div>
                        <div class="text-xs font-mono font-bold text-gray-900">{{ $m->prix_formate }} <span class="text-[9px] text-gray-500">{{ $m->devise }}</span></div>
                    </div>
                    <svg class="w-16 h-6 overflow-visible" viewBox="0 0 80 24">
                        <path d="{{ $m->genererTraceSvg(80, 24) }}" fill="none" stroke="{{ $m->couleur_tendance }}" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <span class="text-xs font-mono font-bold px-1.5 py-0.5 rounded border {{ $m->pourcentage_variation >= 0 ? 'text-emerald-700 bg-emerald-50 border-emerald-200' : 'text-rose-700 bg-rose-50 border-rose-200' }}">
                        {{ $m->pourcentage_variation >= 0 ? '+' : '' }}{{ $m->pourcentage_variation }}%
                    </span>
                </div>
            @empty
                <div class="text-gray-500 text-xs py-2">Aucune matière première configurée pour le moment.</div>
            @endforelse
        </div>
    </div>

    {{-- Tableau des matières premières --}}
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50 text-gray-600 uppercase text-xs font-semibold">
                    <tr>
                        <th class="px-4 py-3 text-left">Ordre</th>
                        <th class="px-4 py-3 text-left">Icône PNG</th>
                        <th class="px-4 py-3 text-left">Matière / Symbole</th>
                        <th class="px-4 py-3 text-right">Dernier Prix</th>
                        <th class="px-4 py-3 text-center">Écart Entrée/Sortie</th>
                        <th class="px-4 py-3 text-center">Courbe (5-10 nœuds)</th>
                        <th class="px-4 py-3 text-center">Statut</th>
                        <th class="px-4 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 text-gray-800">
                    @forelse ($matieres as $matiere)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-4 py-3 font-mono font-bold text-gray-500">
                                #{{ $matiere->ordre_affichage }}
                            </td>
                            <td class="px-4 py-3">
                                <div class="w-10 h-10 rounded bg-gray-100 border border-gray-200 flex items-center justify-center p-1">
                                    <img src="{{ asset($matiere->image_png) }}" alt="{{ $matiere->nom }}" class="w-8 h-8 object-contain" />
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <div class="font-bold text-gray-900">{{ $matiere->nom }}</div>
                                <div class="text-xs text-gray-500 font-mono">{{ $matiere->code_symbole }} &bull; {{ $matiere->unite_mesure }}</div>
                            </td>
                            <td class="px-4 py-3 text-right font-mono font-bold text-gray-900">
                                {{ $matiere->prix_formate }} <span class="text-xs text-gray-500">{{ $matiere->devise }}</span>
                            </td>
                            <td class="px-4 py-3 text-center font-mono">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-bold {{ $matiere->pourcentage_variation >= 0 ? 'text-emerald-700 bg-emerald-50' : 'text-rose-700 bg-rose-50' }}">
                                    {{ $matiere->pourcentage_variation >= 0 ? '+' : '' }}{{ $matiere->pourcentage_variation }}%
                                </span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <div class="inline-flex items-center gap-2">
                                    <svg class="w-20 h-7 bg-gray-900/5 rounded p-0.5" viewBox="0 0 80 24">
                                        <path d="{{ $matiere->genererTraceSvg(80, 24) }}" fill="none" stroke="{{ $matiere->couleur_tendance }}" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                    <span class="text-[10px] text-gray-500 font-mono">({{ count($matiere->obtenirPointsCourbe()) }} pts)</span>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <span class="inline-flex px-2 py-0.5 rounded text-xs font-semibold {{ $matiere->est_actif ? 'bg-emerald-100 text-emerald-800' : 'bg-gray-100 text-gray-600' }}">
                                    {{ $matiere->est_actif ? 'Actif' : 'Désactivé' }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.matieres-premieres.edit', $matiere->id) }}" class="text-xs font-semibold text-blue-600 hover:text-blue-800 bg-blue-50 px-2.5 py-1 rounded border border-blue-200">
                                        Modifier
                                    </a>

                                    <form action="{{ route('admin.matieres-premieres.destroy', $matiere->id) }}" method="POST" onsubmit="return confirm('Supprimer ce produit du bandeau de cours ?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-xs font-semibold text-rose-600 hover:text-rose-800 bg-rose-50 px-2.5 py-1 rounded border border-rose-200">
                                            Supprimer
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-4 py-8 text-center text-gray-500">
                                Aucune matière première enregistrée. Cliquez sur « Ajouter une matière » ci-dessus pour commencer.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
