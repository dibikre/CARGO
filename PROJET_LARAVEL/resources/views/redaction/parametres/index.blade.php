@extends('redaction.layouts.redaction')

@section('titre', 'Account Settings & Security')

@section('contenu')
<div class="space-y-6 pb-12">
    {{-- Header Banner --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 border-b border-gray-200">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="w-2.5 h-2.5 rounded-full bg-cargo-accent"></span>
                <span class="text-xs font-bold uppercase tracking-wider text-gray-500">Newsroom Desk &bull; Editorial Profile</span>
            </div>
            <h1 class="text-2xl font-heading font-black text-gray-900 tracking-tight">Account & Security Settings</h1>
            <p class="text-xs text-gray-500">Manage your journalist handle, email credentials, password, and security preferences.</p>
        </div>

        <div class="flex items-center gap-2">
            <span class="px-3 py-1.5 bg-white rounded-lg border border-gray-200 text-xs font-mono text-gray-700 flex items-center gap-2 shadow-sm">
                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                <span>Active Writer: <strong>{{ $utilisateur->name }}</strong></span>
            </span>
        </div>
    </div>

    {{-- Main Settings Forms --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Profile and Password (2 Cols) --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Edit Profile (Pseudo, Email, Phone, Bio) --}}
            @include('redaction.parametres.composants.carte-profil')

            {{-- Change Password --}}
            @include('redaction.parametres.composants.carte-mot-de-passe')
        </div>

        {{-- Side Summary / Security Specs & Deletion Card (1 Col) --}}
        <div class="space-y-6">
            {{-- Account Information Overview Card --}}
            <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm space-y-4">
                <div class="flex items-center gap-3 pb-3 border-b border-gray-100">
                    @if($utilisateur->avatar)
                        <img src="{{ $utilisateur->url_avatar }}" alt="{{ $utilisateur->name }}" class="w-10 h-10 rounded-full object-cover border border-gray-300 shadow-sm shrink-0">
                    @else
                        <div class="w-10 h-10 rounded-full bg-gray-900 text-white flex items-center justify-center font-heading font-black text-sm uppercase shrink-0">
                            {{ substr($utilisateur->name, 0, 2) }}
                        </div>
                    @endif
                    <div class="min-w-0 flex-1">
                        <div class="font-heading font-bold text-sm text-gray-900 truncate">{{ $utilisateur->name }}</div>
                        <div class="text-xs text-gray-500 truncate font-mono">{{ $utilisateur->email }}</div>
                    </div>
                </div>

                <div class="space-y-2.5 text-xs">
                    <div class="flex items-center justify-between text-gray-600">
                        <span>Editorial Role</span>
                        <span class="font-bold text-gray-900 uppercase font-mono">{{ $utilisateur->role }}</span>
                    </div>
                    <div class="flex items-center justify-between text-gray-600">
                        <span>Account Status</span>
                        <span class="font-bold text-emerald-600 uppercase flex items-center gap-1">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                            Active
                        </span>
                    </div>
                    <div class="flex items-center justify-between text-gray-600">
                        <span>Joined Cargo</span>
                        <span class="font-mono text-gray-800">{{ $utilisateur->created_at ? $utilisateur->created_at->format('M d, Y') : 'N/A' }}</span>
                    </div>
                    <div class="flex items-center justify-between text-gray-600">
                        <span>Last Session</span>
                        <span class="font-mono text-gray-800">{{ $utilisateur->derniere_connexion_a ? $utilisateur->derniere_connexion_a->diffForHumans() : 'Just now' }}</span>
                    </div>
                </div>
            </div>

            {{-- Danger Zone: Account Deletion (Included ONLY if enabled by Admin) --}}
            @if($suppressionCompteActive)
                @include('redaction.parametres.composants.carte-suppression-compte')
            @endif
        </div>
    </div>
</div>

{{-- Deletion Confirmation Modal --}}
@if($suppressionCompteActive)
    @include('redaction.parametres.composants.modale-confirmation-suppression')
@endif
@endsection
