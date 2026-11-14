{{-- Orchestrateur d'état, requêtes sécurisées JWT et navigation zéro rechargement --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    const rendu = window.RenduVideosCargo;
    const modales = window.ModalesVideosCargo;

    const etat = {
        recherche: @json($criteres['recherche'] ?? ''),
        plateforme: @json($criteres['plateforme'] ?? 'toutes'),
        statut: @json($criteres['statut'] ?? 'tous'),
        tri: @json($criteres['tri'] ?? 'recent'),
        page: {{ $videos->currentPage() }},
        vue: 'tableau',
        jeton: @json($jetonSecurite),
        selectionIds: new Set()
    };

    const tokenCsrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
    const construireUrlApi = (sous = '') => sous ? `/redaction/videos/api/${encodeURIComponent(etat.jeton)}/${sous}` : `/redaction/videos/api/${encodeURIComponent(etat.jeton)}`;

    async function chargerDonnees(mettreAJourHistorique = true) {
        const params = new URLSearchParams({
            recherche: etat.recherche,
            plateforme: etat.plateforme,
            statut: etat.statut,
            tri: etat.tri,
            page: etat.page
        });

        try {
            const reponse = await fetch(`${construireUrlApi()}?${params.toString()}`, {
                headers: { 'Accept': 'application/json', 'X-Jeton-Securite': etat.jeton, 'X-Requested-With': 'XMLHttpRequest' }
            });

            if (!reponse.ok) throw new Error('Échec de la vérification de sécurité API');
            const res = await reponse.json();

            if (res.jeton_securite) etat.jeton = res.jeton_securite;
            rendu.indicateurs(res.indicateurs);
            rendu.listes(res.donnees, res.pagination);

            etat.selectionIds.clear();
            const tout = document.getElementById('case-tout-selectionner');
            if (tout) tout.checked = false;
            actualiserBoutonSelection();

            if (mettreAJourHistorique) {
                window.history.pushState({ ...etat }, '', `/redaction/videos?${params.toString()}`);
            }
        } catch (e) {
            rendu.toast('Erreur de jeton de sécurité. Navigation dynamique verrouillée.', 'erreur');
        }
    }

    function actualiserBoutonSelection() {
        const btn = document.getElementById('bouton-suppression-groupee');
        const cpt = document.getElementById('compteur-selection');
        if (!btn || !cpt) return;
        cpt.textContent = etat.selectionIds.size;
        btn.classList.toggle('hidden', etat.selectionIds.size === 0);
        btn.classList.toggle('inline-flex', etat.selectionIds.size > 0);
    }

    // Recherche instantanée avec temporisation
    let chronoRecherche = null;
    const inputRecherche = document.getElementById('champ-recherche-video');
    const btnEffacer = document.getElementById('bouton-effacer-recherche');

    inputRecherche?.addEventListener('input', function (e) {
        clearTimeout(chronoRecherche);
        const val = e.target.value.trim();
        btnEffacer?.classList.toggle('hidden', val.length === 0);
        chronoRecherche = setTimeout(() => {
            etat.recherche = val;
            etat.page = 1;
            chargerDonnees();
        }, 220);
    });

    btnEffacer?.addEventListener('click', function () {
        if (inputRecherche) inputRecherche.value = '';
        btnEffacer.classList.add('hidden');
        etat.recherche = '';
        etat.page = 1;
        chargerDonnees();
    });

    document.addEventListener('keydown', function (e) {
        if (e.key === '/' && document.activeElement !== inputRecherche && !['INPUT', 'TEXTAREA'].includes(document.activeElement.tagName)) {
            e.preventDefault();
            inputRecherche?.focus();
        }
    });

    // Filtres plateforme
    document.querySelectorAll('.filtre-plateforme').forEach(btn => {
        btn.addEventListener('click', function () {
            document.querySelectorAll('.filtre-plateforme').forEach(b => {
                b.className = 'filtre-plateforme px-3 py-1.5 rounded-lg border transition bg-gray-50 text-gray-700 border-gray-200 hover:bg-gray-100';
            });
            this.className = 'filtre-plateforme px-3 py-1.5 rounded-lg border transition bg-cargo-dark text-white border-cargo-dark shadow-sm';
            etat.plateforme = this.dataset.plateforme;
            etat.page = 1;
            chargerDonnees();
        });
    });

    document.getElementById('select-filtre-statut')?.addEventListener('change', e => { etat.statut = e.target.value; etat.page = 1; chargerDonnees(); });
    document.getElementById('select-tri-video')?.addEventListener('change', e => { etat.tri = e.target.value; etat.page = 1; chargerDonnees(); });

    // Bascule d'affichage
    const btnVueTab = document.getElementById('bouton-vue-tableau');
    const btnVueGri = document.getElementById('bouton-vue-grille');
    const vueTab = document.getElementById('conteneur-vue-tableau');
    const vueGri = document.getElementById('conteneur-vue-grille');

    function basculerVue(vue) {
        etat.vue = vue;
        const estTab = vue === 'tableau';
        if (btnVueTab) btnVueTab.className = estTab ? 'px-2.5 py-1.5 rounded-md text-xs font-bold uppercase tracking-wider transition flex items-center gap-1 bg-white text-gray-900 shadow-sm' : 'px-2.5 py-1.5 rounded-md text-xs font-bold uppercase tracking-wider transition flex items-center gap-1 text-gray-500 hover:text-gray-900';
        if (btnVueGri) btnVueGri.className = !estTab ? 'px-2.5 py-1.5 rounded-md text-xs font-bold uppercase tracking-wider transition flex items-center gap-1 bg-white text-gray-900 shadow-sm' : 'px-2.5 py-1.5 rounded-md text-xs font-bold uppercase tracking-wider transition flex items-center gap-1 text-gray-500 hover:text-gray-900';
        vueTab?.classList.toggle('hidden', !estTab);
        vueGri?.classList.toggle('hidden', estTab);
    }
    btnVueTab?.addEventListener('click', () => basculerVue('tableau'));
    btnVueGri?.addEventListener('click', () => basculerVue('grille'));

    // Initialisation des modales avec délégation d'enregistrement
    modales.init(async function (donnees) {
        try {
            const rep = await fetch(construireUrlApi(), {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': tokenCsrf, 'X-Jeton-Securite': etat.jeton, 'Accept': 'application/json', 'Content-Type': 'application/json' },
                body: JSON.stringify(donnees)
            });
            const res = await rep.json();
            if (rep.ok && res.succes) {
                rendu.toast(res.message);
                etat.page = 1;
                chargerDonnees();
                return { succes: true };
            }
            return { succes: false, erreur: res.message || 'Erreur de validation' };
        } catch (e) {
            return { succes: false, erreur: 'Impossible de joindre l\'API sécurisée.' };
        }
    });

    // Délégation d'événements
    document.addEventListener('click', async function (e) {
        const btnPage = e.target.closest('.bouton-page-nav');
        if (btnPage && !btnPage.disabled) {
            const p = parseInt(btnPage.dataset.page);
            if (p && p !== etat.page) { etat.page = p; chargerDonnees(); window.scrollTo({ top: 120, behavior: 'smooth' }); }
            return;
        }

        const btnStatut = e.target.closest('.bouton-basculer-statut');
        if (btnStatut) {
            const id = btnStatut.dataset.id;
            btnStatut.style.opacity = '0.5';
            try {
                const rep = await fetch(construireUrlApi(`${id}/basculer`), {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': tokenCsrf, 'X-Jeton-Securite': etat.jeton, 'Accept': 'application/json' }
                });
                const r = await rep.json();
                if (r.succes) {
                    if (r.jeton_securite) {
                        etat.jeton = r.jeton_securite;
                    }
                    rendu.toast(r.message);
                    await chargerDonnees(false);
                } else {
                    rendu.toast(r.erreur || 'Échec de la mise à jour du statut', 'erreur');
                    btnStatut.style.opacity = '1';
                }
            } catch (err) {
                rendu.toast('Échec de la mise à jour du statut', 'erreur');
                btnStatut.style.opacity = '1';
            }
            return;
        }

        const btnApercu = e.target.closest('.bouton-declencher-apercu');
        if (btnApercu) {
            modales.ouvrirApercu({
                urlIframe: btnApercu.dataset.urlIframe,
                titre: btnApercu.dataset.titre,
                source: btnApercu.dataset.source,
                duree: btnApercu.dataset.duree,
                description: btnApercu.dataset.description,
                codeIframe: btnApercu.dataset.codeIframe
            });
            return;
        }

        const btnCopier = e.target.closest('.bouton-copier-code');
        if (btnCopier) {
            const code = decodeURIComponent(btnCopier.dataset.code || '');
            if (code) {
                navigator.clipboard.writeText(code).then(() => rendu.toast('Code HTML copié dans le presse-papiers'))
                    .catch(() => rendu.toast('Impossible de copier le code', 'erreur'));
            }
            return;
        }

        const btnSuppr = e.target.closest('.bouton-demander-suppression');
        if (btnSuppr) {
            const id = parseInt(btnSuppr.dataset.id);
            modales.demanderSuppression('Supprimer la vidéo embarquée', `Supprimer « ${btnSuppr.dataset.titre} » ?`, async () => {
                const rep = await fetch(construireUrlApi(id), { method: 'DELETE', headers: { 'X-CSRF-TOKEN': tokenCsrf, 'X-Jeton-Securite': etat.jeton, 'Accept': 'application/json' } });
                const res = await rep.json();
                if (res.succes) {
                    if (res.jeton_securite) etat.jeton = res.jeton_securite;
                    rendu.toast(res.message);
                    chargerDonnees(false);
                } else {
                    rendu.toast(res.erreur || 'Échec de la suppression', 'erreur');
                }
            });
            return;
        }

        if (e.target.classList.contains('case-selection-video')) {
            const id = parseInt(e.target.value);
            if (e.target.checked) etat.selectionIds.add(id); else etat.selectionIds.delete(id);
            actualiserBoutonSelection();
        }
    });

    document.getElementById('case-tout-selectionner')?.addEventListener('change', function (e) {
        document.querySelectorAll('.case-selection-video').forEach(cb => {
            cb.checked = e.target.checked;
            const id = parseInt(cb.value);
            if (e.target.checked) etat.selectionIds.add(id); else etat.selectionIds.delete(id);
        });
        actualiserBoutonSelection();
    });

    document.getElementById('bouton-suppression-groupee')?.addEventListener('click', function () {
        if (etat.selectionIds.size === 0) return;
        const ids = Array.from(etat.selectionIds);
        modales.demanderSuppression('Suppression groupée', `Supprimer définitivement les ${ids.length} vidéos sélectionnées ?`, async () => {
            const rep = await fetch(construireUrlApi('supprimer-lot'), {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': tokenCsrf, 'X-Jeton-Securite': etat.jeton, 'Accept': 'application/json', 'Content-Type': 'application/json' },
                body: JSON.stringify({ ids })
            });
            const res = await rep.json();
            if (res.succes) {
                if (res.jeton_securite) etat.jeton = res.jeton_securite;
                rendu.toast(res.message);
                chargerDonnees(false);
            } else {
                rendu.toast(res.erreur || 'Échec de la suppression groupée', 'erreur');
            }
        });
    });

    window.addEventListener('popstate', e => {
        if (e.state) { Object.assign(etat, e.state); chargerDonnees(false); }
    });
});
</script>
