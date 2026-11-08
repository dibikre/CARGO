{{-- Composant Modal / Formulaire d'Ajout d'une Formule d'Abonnement --}}
<div id="modal-nouvelle-formule" class="hidden fixed inset-0 z-50 overflow-y-auto bg-black/60 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-2xl max-w-2xl w-full overflow-hidden border border-gray-200 animate-fadeIn">
        <div class="px-6 py-4 bg-[#14151C] text-white flex items-center justify-between">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-full bg-cargo-red flex items-center justify-center text-white font-bold">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                </div>
                <div>
                    <h3 class="font-heading font-black text-lg">Create New Subscription Plan</h3>
                    <p class="text-xs text-gray-300">Route: POST /admin/abonnements/formules · Logged in JournalActivite</p>
                </div>
            </div>
            <button type="button" onclick="document.getElementById('modal-nouvelle-formule').classList.add('hidden')" class="text-gray-400 hover:text-white transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <form action="{{ route('admin.abonnements.formules.store') }}" method="POST" class="p-6 space-y-4 text-xs">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block font-bold text-gray-700 mb-1">Plan Name *</label>
                    <input type="text" name="nom" required placeholder="e.g. CARGO Pro Enterprise 5-Seats" class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:border-cargo-red">
                </div>

                <div>
                    <label class="block font-bold text-gray-700 mb-1">Duration (in Days) *</label>
                    <select name="duree_en_jours" required class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:border-cargo-red bg-white">
                        <option value="30">30 Days (1 Month)</option>
                        <option value="90">90 Days (Quarterly / 3 Months)</option>
                        <option value="180">180 Days (Semi-Annual / 6 Months)</option>
                        <option value="365" selected>365 Days (Annual / 1 Year)</option>
                        <option value="730">730 Days (Biennial / 2 Years)</option>
                    </select>
                </div>

                <div>
                    <label class="block font-bold text-gray-700 mb-1">Price excl. Tax (EUR HT) *</label>
                    <input type="number" step="0.01" min="0" name="prix_ht" required placeholder="e.g. 290.00" class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:border-cargo-red font-mono">
                </div>

                <div>
                    <label class="block font-bold text-gray-700 mb-1">VAT Rate (%)</label>
                    <input type="number" step="0.1" min="0" max="100" name="taux_tva" value="20.0" class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:border-cargo-red font-mono">
                </div>

                <div>
                    <label class="block font-bold text-gray-700 mb-1">Max Simultaneous Users / Seats</label>
                    <input type="number" min="1" max="500" name="nombre_utilisateurs_max" value="1" class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:border-cargo-red font-mono">
                </div>

                <div>
                    <label class="block font-bold text-gray-700 mb-1">Display Order</label>
                    <input type="number" min="0" name="ordre_affichage" value="1" class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:border-cargo-red font-mono">
                </div>
            </div>

            <div>
                <label class="block font-bold text-gray-700 mb-1">Plan Description / Target Audience</label>
                <textarea name="description" rows="2" placeholder="Summary of benefits, target audience, and editorial inclusions..." class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:border-cargo-red"></textarea>
            </div>

            <div class="pt-2 border-t border-gray-100 grid grid-cols-1 md:grid-cols-3 gap-3">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="acces_archives_illimite" value="1" checked class="rounded text-cargo-red focus:ring-cargo-red">
                    <span class="text-gray-800 font-semibold">Unlimited Archives</span>
                </label>

                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="acces_dossiers_exclusifs" value="1" checked class="rounded text-cargo-red focus:ring-cargo-red">
                    <span class="text-gray-800 font-semibold">Exclusive PRO Briefings</span>
                </label>

                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="est_actif" value="1" checked class="rounded text-cargo-red focus:ring-cargo-red">
                    <span class="text-gray-800 font-semibold">Activate Immediately</span>
                </label>
            </div>

            <div class="pt-4 border-t border-gray-200 flex items-center justify-end gap-3">
                <button type="button" onclick="document.getElementById('modal-nouvelle-formule').classList.add('hidden')" class="px-4 py-2 border border-gray-300 rounded text-gray-700 font-bold hover:bg-gray-50 transition">
                    Cancel
                </button>
                <button type="submit" class="px-6 py-2 bg-cargo-red hover:bg-cargo-redDark text-white font-bold rounded transition shadow">
                    Create Subscription Plan
                </button>
            </div>
        </form>
    </div>
</div>
