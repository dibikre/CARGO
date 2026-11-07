/**
 * Diaporama Hero CARGO — Animation de passation en fondu (fade) toutes les 7 secondes
 * Gestion du carrousel d'articles à la une, transitions fluides sans déplacement de texte.
 */
document.addEventListener('DOMContentLoaded', function () {
    const sectionDiaporama = document.getElementById('diaporama-hero-section');
    if (!sectionDiaporama) return;

    const fonds = sectionDiaporama.querySelectorAll('.hero-fond-slide');
    const textes = sectionDiaporama.querySelectorAll('.hero-texte-slide');
    const onglets = sectionDiaporama.querySelectorAll('.hero-onglet-bouton');

    const total = fonds.length;
    if (total <= 1) return;

    let indexCourant = 0;
    let progressionTimer = null;
    const intervalleMs = 50;
    let tempsEcouleMs = 0;
    let estEnPause = false;
    const dureeCycleMs = 7000;

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

        // Transition pure en opacité sur les textes superposés à coordonnées fixes
        // Supprime tout saut de hauteur ou déplacement brusque de la mise en page
        textes.forEach((txt, i) => {
            if (i === nouveauIndex) {
                txt.style.opacity = '1';
                txt.style.pointerEvents = 'auto';
                txt.style.zIndex = '2';
            } else {
                txt.style.opacity = '0';
                txt.style.pointerEvents = 'none';
                txt.style.zIndex = '1';
            }
        });

        // Mise à jour visuelle des onglets d'édition latéraux (01 à 05)
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

    // Sélection manuelle au clic direct sur un article numéroté
    onglets.forEach(btn => {
        btn.addEventListener('click', function () {
            const indexVoulu = parseInt(this.getAttribute('data-hero-index'), 10);
            activerSlide(indexVoulu);
        });
    });

    // Mise en pause ergonomique au survol de la souris sur le hero
    sectionDiaporama.addEventListener('mouseenter', () => {
        estEnPause = true;
    });
    sectionDiaporama.addEventListener('mouseleave', () => {
        estEnPause = false;
    });

    demarrerMinuteurProgression();
});
