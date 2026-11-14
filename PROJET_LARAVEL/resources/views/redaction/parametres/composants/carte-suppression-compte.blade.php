{{-- Carte de suppression de compte : Uniquement visible si activée par l'administrateur --}}
@if($suppressionCompteActive)
<div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
    <div class="px-6 py-4 bg-gray-50/75 border-b border-gray-200 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="w-8 h-8 rounded-lg bg-gray-900 text-white flex items-center justify-center">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
            </div>
            <div>
                <h3 class="font-heading font-black text-sm uppercase tracking-wider text-gray-900">Zone sensible : Supprimer le compte éditorial</h3>
                <p class="text-xs text-gray-500">Suppression autonome du compte (Activée par l'administrateur)</p>
            </div>
        </div>
        <span class="px-2.5 py-1 text-[11px] font-bold uppercase tracking-wider bg-gray-100 text-gray-700 rounded border border-gray-200">
            Option active
        </span>
    </div>

    <div class="p-6 space-y-4">
        <div class="p-4 bg-gray-50 border border-gray-200 rounded-lg text-xs text-gray-700 space-y-2">
            <div class="font-bold uppercase tracking-wider text-gray-900 flex items-center gap-2">
                <svg class="w-4 h-4 text-cargo-red shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                <span>Action permanente et irréversible</span>
            </div>
            <p class="text-gray-600">
                La suppression de votre compte révoquera immédiatement tout accès à l'espace rédaction CARGO. Vos brouillons et publications resteront archivés selon les règles d'archivage éditorial.
            </p>
            <p class="text-[11px] text-gray-500 font-mono">
                Remarque : vous devrez saisir votre mot de passe pour confirmer la suppression définitive.
            </p>
        </div>

        <div class="flex items-center justify-between pt-2">
            <span class="text-xs text-gray-500">
                Pour continuer, cliquez sur le bouton afin d'ouvrir la confirmation sécurisée.
            </span>
            <button 
                type="button" 
                id="bouton-ouvrir-modale-suppression"
                style="background-color: #D4201A; color: #ffffff;"
                class="px-5 py-2.5 text-xs font-heading font-bold uppercase tracking-wider bg-[#D4201A] hover:bg-[#B3140F] rounded-lg shadow-sm transition flex items-center gap-2 cursor-pointer"
            >
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                <span>Supprimer le compte</span>
            </button>
        </div>
    </div>
</div>
@endif
