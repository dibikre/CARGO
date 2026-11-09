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

    const labelsHebdo = @json($statistiquesSemaine['labels_jours']);
    const couleursHebdo = @json($statistiquesSemaine['couleurs_jours']);
    const donneesPubsHebdo = @json($statistiquesSemaine['donnees_publications']);
    const donneesVuesHebdo = @json($statistiquesSemaine['donnees_vues']);

    const labelsEvolution = @json($labelsGraphique);
    const donneesVuesEvolution = @json($vuesGraphique);
    const donneesPubsEvolution = @json($pubsGraphique);

    // 1. Initialisation des diagrammes circulaires
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

    // 2. Initialisation des courbes d'évolution temporelle
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
                    borderColor: '#14151C',
                    backgroundColor: 'rgba(20, 21, 28, 0.05)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.3,
                    pointBackgroundColor: '#14151C',
                    pointRadius: 2.5
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    x: { grid: { display: false }, ticks: { font: { size: 10 } } },
                    y: { beginAtZero: true, grid: { color: '#F1F3F5' }, ticks: { precision: 0, font: { size: 10 } } }
                }
            }
        });
    }

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
                    borderRadius: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    x: { grid: { display: false }, ticks: { font: { size: 10 } } },
                    y: { beginAtZero: true, grid: { color: '#F1F3F5' }, ticks: { precision: 0, font: { size: 10 } } }
                }
            }
        });
    }

    // 3. Navigation dynamique sans rechargement de page
    async function chargerDonneesPeriode(periode, majHistorique = true) {
        if (indicateurChargement) indicateurChargement.classList.remove('hidden');
        if (banniereAlerte) banniereAlerte.classList.add('hidden');

        document.querySelectorAll('.bouton-periode').forEach(btn => {
            if (btn.dataset.periode === String(periode)) {
                btn.className = 'bouton-periode px-4 py-2 font-bold text-xs uppercase tracking-wider transition bg-cargo-dark text-white';
            } else {
                btn.className = 'bouton-periode px-4 py-2 font-bold text-xs uppercase tracking-wider transition bg-white text-gray-800 border border-gray-300 hover:bg-gray-50';
            }
        });

        try {
            const urlRequete = `${urlApiBase}?periode=${encodeURIComponent(periode)}&jeton_securite=${encodeURIComponent(jetonSecurite)}`;
            const reponse = await fetch(urlRequete, {
                method: 'GET',
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
            });

            if (!reponse.ok) {
                const donneesErreur = await reponse.json().catch(() => ({}));
                throw new Error(donneesErreur.erreur || `HTTP error ${reponse.status}`);
            }

            const donnees = await reponse.json();
            if (!donnees.succes) throw new Error(donnees.erreur || 'Failed to retrieve stats.');

            if (donnees.jeton_securite) {
                jetonSecurite = donnees.jeton_securite;
                conteneur.dataset.jeton = jetonSecurite;
            }

            const elVues = document.getElementById('kpiTotalVues');
            if (elVues && donnees.kpis) elVues.textContent = Number(donnees.kpis.total_vues).toLocaleString();

            const elPubs = document.getElementById('kpiPubsPeriode');
            if (elPubs && donnees.kpis) elPubs.textContent = donnees.kpis.publications_periode;

            const elJoursLib = document.getElementById('kpiJoursLibelle');
            if (elJoursLib) elJoursLib.textContent = `Articles published in past ${periode} days`;

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

    document.querySelectorAll('.bouton-periode').forEach(btn => {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            chargerDonneesPeriode(this.dataset.periode, true);
        });
    });

    window.addEventListener('popstate', function () {
        const params = new URLSearchParams(window.location.search);
        chargerDonneesPeriode(params.get('periode') || '30', false);
    });
});
</script>
