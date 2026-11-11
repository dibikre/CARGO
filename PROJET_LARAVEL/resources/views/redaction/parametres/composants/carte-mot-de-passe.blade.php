{{-- Carte de modification du mot de passe avec validation de sécurité --}}
<div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
    <div class="px-6 py-4 bg-gray-50/75 border-b border-gray-200 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="w-8 h-8 rounded-lg bg-gray-900 text-white flex items-center justify-center">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
            </div>
            <div>
                <h3 class="font-heading font-black text-sm uppercase tracking-wider text-gray-900">Security & Password</h3>
                <p class="text-xs text-gray-500">Ensure your account is protected by an enterprise-grade password (min. 8 characters)</p>
            </div>
        </div>
        <span class="px-2.5 py-1 text-[11px] font-bold uppercase tracking-wider bg-gray-100 text-gray-700 rounded border border-gray-200">
            BCrypt Encrypted
        </span>
    </div>

    <form action="{{ route('redaction.parametres.mot-de-passe') }}" method="POST" class="p-6 space-y-5">
        @csrf
        @method('PUT')

        {{-- Mot de passe actuel --}}
        <div>
            <label for="input-mdp-actuel" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">
                Current Password *
            </label>
            <input 
                type="password" 
                id="input-mdp-actuel" 
                name="mot_de_passe_actuel" 
                required 
                placeholder="Enter your existing account password"
                class="w-full text-sm px-3.5 py-2.5 bg-gray-50 border @error('mot_de_passe_actuel') border-red-400 @else border-gray-200 @enderror rounded-lg text-gray-900 focus:bg-white focus:outline-none focus:ring-1 focus:ring-cargo-red focus:border-cargo-red transition"
            >
            @error('mot_de_passe_actuel')
                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 pt-1">
            {{-- Nouveau mot de passe --}}
            <div>
                <label for="input-nouveau-mdp" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">
                    New Secure Password *
                </label>
                <input 
                    type="password" 
                    id="input-nouveau-mdp" 
                    name="nouveau_mot_de_passe" 
                    required 
                    placeholder="Min. 8 characters"
                    class="w-full text-sm px-3.5 py-2.5 bg-gray-50 border @error('nouveau_mot_de_passe') border-red-400 @else border-gray-200 @enderror rounded-lg text-gray-900 focus:bg-white focus:outline-none focus:ring-1 focus:ring-cargo-red focus:border-cargo-red transition"
                >
                @error('nouveau_mot_de_passe')
                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Confirmation du nouveau mot de passe --}}
            <div>
                <label for="input-confirmation-mdp" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">
                    Confirm New Password *
                </label>
                <input 
                    type="password" 
                    id="input-confirmation-mdp" 
                    name="nouveau_mot_de_passe_confirmation" 
                    required 
                    placeholder="Re-type new password"
                    class="w-full text-sm px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-gray-900 focus:bg-white focus:outline-none focus:ring-1 focus:ring-cargo-red focus:border-cargo-red transition"
                >
            </div>
        </div>

        <div class="p-3 bg-gray-50 border border-gray-200 rounded-lg flex items-start gap-2.5 text-xs text-gray-600">
            <svg class="w-4 h-4 text-gray-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <div>
                <strong class="text-gray-900 font-bold">Recommendation:</strong> Use at least 8 characters combining uppercase letters, numbers, and special symbols. Changing your password will keep your current editorial session active.
            </div>
        </div>

        <div class="flex items-center justify-end pt-3 border-t border-gray-100">
            <button 
                type="submit" 
                style="background-color: #D4201A; color: #ffffff;"
                class="px-5 py-2.5 text-xs font-heading font-bold uppercase tracking-wider bg-[#D4201A] hover:bg-[#B3140F] rounded-lg shadow-sm transition flex items-center gap-2 cursor-pointer"
            >
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                <span>Update Password</span>
            </button>
        </div>
    </form>
</div>
