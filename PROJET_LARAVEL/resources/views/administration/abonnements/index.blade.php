@extends('administration.layouts.admin')

@section('titre', 'Abonnements & Formules PRO')

@section('contenu')
<div class="space-y-6">
    {{-- Commutateurs Généraux : Paywall Global & Autorisation des Souscriptions --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        {{-- Commutateur 1 : Paywall Global (Site 100% Gratuit) --}}
        <div class="p-5 rounded-lg border {{ $abonnementsActifs ? 'bg-white border-gray-200' : 'bg-emerald-50 border-emerald-400' }} shadow-xs flex flex-col justify-between">
            <div class="space-y-1">
                <div class="flex items-center gap-2">
                    <span class="inline-flex items-center gap-1.5 text-xs font-bold uppercase tracking-wider px-2 py-0.5 rounded {{ $abonnementsActifs ? 'bg-amber-100 text-amber-900 border border-amber-300' : 'bg-emerald-100 text-emerald-800 border border-emerald-300' }}">
                        <span class="w-2 h-2 rounded-full {{ $abonnementsActifs ? 'bg-amber-500' : 'bg-emerald-500' }}"></span>
                        {{ $abonnementsActifs ? 'Paywall Active' : '100% Free Open Access' }}
                    </span>
                    <span class="text-[11px] font-mono text-gray-400">abonnements_actifs</span>
                </div>
                <h3 class="text-base font-heading font-black text-cargo-dark">
                    {{ $abonnementsActifs ? 'Monetized PRO Paywall is Active' : 'Platform is 100% Free for Everyone' }}
                </h3>
                <p class="text-xs text-gray-600 leading-relaxed">
                    {{ $abonnementsActifs 
                        ? 'Exclusive logistics intelligence is gated behind CARGO PRO plans for non-subscribers.'
                        : 'All paywalls are bypassed. Visitors read every briefing without paying or signing up.' }}
                </p>
            </div>

            <form action="{{ route('admin.abonnements.basculer-paywall') }}" method="POST" onsubmit="return confirm('{{ $abonnementsActifs ? 'Disable paywall? The entire publication will be 100% FREE for all visitors.' : 'Re-activate paid subscriptions and restore the PRO paywall?' }}');" class="pt-3 mt-3 border-t border-gray-100">
                @csrf
                <button type="submit" class="w-full py-2 px-3 rounded font-heading font-bold uppercase text-xs tracking-wider transition shadow flex items-center justify-center gap-2 {{ $abonnementsActifs ? 'bg-emerald-600 hover:bg-emerald-700 text-white' : 'bg-cargo-red hover:bg-cargo-redDark text-white' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                    {{ $abonnementsActifs ? 'Deactivate Paywall (Make 100% Free)' : 'Re-Activate Paid Paywall' }}
                </button>
            </form>
        </div>

        {{-- Commutateur 2 : Possibilité de souscrire (Désactiver le fait de s'abonner) --}}
        <div class="p-5 rounded-lg border {{ $souscriptionsActives ? 'bg-white border-gray-200' : 'bg-amber-50 border-amber-400' }} shadow-xs flex flex-col justify-between">
            <div class="space-y-1">
                <div class="flex items-center gap-2">
                    <span class="inline-flex items-center gap-1.5 text-xs font-bold uppercase tracking-wider px-2 py-0.5 rounded {{ $souscriptionsActives ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-900 border border-amber-300' }}">
                        <span class="w-2 h-2 rounded-full {{ $souscriptionsActives ? 'bg-emerald-500' : 'bg-amber-500' }}"></span>
                        {{ $souscriptionsActives ? 'Purchases Allowed' : 'Purchases Disabled' }}
                    </span>
                    <span class="text-[11px] font-mono text-gray-400">souscriptions_actives</span>
                </div>
                <h3 class="text-base font-heading font-black text-cargo-dark">
                    {{ $souscriptionsActives ? 'Reader Subscription Sign-Ups Enabled' : 'New Subscription Sign-Ups Are Disabled' }}
                </h3>
                <p class="text-xs text-gray-600 leading-relaxed">
                    {{ $souscriptionsActives 
                        ? 'Readers and logistics enterprises can select a plan and subscribe via online payment.'
                        : 'New subscriptions are paused. Readers cannot subscribe, while existing subscribers retain access.' }}
                </p>
            </div>

            <form action="{{ route('admin.abonnements.basculer-souscriptions') }}" method="POST" onsubmit="return confirm('{{ $souscriptionsActives ? 'Disable new subscription purchases? Readers will not be able to purchase new subscriptions.' : 'Allow readers to purchase subscriptions again?' }}');" class="pt-3 mt-3 border-t border-gray-100">
                @csrf
                <button type="submit" class="w-full py-2 px-3 rounded font-heading font-bold uppercase text-xs tracking-wider transition shadow flex items-center justify-center gap-2 {{ $souscriptionsActives ? 'bg-amber-600 hover:bg-amber-700 text-white' : 'bg-emerald-600 hover:bg-emerald-700 text-white' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                    {{ $souscriptionsActives ? 'Disable Subscribing (Pause Sign-Ups)' : 'Enable Subscription Sign-Ups' }}
                </button>
            </form>
        </div>
    </div>

    {{-- Métriques Synthétiques --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white p-5 rounded-lg border border-gray-200">
            <span class="text-xs uppercase font-bold tracking-wider text-gray-500">Active PRO Subscribers</span>
            <div class="text-3xl font-heading font-black text-cargo-dark mt-1">{{ number_format($metriques['total_abonnes_actifs']) }}</div>
        </div>
        <div class="bg-white p-5 rounded-lg border border-gray-200">
            <span class="text-xs uppercase font-bold tracking-wider text-gray-500">Total Subscriptions Processed</span>
            <div class="text-3xl font-heading font-black text-cargo-dark mt-1">{{ number_format($metriques['total_commandes']) }}</div>
        </div>
        <div class="bg-white p-5 rounded-lg border border-gray-200">
            <span class="text-xs uppercase font-bold tracking-wider text-gray-500">Gross Processed Volume</span>
            <div class="text-3xl font-heading font-black text-emerald-600 mt-1">{{ number_format($metriques['revenu_total'], 2, ',', ' ') }} €</div>
        </div>
    </div>

    {{-- Grille des Formules d'Abonnement --}}
    <div class="bg-white rounded-lg border border-gray-200 p-6 space-y-4">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-gray-100">
            <div>
                <h2 class="text-lg font-heading font-bold text-cargo-dark">CARGO PRO Subscription Plans Grids</h2>
                <p class="text-xs text-gray-500">Manage plan pricing, billing duration, feature allowances, and active status.</p>
            </div>
            <button type="button" onclick="document.getElementById('modal-nouvelle-formule').classList.remove('hidden')" class="px-4 py-2 bg-cargo-red hover:bg-cargo-redDark text-white text-xs font-bold uppercase tracking-wider rounded transition shadow flex items-center gap-1.5 shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                Add Subscription Plan
            </button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse($formules as $formule)
                @include('administration.abonnements.composants.carte-formule', ['formule' => $formule])
            @empty
                <div class="col-span-3 text-center py-8 text-gray-400 italic">No subscription plans created yet. Click "Add Subscription Plan" to create one.</div>
            @endforelse
        </div>
    </div>

    {{-- Modal de Création --}}
    @include('administration.abonnements.composants.formulaire-formule')

    {{-- Registre des Abonnés & Droits d'Accès --}}
    <div class="bg-white rounded-lg border border-gray-200 overflow-hidden shadow-xs">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <h3 class="text-sm font-bold uppercase tracking-wider text-gray-700">Subscriber Orders & Access Rights Registry</h3>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-200 text-[11px] font-bold uppercase tracking-wider text-gray-500">
                        <th class="py-3 px-4">Order Ref</th>
                        <th class="py-3 px-4">Subscriber Account</th>
                        <th class="py-3 px-4">Plan</th>
                        <th class="py-3 px-4">Period</th>
                        <th class="py-3 px-4 text-center">Status</th>
                        <th class="py-3 px-4 text-right">Quick Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($abonnements as $abn)
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="py-3 px-4 font-mono font-bold text-xs text-gray-700">
                                {{ $abn->reference_commande }}
                            </td>
                            <td class="py-3 px-4">
                                <div class="font-bold text-cargo-dark">{{ $abn->utilisateur->name ?? 'Reader' }}</div>
                                <div class="text-xs text-gray-500 font-mono">{{ $abn->utilisateur->email ?? '—' }}</div>
                            </td>
                            <td class="py-3 px-4 text-xs font-semibold text-gray-700">
                                {{ $abn->formule->nom ?? 'Standard PRO' }}
                            </td>
                            <td class="py-3 px-4 text-xs text-gray-500">
                                {{ $abn->date_debut?->translatedFormat('d M Y') }} &rarr; {{ $abn->date_fin?->translatedFormat('d M Y') }}
                            </td>
                            <td class="py-3 px-4 text-center">
                                @if($abn->statut === 'actif')
                                    <span class="bg-emerald-100 text-emerald-800 text-xs px-2.5 py-0.5 rounded font-bold uppercase tracking-wider">Active</span>
                                @elseif($abn->statut === 'expire')
                                    <span class="bg-gray-100 text-gray-700 text-xs px-2.5 py-0.5 rounded font-bold uppercase tracking-wider">Expired</span>
                                @else
                                    <span class="bg-red-100 text-red-800 text-xs px-2.5 py-0.5 rounded font-bold uppercase tracking-wider">{{ $abn->statut }}</span>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-right">
                                <form action="{{ route('admin.abonnements.update-statut', $abn->id) }}" method="POST" class="inline-flex gap-1">
                                    @csrf
                                    @if($abn->statut !== 'actif')
                                        <input type="hidden" name="statut" value="actif">
                                        <button type="submit" class="text-xs bg-emerald-600 hover:bg-emerald-700 text-white font-bold px-2.5 py-1 rounded">Grant 30d</button>
                                    @else
                                        <input type="hidden" name="statut" value="expire">
                                        <button type="submit" class="text-xs bg-gray-600 hover:bg-gray-700 text-white font-bold px-2.5 py-1 rounded">Revoke</button>
                                    @endif
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-gray-400 italic">No subscriber orders found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-gray-100">
            {{ $abonnements->links() }}
        </div>
    </div>
</div>
@endsection
