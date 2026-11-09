<?php

namespace App\Services;

use App\Models\Article;
use App\Models\StatistiqueArticle;
use App\Models\VueArticleIp;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ServiceStatistiquesHebdomadaires
{
    /**
     * Palette de couleurs éditoriales harmonisées pour les 7 jours de la semaine.
     */
    public const COULEURS_JOURS = [
        '#14151C', // Lundi : Anthracite profond CARGO
        '#D4201A', // Mardi : Rouge CARGO
        '#2B2E3A', // Mercredi : Ardoise foncée
        '#4B505C', // Jeudi : Gris graphite
        '#6B7280', // Vendredi : Gris acier
        '#8E95A5', // Samedi : Ardoise moyenne
        '#B8BDC8', // Dimanche : Gris clair éditorial
    ];

    /**
     * Calcule l'ensemble des métriques de la semaine courante (du lundi au dimanche) :
     * 1. Volume de publication par jour (diagramme circulaire)
     * 2. Nombre de vues par jour (diagramme circulaire)
     * 3. Article le plus consulté pour chaque jour de la semaine
     *
     * @return array<string, mixed>
     */
    public function calculerStatistiquesSemaineCourante(): array
    {
        $maintenant = Carbon::now();
        $debutSemaine = $maintenant->copy()->startOfWeek(Carbon::MONDAY)->startOfDay();
        $finSemaine = $debutSemaine->copy()->addDays(6)->endOfDay();

        $joursSemaine = [];
        $labelsJours = [];
        $donneesPublications = [];
        $donneesVues = [];
        $articlesPlusVusParJour = [];

        // Pré-récupérer les publications de la semaine groupées par date
        $publicationsGroupes = Article::where('statut', 'publie')
            ->whereBetween('date_publication', [$debutSemaine, $finSemaine])
            ->select(DB::raw('DATE(date_publication) as date_pub'), DB::raw('COUNT(*) as total'))
            ->groupBy(DB::raw('DATE(date_publication)'))
            ->pluck('total', 'date_pub')
            ->toArray();

        // Pré-récupérer les vues consolidées de la semaine
        $vuesGroupes = StatistiqueArticle::whereBetween('date_statistique', [
                $debutSemaine->format('Y-m-d'),
                $finSemaine->format('Y-m-d')
            ])
            ->select('date_statistique', DB::raw('SUM(nombre_vues) as total_vues'))
            ->groupBy('date_statistique')
            ->pluck('total_vues', 'date_statistique')
            ->toArray();

        // Parcourir les 7 jours consécutifs du lundi au dimanche
        for ($i = 0; $i < 7; $i++) {
            $dateJour = $debutSemaine->copy()->addDays($i);
            $cleDate = $dateJour->format('Y-m-d');
            $nomJourAnglais = $dateJour->format('l');
            $labelCourt = $dateJour->format('D (d/m)');

            $totalPubsJour = (int) ($publicationsGroupes[$cleDate] ?? 0);
            $totalVuesJour = (int) ($vuesGroupes[$cleDate] ?? 0);

            // Recherche de l'article le plus vu de cette journée
            $articleTopJour = $this->rechercherArticlePlusVuDuJour($cleDate);

            // Si aucune vue n'est dans StatistiqueArticle mais qu'il y a un article top, synchroniser
            if ($totalVuesJour === 0 && $articleTopJour && isset($articleTopJour['vues_jour'])) {
                $totalVuesJour = (int) $articleTopJour['vues_jour'];
            }

            $labelsJours[] = $nomJourAnglais;
            $donneesPublications[] = $totalPubsJour;
            $donneesVues[] = $totalVuesJour;

            $estAujourdhui = $dateJour->isToday();
            $estPasse = $dateJour->isPast() && !$estAujourdhui;
            $estFutur = $dateJour->isFuture() && !$estAujourdhui;

            $joursSemaine[] = [
                'index' => $i,
                'nom_anglais' => $nomJourAnglais,
                'label_court' => $labelCourt,
                'date' => $cleDate,
                'couleur' => self::COULEURS_JOURS[$i],
                'publications' => $totalPubsJour,
                'vues' => $totalVuesJour,
                'est_aujourdhui' => $estAujourdhui,
                'est_passe' => $estPasse,
                'est_futur' => $estFutur,
                'article_top' => $articleTopJour,
            ];

            $articlesPlusVusParJour[] = [
                'jour' => $nomJourAnglais,
                'label_court' => $labelCourt,
                'date' => $cleDate,
                'couleur' => self::COULEURS_JOURS[$i],
                'est_aujourdhui' => $estAujourdhui,
                'vues_jour' => $totalVuesJour,
                'article' => $articleTopJour,
            ];
        }

        $totalPublicationsSemaine = array_sum($donneesPublications);
        $totalVuesSemaine = array_sum($donneesVues);

        return [
            'date_debut' => $debutSemaine,
            'date_fin' => $finSemaine,
            'total_publications' => $totalPublicationsSemaine,
            'total_vues' => $totalVuesSemaine,
            'labels_jours' => $labelsJours,
            'couleurs_jours' => self::COULEURS_JOURS,
            'donnees_publications' => $donneesPublications,
            'donnees_vues' => $donneesVues,
            'details_jours' => $joursSemaine,
            'articles_plus_vus' => $articlesPlusVusParJour,
        ];
    }

    /**
     * Identifie l'article ayant cumulé le plus grand nombre de vues pour une date donnée.
     *
     * @param string $dateFormattee Format Y-m-d
     * @return array<string, mixed>|null
     */
    protected function rechercherArticlePlusVuDuJour(string $dateFormattee): ?array
    {
        // 1. Priorité aux métriques de StatistiqueArticle
        $statTop = StatistiqueArticle::with(['article.categorie', 'article.auteur'])
            ->where('date_statistique', $dateFormattee)
            ->whereHas('article', function ($requete) {
                $requete->where('statut', 'publie');
            })
            ->orderByDesc('nombre_vues')
            ->first();

        if ($statTop && $statTop->article) {
            $art = $statTop->article;
            return [
                'id' => $art->id,
                'titre' => $art->titre,
                'slug' => $art->slug,
                'categorie' => $art->categorie?->nom ?? 'General',
                'auteur' => $art->auteur?->name ?? 'Staff Editorial',
                'vues_jour' => (int) $statTop->nombre_vues,
                'vues_totales' => (int) $art->vues_compteur,
            ];
        }

        // 2. Repli : vues par IP uniques pour cette date
        $topIp = VueArticleIp::whereDate('created_at', $dateFormattee)
            ->select('article_id', DB::raw('COUNT(*) as total_ip'))
            ->groupBy('article_id')
            ->orderByDesc('total_ip')
            ->first();

        if ($topIp) {
            $art = Article::with(['categorie', 'auteur'])->find($topIp->article_id);
            if ($art && $art->statut === 'publie') {
                return [
                    'id' => $art->id,
                    'titre' => $art->titre,
                    'slug' => $art->slug,
                    'categorie' => $art->categorie?->nom ?? 'General',
                    'auteur' => $art->auteur?->name ?? 'Staff Editorial',
                    'vues_jour' => (int) $topIp->total_ip,
                    'vues_totales' => (int) $art->vues_compteur,
                ];
            }
        }

        // 3. Repli : article publié à cette date avec le plus fort compteur global
        $artPublie = Article::with(['categorie', 'auteur'])
            ->where('statut', 'publie')
            ->whereDate('date_publication', $dateFormattee)
            ->orderByDesc('vues_compteur')
            ->first();

        if ($artPublie) {
            return [
                'id' => $artPublie->id,
                'titre' => $artPublie->titre,
                'slug' => $artPublie->slug,
                'categorie' => $artPublie->categorie?->nom ?? 'General',
                'auteur' => $artPublie->auteur?->name ?? 'Staff Editorial',
                'vues_jour' => (int) round($artPublie->vues_compteur / 7),
                'vues_totales' => (int) $artPublie->vues_compteur,
            ];
        }

        return null;
    }
}
