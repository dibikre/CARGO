@extends('administration.layouts.admin')

@section('titre', 'Subscriptions & PRO Plans')

@section('contenu')
<div class="space-y-6">
    {{-- Master Subscription & Paywall Switch (Make Site 100% Free) --}}
    <div class="p-6 rounded-lg border {{ $abonnementsActifs ? 'bg-white border-gray-200' : 'bg-amber-50/70 border-amber-300' }} shadow-sm">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="space-y-1">
                <div class="flex items-center gap-2">
                    <span class="inline-flex items-center gap-1.5 text-xs font-bold uppercase tracking-wider px-2.5 py-0.5 rounded {{ $abonnementsActifs ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-900 border border-amber-300' }}">
                        <span class="w-2 h-2 rounded-full {{ $abonnementsActifs ? 'bg-emerald-500' : 'bg-amber-500' }}"></span>
                        {{ $abonnementsActifs ? 'Monetized Mode Active (Paywall Enabled)' : 'Free Open Access Mode (Site 100% Free)' }}
                    </span>
                    <span class="text-xs font-bold text-gray-400">· Master Control</span>
                </div>
                <h2 class="text-xl font-heading font-black text-cargo-dark">
                    {{ $abonnementsActifs ? 'Global Subscriptions & Paywall Control' : 'CARGO Platform is Currently 100% FREE for Everyone' }}
                </h2>
                <p class="text-xs text-gray-600 max-w-2xl leading-relaxed">
                    {{ $abonnementsActifs 
                        ? 'Subscribers and PRO accounts currently have exclusive access to paywalled articles and reports. You can disable subscriptions at any time to make the entire site completely free for all readers.'
                        : 'Subscriptions are currently deactivated. All restricted articles, logistics analyses, and research papers are immediately accessible to all readers and visitors without payment or registration.' }}
                </p>
            </div>

            <form action="{{ route('admin.abonnements.basculer-paywall') }}" method="POST" onsubmit="return confirm('{{ $abonnementsActifs ? 'Are you sure you want to deactivate subscriptions? This will make the entire site 100% FREE for everyone!' : 'Reactivate paid subscriptions and restore the PRO paywall?' }}');" class="shrink-0">
                @csrf
                <button type="submit" class="px-5 py-3 rounded font-heading font-bold uppercase text-xs tracking-wider transition shadow flex items-center gap-2 {{ $abonnementsActifs ? 'bg-amber-600 hover:bg-amber-700 text-white' : 'bg-cargo-red hover:bg-cargo-redDark text-white' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                    {{ $abonnementsActifs ? 'Deactivate Subscriptions (Make Site 100% Free)' : 'Reactivate Subscriptions & Paywall' }}
                </button>
            </form>
        </div>
    </div>

    {{-- Header & Metrics --}}
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
            <span class="text-xs uppercase font-bold tracking-wider text-gray-500">Gross Subscriber Volume</span>
            <div class="text-3xl font-heading font-black text-emerald-600 mt-1">{{ number_format($metriques['revenu_total'], 2) }} €</div>
        </div>
    </div>

    {{-- Plans Configuration --}}
    <div class="bg-white rounded-lg border border-gray-200 p-6">
        <div class="flex items-center justify-between pb-4 mb-4 border-b border-gray-100">
            <div>
                <h2 class="text-lg font-heading font-bold text-cargo-dark">PRO Subscription Plans & Tariffs</h2>
                <p class="text-xs text-gray-500">Pricing tiers, billing frequency, and paywall access rules.</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            @forelse($formules as $formule)
                <div class="border border-gray-200 rounded-lg p-5 flex flex-col justify-between bg-gray-50/50">
                    <div>
                        <div class="flex items-center justify-between">
                            <h3 class="font-bold text-cargo-dark font-heading">{{ $formule->nom }}</h3>
                            <span class="text-xs px-2 py-0.5 rounded font-bold uppercase {{ $formule->est_actif ? 'bg-emerald-100 text-emerald-800' : 'bg-gray-200 text-gray-600' }}">
                                {{ $formule->est_actif ? 'Active' : 'Disabled' }}
                            </span>
                        </div>
                        <div class="text-2xl font-black font-heading text-cargo-dark my-2">
                            {{ number_format($formule->prix_ht, 2) }} € <span class="text-xs font-normal text-gray-500 font-sans">HT / {{ $formule->duree_en_jours }} days</span>
                        </div>
                        <p class="text-xs text-gray-600 mb-4">{{ $formule->description }}</p>
                    </div>

                    <div class="pt-3 border-t border-gray-200 flex items-center justify-between text-xs">
                        <span class="text-gray-500 font-semibold">{{ $formule->abonnements_count }} subscribers</span>
                        <form action="{{ route('admin.abonnements.formules.basculer', $formule->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="font-bold text-cargo-red hover:underline uppercase text-[11px]">
                                {{ $formule->est_actif ? 'Deactivate' : 'Activate' }}
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="col-span-3 text-center py-6 text-gray-400 italic">No subscription plans created yet.</div>
            @endforelse
        </div>
    </div>

    {{-- Subscriber Roster --}}
    <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <h3 class="text-sm font-bold uppercase tracking-wider text-gray-700">Subscribers & Access Ledger</h3>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-200 text-[11px] font-bold uppercase tracking-wider text-gray-500">
                        <th class="py-3 px-4">Order Ref</th>
                        <th class="py-3 px-4">Reader / Account</th>
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
                                <div class="font-bold text-cargo-dark">{{ $abn->utilisateur->name ?? 'Guest Reader' }}</div>
                                <div class="text-xs text-gray-500">{{ $abn->utilisateur->email ?? 'N/A' }}</div>
                            </td>
                            <td class="py-3 px-4 text-xs font-semibold text-gray-700">
                                {{ $abn->formule->nom ?? 'Standard PRO' }}
                            </td>
                            <td class="py-3 px-4 text-xs text-gray-500">
                                {{ $abn->date_debut?->format('M d, Y') }} &rarr; {{ $abn->date_fin?->format('M d, Y') }}
                            </td>
                            <td class="py-3 px-4 text-center">
                                @if($abn->statut === 'actif')
                                    <span class="bg-emerald-100 text-emerald-800 text-xs px-2.5 py-1 rounded font-bold uppercase tracking-wider">Active</span>
                                @elseif($abn->statut === 'expire')
                                    <span class="bg-gray-100 text-gray-700 text-xs px-2.5 py-1 rounded font-bold uppercase tracking-wider">Expired</span>
                                @else
                                    <span class="bg-red-100 text-red-800 text-xs px-2.5 py-1 rounded font-bold uppercase tracking-wider">{{ $abn->statut }}</span>
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
                            <td colspan="6" class="py-8 text-center text-gray-400 italic">No subscriber orders registered yet.</td>
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
