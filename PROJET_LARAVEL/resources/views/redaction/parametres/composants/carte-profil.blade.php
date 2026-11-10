{{-- Carte de modification du profil éditorial (Pseudo, Email, Téléphone, Bio) --}}
<div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
    <div class="px-6 py-4 bg-gray-50/75 border-b border-gray-200 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="w-8 h-8 rounded-lg bg-gray-900 text-white flex items-center justify-center">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
            </div>
            <div>
                <h3 class="font-heading font-black text-sm uppercase tracking-wider text-gray-900">Editorial Identity & Profile</h3>
                <p class="text-xs text-gray-500">Update your public journalist handle (pseudo) and official email address</p>
            </div>
        </div>
        <span class="px-2.5 py-1 text-[11px] font-bold uppercase tracking-wider bg-gray-100 text-gray-700 rounded border border-gray-200">
            ID #{{ $utilisateur->id }}
        </span>
    </div>

    <form action="{{ route('redaction.parametres.profil') }}" method="POST" class="p-6 space-y-5">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            {{-- Pseudo / Nom d'affichage --}}
            <div>
                <label for="input-nom" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">
                    Journalist Handle / Pseudo *
                </label>
                <input 
                    type="text" 
                    id="input-nom" 
                    name="name" 
                    value="{{ old('name', $utilisateur->name) }}" 
                    required 
                    placeholder="e.g. Claire Delamare"
                    class="w-full text-sm px-3.5 py-2.5 bg-gray-50 border @error('name') border-red-400 @else border-gray-200 @enderror rounded-lg text-gray-900 focus:bg-white focus:outline-none focus:ring-1 focus:ring-cargo-red focus:border-cargo-red transition"
                >
                <p class="text-[11px] text-gray-400 mt-1">This name appears as the author byline on your published logistics articles.</p>
                @error('name')
                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Adresse e-mail --}}
            <div>
                <label for="input-email" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">
                    Official Email Address *
                </label>
                <input 
                    type="email" 
                    id="input-email" 
                    name="email" 
                    value="{{ old('email', $utilisateur->email) }}" 
                    required 
                    placeholder="e.g. claire.delamare@cargo-press.com"
                    class="w-full text-sm px-3.5 py-2.5 bg-gray-50 border @error('email') border-red-400 @else border-gray-200 @enderror rounded-lg text-gray-900 focus:bg-white focus:outline-none focus:ring-1 focus:ring-cargo-red focus:border-cargo-red transition"
                >
                <p class="text-[11px] text-gray-400 mt-1">Used for newsroom authentication, notifications, and editorial alerts.</p>
                @error('email')
                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 pt-2">
            {{-- Téléphone professionnel --}}
            <div>
                <label for="input-telephone" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">
                    Editorial Desk Direct Phone
                </label>
                <input 
                    type="text" 
                    id="input-telephone" 
                    name="telephone" 
                    value="{{ old('telephone', $utilisateur->telephone) }}" 
                    placeholder="e.g. +33 1 42 68 00 00"
                    class="w-full text-sm px-3.5 py-2.5 bg-gray-50 border @error('telephone') border-red-400 @else border-gray-200 @enderror rounded-lg text-gray-900 focus:bg-white focus:outline-none focus:ring-1 focus:ring-cargo-red focus:border-cargo-red transition"
                >
                @error('telephone')
                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Rôle affiché --}}
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">
                    Assigned Editorial Role
                </label>
                <div class="px-3.5 py-2.5 bg-gray-100 border border-gray-200 rounded-lg text-sm text-gray-700 font-mono flex items-center justify-between">
                    <span class="font-bold uppercase text-xs">{{ $utilisateur->role }}</span>
                    <span class="text-[10px] text-gray-500 uppercase">Managed by Admin</span>
                </div>
            </div>
        </div>

        {{-- Biographie éditoriale --}}
        <div>
            <label for="input-bio" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">
                Editorial Bio / Specialization
            </label>
            <textarea 
                id="input-bio" 
                name="bio" 
                rows="3" 
                placeholder="Senior investigative correspondent covering maritime corridors and port digitisation..."
                class="w-full text-sm px-3.5 py-2.5 bg-gray-50 border @error('bio') border-red-400 @else border-gray-200 @enderror rounded-lg text-gray-900 focus:bg-white focus:outline-none focus:ring-1 focus:ring-cargo-red focus:border-cargo-red transition"
            >{{ old('bio', $utilisateur->bio) }}</textarea>
            @error('bio')
                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex items-center justify-end pt-3 border-t border-gray-100">
            <button 
                type="submit" 
                style="background-color: #D4201A; color: #ffffff;"
                class="px-5 py-2.5 text-xs font-heading font-bold uppercase tracking-wider bg-[#D4201A] hover:bg-[#B3140F] rounded-lg shadow-sm transition flex items-center gap-2 cursor-pointer"
            >
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <span>Save Profile Changes</span>
            </button>
        </div>
    </form>
</div>
