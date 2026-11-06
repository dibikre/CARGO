/**
 * Diaporama Hero CARGO — Animation de passation en fondu (fade) toutes les 7 secondes
 * Gestion du carrousel d'articles à la une, des images d'arrière-plan, et des barres de progression.
 */
document.addEventListener('DOMContentLoaded', function () {
    const sectionDiaporama = document.getElementById('diaporama-hero-section');
    if (!sectionDiaporama) return;

    const fonds = sectionDiaporama.querySelectorAll('.hero-fond-slide');
    const textes = sectionDiaporama.querySelectorAll('.hero-texte-slide');
    const onglets = sectionDiaporama.querySelectorAll('.hero-onglet-bouton');
    const compteurActuel = document.getElementById('hero-compteur-actuel');
    const btnPrecedent = document.getElementById('hero-btn-precedent');
    const btnSuivant = document.getElementById('hero-btn-suivant');
    const btnPause = document.getElementById('hero-btn-pause');
    const iconePause = document.getElementById('hero-icone-pause');
    const iconePlay = document.getElementById('hero-icone-play');

    const total = fonds.length;
    if (total <= 1) return;

    let indexCourant = 0;
    let progressionTimer = null;
    const intervalleMs = 50;
    let tempsEcouleMs = 0;
    let estEnPause = false;
    const dureeCycleMs = 7000;

    function formaterIndex(valeur) {
        return (valeur + 1).toString().padStart(2, '0');
    }

    function activerSlide(nouveauIndex) {
        if (nouveauIndex === indexCourant && fonds[indexCourant].style.opacity === '1') return;

        // Transition en fondu (fade) sur les images d'arrière-plan
        fonds.forEach((fond, i) => {
            if (i === nouveauIndex) {
                fond.style.opacity = '1';
                fond.style.transform = 'scale(1)';
            } else {
                fond.style.opacity = '0';
                fond.style.transform = 'scale(1.04)';
            }
        });

        // Transition en fondu sur les textes et titres
        textes.forEach((txt, i) => {
            if (i === nouveauIndex) {
                txt.style.display = 'flex';
                setTimeout(() => { txt.style.opacity = '1'; }, 20);
            } else {
                txt.style.opacity = '0';
                setTimeout(() => { if (i !== nouveauIndex) txt.style.display = 'none'; }, 500);
            }
        });

        // Mise à jour visuelle des 5 onglets latéraux (01 à 05)
        onglets.forEach((onglet, i) => {
            const barre = onglet.querySelector('.hero-barre-progression');
            const titre = onglet.querySelector('.dsp');
            const chiffre = onglet.querySelector('b');

            if (i === nouveauIndex) {
                onglet.style.background = 'rgba(255,255,255,0.08)';
                if (titre) { titre.style.color = '#ffffff'; titre.style.fontWeight = '800'; }
                if (chiffre) { chiffre.style.color = 'var(--yellow)'; }
                if (barre) { barre.style.width = '0%'; }
            } else {
                onglet.style.background = 'transparent';
                if (titre) { titre.style.color = '#AEB3C0'; titre.style.fontWeight = '600'; }
                if (chiffre) { chiffre.style.color = '#7C8191'; }
                if (barre) { barre.style.width = '0%'; }
            }
        });

        indexCourant = nouveauIndex;
        if (compteurActuel) {
            compteurActuel.textContent = formaterIndex(indexCourant);
        }

        tempsEcouleMs = 0;
    }

    function demarrerMinuteurProgression() {
        arreterMinuteurProgression();
        tempsEcouleMs = 0;

        progressionTimer = setInterval(() => {
            if (estEnPause) return;

            tempsEcouleMs += intervalleMs;
            const pourcentage = Math.min(100, (tempsEcouleMs / dureeCycleMs) * 100);

            const barreActive = onglets[indexCourant]?.querySelector('.hero-barre-progression');
            if (barreActive) {
                barreActive.style.width = pourcentage + '%';
            }

            if (tempsEcouleMs >= dureeCycleMs) {
                const prochainIndex = (indexCourant + 1) % total;
                activerSlide(prochainIndex);
            }
        }, intervalleMs);
    }

    function arreterMinuteurProgression() {
        if (progressionTimer) {
            clearInterval(progressionTimer);
            progressionTimer = null;
        }
    }

    function passerArticleSuivant() {
        const prochain = (indexCourant + 1) % total;
        activerSlide(prochain);
    }

    function passerArticlePrecedent() {
        const precedent = (indexCourant - 1 + total) % total;
        activerSlide(precedent);
    }

    // Écouteurs de clics directs sur les onglets numérotés
    onglets.forEach(btn => {
        btn.addEventListener('click', function () {
            const indexVoulu = parseInt(this.getAttribute('data-hero-index'), 10);
            activerSlide(indexVoulu);
        });
    });

    if (btnSuivant) btnSuivant.addEventListener('click', passerArticleSuivant);
    if (btnPrecedent) btnPrecedent.addEventListener('click', passerArticlePrecedent);

    if (btnPause) {
        btnPause.addEventListener('click', function () {
            estEnPause = !estEnPause;
            if (estEnPause) {
                iconePause.style.display = 'none';
                iconePlay.style.display = 'block';
                btnPause.setAttribute('aria-label', 'Play automatic slide transition');
            } else {
                iconePause.style.display = 'block';
                iconePlay.style.display = 'none';
                btnPause.setAttribute('aria-label', 'Pause automatic slide transition');
            }
        });
    }

    // Mise en pause ergonomique au survol de la souris pour faciliter la lecture
    sectionDiaporama.addEventListener('mouseenter', () => {
        estEnPause = true;
    });
    sectionDiaporama.addEventListener('mouseleave', () => {
        if (iconePause.style.display !== 'none') {
            estEnPause = false;
        }
    });

    demarrerMinuteurProgression();
});
