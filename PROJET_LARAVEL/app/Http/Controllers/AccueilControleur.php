<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\CorridorTransport;
use App\Models\DocumentRessource;
use App\Models\DossierThematique;
use App\Models\EvenementLogistique;
use App\Models\Tag;
use App\Models\VideoLogistique;
use Illuminate\View\View;

class AccueilControleur extends Controller
{
    /**
     * Afficher la page d'accueil du journal logistique.
     */
    public function index(): View
    {
        // Articles à la une (hero principal avec diaporama animé de 5 articles)
        $articlesALaUne = Article::with(['auteur', 'categorie'])
            ->aLaUne()
            ->limit(5)
            ->get();

        // Garantir exactement 5 articles pour le diaporama du hero
        if ($articlesALaUne->count() < 5) {
            $idsExistants = $articlesALaUne->pluck('id');
            $complements = Article::with(['auteur', 'categorie'])
                ->publie()
                ->whereNotIn('id', $idsExistants)
                ->limit(5 - $articlesALaUne->count())
                ->get();
            $articlesALaUne = $articlesALaUne->concat($complements);
        }

        // Flash infos et alertes transport urgentes
        $alertesUrgentes = Article::urgent()
            ->limit(5)
            ->get();

        // Dernières actualités récentes générales
        $articlesRecents = Article::with(['auteur', 'categorie'])
            ->publie()
            ->whereNotIn('id', $articlesALaUne->pluck('id'))
            ->limit(8)
            ->get();

        // 5 Pôles sectoriels de fret : Routier, Maritime, Ferroviaire, Aérien, Mondial / Multimodal
        $modesConfig = [
            'maritime' => [
                'titre' => 'Fret Maritime & Ports',
                'badge' => 'MARITIME FREIGHT',
                'slug' => 'fret-maritime-ports',
                'description' => 'Armateurs mondiaux, terminaux portuaires à conteneurs, alliances maritimes et cours des taux spot.',
            ],
            'routier' => [
                'titre' => 'Transport Routier & Flotte',
                'badge' => 'ROAD FREIGHT',
                'slug' => 'transport-routier',
                'description' => 'Poids lourds, décarbonation des flottes, cabotage, pénurie de conducteurs et logistique urbaine.',
            ],
            'ferroviaire' => [
                'titre' => 'Ferroviaire & Intermodal',
                'badge' => 'RAIL INTERMODAL',
                'slug' => 'ferroviaire-intermodal',
                'description' => 'Autoroutes ferroviaires, combiné rail-route, corridors TEN-T et fret longue distance bas-carbone.',
            ],
            'aerien' => [
                'titre' => 'Fret Aérien & Cargo Express',
                'badge' => 'AIR CARGO',
                'slug' => 'fret-aerien',
                'description' => 'Hubs aéroportuaires, capacités en soute, corridors pharmaceutiques sous température dirigée et e-commerce transfrontalier.',
            ],
            'multimodal' => [
                'titre' => 'Fret Mondial & Supply Chain',
                'badge' => 'GLOBAL LOGISTICS',
                'slug' => 'supply-chain-tech',
                'description' => 'Flux mondiaux, transbordement multimodal, géopolitique des corridors et résilience de bout-en-bout.',
            ],
        ];

        $sectionsFret = [];
        foreach ($modesConfig as $cle => $config) {
            $articlesMode = Article::with(['auteur', 'categorie'])
                ->publie()
                ->where('mode_transport_principal', $cle)
                ->latest('date_publication')
                ->limit(5)
                ->get();

            // Garantir 1 article centré + 4 à droite grâce à un fallback gracieux
            if ($articlesMode->count() < 5) {
                $idsExistants = $articlesMode->pluck('id');
                $complements = Article::with(['auteur', 'categorie'])
                    ->publie()
                    ->whereNotIn('id', $idsExistants)
                    ->latest('date_publication')
                    ->limit(5 - $articlesMode->count())
                    ->get();

                $articlesMode = $articlesMode->concat($complements);
            }

            $sectionsFret[$cle] = [
                'config' => $config,
                'centre' => $articlesMode->first(),
                'suivants' => $articlesMode->slice(1, 4),
            ];
        }

        // Corridors sous surveillance
        $corridorsSousSurveillance = CorridorTransport::actif()->limit(6)->get();

        // Les 6 meilleurs articles de la semaine (les plus vus sur les 7 derniers jours)
        $articlesTopSemaine = Article::with(['auteur', 'categorie'])
            ->publie()
            ->where('date_publication', '>=', now()->subDays(7))
            ->orderByDesc('vues_compteur')
            ->limit(6)
            ->get();

        // Si moins de 6 articles sur 7 jours (développement/démo), compléter avec les plus vus globaux
        if ($articlesTopSemaine->count() < 6) {
            $idsTop = $articlesTopSemaine->pluck('id');
            $complementsTop = Article::with(['auteur', 'categorie'])
                ->publie()
                ->whereNotIn('id', $idsTop)
                ->orderByDesc('vues_compteur')
                ->limit(6 - $articlesTopSemaine->count())
                ->get();
            $articlesTopSemaine = $articlesTopSemaine->concat($complementsTop);
        }

        // Vidéos logistiques embarquées (embeds) ajoutées par les publicateurs
        $videosLogistiques = VideoLogistique::actif()->limit(3)->get();

        // Grands dossiers
        $dossiersVedettes = DossierThematique::enVedette()->limit(2)->get();

        // Salons
        $evenementsProchains = EvenementLogistique::aVenir()->limit(3)->get();

        // Livre blanc
        $dernierDocument = DocumentRessource::actif()->latest()->first();

        // Les 10 meilleurs tags (mots-clés avec le plus grand nombre d'articles associés)
        $meilleursTags = Tag::withCount('articles')
            ->having('articles_count', '>', 0)
            ->orderByDesc('articles_count')
            ->orderBy('nom')
            ->limit(10)
            ->get();

        if ($meilleursTags->count() < 10) {
            $idsExclus = $meilleursTags->pluck('id');
            $complementsTags = Tag::withCount('articles')
                ->whereNotIn('id', $idsExclus)
                ->orderBy('nom')
                ->limit(10 - $meilleursTags->count())
                ->get();
            $meilleursTags = $meilleursTags->concat($complementsTags);
        }

        return view('journal.accueil', compact(
            'articlesALaUne',
            'alertesUrgentes',
            'articlesRecents',
            'sectionsFret',
            'articlesTopSemaine',
            'videosLogistiques',
            'meilleursTags',
            'corridorsSousSurveillance',
            'dossiersVedettes',
            'evenementsProchains',
            'dernierDocument'
        ));
    }
}