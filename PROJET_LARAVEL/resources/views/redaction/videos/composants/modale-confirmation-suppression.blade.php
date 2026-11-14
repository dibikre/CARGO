{{-- Modale de confirmation de suppression pour suppression unitaire ou par lot --}}
<div id="modale-confirmation-suppression" class="fixed inset-0 z-50 hidden overflow-y-auto bg-black/60 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="relative w-full max-w-md bg-white rounded-xl shadow-2xl border border-gray-200 overflow-hidden transform transition-all">
        <div class="p-6 text-center space-y-4">
            <div class="w-12 h-12 rounded-full bg-red-100 text-cargo-red mx-auto flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
            </div>

            <div>
                <h3 class="font-heading font-black text-lg text-gray-900 tracking-tight" id="titre-confirmation-suppression">Confirmer la suppression</h3>
                <p class="text-xs text-gray-500 mt-2" id="message-confirmation-suppression">
                    Voulez-vous vraiment supprimer définitivement cette vidéo embarquée du catalogue éditorial ?
                </p>
            </div>

            <div class="flex items-center justify-center gap-3 pt-2">
                <button 
                    type="button" 
                    id="bouton-annuler-suppression" 
                    class="px-4 py-2 text-xs font-bold uppercase tracking-wider text-gray-700 bg-gray-100 hover:bg-gray-200 rounded transition"
                >
                    Annuler
                </button>
                <button 
                    type="button" 
                    id="bouton-confirmer-suppression" 
                    style="background-color: #D4201A; color: #ffffff;"
                    class="px-5 py-2 text-xs font-bold uppercase tracking-wider text-white bg-[#D4201A] hover:bg-[#B3140F] rounded shadow-sm transition flex items-center gap-1.5"
                >
                    <svg id="spinner-suppression" class="hidden animate-spin w-3.5 h-3.5 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
                    <span>Supprimer définitivement</span>
                </button>
            </div>
        </div>
    </div>
</div>
