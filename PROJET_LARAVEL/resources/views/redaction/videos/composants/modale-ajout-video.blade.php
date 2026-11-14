{{-- Modale d'ajout rapide d'une vidéo embarquée --}}
<div id="modale-ajout-video" class="fixed inset-0 z-50 hidden overflow-y-auto bg-black/60 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="relative w-full max-w-xl bg-white rounded-xl shadow-2xl border border-gray-200 overflow-hidden transform transition-all">
        {{-- En-tête de la modale --}}
        <div class="bg-cargo-dark text-white px-6 py-4 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-cargo-red"></span>
                <h3 class="font-heading font-black text-base uppercase tracking-wider">Ajouter une vidéo embarquée</h3>
            </div>
            <button type="button" id="bouton-fermer-modale-ajout" class="text-gray-400 hover:text-white transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        {{-- Formulaire --}}
        <form id="formulaire-ajout-video" class="p-6 space-y-4">
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">Titre de la vidéo *</label>
                <input 
                    type="text" 
                    id="input-ajout-titre" 
                    required 
                    placeholder="ex. Terminaux à conteneurs automatisés : opérations à Maasvlakte II"
                    class="w-full text-sm px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-gray-900 focus:outline-none focus:ring-1 focus:ring-cargo-red focus:border-cargo-red"
                >
            </div>

            <div>
                <div class="flex items-center justify-between mb-1">
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-700">URL d'intégration, lien direct ou code &lt;iframe&gt; *</label>
                    <span id="indicateur-detection-plateforme" class="text-[11px] font-mono text-gray-400">Tout site web pris en charge</span>
                </div>
                <input 
                    type="text" 
                    id="input-ajout-url" 
                    required 
                    placeholder="Collez un lien ou un code iframe (YouTube, Vimeo, Dailymotion, Loom, CDN ou autre site...)"
                    class="w-full text-sm px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-gray-900 focus:outline-none focus:ring-1 focus:ring-cargo-red focus:border-cargo-red"
                >
                <span class="block text-[11px] text-gray-400 mt-1">Accepte tout site : plateformes de streaming vidéo, CDN d'entreprise privés, lecteurs multimédias ou code HTML &lt;iframe&gt; complet.</span>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">Source / Chaîne</label>
                    <input 
                        type="text" 
                        id="input-ajout-source" 
                        placeholder="ex. Port de Rotterdam"
                        class="w-full text-sm px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-gray-900 focus:outline-none focus:ring-1 focus:ring-cargo-red focus:border-cargo-red"
                    >
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">Durée</label>
                    <input 
                        type="text" 
                        id="input-ajout-duree" 
                        placeholder="ex. 06:45"
                        class="w-full text-sm px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-gray-900 focus:outline-none focus:ring-1 focus:ring-cargo-red focus:border-cargo-red"
                    >
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">Synopsis / Notes de contexte</label>
                <textarea 
                    id="input-ajout-description" 
                    rows="3" 
                    placeholder="Bref contexte éditorial sur les opérations logistiques présentées..."
                    class="w-full text-sm px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-gray-900 focus:outline-none focus:ring-1 focus:ring-cargo-red focus:border-cargo-red"
                ></textarea>
            </div>

            <div class="flex items-center gap-2 pt-1">
                <input type="checkbox" id="input-ajout-actif" checked class="rounded border-gray-300 text-cargo-red focus:ring-cargo-red">
                <label for="input-ajout-actif" class="text-xs font-bold uppercase tracking-wider text-gray-700 cursor-pointer">
                    Afficher immédiatement dans la colonne média de l'accueil
                </label>
            </div>

            <div id="erreur-formulaire-ajout" class="hidden p-3 bg-red-50 border border-red-200 rounded-lg text-xs text-red-700"></div>

            <div class="flex items-center justify-end gap-3 pt-3 border-t border-gray-100">
                <button 
                    type="button" 
                    id="bouton-annuler-ajout" 
                    class="px-4 py-2 text-xs font-bold uppercase tracking-wider text-gray-700 bg-gray-100 hover:bg-gray-200 rounded transition"
                >
                    Annuler
                </button>
                <button 
                    type="submit" 
                    id="bouton-soumettre-ajout" 
                    style="background-color: #D4201A; color: #ffffff;"
                    class="px-5 py-2 text-xs font-bold uppercase tracking-wider text-white bg-[#D4201A] hover:bg-[#B3140F] rounded shadow-sm transition flex items-center gap-2"
                >
                    <svg id="spinner-ajout" class="hidden animate-spin w-3.5 h-3.5 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
                    <span>Publier la vidéo</span>
                </button>
            </div>
        </form>
    </div>
</div>
