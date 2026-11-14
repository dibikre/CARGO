{{-- Carte de modification du profil éditorial (Pseudo, Email, Téléphone, Bio) --}}
<div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
    <div class="px-6 py-4 bg-gray-50/75 border-b border-gray-200 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="w-8 h-8 rounded-lg bg-gray-900 text-white flex items-center justify-center">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
            </div>
            <div>
                <h3 class="font-heading font-black text-sm uppercase tracking-wider text-gray-900">Identité éditoriale &amp; Profil</h3>
                <p class="text-xs text-gray-500">Mettez à jour votre nom de journaliste public (pseudo) et votre adresse e-mail officielle</p>
            </div>
        </div>
        <span class="px-2.5 py-1 text-[11px] font-bold uppercase tracking-wider bg-gray-100 text-gray-700 rounded border border-gray-200">
            ID #{{ $utilisateur->id }}
        </span>
    </div>

    <form action="{{ route('redaction.parametres.profil') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-5">
        @csrf
        @method('PUT')

        {{-- Section Photo de Profil et Miniature 40x40 pour articles publics --}}
        <div class="p-4 bg-gray-50 border border-gray-200 rounded-xl">
            <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-2">
                Photo de profil &amp; Signature d'article (40x40 px)
            </label>
            <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4">
                {{-- Aperçu Photo principale --}}
                <div class="relative shrink-0">
                    @if($utilisateur->avatar)
                        <img src="{{ $utilisateur->url_avatar }}" alt="{{ $utilisateur->name }}" class="w-16 h-16 rounded-full object-cover border-2 border-cargo-dark shadow-sm">
                    @else
                        <div class="w-16 h-16 rounded-full bg-cargo-dark text-white font-heading font-black text-xl flex items-center justify-center uppercase shadow-sm">
                            {{ substr($utilisateur->name, 0, 2) }}
                        </div>
                    @endif
                </div>

                {{-- Aperçu miniature 40x40 exacte pour article public --}}
                <div class="flex items-center gap-3 p-2.5 bg-white rounded-lg border border-gray-200 shrink-0">
                    <div class="shrink-0">
                        @if($utilisateur->avatar)
                            <img src="{{ $utilisateur->url_avatar_vignette }}" alt="{{ $utilisateur->name }}" style="width: 40px; height: 40px;" class="rounded-full object-cover border border-gray-300">
                        @else
                            <div style="width: 40px; height: 40px;" class="rounded-full bg-cargo-dark text-white font-heading font-black text-xs flex items-center justify-center uppercase">
                                {{ substr($utilisateur->name, 0, 1) }}
                            </div>
                        @endif
                    </div>
                    <div class="text-[11px] leading-tight">
                        <span class="block font-bold text-gray-800">Vignette d'article 40x40</span>
                        @if(isset($photoAuteurActive) && $photoAuteurActive)
                            <span class="text-gray-700 font-medium flex items-center gap-1.5">
                                <span class="w-1.5 h-1.5 rounded-full bg-gray-900"></span>
                                Affichage activé par l'admin
                            </span>
                        @else
                            <span class="text-gray-500 font-medium flex items-center gap-1.5">
                                <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span>
                                Affichage désactivé par l'admin
                            </span>
                        @endif
                    </div>
                </div>

                {{-- Champ de sélection de fichier image --}}
                <div class="flex-1 min-w-0 w-full sm:w-auto">
                    <input 
                        type="file" 
                        id="input-photo-profil" 
                        name="photo_profil" 
                        accept="image/jpeg,image/png,image/webp"
                        class="block w-full text-xs text-gray-500 file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:uppercase file:bg-gray-900 file:text-white hover:file:bg-cargo-red file:cursor-pointer transition"
                    >
                    <p class="text-[10px] text-gray-400 mt-1 font-mono">JPG, PNG, WebP jusqu'à 4 Mo. Redimensionnée et recadrée automatiquement en vignette circulaire nette 40x40 px.</p>
                    @error('photo_profil')
                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            {{-- Pseudo / Nom d'affichage --}}
            <div>
                <label for="input-nom" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">
                    Nom de journaliste / Pseudo *
                </label>
                <input 
                    type="text" 
                    id="input-nom" 
                    name="name" 
                    value="{{ old('name', $utilisateur->name) }}" 
                    required 
                    placeholder="ex. Claire Delamare"
                    class="w-full text-sm px-3.5 py-2.5 bg-gray-50 border @error('name') border-red-400 @else border-gray-200 @enderror rounded-lg text-gray-900 focus:bg-white focus:outline-none focus:ring-1 focus:ring-cargo-red focus:border-cargo-red transition"
                >
                <p class="text-[11px] text-gray-400 mt-1">Ce nom apparaît comme signature d'auteur sur vos articles logistiques publiés.</p>
                @error('name')
                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Adresse e-mail --}}
            <div>
                <label for="input-email" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">
                    Adresse e-mail officielle *
                </label>
                <input 
                    type="email" 
                    id="input-email" 
                    name="email" 
                    value="{{ old('email', $utilisateur->email) }}" 
                    required 
                    placeholder="ex. claire.delamare@cargo-press.com"
                    class="w-full text-sm px-3.5 py-2.5 bg-gray-50 border @error('email') border-red-400 @else border-gray-200 @enderror rounded-lg text-gray-900 focus:bg-white focus:outline-none focus:ring-1 focus:ring-cargo-red focus:border-cargo-red transition"
                >
                <p class="text-[11px] text-gray-400 mt-1">Utilisée pour l'authentification à la rédaction, les notifications et les alertes éditoriales.</p>
                @error('email')
                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 pt-2">
            {{-- Téléphone professionnel --}}
            <div>
                <label for="input-telephone" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">
                    Ligne directe rédaction
                </label>
                <input 
                    type="text" 
                    id="input-telephone" 
                    name="telephone" 
                    value="{{ old('telephone', $utilisateur->telephone) }}" 
                    placeholder="ex. +33 1 42 68 00 00"
                    class="w-full text-sm px-3.5 py-2.5 bg-gray-50 border @error('telephone') border-red-400 @else border-gray-200 @enderror rounded-lg text-gray-900 focus:bg-white focus:outline-none focus:ring-1 focus:ring-cargo-red focus:border-cargo-red transition"
                >
                @error('telephone')
                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Rôle affiché --}}
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">
                    Rôle éditorial attribué
                </label>
                <div class="px-3.5 py-2.5 bg-gray-100 border border-gray-200 rounded-lg text-sm text-gray-700 font-mono flex items-center justify-between">
                    <span class="font-bold uppercase text-xs">{{ $utilisateur->role }}</span>
                    <span class="text-[10px] text-gray-500 uppercase">Géré par l'admin</span>
                </div>
            </div>
        </div>

        {{-- Biographie éditoriale --}}
        <div>
            <label for="input-bio" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">
                Biographie éditoriale / Spécialité
            </label>
            <textarea 
                id="input-bio" 
                name="bio" 
                rows="3" 
                placeholder="Grand reporter spécialisé dans les corridors maritimes et la numérisation portuaire..."
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
                <span>Enregistrer le profil</span>
            </button>
        </div>
    </form>
</div>
