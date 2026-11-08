@extends('administration.layouts.admin')

@section('titre', 'Tableau de bord exécutif')

@section('contenu')
    <div class="space-y-6">
        {{-- En-tête de la console --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 border-b border-gray-200">
            <div>
                <h1 class="text-2xl font-heading font-black text-gray-900 tracking-tight">Tableau de bord exécutif</h1>
                <p class="text-xs text-gray-500 uppercase tracking-wider">Métriques éditoriales, gestion des abonnés & surveillance des flux de fret</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.articles.create') }}" class="bg-cargo-red hover:bg-cargo-redDark text-white font-heading font-bold text-xs uppercase tracking-wider px-4 py-2.5 rounded shadow transition flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Nouvel Article
                </a>
            </div>
        </div>

        {{-- Panneau Dédié Alerte Urgente Actionnable (Point 5) --}}
        @include('administration.tableau-bord.panneau-alerte-urgente')

        {{-- Grille d'indicateurs clés (KPIs) avec états vides expressifs & actions (Point 3) --}}
        @include('administration.tableau-bord.grille-kpis')

        {{-- Actions rapides & Tâches prioritaires (Remplace la rangée de doublons - Point 4) --}}
        @include('administration.tableau-bord.taches-prioritaires')

        {{-- Deux Colonnes : Surveillance des Corridors & Dernières Dépêches --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            {{-- Surveillance opérationnelle des corridors --}}
            <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-5 space-y-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-base font-heading font-bold text-gray-900">État des Corridors de Transport</h2>
                        <p class="text-xs text-gray-500">Alertes de perturbation en temps réel sur la chaîne logistique</p>
                    </div>
                    <a href="{{ route('admin.corridors.index') }}" class="text-xs text-cargo-red hover:underline font-semibold">Gérer les flux</a>
                </div>

                <div class="space-y-2.5">
                    @forelse ($corridorsPerturbes as $corridor)
                        @php
                            $statutLibelle = match($corridor->statut_operationnel) {
                                'bloque' => 'Trafic bloqué',
                                'perturbe' => 'Perturbation majeure',
                                'ralenti' => 'Trafic ralenti',
                                'congestionne' => 'Congestion portuaire',
                                default => 'Normal & Fluide'
                            };
                        @endphp
                        <div class="p-3 rounded border border-gray-100 bg-gray-50 flex items-center justify-between">
                            <div>
                                <div class="font-heading font-bold text-sm text-gray-900">{{ $corridor->nom }}</div>
                                <div class="text-xs text-gray-500">{{ ucfirst($corridor->type_flux) }} &bull; {{ $corridor->description ?? 'Aucun incident complémentaire' }}</div>
                            </div>
                            <span class="text-[11px] font-bold px-2 py-0.5 rounded
                                @if($corridor->statut_operationnel === 'bloque') bg-red-100 text-red-800
                                @elseif($corridor->statut_operationnel === 'perturbe') bg-amber-100 text-amber-800
                                @else bg-yellow-100 text-yellow-800 @endif">
                                {{ $statutLibelle }}
                            </span>
                        </div>
                    @empty
                        <div class="text-xs py-4 text-center bg-emerald-50 rounded border border-emerald-100 text-emerald-800 font-medium">
                            Tous les corridors logistiques surveillés fonctionnent normalement (Flux fluides).
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Dernières Publications Éditoriales --}}
            <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-5 space-y-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-base font-heading font-bold text-gray-900">Dernières Publications</h2>
                        <p class="text-xs text-gray-500">Articles et enquêtes récemment enregistrés</p>
                    </div>
                    <a href="{{ route('admin.articles.index') }}" class="text-xs text-cargo-red hover:underline font-semibold">Voir tout</a>
                </div>

                <div class="divide-y divide-gray-100 text-sm">
                    @forelse ($derniersArticles as $article)
                        <div class="py-2.5 flex items-center justify-between gap-3">
                            <div class="min-w-0">
                                <div class="font-heading font-bold text-xs truncate text-gray-900">{{ $article->titre }}</div>
                                <div class="text-[11px] text-gray-500">{{ $article->categorie->nom ?? 'Général' }} &bull; Par {{ $article->auteur->name ?? 'La Rédaction' }}</div>
                            </div>
                            <div class="flex items-center gap-1.5 shrink-0">
                                <span class="text-[10px] font-mono bg-gray-100 text-gray-700 px-1.5 py-0.5 rounded">
                                    {{ number_format($article->vues_compteur, 0, ',', ' ') }} lectures
                                </span>
                                @if ($article->est_reserve_abonnes)
                                    <span class="text-[10px] font-bold bg-amber-100 text-amber-800 px-1.5 py-0.5 rounded">PRO</span>
                                @endif
                                @if ($article->est_urgent)
                                    <span class="text-[10px] font-bold bg-red-100 text-cargo-red px-1.5 py-0.5 rounded">URGENT</span>
                                @endif
                                <span class="text-[10px] font-semibold text-gray-600">
                                    {{ $article->statut === 'publie' ? 'Publié' : 'Brouillon' }}
                                </span>
                            </div>
                        </div>
                    @empty
                        <div class="text-xs text-gray-500 py-4 text-center">Aucun article enregistré pour le moment.</div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Journal d'Audit & Sécurité avec codes traduits (Point 6) --}}
        @include('administration.tableau-bord.tableau-audit')
    </div>
@endsection
