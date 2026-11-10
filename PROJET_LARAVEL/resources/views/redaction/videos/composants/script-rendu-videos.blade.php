{{-- Moteur de rendu dynamique du DOM pour la vidéothèque --}}
<script>
window.RenduVideosCargo = (function () {
    function afficherToast(message, type = 'succes') {
        const toast = document.createElement('div');
        toast.className = `fixed bottom-5 right-5 z-50 px-4 py-3 rounded-lg shadow-lg text-xs font-bold uppercase tracking-wider flex items-center gap-2 transition-all transform translate-y-2 opacity-0 ${type === 'succes' ? 'bg-cargo-dark text-white border-l-4 border-cargo-red' : 'bg-red-600 text-white'}`;
        toast.innerHTML = `<span>${message}</span>`;
        document.body.appendChild(toast);
        setTimeout(() => toast.classList.remove('translate-y-2', 'opacity-0'), 10);
        setTimeout(() => {
            toast.classList.add('translate-y-2', 'opacity-0');
            setTimeout(() => toast.remove(), 300);
        }, 3200);
    }

    function actualiserIndicateurs(ind) {
        if (!ind) return;
        const total = document.getElementById('kpi-total-videos');
        const actifs = document.getElementById('kpi-actifs-videos');
        const yt = document.getElementById('kpi-youtube-videos');
        const vim = document.getElementById('kpi-vimeo-videos');
        if (total) total.textContent = ind.total;
        if (actifs) actifs.textContent = ind.actifs;
        if (yt) yt.textContent = ind.youtube;
        if (vim) vim.textContent = (ind.vimeo || 0) + (ind.autres || 0);
    }

    function genererLigneTableau(v) {
        const minHtml = v.url_miniature 
            ? `<img src="${v.url_miniature}" alt="${v.titre}" class="w-full h-full object-cover group-hover:scale-105 transition">`
            : `<div class="w-full h-full bg-cargo-dark flex items-center justify-center text-gray-500"><svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/></svg></div>`;

        return `
            <tr class="hover:bg-gray-50/80 transition ligne-video" data-id="${v.id}" id="ligne-video-${v.id}">
                <td class="py-3 px-4 text-center">
                    <input type="checkbox" value="${v.id}" class="case-selection-video rounded border-gray-300 text-cargo-red focus:ring-cargo-red cursor-pointer">
                </td>
                <td class="py-3 px-4">
                    <div class="flex items-start gap-3">
                        <div class="relative w-20 h-12 bg-gray-900 rounded overflow-hidden flex-shrink-0 group cursor-pointer bouton-declencher-apercu" data-id="${v.id}" data-titre="${v.titre}" data-url-iframe="${v.url_iframe}" data-source="${v.source}" data-duree="${v.duree || ''}" data-code-iframe="${encodeURIComponent(v.code_iframe)}">
                            ${minHtml}
                            <div class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-70 group-hover:opacity-100 transition">
                                <div style="background-color: #D4201A;" class="w-6 h-6 rounded-full text-white flex items-center justify-center shadow"><svg class="w-3 h-3 ml-0.5" fill="currentColor" viewBox="0 0 20 20"><path d="M6.3 2.841A1.5 1.5 0 004 4.11V15.89a1.5 1.5 0 002.3 1.269l9.344-5.89a1.5 1.5 0 000-2.538L6.3 2.84z"/></svg></div>
                            </div>
                        </div>
                        <div class="min-w-0 flex-1">
                            <button type="button" class="text-left font-bold text-gray-900 hover:text-cargo-red transition text-sm leading-snug line-clamp-1 bouton-declencher-apercu" data-id="${v.id}" data-titre="${v.titre}" data-url-iframe="${v.url_iframe}" data-source="${v.source}" data-duree="${v.duree || ''}" data-code-iframe="${encodeURIComponent(v.code_iframe)}">${v.titre}</button>
                            <div class="flex items-center gap-2 mt-1 text-xs text-gray-500">
                                <span>${v.date_creation || ''}</span>
                                ${v.description ? `<span>&bull;</span><span class="truncate max-w-xs text-gray-400">${v.description}</span>` : ''}
                            </div>
                        </div>
                    </div>
                </td>
                <td class="py-3 px-4">
                    <span class="px-2 py-0.5 rounded text-[11px] font-bold uppercase tracking-wider bg-gray-100 text-gray-700">${v.plateforme}</span>
                    ${v.duree ? `<span class="block text-xs font-mono text-gray-500 mt-1">${v.duree}</span>` : ''}
                </td>
                <td class="py-3 px-4 text-xs font-medium text-gray-700">${v.source}</td>
                <td class="py-3 px-4 text-center">
                    <button type="button" style="${v.est_actif ? 'background-color: #D4201A;' : 'background-color: #D1D5DB;'}" class="bouton-basculer-statut relative inline-flex h-5 w-9 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 focus:outline-none" data-id="${v.id}" data-actif="${v.est_actif ? '1' : '0'}">
                        <span class="pointer-events-none inline-block h-4 w-4 transform rounded-full bg-white shadow transition duration-200 ${v.est_actif ? 'translate-x-4' : 'translate-x-0'}"></span>
                    </button>
                </td>
                <td class="py-3 px-4 text-right">
                    <div class="flex items-center justify-end gap-1.5">
                        <button type="button" class="bouton-copier-code p-1.5 text-gray-500 hover:text-gray-900 hover:bg-gray-100 rounded transition" data-code="${encodeURIComponent(v.code_iframe)}" title="Copy HTML snippet"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg></button>
                        <button type="button" class="bouton-declencher-apercu p-1.5 text-gray-500 hover:text-cargo-dark hover:bg-gray-100 rounded transition" data-id="${v.id}" data-titre="${v.titre}" data-url-iframe="${v.url_iframe}" data-source="${v.source}" data-duree="${v.duree || ''}" data-code-iframe="${encodeURIComponent(v.code_iframe)}" title="Preview player"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg></button>
                        <button type="button" class="bouton-demander-suppression p-1.5 text-gray-400 hover:text-cargo-red hover:bg-red-50 rounded transition" data-id="${v.id}" data-titre="${v.titre}" title="Delete"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button>
                    </div>
                </td>
            </tr>`;
    }

    function genererCarteGrille(v) {
        const minHtml = v.url_miniature 
            ? `<img src="${v.url_miniature}" alt="${v.titre}" class="w-full h-full object-cover group-hover:scale-105 transition">`
            : `<div class="w-full h-full bg-cargo-dark flex items-center justify-center text-gray-500"><svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/></svg></div>`;

        return `
            <div class="carte-video bg-white rounded-xl border border-gray-200 overflow-hidden shadow-sm flex flex-col justify-between" data-id="${v.id}" id="carte-video-${v.id}">
                <div>
                    <div class="relative aspect-video bg-gray-900 overflow-hidden cursor-pointer group bouton-declencher-apercu" data-id="${v.id}" data-titre="${v.titre}" data-url-iframe="${v.url_iframe}" data-source="${v.source}" data-duree="${v.duree || ''}" data-code-iframe="${encodeURIComponent(v.code_iframe)}">
                        ${minHtml}
                        <div class="absolute inset-0 bg-black/30 group-hover:bg-black/50 flex items-center justify-center transition">
                            <div style="background-color: #D4201A;" class="w-10 h-10 rounded-full text-white flex items-center justify-center shadow-lg group-hover:scale-110 transition"><svg class="w-5 h-5 ml-0.5" fill="currentColor" viewBox="0 0 20 20"><path d="M6.3 2.841A1.5 1.5 0 004 4.11V15.89a1.5 1.5 0 002.3 1.269l9.344-5.89a1.5 1.5 0 000-2.538L6.3 2.84z"/></svg></div>
                        </div>
                        ${v.duree ? `<span class="absolute bottom-2 right-2 bg-black/80 text-white text-[10px] font-mono font-bold px-1.5 py-0.5 rounded">${v.duree}</span>` : ''}
                        <span class="absolute top-2 left-2 bg-black/70 text-white text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded">${v.plateforme}</span>
                    </div>
                    <div class="p-4 space-y-2">
                        <span class="text-xs font-bold uppercase tracking-wider text-cargo-red block">${v.source}</span>
                        <h3 class="font-heading font-black text-gray-900 text-sm leading-snug line-clamp-2 hover:text-cargo-red cursor-pointer transition bouton-declencher-apercu" data-id="${v.id}" data-titre="${v.titre}" data-url-iframe="${v.url_iframe}" data-source="${v.source}" data-duree="${v.duree || ''}" data-code-iframe="${encodeURIComponent(v.code_iframe)}">${v.titre}</h3>
                        ${v.description ? `<p class="text-xs text-gray-500 line-clamp-2">${v.description}</p>` : ''}
                    </div>
                </div>
                <div class="px-4 py-3 bg-gray-50 border-t border-gray-100 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <button type="button" style="${v.est_actif ? 'background-color: #D4201A;' : 'background-color: #D1D5DB;'}" class="bouton-basculer-statut relative inline-flex h-4 w-7 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 focus:outline-none" data-id="${v.id}" data-actif="${v.est_actif ? '1' : '0'}">
                            <span class="pointer-events-none inline-block h-3 w-3 transform rounded-full bg-white shadow transition duration-200 ${v.est_actif ? 'translate-x-3' : 'translate-x-0'}"></span>
                        </button>
                        <span class="text-[11px] font-bold uppercase text-gray-500">${v.est_actif ? 'Active' : 'Hidden'}</span>
                    </div>
                    <div class="flex items-center gap-1">
                        <button type="button" class="bouton-copier-code p-1 text-gray-500 hover:text-gray-900 rounded transition" data-code="${encodeURIComponent(v.code_iframe)}" title="Copy HTML"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg></button>
                        <button type="button" class="bouton-demander-suppression p-1 text-gray-400 hover:text-cargo-red rounded transition" data-id="${v.id}" data-titre="${v.titre}" title="Delete"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button>
                    </div>
                </div>
            </div>`;
    }

    function afficherPagination(pag, conteneurId) {
        const conteneur = document.getElementById(conteneurId);
        if (!conteneur) return;
        if (pag.derniere_page <= 1) { conteneur.innerHTML = ''; return; }

        let html = `
            <button type="button" class="bouton-page-nav px-3 py-1.5 rounded border text-xs font-bold uppercase transition flex items-center gap-1 ${pag.page_courante <= 1 ? 'border-gray-200 text-gray-300 cursor-not-allowed' : 'border-gray-300 text-gray-700 hover:bg-gray-50'}" data-page="${pag.page_courante - 1}" ${pag.page_courante <= 1 ? 'disabled' : ''}>
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg><span>Prev</span>
            </button>`;

        for (let i = 1; i <= pag.derniere_page; i++) {
            if (i === pag.page_courante) {
                html += `<button type="button" class="bouton-page-nav px-3 py-1.5 rounded border text-xs font-bold uppercase bg-cargo-dark text-white border-cargo-dark shadow-sm" data-page="${i}">${i}</button>`;
            } else if (i <= 3 || i >= pag.derniere_page - 1 || Math.abs(i - pag.page_courante) <= 1) {
                html += `<button type="button" class="bouton-page-nav px-3 py-1.5 rounded border text-xs font-bold uppercase bg-white text-gray-700 border-gray-300 hover:bg-gray-50 transition" data-page="${i}">${i}</button>`;
            } else if (i === 4 && pag.derniere_page > 5) {
                html += `<span class="px-1 text-gray-400 text-xs">...</span>`;
            }
        }

        html += `
            <button type="button" class="bouton-page-nav px-3 py-1.5 rounded border text-xs font-bold uppercase transition flex items-center gap-1 ${pag.page_courante >= pag.derniere_page ? 'border-gray-200 text-gray-300 cursor-not-allowed' : 'border-gray-300 text-gray-700 hover:bg-gray-50'}" data-page="${pag.page_courante + 1}" ${pag.page_courante >= pag.derniere_page ? 'disabled' : ''}>
                <span>Next</span><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </button>`;

        conteneur.innerHTML = html;
    }

    function actualiserListes(donnees, pag) {
        const corps = document.getElementById('corps-tableau-videos');
        if (corps) {
            corps.innerHTML = (!donnees || donnees.length === 0)
                ? `<tr><td colspan="6" class="py-12 text-center text-gray-500 font-bold">No video embeds found matching criteria.</td></tr>`
                : donnees.map(genererLigneTableau).join('');
        }
        const grille = document.getElementById('conteneur-vue-grille');
        if (grille) {
            grille.innerHTML = (!donnees || donnees.length === 0)
                ? `<div class="col-span-full py-12 text-center text-gray-500 font-bold bg-white rounded-xl border border-gray-100">No video embeds found.</div>`
                : donnees.map(genererCarteGrille).join('');
        }
        const de = document.getElementById('pagination-de');
        const a = document.getElementById('pagination-a');
        const total = document.getElementById('pagination-total');
        if (de) de.textContent = pag.de || 0;
        if (a) a.textContent = pag.a || 0;
        if (total) total.textContent = pag.total || 0;
        afficherPagination(pag, 'conteneur-liens-pagination');
    }

    return {
        toast: afficherToast,
        indicateurs: actualiserIndicateurs,
        ligneTableau: genererLigneTableau,
        carteGrille: genererCarteGrille,
        pagination: afficherPagination,
        listes: actualiserListes
    };
})();
</script>
