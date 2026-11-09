{{-- 
  Script client : Navigation dynamique sans rechargement de page (AJAX / Fetch)
  Sécurisée par jeton JWT lié au login et à l'empreinte de l'appareil
--}}
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const conteneur = document.getElementById('conteneurStatistiques');
    if (!conteneur) return;

    let jetonSecurite = conteneur.dataset.jeton || '';
    const urlApiBase = conteneur.dataset.urlApi || '/redaction/statistiques/api';
    const indicateurChargement = document.getElementById('indicateurChargement');
    const banniereAlerte = document.getElementById('banniereAlerteSecurite');
    const messageAlerte = document.getElementById('messageAlerteSecurite');

    // 1. Initialisation des graphiques Chart.js
    const labelsHebdo = @json($statistiquesSemaine['labels_jours']);
    const couleursHebdo = @json($statistiquesSemaine['couleurs_jours']);
    const donneesPubsHebdo = @json($statistiquesSemaine['donnees_publications']);
    const donneesVuesHebdo = @json($statistiquesSemaine['donnees_vues']);

    const labelsEvolution = @json($labelsGraphique);
    const donneesVuesEvolution = @json($vuesGraphique);
    const donneesPubsEvolution = @json($pubsGraphique);

    // Diagramme circulaire 1 : Publications hebdomadaires
    let chartCirculairePubs = null;
    const ctxCirculairePubs = document.getElementById('graphiqueCirculairePublications');
    if (ctxCirculairePubs) {
        chartCirculairePubs = new Chart(ctxCirculairePubs, {
            type: 'doughnut',
            data: {
                labels: labelsHebdo,
                datasets: [{
                    data: donneesPubsHebdo,
                    backgroundColor: couleursHebdo,
                    borderWidth: 2,
                    borderColor: '#FFFFFF',
                    hoverOffset: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '72%',
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#14151C',
                        titleFont: { size: 12, weight: 'bold' },
                        padding: 10,
                        callbacks: {
                            label: function(ctx) {
                                const val = ctx.parsed;
                                const tot = ctx.dataset.data.reduce((a, b) => a + b, 0);
                                const pct = tot > 0 ? Math.round((val / tot) * 100) : 0;
                                return ` ${val} articles (${pct}%)`;
                            }
                        }
                    }
                }
            }
        });
    }

    // Diagramme circulaire 2 : Vues hebdomadaires
    let chartCirculaireVues = null;
    const ctxCirculaireVues = document.getElementById('graphiqueCirculaireVues');
    if (ctxCirculaireVues) {
        chartCirculaireVues = new Chart(ctxCirculaireVues, {
            type: 'doughnut',
            data: {
                labels: labelsHebdo,
                datasets: [{
                    data: donneesVuesHebdo,
                    backgroundColor: couleursHebdo,
                    borderWidth: 2,
                    borderColor: '#FFFFFF',
                    hoverOffset: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '72%',
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#14151C',
                        titleFont: { size: 12, weight: 'bold' },
                        padding: 10,
                        callbacks: {
                            label: function(ctx) {
                                const val = ctx.parsed;
                                const tot = ctx.dataset.data.reduce((a, b) => a + b, 0);
                                const pct = tot > 0 ? Math.round((val / tot) * 100) : 0;
                                return ` ${val.toLocaleString()} views (${pct}%)`;
                            }
                        }
                    }
                }
            }
        });
    }

    // Graphique linéaire d'évolution des vues
    let chartEvolutionVues = null;
    const ctxVues = document.getElementById('graphiqueVues');
    if (ctxVues) {
        chartEvolutionVues = new Chart(ctxVues, {
            type: 'line',
            data: {
                labels: labelsEvolution,
                datasets: [{
                    label: 'Unique Views',
                    data: donneesVuesEvolution,
                    borderColor: '#FFC93C',
                    backgroundColor: 'rgba(255, 201, 60, 0.15)',
                    borderWidth: 2.5,
                    fill: true,
                    tension: 0.35,
                    pointBackgroundColor: '#14151C',
                    pointBorderColor: '#FFC93C',
                    pointBorderWidth: 2,
                    pointRadius: 3
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    x: { grid: { display: false }, ticks: { font: { size: 10 }, maxRotation: 45 } },
                    y: { beginAtZero: true, grid: { color: '#F1F3F5' }, ticks: { precision: 0, font: { size: 10 } } }
                }
            }
        });
    }

    // Graphique histogramme des publications
    let chartEvolutionPubs = null;
    const ctxPubs = document.getElementById('graphiquePublications');
    if (ctxPubs) {
        chartEvolutionPubs = new Chart(ctxPubs, {
            type: 'bar',
            data: {
                labels: labelsEvolution,
                datasets: [{
                    label: 'Articles Published',
                    data: donneesPubsEvolution,
                    backgroundColor: '#D4201A',
                    borderRadius: 3
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    x: { grid: { display: false }, ticks: { font: { size: 10 }, maxRotation: 45 } },
                    y: { beginAtZero: true, grid: { color: '#F1F3F5' }, ticks: { precision: 0, font: { size: 10 } } }
                }
            }
        });
    }

    // 2. Gestionnaire de navigation dynamique sans rechargement
    async function chargerDonneesPeriode(periode, majHistorique = true) {
        if (indicateurChargement) indicateurChargement.classList.remove('hidden');
        if (banniereAlerte) banniereAlerte.classList.add('hidden');

        // Mettre à jour l'apparence des boutons de période
        document.querySelectorAll('.bouton-periode').forEach(btn => {
            if (btn.dataset.periode === String(periode)) {
                btn.className = 'bouton-periode px-3 py-1.5 text-xs font-bold rounded bg-cargo-dark text-white transition';
            } else {
                btn.className = 'bouton-periode px-3 py-1.5 text-xs font-bold rounded text-gray-700 hover:bg-gray-100 transition';
            }
        });

        try {
            // URL sécurisée avec le secret JWT en paramètre
            const urlRequete = `${urlApiBase}?periode=${encodeURIComponent(periode)}&jeton_securite=${encodeURIComponent(jetonSecurite)}`;
            
            const reponse = await fetch(urlRequete, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            if (!reponse.ok) {
                const donneesErreur = await reponse.json().catch(() => ({}));
                throw new Error(donneesErreur.erreur || `HTTP error ${reponse.status}`);
            }

            const donnees = await reponse.json();
            if (!donnees.succes) {
                throw new Error(donnees.erreur || 'Failed to retrieve editorial statistics.');
            }

            // Rafraîchir le jeton de sécurité pour les appels suivants
            if (donnees.jeton_securite) {
                jetonSecurite = donnees.jeton_securite;
                conteneur.dataset.jeton = jetonSecurite;
            }

            // Mettre à jour les indicateurs KPIs
            const elVues = document.getElementById('kpiTotalVues');
            if (elVues && donnees.kpis) elVues.textContent = Number(donnees.kpis.total_vues).toLocaleString();

            const elPubs = document.getElementById('kpiPubsPeriode');
            if (elPubs && donnees.kpis) elPubs.textContent = donnees.kpis.publications_periode;

            const elJoursLib = document.getElementById('kpiJoursLibelle');
            if (elJoursLib) elJoursLib.textContent = `Articles published in past ${periode} days`;

            // Mettre à jour les courbes d'évolution temporelle
            if (chartEvolutionVues && donnees.evolution) {
                chartEvolutionVues.data.labels = donnees.evolution.labels;
                chartEvolutionVues.data.datasets[0].data = donnees.evolution.vues;
                chartEvolutionVues.update();
            }

            if (chartEvolutionPubs && donnees.evolution) {
                chartEvolutionPubs.data.labels = donnees.evolution.labels;
                chartEvolutionPubs.data.datasets[0].data = donnees.evolution.publications;
                chartEvolutionPubs.update();
            }

            // Mettre à jour l'historique du navigateur sans rechargement
            if (majHistorique) {
                const nouvelleUrl = new URL(window.location.href);
                nouvelleUrl.searchParams.set('periode', periode);
                window.history.pushState({ periode: periode }, '', nouvelleUrl.toString());
            }
        } catch (erreur) {
            console.error('Erreur API dynamique sécurisée :', erreur);
            if (banniereAlerte && messageAlerte) {
                messageAlerte.textContent = erreur.message || 'Security validation failure. Device or session mismatch.';
                banniereAlerte.classList.remove('hidden');
            }
        } finally {
            if (indicateurChargement) indicateurChargement.classList.add('hidden');
        }
    }

    // Interception des clics sur les filtres de période
    document.querySelectorAll('.bouton-periode').forEach(btn => {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            const periode = this.dataset.periode;
            chargerDonneesPeriode(periode, true);
        });
    });

    // Prise en charge du bouton Précédent/Suivant du navigateur sans rechargement
    window.addEventListener('popstate', function (e) {
        const params = new URLSearchParams(window.location.search);
        const periode = params.get('periode') || '30';
        chargerDonneesPeriode(periode, false);
    });
});
</script>
