{{-- Modale de confirmation sécurisée de suppression de compte rédacteur --}}
@if($suppressionCompteActive)
<div id="modale-suppression-compte" class="fixed inset-0 z-50 hidden overflow-y-auto bg-black/60 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="relative w-full max-w-lg bg-white rounded-xl shadow-2xl border border-gray-200 overflow-hidden transform transition-all">
        {{-- En-tête --}}
        <div class="bg-gray-900 text-white px-6 py-4 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-cargo-red"></span>
                <h3 class="font-heading font-black text-sm uppercase tracking-wider">Confirm Account Deletion</h3>
            </div>
            <button type="button" id="bouton-fermer-modale-suppression" class="text-gray-400 hover:text-white transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        {{-- Formulaire de confirmation avec saisie du mot de passe --}}
        <form action="{{ route('redaction.parametres.supprimer-compte') }}" method="POST" class="p-6 space-y-4">
            @csrf
            @method('DELETE')

            <div class="flex items-start gap-3 p-3 bg-red-50 border border-red-200 rounded-lg text-xs text-red-900">
                <svg class="w-5 h-5 text-cargo-red shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                <div>
                    <strong class="block font-bold uppercase mb-0.5">Are you absolutely sure?</strong>
                    <span>This action is final. You will be logged out immediately and your credentials will be deactivated.</span>
                </div>
            </div>

            <div>
                <label for="input-confirmation-suppression-mdp" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">
                    Enter Your Password to Confirm *
                </label>
                <input 
                    type="password" 
                    id="input-confirmation-suppression-mdp" 
                    name="mot_de_passe_confirmation" 
                    required 
                    placeholder="Type your password to verify your identity"
                    class="w-full text-sm px-3.5 py-2.5 bg-gray-50 border border-gray-300 rounded-lg text-gray-900 focus:bg-white focus:outline-none focus:ring-1 focus:ring-cargo-red focus:border-cargo-red transition"
                >
            </div>

            <div class="flex items-center justify-end gap-3 pt-3 border-t border-gray-100">
                <button 
                    type="button" 
                    id="bouton-annuler-modale-suppression" 
                    class="px-4 py-2 text-xs font-bold uppercase tracking-wider text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition cursor-pointer"
                >
                    Cancel
                </button>
                <button 
                    type="submit" 
                    style="background-color: #D4201A; color: #ffffff;"
                    class="px-5 py-2 text-xs font-heading font-bold uppercase tracking-wider bg-[#D4201A] hover:bg-[#B3140F] rounded-lg shadow-sm transition flex items-center gap-2 cursor-pointer"
                >
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    <span>Permanently Delete Account</span>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const modale = document.getElementById('modale-suppression-compte');
        const btnOuvrir = document.getElementById('bouton-ouvrir-modale-suppression');
        const btnFermer = document.getElementById('bouton-fermer-modale-suppression');
        const btnAnnuler = document.getElementById('bouton-annuler-modale-suppression');

        const ouvrir = () => modale?.classList.remove('hidden');
        const fermer = () => modale?.classList.add('hidden');

        btnOuvrir?.addEventListener('click', ouvrir);
        btnFermer?.addEventListener('click', fermer);
        btnAnnuler?.addEventListener('click', fermer);
        modale?.addEventListener('click', (e) => {
            if (e.target === modale) fermer();
        });
    });
</script>
@endif
