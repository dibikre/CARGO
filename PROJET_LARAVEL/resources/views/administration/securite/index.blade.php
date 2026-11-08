@extends('administration.layouts.admin')

@section('titre', 'Piste d\'audit & Sécurité')

@section('contenu')
    <div class="space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 border-b border-gray-200">
            <div>
                <h1 class="text-2xl font-heading font-black text-gray-900 tracking-tight">Journal d'Audit & Sécurité</h1>
                <p class="text-xs text-gray-500 uppercase tracking-wider">Traçabilité immuable des actions administratives, tentatives de connexion & changements de privilèges</p>
            </div>

            @if(Auth::user()->role === 'super_admin')
                <form action="{{ route('admin.securite.purger') }}" method="POST" onsubmit="return confirm('Purger définitivement les enregistrements d\'audit de plus de 90 jours ?');">
                    @csrf
                    <button type="submit" class="bg-gray-800 hover:bg-black text-white px-3 py-2 rounded text-xs font-heading font-bold uppercase tracking-wider transition">
                        Purger les logs (+90 jours)
                    </button>
                </form>
            @endif
        </div>

        {{-- Barre de filtrage --}}
        <form method="GET" action="{{ route('admin.securite.index') }}" class="bg-white p-4 rounded-lg border border-gray-200 shadow-sm flex flex-wrap gap-4 items-center">
            <select name="type_log" class="bg-gray-50 border border-gray-300 rounded px-3 py-2 text-xs focus:ring-1 focus:ring-cargo-red focus:outline-none">
                <option value="">Toutes les catégories de log</option>
                @foreach ($typesLogs as $type)
                    <option value="{{ $type }}" {{ $logFiltre === $type ? 'selected' : '' }}>{{ ucfirst($type) }}</option>
                @endforeach
            </select>

            <select name="evenement" class="bg-gray-50 border border-gray-300 rounded px-3 py-2 text-xs focus:ring-1 focus:ring-cargo-red focus:outline-none">
                <option value="">Tous les événements</option>
                @foreach ($evenements as $evt)
                    <option value="{{ $evt }}" {{ $evenementFiltre === $evt ? 'selected' : '' }}>{{ ucfirst(str_replace('_', ' ', $evt)) }}</option>
                @endforeach
            </select>

            <button type="submit" class="bg-cargo-red hover:bg-cargo-redDark text-white px-4 py-2 rounded text-xs font-heading font-bold uppercase tracking-wider">
                Filtrer
            </button>

            @if ($logFiltre || $evenementFiltre)
                <a href="{{ route('admin.securite.index') }}" class="text-xs text-gray-500 hover:text-gray-900 underline">Réinitialiser</a>
            @endif
        </form>

        {{-- Tableau d'audit --}}
        <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-200 text-gray-600 uppercase tracking-wider font-semibold">
                            <th class="p-3.5">Date & Heure</th>
                            <th class="p-3.5">Catégorie</th>
                            <th class="p-3.5">Événement</th>
                            <th class="p-3.5">Description</th>
                            <th class="p-3.5">Opérateur</th>
                            <th class="p-3.5">IP Source</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-gray-700">
                        @forelse ($journaux as $journal)
                            @php
                                $evenementLibelle = match($journal->event ?? $journal->log_name) {
                                    'login_succes', 'LOGIN_SUCCES' => 'Connexion réussie',
                                    'login_echec', 'LOGIN_ECHEC' => 'Échec de connexion',
                                    'deconnexion', 'DECONNEXION' => 'Déconnexion',
                                    'compte_desactive' => 'Compte désactivé',
                                    'privilege_insuffisant' => 'Privilège insuffisant',
                                    'creation_article_admin' => 'Création d\'article',
                                    'modification_article_admin' => 'Mise à jour d\'article',
                                    'bascule_alerte_urgente' => 'Alerte urgente modifiée',
                                    'bascule_paywall_pro' => 'Paywall modifié',
                                    'bascule_a_la_une' => 'Mise à la une',
                                    'suppression_article' => 'Suppression d\'article',
                                    'approuve_commentaire' => 'Commentaire approuvé',
                                    'spam_commentaire' => 'Commentaire marqué spam',
                                    'statut_corridor_modifie' => 'Statut corridor mis à jour',
                                    'bascule_paywall_global' => 'Paywall global modifié',
                                    default => ucfirst(str_replace('_', ' ', $journal->event ?? $journal->log_name))
                                };
                            @endphp
                            <tr class="hover:bg-gray-50">
                                <td class="p-3.5 font-mono text-[11px] text-gray-500 whitespace-nowrap">
                                    {{ $journal->created_at->translatedFormat('d/m/Y H:i:s') }}
                                </td>
                                <td class="p-3.5">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase
                                        @if($journal->log_name === 'securite') bg-red-100 text-red-800
                                        @elseif($journal->log_name === 'authentification') bg-blue-100 text-blue-800
                                        @else bg-gray-100 text-gray-800 @endif">
                                        {{ $journal->log_name }}
                                    </span>
                                </td>
                                <td class="p-3.5 font-semibold text-gray-800">
                                    {{ $evenementLibelle }}
                                </td>
                                <td class="p-3.5 font-medium text-gray-900">
                                    <div>{{ $journal->description }}</div>
                                    @if (!empty($journal->properties))
                                        <details class="text-[10px] text-gray-500 mt-1">
                                            <summary class="cursor-pointer text-cargo-red hover:underline">Voir les données techniques</summary>
                                            <pre class="bg-gray-900 text-gray-100 p-2 rounded mt-1 font-mono text-[10px] overflow-x-auto">{{ json_encode($journal->properties, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
                                        </details>
                                    @endif
                                </td>
                                <td class="p-3.5">
                                    <span class="font-semibold text-gray-900">{{ $journal->auteur->name ?? 'Système' }}</span>
                                    @if($journal->auteur)
                                        <span class="block text-[10px] text-gray-500 font-mono">{{ $journal->auteur->email }}</span>
                                    @endif
                                </td>
                                <td class="p-3.5 font-mono text-[11px] text-gray-600">
                                    {{ $journal->properties['ip'] ?? '—' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="p-8 text-center text-gray-500">Aucun enregistrement d'audit ne correspond à ces critères.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($journaux->hasPages())
                <div class="p-4 border-t border-gray-200 bg-gray-50">
                    {{ $journaux->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
