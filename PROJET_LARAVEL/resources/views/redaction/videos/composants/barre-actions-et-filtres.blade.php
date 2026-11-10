{{-- Barre d'outils, recherche instantanée et filtres pour la vidéothèque --}}
<div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4 space-y-4">
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
        {{-- Champ de recherche plein texte instantané --}}
        <div class="relative flex-1 max-w-lg">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>
            <input 
                type="text" 
                id="champ-recherche-video"
                value="{{ $criteres['recherche'] ?? '' }}"
                placeholder="Search by title, source channel, or synopsis..."
                class="w-full pl-9 pr-12 py-2 text-sm bg-gray-50 border border-gray-200 rounded-lg text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-1 focus:ring-cargo-red focus:border-cargo-red transition"
            >
            <div class="absolute inset-y-0 right-0 pr-3 flex items-center gap-1.5">
                <button type="button" id="bouton-effacer-recherche" class="hidden text-gray-400 hover:text-gray-600 transition" title="Clear search">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
                <kbd class="hidden sm:inline-block px-1.5 py-0.5 text-[10px] font-mono text-gray-400 bg-gray-100 border border-gray-200 rounded">/</kbd>
            </div>
        </div>

        {{-- Actions globales et bouton d'ajout --}}
        <div class="flex flex-wrap items-center gap-3">
            {{-- Bouton de suppression groupée (masqué tant qu'aucune sélection n'est active) --}}
            <button 
                type="button" 
                id="bouton-suppression-groupee" 
                style="background-color: #D4201A; color: #ffffff;"
                class="hidden items-center gap-1.5 px-3 py-2 text-xs font-bold uppercase tracking-wider text-white bg-[#D4201A] hover:bg-[#B3140F] rounded shadow-sm transition"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
                <span>Delete Selected (<span id="compteur-selection">0</span>)</span>
            </button>

            {{-- Bascule d'affichage Tableau / Grille --}}
            <div class="inline-flex rounded-lg border border-gray-200 bg-gray-50 p-0.5">
                <button 
                    type="button" 
                    id="bouton-vue-tableau" 
                    class="px-2.5 py-1.5 rounded-md text-xs font-bold uppercase tracking-wider transition flex items-center gap-1 bg-white text-gray-900 shadow-sm"
                    title="Dense Table View"
                >
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                    </svg>
                    <span class="hidden sm:inline">Table</span>
                </button>
                <button 
                    type="button" 
                    id="bouton-vue-grille" 
                    class="px-2.5 py-1.5 rounded-md text-xs font-bold uppercase tracking-wider transition flex items-center gap-1 text-gray-500 hover:text-gray-900"
                    title="Card Grid View"
                >
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                    </svg>
                    <span class="hidden sm:inline">Grid</span>
                </button>
            </div>

            {{-- Bouton d'ajout d'une vidéo --}}
            <button 
                type="button" 
                id="bouton-ouvrir-modale-ajout" 
                style="background-color: #D4201A; color: #ffffff;"
                class="bg-[#D4201A] hover:bg-[#B3140F] text-white font-bold text-xs uppercase tracking-wider px-3.5 py-2 rounded shadow-sm transition flex items-center gap-1.5"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                <span>Add Video Embed</span>
            </button>
        </div>
    </div>

    {{-- Ligne de filtres segmentés : Plateformes, Statut et Tri --}}
    <div class="flex flex-wrap items-center justify-between gap-3 pt-3 border-t border-gray-100">
        {{-- Filtres de plateformes --}}
        <div class="flex flex-wrap items-center gap-1.5 text-xs font-bold uppercase tracking-wider" id="groupe-filtres-plateforme">
            <button type="button" data-plateforme="toutes" class="filtre-plateforme px-3 py-1.5 rounded-lg border transition bg-cargo-dark text-white border-cargo-dark">All</button>
            <button type="button" data-plateforme="youtube" class="filtre-plateforme px-3 py-1.5 rounded-lg border transition bg-gray-50 text-gray-700 border-gray-200 hover:bg-gray-100">YouTube</button>
            <button type="button" data-plateforme="vimeo" class="filtre-plateforme px-3 py-1.5 rounded-lg border transition bg-gray-50 text-gray-700 border-gray-200 hover:bg-gray-100">Vimeo</button>
            <button type="button" data-plateforme="autre" class="filtre-plateforme px-3 py-1.5 rounded-lg border transition bg-gray-50 text-gray-700 border-gray-200 hover:bg-gray-100">Other Iframe</button>
        </div>

        {{-- Filtre de statut et tri --}}
        <div class="flex flex-wrap items-center gap-3">
            <div class="flex items-center gap-1 text-xs">
                <span class="text-gray-400 font-bold uppercase tracking-wider">Status:</span>
                <select id="select-filtre-statut" class="bg-gray-50 border border-gray-200 text-gray-800 text-xs rounded-lg px-2 py-1.5 font-medium focus:outline-none focus:border-cargo-red">
                    <option value="tous">All Statuses</option>
                    <option value="actif">Active on Homepage</option>
                    <option value="inactif">Hidden from Homepage</option>
                </select>
            </div>

            <div class="flex items-center gap-1 text-xs">
                <span class="text-gray-400 font-bold uppercase tracking-wider">Sort:</span>
                <select id="select-tri-video" class="bg-gray-50 border border-gray-200 text-gray-800 text-xs rounded-lg px-2 py-1.5 font-medium focus:outline-none focus:border-cargo-red">
                    <option value="recent">Newest First</option>
                    <option value="ancien">Oldest First</option>
                    <option value="titre_az">Title (A-Z)</option>
                    <option value="titre_za">Title (Z-A)</option>
                </select>
            </div>
        </div>
    </div>
</div>
