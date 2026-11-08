{{-- Carte d'une Formule d'Abonnement avec Édition, Suppression & Bascule --}}
<div class="border border-gray-200 rounded-lg p-5 flex flex-col justify-between bg-white shadow-xs hover:border-gray-300 transition">
    <div>
        <div class="flex items-start justify-between gap-2">
            <div>
                <span class="text-[10px] font-mono font-bold text-gray-400 uppercase">#{{ $formule->ordre_affichage }} · SLUG: {{ $formule->slug }}</span>
                <h3 class="font-bold text-base text-cargo-dark font-heading mt-0.5">{{ $formule->nom }}</h3>
            </div>
            <span class="text-[10px] px-2 py-0.5 rounded font-bold uppercase tracking-wider shrink-0 {{ $formule->est_actif ? 'bg-emerald-100 text-emerald-800' : 'bg-gray-200 text-gray-600' }}">
                {{ $formule->est_actif ? 'Active' : 'Inactive' }}
            </span>
        </div>

        <div class="text-2xl font-black font-heading text-cargo-dark my-2">
            {{ number_format($formule->prix_ht, 2, ',', ' ') }} € <span class="text-xs font-normal text-gray-500 font-sans">HT / {{ $formule->duree_en_jours }} days</span>
            <span class="block text-[11px] font-normal text-gray-400 font-mono">TTC: {{ number_format($formule->prix_ttc, 2, ',', ' ') }} € (TVA {{ $formule->taux_tva }}%)</span>
        </div>

        @if($formule->description)
            <p class="text-xs text-gray-600 mb-3">{{ $formule->description }}</p>
        @endif

        <div class="flex flex-wrap gap-1.5 mb-3 text-[11px]">
            <span class="px-2 py-0.5 bg-gray-100 text-gray-700 rounded font-medium">
                {{ $formule->nombre_utilisateurs_max }} seat{{ $formule->nombre_utilisateurs_max > 1 ? 's' : '' }}
            </span>
            @if($formule->acces_archives_illimite)
                <span class="px-2 py-0.5 bg-blue-50 text-blue-700 rounded font-medium">Archives</span>
            @endif
            @if($formule->acces_dossiers_exclusifs)
                <span class="px-2 py-0.5 bg-purple-50 text-purple-700 rounded font-medium">PRO Briefings</span>
            @endif
        </div>
    </div>

    <div class="pt-3 border-t border-gray-100 flex flex-col gap-2.5">
        <div class="flex items-center justify-between text-xs text-gray-500">
            <span class="font-semibold">{{ $formule->abonnements_count }} active/past subscriber(s)</span>
            
            {{-- Toggle status form --}}
            <form action="{{ route('admin.abonnements.formules.basculer', $formule->id) }}" method="POST">
                @csrf
                <button type="submit" class="font-bold text-[11px] uppercase tracking-wider transition {{ $formule->est_actif ? 'text-amber-700 hover:text-amber-900' : 'text-emerald-700 hover:text-emerald-900' }}">
                    {{ $formule->est_actif ? 'Deactivate' : 'Activate' }}
                </button>
            </form>
        </div>

        {{-- Actions: Edit & Delete --}}
        <div class="flex items-center justify-end gap-2 pt-1 border-t border-gray-50">
            <button type="button" onclick="document.getElementById('modal-edition-formule-{{ $formule->id }}').classList.remove('hidden')" class="px-2.5 py-1 text-xs font-bold text-gray-700 hover:text-cargo-dark bg-gray-100 hover:bg-gray-200 rounded transition flex items-center gap-1">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                Edit
            </button>

            @if($formule->abonnements_count > 0)
                <span class="text-[11px] text-gray-400 italic" title="Cannot delete: plan is referenced by {{ $formule->abonnements_count }} subscriber records. Deactivate it instead.">
                    Protected (Linked)
                </span>
            @else
                <form action="{{ route('admin.abonnements.formules.destroy', $formule->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to permanently delete subscription plan \'{{ addslashes($formule->nom) }}\'?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-2.5 py-1 text-xs font-bold text-red-600 hover:text-red-800 hover:bg-red-50 rounded transition flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        Delete
                    </button>
                </form>
            @endif
        </div>
    </div>
</div>

{{-- Modal d'Édition de cette Formule --}}
<div id="modal-edition-formule-{{ $formule->id }}" class="hidden fixed inset-0 z-50 overflow-y-auto bg-black/60 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-2xl max-w-2xl w-full overflow-hidden border border-gray-200 animate-fadeIn">
        <div class="px-6 py-4 bg-[#14151C] text-white flex items-center justify-between">
            <div>
                <h3 class="font-heading font-black text-lg">Edit Subscription Plan: {{ $formule->nom }}</h3>
                <p class="text-xs text-gray-300">ID: {{ $formule->id }} · {{ $formule->abonnements_count }} subscriber record(s)</p>
            </div>
            <button type="button" onclick="document.getElementById('modal-edition-formule-{{ $formule->id }}').classList.add('hidden')" class="text-gray-400 hover:text-white transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <form action="{{ route('admin.abonnements.formules.update', $formule->id) }}" method="POST" class="p-6 space-y-4 text-xs">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block font-bold text-gray-700 mb-1">Plan Name *</label>
                    <input type="text" name="nom" required value="{{ old('nom', $formule->nom) }}" class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:border-cargo-red">
                </div>

                <div>
                    <label class="block font-bold text-gray-700 mb-1">Duration (in Days) *</label>
                    <select name="duree_en_jours" required class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:border-cargo-red bg-white">
                        <option value="30" {{ $formule->duree_en_jours == 30 ? 'selected' : '' }}>30 Days (1 Month)</option>
                        <option value="90" {{ $formule->duree_en_jours == 90 ? 'selected' : '' }}>90 Days (Quarterly)</option>
                        <option value="180" {{ $formule->duree_en_jours == 180 ? 'selected' : '' }}>180 Days (Semi-Annual)</option>
                        <option value="365" {{ $formule->duree_en_jours == 365 ? 'selected' : '' }}>365 Days (1 Year)</option>
                        <option value="730" {{ $formule->duree_en_jours == 730 ? 'selected' : '' }}>730 Days (2 Years)</option>
                    </select>
                </div>

                <div>
                    <label class="block font-bold text-gray-700 mb-1">Price excl. Tax (EUR HT) *</label>
                    <input type="number" step="0.01" min="0" name="prix_ht" required value="{{ old('prix_ht', $formule->prix_ht) }}" class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:border-cargo-red font-mono">
                </div>

                <div>
                    <label class="block font-bold text-gray-700 mb-1">VAT Rate (%)</label>
                    <input type="number" step="0.1" min="0" max="100" name="taux_tva" value="{{ old('taux_tva', $formule->taux_tva) }}" class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:border-cargo-red font-mono">
                </div>

                <div>
                    <label class="block font-bold text-gray-700 mb-1">Max Simultaneous Seats</label>
                    <input type="number" min="1" max="500" name="nombre_utilisateurs_max" value="{{ old('nombre_utilisateurs_max', $formule->nombre_utilisateurs_max) }}" class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:border-cargo-red font-mono">
                </div>

                <div>
                    <label class="block font-bold text-gray-700 mb-1">Display Order</label>
                    <input type="number" min="0" name="ordre_affichage" value="{{ old('ordre_affichage', $formule->ordre_affichage) }}" class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:border-cargo-red font-mono">
                </div>
            </div>

            <div>
                <label class="block font-bold text-gray-700 mb-1">Description</label>
                <textarea name="description" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:border-cargo-red">{{ old('description', $formule->description) }}</textarea>
            </div>

            <div class="pt-2 border-t border-gray-100 grid grid-cols-1 md:grid-cols-3 gap-3">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="acces_archives_illimite" value="1" {{ $formule->acces_archives_illimite ? 'checked' : '' }} class="rounded text-cargo-red focus:ring-cargo-red">
                    <span class="text-gray-800 font-semibold">Unlimited Archives</span>
                </label>

                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="acces_dossiers_exclusifs" value="1" {{ $formule->acces_dossiers_exclusifs ? 'checked' : '' }} class="rounded text-cargo-red focus:ring-cargo-red">
                    <span class="text-gray-800 font-semibold">Exclusive PRO Briefings</span>
                </label>

                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="est_actif" value="1" {{ $formule->est_actif ? 'checked' : '' }} class="rounded text-cargo-red focus:ring-cargo-red">
                    <span class="text-gray-800 font-semibold">Active Plan</span>
                </label>
            </div>

            <div class="pt-4 border-t border-gray-200 flex items-center justify-end gap-3">
                <button type="button" onclick="document.getElementById('modal-edition-formule-{{ $formule->id }}').classList.add('hidden')" class="px-4 py-2 border border-gray-300 rounded text-gray-700 font-bold hover:bg-gray-50 transition">
                    Cancel
                </button>
                <button type="submit" class="px-6 py-2 bg-cargo-red hover:bg-cargo-redDark text-white font-bold rounded transition shadow">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>
