@extends('administration.layouts.admin')

@section('titre', 'Users & Access Control (RBAC)')

@section('contenu')
    @php
        $rolesTraductions = [
            'super_admin' => 'Super Administrator',
            'administrateur' => 'Administrator',
            'redacteur_en_chef' => 'Editor-in-Chief',
            'journaliste' => 'Journalist',
            'redacteur' => 'Writer',
            'abonne' => 'PRO Subscriber',
            'lecteur' => 'Reader',
        ];
    @endphp

    <div class="space-y-6">
        {{-- Header & Metrics Bar --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 border-b border-gray-200">
            <div>
                <h1 class="text-2xl font-heading font-black text-gray-900 tracking-tight">Users & Access Control (RBAC)</h1>
                <p class="text-xs text-gray-500 uppercase tracking-wider">Account lifecycle, role privileges & platform access control</p>
            </div>

            <div class="flex items-center gap-2 text-xs font-mono">
                <span class="px-3 py-1 bg-white rounded border border-gray-200 text-gray-700">
                    Total: <strong class="text-gray-900">{{ $utilisateurs->total() }}</strong>
                </span>
                <span class="px-3 py-1 bg-emerald-50 rounded border border-emerald-200 text-emerald-800 font-bold">
                    Active: {{ $totalActifs ?? 0 }}
                </span>
                <span class="px-3 py-1 bg-red-50 rounded border border-red-200 text-red-800 font-bold">
                    Deactivated: {{ $totalDesactives ?? 0 }}
                </span>
            </div>
        </div>

        {{-- Admin Editorial Policies & Permissions (2 Toggles) --}}
        @php
            $autoSuppressionActive = \App\Models\ConfigurationSysteme::suppressionCompteRedacteurEstActive();
            $photoAuteurActive = \App\Models\ConfigurationSysteme::photoAuteurArticlesEstActivee();
        @endphp
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            {{-- Toggle 1: Auto-Suppression Rédacteurs --}}
            <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm flex flex-col justify-between gap-3">
                <div class="flex items-start gap-3">
                    <div class="w-9 h-9 rounded-lg {{ $autoSuppressionActive ? 'bg-red-50 text-cargo-red border border-red-200' : 'bg-gray-100 text-gray-500 border border-gray-200' }} flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    </div>
                    <div class="min-w-0">
                        <div class="flex items-center gap-2 flex-wrap">
                            <h3 class="font-heading font-black text-xs uppercase tracking-wider text-gray-900">Writer Self-Deletion</h3>
                            <span class="px-2 py-0.5 text-[9px] font-black uppercase rounded {{ $autoSuppressionActive ? 'bg-emerald-100 text-emerald-800 border border-emerald-300' : 'bg-gray-100 text-gray-600 border border-gray-300' }}">
                                {{ $autoSuppressionActive ? 'ENABLED' : 'DISABLED' }}
                            </span>
                        </div>
                        <p class="text-[11px] text-gray-500 mt-0.5">Allow editorial writers to permanently delete their own account from settings.</p>
                    </div>
                </div>
                <form action="{{ route('admin.utilisateurs.basculer-suppression') }}" method="POST" class="pt-2 border-t border-gray-100 text-right">
                    @csrf
                    <button type="submit" class="px-3.5 py-1.5 text-[11px] font-heading font-bold uppercase tracking-wider rounded-lg transition shadow-sm border {{ $autoSuppressionActive ? 'bg-amber-100 hover:bg-amber-200 text-amber-900 border-amber-300' : 'bg-cargo-red hover:bg-red-700 text-white border-transparent' }}">
                        {{ $autoSuppressionActive ? 'Disable Self-Deletion' : 'Enable Self-Deletion' }}
                    </button>
                </form>
            </div>

            {{-- Toggle 2: Affichage Photo Auteur 40x40 sur Articles Publics --}}
            <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm flex flex-col justify-between gap-3">
                <div class="flex items-start gap-3">
                    <div class="w-9 h-9 rounded-lg {{ $photoAuteurActive ? 'bg-blue-50 text-blue-700 border border-blue-200' : 'bg-gray-100 text-gray-500 border border-gray-200' }} flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div class="min-w-0">
                        <div class="flex items-center gap-2 flex-wrap">
                            <h3 class="font-heading font-black text-xs uppercase tracking-wider text-gray-900">Author Photo on Articles (40x40)</h3>
                            <span class="px-2 py-0.5 text-[9px] font-black uppercase rounded {{ $photoAuteurActive ? 'bg-emerald-100 text-emerald-800 border border-emerald-300' : 'bg-gray-100 text-gray-600 border border-gray-300' }}">
                                {{ $photoAuteurActive ? 'ENABLED' : 'DISABLED' }}
                            </span>
                        </div>
                        <p class="text-[11px] text-gray-500 mt-0.5">Display the journalist's 40x40 circular avatar photo next to their byline on public articles.</p>
                    </div>
                </div>
                <form action="{{ route('admin.utilisateurs.basculer-photo-auteur') }}" method="POST" class="pt-2 border-t border-gray-100 text-right">
                    @csrf
                    <button type="submit" class="px-3.5 py-1.5 text-[11px] font-heading font-bold uppercase tracking-wider rounded-lg transition shadow-sm border {{ $photoAuteurActive ? 'bg-amber-100 hover:bg-amber-200 text-amber-900 border-amber-300' : 'bg-cargo-red hover:bg-red-700 text-white border-transparent' }}">
                        {{ $photoAuteurActive ? 'Hide Author Photos' : 'Show Author Photos' }}
                    </button>
                </form>
            </div>
        </div>

        {{-- Add User Collapsible Form --}}
        @include('administration.utilisateurs.composants.formulaire-creation')

        {{-- Filter & Search Bar --}}
        <form method="GET" action="{{ route('admin.utilisateurs.index') }}" class="bg-white p-4 rounded-lg border border-gray-200 shadow-sm flex flex-wrap gap-3 items-center justify-between">
            <div class="flex-1 min-w-[280px] flex flex-wrap gap-2 items-center">
                <input type="text" name="recherche" value="{{ $recherche }}" placeholder="Search by name, email or phone..."
                       class="flex-1 min-w-[180px] bg-gray-50 border border-gray-300 rounded px-3 py-2 text-xs text-gray-900 focus:bg-white focus:ring-1 focus:ring-cargo-red focus:outline-none">

                <select name="role" class="bg-gray-50 border border-gray-300 rounded px-3 py-2 text-xs text-gray-900 focus:bg-white focus:ring-1 focus:ring-cargo-red focus:outline-none font-semibold">
                    <option value="">All Roles</option>
                    @foreach($rolesTraductions as $cle => $libelle)
                        <option value="{{ $cle }}" {{ $roleFiltre === $cle ? 'selected' : '' }}>{{ $libelle }}</option>
                    @endforeach
                </select>

                <select name="statut" class="bg-gray-50 border border-gray-300 rounded px-3 py-2 text-xs text-gray-900 focus:bg-white focus:ring-1 focus:ring-cargo-red focus:outline-none font-semibold">
                    <option value="">All Statuses</option>
                    <option value="1" {{ $statutFiltre === '1' ? 'selected' : '' }}>Active Only</option>
                    <option value="0" {{ $statutFiltre === '0' ? 'selected' : '' }}>Deactivated Only</option>
                </select>

                <button type="submit" class="bg-cargo-red hover:bg-red-700 text-white px-4 py-2 rounded text-xs font-heading font-bold uppercase tracking-wider transition shadow">
                    Filter
                </button>
            </div>

            @if ($recherche || $roleFiltre || $statutFiltre !== null && $statutFiltre !== '')
                <a href="{{ route('admin.utilisateurs.index') }}" class="text-xs text-gray-500 hover:text-gray-900 underline font-semibold">
                    Reset Filters
                </a>
            @endif
        </form>

        {{-- Users Table --}}
        <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-200 text-gray-600 uppercase tracking-wider font-semibold">
                            <th class="p-3.5">User</th>
                            <th class="p-3.5">Role</th>
                            <th class="p-3.5 text-center">Status (Toggle)</th>
                            <th class="p-3.5">Last Login</th>
                            <th class="p-3.5 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-gray-700">
                        @forelse ($utilisateurs as $user)
                            <tr class="hover:bg-gray-50 transition">
                                {{-- User Name & Email --}}
                                <td class="p-3.5">
                                    <div class="flex items-center gap-2.5">
                                        <div class="w-8 h-8 rounded-full bg-[#14151C] text-white flex items-center justify-center font-heading font-black text-xs uppercase shrink-0">
                                            {{ substr($user->name, 0, 2) }}
                                        </div>
                                        <div>
                                            <div class="font-heading font-bold text-gray-900 flex items-center gap-1.5">
                                                <span>{{ $user->name }}</span>
                                                @if($user->id === Auth::id())
                                                    <span class="text-[9px] bg-amber-100 text-amber-800 font-bold px-1.5 py-0.2 rounded uppercase">You</span>
                                                @endif
                                            </div>
                                            <div class="text-[11px] text-gray-500 font-mono">{{ $user->email }}</div>
                                            @if($user->telephone)
                                                <div class="text-[10px] text-gray-400 font-mono">{{ $user->telephone }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </td>

                                {{-- Role Badge --}}
                                <td class="p-3.5">
                                    <span class="inline-block px-2.5 py-1 rounded text-[11px] font-bold uppercase tracking-wider border {{ in_array($user->role, ['super_admin', 'administrateur', 'admin']) ? 'bg-red-50 text-red-800 border-red-200' : (in_array($user->role, ['redacteur_en_chef', 'journaliste', 'redacteur']) ? 'bg-blue-50 text-blue-800 border-blue-200' : 'bg-gray-50 text-gray-700 border-gray-200') }}">
                                        {{ $rolesTraductions[$user->role] ?? $user->role }}
                                    </span>
                                </td>

                                {{-- Status 1-Click Toggle Form --}}
                                <td class="p-3.5 text-center">
                                    @if($user->id === Auth::id())
                                        <span class="inline-block px-2.5 py-1 rounded text-[10px] font-black uppercase tracking-wider bg-emerald-100 text-emerald-800 border border-emerald-200 cursor-default" title="You cannot deactivate your own account">
                                            Active
                                        </span>
                                    @else
                                        <form action="{{ route('admin.utilisateurs.statut', $user->id) }}" method="POST" class="inline-block">
                                            @csrf
                                            <button type="submit"
                                                    onclick="return confirm('Do you want to {{ $user->est_actif ? 'deactivate' : 'activate' }} user #{{ $user->id }} ({{ $user->email }})?');"
                                                    class="px-2.5 py-1 rounded text-[10px] font-black uppercase tracking-wider transition shadow-sm border {{ $user->est_actif ? 'bg-emerald-100 hover:bg-emerald-200 text-emerald-900 border-emerald-300' : 'bg-red-100 hover:bg-red-200 text-red-900 border-red-300' }}"
                                                    title="{{ $user->est_actif ? 'Click to deactivate account' : 'Click to activate account' }}">
                                                {{ $user->est_actif ? 'Active' : 'Deactivated' }}
                                            </button>
                                        </form>
                                    @endif
                                </td>

                                {{-- Last Login --}}
                                <td class="p-3.5 font-mono text-[11px] text-gray-500">
                                    {{ $user->derniere_connexion_a ? $user->derniere_connexion_a->format('Y-m-d H:i') : 'Never' }}
                                </td>

                                {{-- Contextual Actions (Edit, Delete, Password Reset) --}}
                                <td class="p-3.5 text-right">
                                    @include('administration.utilisateurs.composants.boite-actions', ['user' => $user])
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="p-8 text-center text-gray-500 italic">
                                    No user accounts matched your search criteria.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($utilisateurs->hasPages())
                <div class="p-4 border-t border-gray-200 bg-gray-50">
                    {{ $utilisateurs->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
