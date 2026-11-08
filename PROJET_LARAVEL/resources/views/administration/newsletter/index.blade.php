@extends('administration.layouts.admin')

@section('titre', 'Audience Newsletter — Administration')

@section('contenu')
<div class="space-y-6">
    {{-- En-tête & Métriques --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white p-5 rounded-lg border border-gray-200">
            <span class="text-xs uppercase font-bold tracking-wider text-gray-500">Total Audience Newsletter</span>
            <div class="text-3xl font-heading font-black text-cargo-dark mt-1">{{ number_format($metriques['total']) }}</div>
        </div>
        <div class="bg-white p-5 rounded-lg border border-gray-200">
            <span class="text-xs uppercase font-bold tracking-wider text-gray-500">Confirmés Double Opt-In</span>
            <div class="text-3xl font-heading font-black text-emerald-600 mt-1">{{ number_format($metriques['confirmes']) }}</div>
        </div>
        <div class="bg-white p-5 rounded-lg border border-gray-200">
            <span class="text-xs uppercase font-bold tracking-wider text-gray-500">Destinataires Actifs</span>
            <div class="text-3xl font-heading font-black text-cargo-dark mt-1">{{ number_format($metriques['actifs']) }}</div>
        </div>
    </div>

    {{-- Barre de filtrage --}}
    <div class="bg-white p-4 rounded-lg border border-gray-200">
        <form method="GET" action="{{ route('admin.newsletter.index') }}" class="flex flex-col md:flex-row gap-3">
            <input type="text" name="recherche" value="{{ $recherche }}" placeholder="Rechercher par e-mail, nom ou entreprise..." class="flex-1 text-sm border border-gray-300 rounded px-3 py-2 focus:outline-none focus:border-cargo-dark">

            <select name="statut" class="text-sm border border-gray-300 rounded px-3 py-2 focus:outline-none focus:border-cargo-dark">
                <option value="">Tous les états de confirmation</option>
                <option value="confirme" {{ $statut === 'confirme' ? 'selected' : '' }}>Confirmé (Double Opt-In)</option>
                <option value="en_attente" {{ $statut === 'en_attente' ? 'selected' : '' }}>En attente de confirmation</option>
            </select>

            <button type="submit" class="bg-cargo-red hover:bg-cargo-redDark text-white font-heading font-bold uppercase tracking-wider text-xs px-5 py-2 rounded transition">
                Filtrer les contacts
            </button>
        </form>
    </div>

    {{-- Tableau des abonnés --}}
    <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <h3 class="text-sm font-bold uppercase tracking-wider text-gray-700">Registre des Abonnés Newsletter</h3>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-200 text-[11px] font-bold uppercase tracking-wider text-gray-500">
                        <th class="py-3 px-4">Adresse E-mail</th>
                        <th class="py-3 px-4">Contact / Entreprise</th>
                        <th class="py-3 px-4">Fréquence</th>
                        <th class="py-3 px-4 text-center">État Opt-In</th>
                        <th class="py-3 px-4 text-center">Statut d'Envoi</th>
                        <th class="py-3 px-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($abonnes as $contact)
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="py-3.5 px-4 font-mono font-bold text-xs text-cargo-dark">
                                {{ $contact->email }}
                            </td>
                            <td class="py-3.5 px-4">
                                <div class="font-bold text-gray-800">{{ $contact->nom ?? 'Lecteur' }}</div>
                                <div class="text-xs text-gray-500">{{ $contact->entreprise ?? 'Abonné individuel' }}</div>
                            </td>
                            <td class="py-3.5 px-4 text-xs font-semibold text-gray-600 uppercase">
                                {{ $contact->frequence ?? 'Hebdomadaire' }}
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                @if($contact->confirme_a)
                                    <span class="bg-emerald-100 text-emerald-800 text-[11px] px-2 py-0.5 rounded font-bold uppercase">Confirmé</span>
                                @else
                                    <span class="bg-amber-100 text-amber-800 text-[11px] px-2 py-0.5 rounded font-bold uppercase">En attente</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <form action="{{ route('admin.newsletter.basculer', $contact->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="text-xs px-2.5 py-1 rounded font-bold uppercase transition {{ $contact->est_actif ? 'bg-emerald-50 text-emerald-700 hover:bg-emerald-100' : 'bg-gray-100 text-gray-500 hover:bg-gray-200' }}">
                                        {{ $contact->est_actif ? 'Abonné Actif' : 'En Pause' }}
                                    </button>
                                </form>
                            </td>
                            <td class="py-3.5 px-4 text-right">
                                <form action="{{ route('admin.newsletter.destroy', $contact->id) }}" method="POST" onsubmit="return confirm('Supprimer définitivement ce contact ?');" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1 text-red-500 hover:text-red-700 transition" title="Supprimer le contact">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-gray-400 italic">Aucun abonné newsletter ne correspond aux critères de recherche.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-gray-100">
            {{ $abonnes->links() }}
        </div>
    </div>
</div>
@endsection
