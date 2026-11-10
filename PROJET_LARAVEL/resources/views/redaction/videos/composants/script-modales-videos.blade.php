{{-- Gestionnaire des fenêtres modales : Ajout, Prévisualisation et Suppression --}}
<script>
window.ModalesVideosCargo = (function () {
    let rappelSuppression = null;

    function initApercu() {
        const mod = document.getElementById('modale-apercu-video');
        const ifr = document.getElementById('apercu-iframe');
        const fermer = () => {
            if (ifr) ifr.src = '';
            mod?.classList.add('hidden');
        };

        document.getElementById('bouton-fermer-modale-apercu')?.addEventListener('click', fermer);
        mod?.addEventListener('click', e => { if (e.target === e.currentTarget) fermer(); });
    }

    function ouvrirApercu(donnees) {
        const mod = document.getElementById('modale-apercu-video');
        const ifr = document.getElementById('apercu-iframe');
        if (!mod || !ifr) return;

        ifr.src = donnees.urlIframe;
        document.getElementById('apercu-titre').textContent = donnees.titre;
        document.getElementById('apercu-source').textContent = donnees.source || 'Editorial Field Video';
        document.getElementById('apercu-duree').textContent = donnees.duree || '';
        document.getElementById('apercu-description').textContent = donnees.description || 'No contextual synopsis provided.';
        document.getElementById('bouton-copier-code-modale').dataset.code = donnees.codeIframe;
        mod.classList.remove('hidden');
    }

    function initAjout(actionEnregistrer) {
        const mod = document.getElementById('modale-ajout-video');
        document.getElementById('bouton-ouvrir-modale-ajout')?.addEventListener('click', () => mod?.classList.remove('hidden'));
        document.getElementById('bouton-fermer-modale-ajout')?.addEventListener('click', () => mod?.classList.add('hidden'));
        document.getElementById('bouton-annuler-ajout')?.addEventListener('click', () => mod?.classList.add('hidden'));

        const inputUrl = document.getElementById('input-ajout-url');
        const badgeDet = document.getElementById('indicateur-detection-plateforme');
        inputUrl?.addEventListener('input', e => {
            const v = e.target.value.trim().toLowerCase();
            if (!badgeDet) return;
            if (!v) {
                badgeDet.textContent = 'Any website supported';
            } else if (v.includes('youtube') || v.includes('youtu.be')) {
                badgeDet.textContent = 'YouTube stream detected';
            } else if (v.includes('vimeo')) {
                badgeDet.textContent = 'Vimeo video detected';
            } else if (v.includes('dailymotion') || v.includes('dai.ly')) {
                badgeDet.textContent = 'Dailymotion player detected';
            } else if (v.includes('loom.com')) {
                badgeDet.textContent = 'Loom video detected';
            } else if (v.includes('<iframe')) {
                badgeDet.textContent = 'HTML <iframe> code detected (Any site)';
            } else if (v.startsWith('http://') || v.startsWith('https://')) {
                try {
                    const host = new URL(e.target.value.trim()).hostname;
                    badgeDet.textContent = `Universal Web Embed (${host})`;
                } catch (err) {
                    badgeDet.textContent = 'Universal Web Embed accepted';
                }
            } else {
                badgeDet.textContent = 'Custom embed link accepted';
            }
        });

        document.getElementById('formulaire-ajout-video')?.addEventListener('submit', async function (e) {
            e.preventDefault();
            const spinner = document.getElementById('spinner-ajout');
            const errZone = document.getElementById('erreur-formulaire-ajout');
            spinner?.classList.remove('hidden');
            errZone?.classList.add('hidden');

            const donnees = {
                titre: document.getElementById('input-ajout-titre')?.value,
                url_embed: inputUrl?.value,
                source: document.getElementById('input-ajout-source')?.value,
                duree: document.getElementById('input-ajout-duree')?.value,
                description: document.getElementById('input-ajout-description')?.value,
                est_actif: document.getElementById('input-ajout-actif')?.checked ? 1 : 0
            };

            const resultat = await actionEnregistrer(donnees);
            spinner?.classList.add('hidden');
            if (resultat && resultat.succes) {
                mod?.classList.add('hidden');
                document.getElementById('formulaire-ajout-video')?.reset();
            } else if (resultat) {
                errZone.textContent = resultat.erreur || 'Validation error while saving video embed.';
                errZone.classList.remove('hidden');
            }
        });
    }

    function initSuppression() {
        const mod = document.getElementById('modale-confirmation-suppression');
        document.getElementById('bouton-annuler-suppression')?.addEventListener('click', () => {
            mod?.classList.add('hidden');
            rappelSuppression = null;
        });

        document.getElementById('bouton-confirmer-suppression')?.addEventListener('click', async () => {
            if (typeof rappelSuppression === 'function') {
                const spinner = document.getElementById('spinner-suppression');
                spinner?.classList.remove('hidden');
                await rappelSuppression();
                spinner?.classList.add('hidden');
                mod?.classList.add('hidden');
                rappelSuppression = null;
            }
        });
    }

    function demanderSuppression(titre, message, actionConfirmer) {
        const mod = document.getElementById('modale-confirmation-suppression');
        document.getElementById('titre-confirmation-suppression').textContent = titre;
        document.getElementById('message-confirmation-suppression').textContent = message;
        rappelSuppression = actionConfirmer;
        mod?.classList.remove('hidden');
    }

    return {
        init: function (actionEnregistrer) {
            initApercu();
            initAjout(actionEnregistrer);
            initSuppression();
        },
        ouvrirApercu,
        demanderSuppression
    };
})();
</script>
