{{-- Modale de prévisualisation à la demande (n'instancie l'iframe qu'à l'ouverture) --}}
<div id="modale-apercu-video" class="fixed inset-0 z-50 hidden overflow-y-auto bg-black/75 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="relative w-full max-w-3xl bg-white rounded-xl shadow-2xl border border-gray-200 overflow-hidden transform transition-all">
        {{-- En-tête --}}
        <div class="bg-cargo-dark text-white px-6 py-4 flex items-center justify-between">
            <div class="flex items-center gap-2 truncate pr-4">
                <span class="w-2.5 h-2.5 rounded-full bg-cargo-red flex-shrink-0"></span>
                <h3 id="apercu-titre" class="font-heading font-black text-sm uppercase tracking-wider truncate">Aperçu vidéo</h3>
            </div>
            <button type="button" id="bouton-fermer-modale-apercu" class="text-gray-400 hover:text-white transition flex-shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        {{-- Lecteur iframe monté dynamiquement --}}
        <div class="relative bg-black" style="padding-top: 56.25%;">
            <iframe id="apercu-iframe" src="" title="Aperçu vidéo" class="absolute inset-0 w-full h-full border-0" allowfullscreen allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"></iframe>
        </div>

        {{-- Métadonnées et actions --}}
        <div class="p-6 bg-white space-y-4">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 pb-3 border-b border-gray-100">
                <div>
                    <span id="apercu-source" class="text-xs font-bold uppercase tracking-wider text-cargo-red">Vidéo de terrain</span>
                    <span id="apercu-duree" class="text-xs font-mono text-gray-500 ml-2"></span>
                </div>

                <div class="flex items-center gap-2">
                    <button 
                        type="button" 
                        id="bouton-copier-code-modale" 
                        class="px-3 py-1.5 text-xs font-bold uppercase tracking-wider text-gray-700 bg-gray-100 hover:bg-gray-200 rounded transition flex items-center gap-1.5"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                        <span>Copier le code HTML</span>
                    </button>
                </div>
            </div>

            <div class="text-xs text-gray-600 space-y-1">
                <span class="font-bold uppercase tracking-wider text-gray-400 block text-[11px]">Synopsis éditorial</span>
                <p id="apercu-description" class="leading-relaxed">Aucun synopsis fourni.</p>
            </div>
        </div>
    </div>
</div>
