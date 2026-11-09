@extends('redaction.layouts.redaction')

@section('titre', 'Audience & Publication Analytics — CARGO Editorial')

@section('contenu')
<div class="space-y-6">
    {{-- En-tête avec sélecteur de période --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 border-b border-gray-200">
        <div>
            <div class="flex items-center gap-2">
                <span class="inline-block w-2.5 h-2.5 rounded-full bg-cargo-accent"></span>
                <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">Editorial Performance & Readership</span>
            </div>
            <h1 class="text-2xl font-heading font-black text-gray-900 tracking-tight mt-1">Audience & Publications Analytics</h1>
        </div>

        {{-- Commutateur de période d'analyse --}}
        <div class="flex items-center bg-white border border-gray-300 rounded p-1 shadow-sm">
            <span class="text-xs font-semibold text-gray-500 px-3 uppercase">Period:</span>
            <a href="{{ route('redaction.statistiques.index', ['periode' => '7']) }}" class="px-3 py-1.5 text-xs font-bold rounded {{ $periode === '7' ? 'bg-cargo-dark text-white' : 'text-gray-700 hover:bg-gray-100' }}">Last 7 Days</a>
            <a href="{{ route('redaction.statistiques.index', ['periode' => '30']) }}" class="px-3 py-1.5 text-xs font-bold rounded {{ $periode === '30' ? 'bg-cargo-dark text-white' : 'text-gray-700 hover:bg-gray-100' }}">Last 30 Days</a>
            <a href="{{ route('redaction.statistiques.index', ['periode' => '90']) }}" class="px-3 py-1.5 text-xs font-bold rounded {{ $periode === '90' ? 'bg-cargo-dark text-white' : 'text-gray-700 hover:bg-gray-100' }}">Last 90 Days</a>
        </div>
    </div>

    {{-- 1. Cartes des métriques clés --}}
    @include('redaction.statistiques.composants.kpis-principaux')

    {{-- 2. Diagrammes circulaires hebdomadaires (depuis lundi) : Volume de publication & Nombre de vues --}}
    @include('redaction.statistiques.composants.diagrammes-circulaires-hebdomadaires')

    {{-- 3. Statistique des articles les plus vus de chaque jour de la semaine --}}
    @include('redaction.statistiques.composants.tableau-plus-vus-par-jour')

    {{-- 4. Évolution quotidienne (courbe des vues et histogramme des publications) --}}
    @include('redaction.statistiques.composants.graphiques-evolution')

    {{-- 5. Répartition par catégorie et Top 10 des articles --}}
    @include('redaction.statistiques.composants.repartition-categories-et-top')
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Données globales d'évolution
        const labelsEvolution = @json($labelsGraphique);
        const donneesVuesEvolution = @json($vuesGraphique);
        const donneesPubsEvolution = @json($pubsGraphique);

        // Données hebdomadaires de la semaine courante (du lundi au dimanche)
        const labelsHebdo = @json($statistiquesSemaine['labels_jours']);
        const couleursHebdo = @json($statistiquesSemaine['couleurs_jours']);
        const donneesPubsHebdo = @json($statistiquesSemaine['donnees_publications']);
        const donneesVuesHebdo = @json($statistiquesSemaine['donnees_vues']);

        // 1. Diagramme circulaire : Volume de publication hebdomadaire par jour
        const ctxCirculairePubs = document.getElementById('graphiqueCirculairePublications');
        if (ctxCirculairePubs) {
            new Chart(ctxCirculairePubs, {
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
                            bodyFont: { size: 12 },
                            padding: 10,
                            callbacks: {
                                label: function(context) {
                                    const valeur = context.parsed;
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const pct = total > 0 ? Math.round((valeur / total) * 100) : 0;
                                    return ` ${valeur} articles (${pct}%)`;
                                }
                            }
                        }
                    }
                }
            });
        }

        // 2. Diagramme circulaire : Nombre de vues hebdomadaire par jour
        const ctxCirculaireVues = document.getElementById('graphiqueCirculaireVues');
        if (ctxCirculaireVues) {
            new Chart(ctxCirculaireVues, {
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
                            bodyFont: { size: 12 },
                            padding: 10,
                            callbacks: {
                                label: function(context) {
                                    const valeur = context.parsed;
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const pct = total > 0 ? Math.round((valeur / total) * 100) : 0;
                                    return ` ${valeur.toLocaleString()} views (${pct}%)`;
                                }
                            }
                        }
                    }
                }
            });
        }

        // 3. Graphique d'évolution : Courbe des vues uniques
        const ctxVues = document.getElementById('graphiqueVues');
        if (ctxVues) {
            new Chart(ctxVues, {
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
                        pointRadius: 3,
                        pointHoverRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#14151C',
                            padding: 10,
                            displayColors: false
                        }
                    },
                    scales: {
                        x: {
                            grid: { display: false },
                            ticks: { font: { size: 10 }, maxRotation: 45 }
                        },
                        y: {
                            beginAtZero: true,
                            grid: { color: '#F1F3F5' },
                            ticks: { precision: 0, font: { size: 10 } }
                        }
                    }
                }
            });
        }

        // 4. Graphique d'évolution : Rythme de publication quotidien
        const ctxPubs = document.getElementById('graphiquePublications');
        if (ctxPubs) {
            new Chart(ctxPubs, {
                type: 'bar',
                data: {
                    labels: labelsEvolution,
                    datasets: [{
                        label: 'Articles Published',
                        data: donneesPubsEvolution,
                        backgroundColor: '#D4201A',
                        borderRadius: 3,
                        hoverBackgroundColor: '#B3140F'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#14151C',
                            padding: 10,
                            displayColors: false
                        }
                    },
                    scales: {
                        x: {
                            grid: { display: false },
                            ticks: { font: { size: 10 }, maxRotation: 45 }
                        },
                        y: {
                            beginAtZero: true,
                            grid: { color: '#F1F3F5' },
                            ticks: { precision: 0, font: { size: 10 } }
                        }
                    }
                }
            });
        }
    });
</script>
@endpush
