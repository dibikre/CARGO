@extends('administration.layouts.admin')

@section('titre', 'Gestion des Articles & Dépêches')

@section('contenu')
    <div class="space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 border-b border-gray-200">
            <div>
                <h1 class="text-2xl font-heading font-black text-gray-900 tracking-tight">Gestion des Articles & Alertes en Temps Réel</h1>
                <p class="text-xs text-gray-500 uppercase tracking-wider">Supervision des publications, alertes urgentes de fret et accès abonnés</p>
            </div>
            <a href="{{ route('admin.articles.create') }}" class="bg-cargo-red hover:bg-cargo-redDark text-white font-heading font-bold text-xs uppercase tracking-wider px-4 py-2.5 rounded shadow transition">
                + Nouvel Article
            </a>
        </div>

        {{-- Barre de filtrage --}}
        <form method="GET" action="{{ route('admin.articles.index') }}" class="bg-white p-4 rounded-lg border border-gray-200 shadow-sm flex flex-wrap gap-4 items-center justify-between">
            <div class="flex-1 min-w-[240px] flex gap-2">
                <input type="text" name="recherche" value="{{ $recherche }}" placeholder="Rechercher par titre d'article..."
                       class="w-full bg-gray-50 border border-gray-300 rounded px-3 py-2 text-xs focus:ring-1 focus:ring-cargo-red focus:outline-none">
                <select name="statut" class="bg-gray-50 border border-gray-300 rounded px-3 py-2 text-xs focus:ring-1 focus:ring-cargo-red focus:outline-none">
                    <option value="">Tous les statuts</option>
                    <option value="publie" {{ $statutFiltre === 'publie' ? 'selected' : '' }}>Publié</option>
                    <option value="brouillon" {{ $statutFiltre === 'brouillon' ? 'selected' : '' }}>Brouillon</option>
                    <option value="archive" {{ $statutFiltre === 'archive' ? 'selected' : '' }}>Archivé</option>
                </select>
                <select name="categorie_id" class="bg-gray-50 border border-gray-300 rounded px-3 py-2 text-xs focus:ring-1 focus:ring-cargo-red focus:outline-none">
                    <option value="">Toutes les rubriques</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat->id }}" {{ $categorieFiltre == $cat->id ? 'selected' : '' }}>{{ $cat->nom }}</option>
                    @endforeach
                </select>
                <select name="tri" class="bg-gray-50 border border-gray-300 rounded px-3 py-2 text-xs focus:ring-1 focus:ring-cargo-red focus:outline-none font-medium">
                    <option value="recent" {{ ($tri ?? 'recent') === 'recent' ? 'selected' : '' }}>Tri : Plus récents</option>
                    <option value="plus_vus" {{ ($tri ?? '') === 'plus_vus' ? 'selected' : '' }}>Tri : Plus consultés</option>
                    <option value="moins_vus" {{ ($tri ?? '') === 'moins_vus' ? 'selected' : '' }}>Tri : Moins consultés</option>
                    <option value="ancien" {{ ($tri ?? '') === 'ancien' ? 'selected' : '' }}>Tri : Plus anciens</option>
                </select>
                <button type="submit" class="bg-cargo-red hover:bg-cargo-redDark text-white px-4 py-2 rounded text-xs font-heading font-bold uppercase tracking-wider">
                    Filtrer
                </button>
            </div>
        </form>

        {{-- Tableau des articles --}}
        <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-200 text-gray-600 uppercase tracking-wider font-semibold">
                            <th class="p-3.5">Titre & Auteur</th>
                            <th class="p-3.5">Rubrique</th>
                            <th class="p-3.5 text-center">Lectures certifiées</th>
                            <th class="p-3.5 text-center">Alerte Urgente</th>
                            <th class="p-3.5 text-center">Accès PRO</th>
                            <th class="p-3.5 text-center">À la une</th>
                            <th class="p-3.5">Statut</th>
                            <th class="p-3.5 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-gray-700">
                        @forelse ($articles as $article)
                            <tr class="hover:bg-gray-50">
                                <td class="p-3.5 max-w-xs">
                                    <a href="{{ route('articles.show', $article->slug) }}" target="_blank" class="font-heading font-bold text-gray-900 hover:text-cargo-red line-clamp-1">
                                        {{ $article->titre }}
                                    </a>
                                    <div class="text-[11px] text-gray-500">Par {{ $article->auteur->name ?? 'La Rédaction' }} &bull; {{ $article->date_publication?->translatedFormat('d M Y') ?? $article->created_at->translatedFormat('d M Y') }}</div>
                                </td>
                                <td class="p-3.5">
                                    <span class="bg-gray-100 text-gray-800 px-2 py-0.5 rounded text-[10px] font-semibold">
                                        {{ $article->categorie->nom ?? 'Général' }}
                                    </span>
                                </td>
                                <td class="p-3.5 text-center">
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded bg-slate-100 text-slate-800 font-mono font-bold text-xs" title="{{ number_format($article->vues_compteur, 0, ',', ' ') }} consultations uniques">
                                        <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        {{ number_format($article->vues_compteur, 0, ',', ' ') }}
                                    </span>
                                </td>
                                <td class="p-3.5 text-center">
                                    <form action="{{ route('admin.articles.alerte', $article->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="px-2 py-1 rounded text-[10px] font-bold uppercase tracking-wider transition {{ $article->est_urgent ? 'bg-red-600 text-white hover:bg-red-700 shadow-sm' : 'bg-gray-100 text-gray-500 hover:bg-gray-200' }}">
                                            {{ $article->est_urgent ? 'EN DIRECT' : 'Inactif' }}
                                        </button>
                                    </form>
                                </td>
                                <td class="p-3.5 text-center">
                                    <form action="{{ route('admin.articles.pro', $article->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="px-2 py-1 rounded text-[10px] font-bold uppercase tracking-wider transition {{ $article->est_reserve_abonnes ? 'bg-amber-500 text-white hover:bg-amber-600 shadow-sm' : 'bg-gray-100 text-gray-500 hover:bg-gray-200' }}">
                                            {{ $article->est_reserve_abonnes ? 'PRO' : 'Libre' }}
                                        </button>
                                    </form>
                                </td>
                                <td class="p-3.5 text-center">
                                    <form action="{{ route('admin.articles.alaune', $article->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="px-2 py-1 rounded text-[10px] font-bold uppercase tracking-wider transition {{ $article->est_a_la_une ? 'bg-cargo-dark text-white hover:bg-black shadow-sm' : 'bg-gray-100 text-gray-500 hover:bg-gray-200' }}">
                                            {{ $article->est_a_la_une ? 'À la une' : 'Standard' }}
                                        </button>
                                    </form>
                                </td>
                                <td class="p-3.5">
                                    <span class="text-[10px] font-bold px-2 py-0.5 rounded {{ $article->statut === 'publie' ? 'bg-emerald-100 text-emerald-800' : 'bg-yellow-100 text-yellow-800' }}">
                                        {{ $article->statut === 'publie' ? 'Publié' : 'Brouillon' }}
                                    </span>
                                </td>
                                <td class="p-3.5 text-right space-x-2">
                                    <a href="{{ route('admin.articles.edit', $article->id) }}" class="text-xs font-semibold text-gray-600 hover:text-cargo-red">Modifier</a>
                                    <form action="{{ route('admin.articles.destroy', $article->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Confirmer la suppression de cet article ?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-xs font-semibold text-red-600 hover:text-red-800">Supprimer</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="p-6 text-center text-gray-500">Aucun article ne correspond à votre recherche.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($articles->hasPages())
                <div class="p-4 border-t border-gray-200 bg-gray-50">
                    {{ $articles->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
