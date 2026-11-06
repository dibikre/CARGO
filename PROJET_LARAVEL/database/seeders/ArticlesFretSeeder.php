<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Categorie;
use App\Models\User;
use Illuminate\Database\Seeder;

class ArticlesFretSeeder extends Seeder
{
    /**
     * Alimenter 5 articles par mode pour les 5 pôles de la page d'accueil avec visuels structurés.
     */
    public function run(): void
    {
        $admin = User::where('role', 'super_admin')->first() ?? User::first();
        $journaliste = User::where('role', 'journaliste')->first() ?? $admin;

        $catMaritime = Categorie::firstOrCreate(['slug' => 'fret-maritime-ports'], ['nom' => 'Fret Maritime & Ports', 'description' => 'Maritime', 'ordre_affichage' => 1]);
        $catRoutier = Categorie::firstOrCreate(['slug' => 'transport-routier'], ['nom' => 'Transport Routier', 'description' => 'Routier', 'ordre_affichage' => 2]);
        $catFerroviaire = Categorie::firstOrCreate(['slug' => 'ferroviaire-intermodal'], ['nom' => 'Ferroviaire & Intermodal', 'description' => 'Rail', 'ordre_affichage' => 3]);
        $catAerien = Categorie::firstOrCreate(['slug' => 'fret-aerien'], ['nom' => 'Fret Aérien & Express', 'description' => 'Air cargo', 'ordre_affichage' => 4]);
        $catMondial = Categorie::firstOrCreate(['slug' => 'supply-chain-tech'], ['nom' => 'Supply Chain Mondiale', 'description' => 'Global Logistics', 'ordre_affichage' => 5]);

        $articles = [
            // --- MARITIME ---
            ['mode' => 'maritime', 'cat_id' => $catMaritime->id, 'slug' => 'alliances-maritimes-2026-recomposition-gemini-ocean', 'titre' => 'Alliances maritimes 2026 : La recomposition Gemini et Ocean Alliance rebat les cartes portuaires', 'chapeau' => 'Les armateurs modifient leurs escales directes au profit de méga-hubs de transbordement en Méditerranée et Mer du Nord.', 'minutes' => 7, 'une' => true, 'img' => 'images/modes/maritime.svg'],
            ['mode' => 'maritime', 'cat_id' => $catMaritime->id, 'slug' => 'port-du-havre-nouveau-terminal-conteneurs-electrification', 'titre' => 'Port du Havre : Mise en service du terminal électrifié et connexion fluviale directe vers Paris', 'chapeau' => 'Haropa Port franchit une étape stratégique pour l’axe Seine avec des bornes d’alimentation à quai haute puissance.', 'minutes' => 4, 'une' => false, 'img' => 'images/modes/maritime.svg'],
            ['mode' => 'maritime', 'cat_id' => $catMaritime->id, 'slug' => 'taux-spot-scfi-stabilisation-routes-transpacifiques', 'titre' => 'Indices SCFI : Légère détente des taux de fret spot sur les liaisons Asie-Côte Ouest américaine', 'chapeau' => 'Les chargeurs profitent d’une fenêtre de négociation contractuelle avant la haute saison d’approvisionnement estival.', 'minutes' => 5, 'une' => false, 'img' => 'images/modes/maritime.svg'],
            ['mode' => 'maritime', 'cat_id' => $catMaritime->id, 'slug' => 'decarbonation-maritime-bunkering-methanol-rotterdam', 'titre' => 'Bunkering vert : Rotterdam et Singapour accélèrent sur le soutage méthanol et ammoniac', 'chapeau' => 'L’entrée en vigueur des pénalités FuelEU Maritime impose de nouveaux carburants alternatifs pour les flottes océaniques.', 'minutes' => 6, 'une' => false, 'img' => 'images/modes/maritime.svg'],
            ['mode' => 'maritime', 'cat_id' => $catMaritime->id, 'slug' => 'congestion-canal-panama-retablit-tirant-eau', 'titre' => 'Canal de Panama : Retour aux tirants d’eau nominaux après les pluies sur le lac Gatún', 'chapeau' => 'Le nombre de transits quotidiens de navires Neopanamax remonte à 36 unités par jour.', 'minutes' => 3, 'une' => false, 'img' => 'images/modes/maritime.svg'],

            // --- ROUTIER ---
            ['mode' => 'routier', 'cat_id' => $catRoutier->id, 'slug' => 'decarbonation-flottes-poids-lourds-electriques-megawatt-charging', 'titre' => 'Flottes lourdes : L’arrivée des chargeurs Megawatt (MCS) lève le frein de l’autonomie longue distance', 'chapeau' => 'Les transporteurs européens testent les premiers tracteurs 44 tonnes électriques sur des corridors autoroutiers dédiés.', 'minutes' => 6, 'une' => true, 'img' => 'images/modes/routier.svg'],
            ['mode' => 'routier', 'cat_id' => $catRoutier->id, 'slug' => 'penurie-conducteurs-routiers-reforme-permis-europeen', 'titre' => 'Pénurie de conducteurs : Le Parlement européen valide l’accès au permis poids lourd dès 18 ans', 'chapeau' => 'Une mesure ciblée pour répondre à plus de 400 000 postes de conducteurs non pourvus à travers le continent.', 'minutes' => 4, 'une' => false, 'img' => 'images/modes/routier.svg'],
            ['mode' => 'routier', 'cat_id' => $catRoutier->id, 'slug' => 'chronotachygraphe-intelligent-v2-delai-retrofitting', 'titre' => 'Chronotachygraphe 1C v2 : Les ateliers de contrôle saturés avant l’échéance du rétrofit obligatoire', 'chapeau' => 'Les fédérations de transporteurs réclament une période de tolérance pour éviter l’immobilisation des véhicules internationaux.', 'minutes' => 5, 'une' => false, 'img' => 'images/modes/routier.svg'],
            ['mode' => 'routier', 'cat_id' => $catRoutier->id, 'slug' => 'prix-du-gazole-professionnel-ticpe-evolutions-trimestrielles', 'titre' => 'Carburant professionnel : Impact de la fiscalité énergétique sur la marge opérationnelle des transporteurs', 'chapeau' => 'Le Comité National Routier publie ses indices de coûts de revient et recommande l’activation stricte de la clause gazole.', 'minutes' => 4, 'une' => false, 'img' => 'images/modes/routier.svg'],
            ['mode' => 'routier', 'cat_id' => $catRoutier->id, 'slug' => 'logistique-urbaine-zfe-derogations-camions-distribution', 'titre' => 'ZFE urbaines : Harmonisation des vignettes Crit’Air et dérogations pour les livraisons nocturnes', 'chapeau' => 'Les métropoles adaptent leurs créneaux horaires pour fluidifier l’approvisionnement des commerces de centre-ville.', 'minutes' => 3, 'une' => false, 'img' => 'images/modes/routier.svg'],

            // --- FERROVIAIRE ---
            ['mode' => 'ferroviaire', 'cat_id' => $catFerroviaire->id, 'slug' => 'autoroutes-ferroviaires-axe-atlantique-bayonne-cherbourg', 'titre' => 'Fret ferroviaire : Ouverture de la liaison de semi-remorques non-accompagnées Bayonne-Cherbourg', 'chapeau' => 'Ce corridor évite plus de 20 000 trajets de camions sur les autoroutes de l’Ouest et connecte les flux vers l’Irlande.', 'minutes' => 6, 'une' => true, 'img' => 'images/modes/ferroviaire.svg'],
            ['mode' => 'ferroviaire', 'cat_id' => $catFerroviaire->id, 'slug' => 'attelage-automatique-numerique-dac-deploiement-fret-europe', 'titre' => 'Attelage numérique DAC : Les opérateurs ferroviaires réclament un cofinancement européen pour 2026-2030', 'chapeau' => 'L’automatisation des manœuvres de triage conditionne le doublement de la part modale du rail en Europe.', 'minutes' => 5, 'une' => false, 'img' => 'images/modes/ferroviaire.svg'],
            ['mode' => 'ferroviaire', 'cat_id' => $catFerroviaire->id, 'slug' => 'tunnel-de-base-du-saint-gothard-reprise-fret-integral', 'titre' => 'Corridor Suisse : Le Gothard retrouve sa pleine cadence de convois fret transalpins', 'chapeau' => 'La réouverture complète du tube ouest sécurise les flux intermodaux entre l’Allemagne et l’Italie du Nord.', 'minutes' => 4, 'une' => false, 'img' => 'images/modes/ferroviaire.svg'],
            ['mode' => 'ferroviaire', 'cat_id' => $catFerroviaire->id, 'slug' => 'plateforme-multimodale-bettenbourg-hub-luxembourg', 'titre' => 'Hub de Bettembourg : Record de transbordements combinés rail-route pour CFL multimodal', 'chapeau' => 'Les capacités de stockage de caisses mobiles sont étendues pour soutenir la demande des chargeurs industriels.', 'minutes' => 3, 'une' => false, 'img' => 'images/modes/ferroviaire.svg'],
            ['mode' => 'ferroviaire', 'cat_id' => $catFerroviaire->id, 'slug' => 'tarification-peages-ferroviaires-sillon-fret-sncf-reseau', 'titre' => 'Péages ferroviaires : Gel temporaire des redevances de circulation pour préserver la compétitivité du rail', 'chapeau' => 'L’État compense l’écart de coût d’infrastructure pour soutenir le plan de relance du fret ferroviaire.', 'minutes' => 4, 'une' => false, 'img' => 'images/modes/ferroviaire.svg'],

            // --- AÉRIEN ---
            ['mode' => 'aerien', 'cat_id' => $catAerien->id, 'slug' => 'fret-aerien-cargo-express-hubs-cdg-liege-ecommerce-mondial', 'titre' => 'Fret aérien mondial : L’explosion des volumes e-commerce transfrontaliers sature les terminaux cargo', 'chapeau' => 'À Paris-CDG, Liège et Francfort, les compagnies cargo convertissent de nouveaux gros-porteurs passagers en tout-cargo (P2F).', 'minutes' => 7, 'une' => true, 'img' => 'images/modes/aerien.svg'],
            ['mode' => 'aerien', 'cat_id' => $catAerien->id, 'slug' => 'chaine-du-froid-pharma-certification-ceiv-iata', 'titre' => 'Logistique pharmaceutique : Extension des entrepôts sous température dirigée certifiés CEIV Pharma', 'chapeau' => 'La traçabilité en temps réel des vaccins et produits thermosensibles renforce les corridors de santé mondiaux.', 'minutes' => 5, 'une' => false, 'img' => 'images/modes/aerien.svg'],
            ['mode' => 'aerien', 'cat_id' => $catAerien->id, 'slug' => 'saf-aviation-cargo-quotas-incorporation-carburants-verts', 'titre' => 'Mandat ReFuelEU Aviation : Premiers ravitaillements en carburant durable (SAF) pour les vols cargo réguliers', 'chapeau' => 'Les transporteurs express intègrent un supplément écologique transparent pour financer la filière biojet.', 'minutes' => 4, 'une' => false, 'img' => 'images/modes/aerien.svg'],
            ['mode' => 'aerien', 'cat_id' => $catAerien->id, 'slug' => 'capacites-en-soute-belly-cargo-reprise-lignes-asiatiques', 'titre' => 'Capacités en soute : La multiplication des vols long-courriers stabilise les rendements du belly-cargo', 'chapeau' => 'L’offre globale de tonnage aérien retrouve ses niveaux d’avant-crise sur les routes Asie-Pacifique.', 'minutes' => 3, 'une' => false, 'img' => 'images/modes/aerien.svg'],
            ['mode' => 'aerien', 'cat_id' => $catAerien->id, 'slug' => 'drones-cargo-lourds-tests-corridors-insulaires', 'titre' => 'Aviation sans pilote : Premiers vols d’essais de drones cargo lourds pour le ravitaillement d’îles', 'chapeau' => 'Des appareils autonomes capables d’emporter 1 tonne de fret sur 500 km obtiennent leurs autorisations de vol civil.', 'minutes' => 5, 'une' => false, 'img' => 'images/modes/aerien.svg'],

            // --- MONDIAL / MULTIMODAL ---
            ['mode' => 'multimodal', 'cat_id' => $catMondial->id, 'slug' => 'geopolitique-supply-chain-nearshoring-corridors-transcaspiens', 'titre' => 'Géopolitique logistique : Le Middle Corridor transcaspien s’impose comme alternative majeure aux routes du Nord', 'chapeau' => 'Combinant rail, transbordement en mer Caspienne et mer Noire, ce corridor attire les investissements des chargeurs mondiaux.', 'minutes' => 8, 'une' => true, 'img' => 'images/modes/multimodal.svg'],
            ['mode' => 'multimodal', 'cat_id' => $catMondial->id, 'slug' => 'visibilite-temps-reel-tour-de-controle-iot-conteneurs-intelligents', 'titre' => 'Tours de contrôle supply chain : Déploiement massif de balises IoT et d’analyses prédictives d’ETA', 'chapeau' => 'Les chargeurs réduisent de 18% leurs stocks dormants en anticipant les ruptures d’approvisionnement.', 'minutes' => 6, 'une' => false, 'img' => 'images/modes/multimodal.svg'],
            ['mode' => 'multimodal', 'cat_id' => $catMondial->id, 'slug' => 'csddd-devoir-de-vigilance-tracabilite-fournisseurs-rang-2', 'titre' => 'Directive CSDDD : Les directions achats logistiques audite l’empreinte carbone et sociale de leurs sous-traitants', 'chapeau' => 'La conformité européenne impose une preuve formelle d’audit sur l’ensemble de la chaîne de valeur amont.', 'minutes' => 5, 'une' => false, 'img' => 'images/modes/multimodal.svg'],
            ['mode' => 'multimodal', 'cat_id' => $catMondial->id, 'slug' => 'resilience-des-stocks-du-just-in-time-au-just-in-case', 'titre' => 'Stratégie des stocks : Comment l’industrie automobile bascule du « Just-in-Time » vers des hubs régionaux sécurisés', 'chapeau' => 'La constitution de stocks stratégiques de semi-conducteurs et composants critiques reconfigure la carte des entrepôts.', 'minutes' => 4, 'une' => false, 'img' => 'images/modes/multimodal.svg'],
            ['mode' => 'multimodal', 'cat_id' => $catMondial->id, 'slug' => 'passports-numeriques-produits-dpp-douane-europe', 'titre' => 'Passeport numérique des produits (DPP) : Les plateformes logistiques se préparent à la traçabilité unitaire', 'chapeau' => 'Les douanes européennes intègrent le scanning automatisé des flux de batteries et textiles à l’importation.', 'minutes' => 5, 'une' => false, 'img' => 'images/modes/multimodal.svg'],
        ];

        foreach ($articles as $index => $item) {
            Article::firstOrCreate(
                ['slug' => $item['slug']],
                [
                    'titre' => $item['titre'],
                    'chapeau' => $item['chapeau'],
                    'contenu' => '<p>' . e($item['chapeau']) . '</p><p>Dans un contexte d’intensification des échanges et de transformation des chaînes d’approvisionnement, cette analyse détaille les implications opérationnelles directes pour les directeurs supply chain, directeurs transport et gestionnaires de flottes.</p><h3>Enjeux opérationnels et perspectives de marché</h3><p>Les indicateurs macro-économiques confirment une dynamique d’optimisation continue des flux et une convergence vers des solutions logistiques plus sobres et mieux connectées.</p>',
                    'image_principale' => $item['img'],
                    'legende_image' => 'Flux logistiques et infrastructures de transport',
                    'credit_photo' => 'CARGO Intelligence',
                    'statut' => 'publie',
                    'format_article' => 'standard',
                    'categorie_id' => $item['cat_id'],
                    'auteur_id' => $journaliste->id,
                    'mode_transport_principal' => $item['mode'],
                    'est_a_la_une' => $item['une'],
                    'est_reserve_abonnes' => $index % 3 === 0,
                    'est_urgent' => false,
                    'temps_lecture_minutes' => $item['minutes'],
                    'date_publication' => now()->subHours($index * 3),
                ]
            );
        }
    }
}
