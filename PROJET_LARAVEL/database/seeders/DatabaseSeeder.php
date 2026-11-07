<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Categorie;
use App\Models\CorridorTransport;
use App\Models\FormuleAbonnement;
use App\Models\User;
use App\Models\VideoLogistique;
use App\Models\ZoneLogistique;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Alimenter la base de données de test pour Laragon.
     */
    public function run(): void
    {
        // 1. Création des comptes utilisateurs de démonstration
        $superAdmin = User::firstOrCreate(
            ['email' => 'admin@cargo-logistics.com'],
            [
                'name' => 'Jean-Marc Directeur',
                'password' => Hash::make('CargoAdmin2026!'),
                'role' => 'super_admin',
                'est_actif' => true,
                'telephone' => '+33 1 40 50 60 70',
                'bio' => 'Directeur de la publication et administrateur général de CARGO.',
                'derniere_connexion_a' => now(),
            ]
        );

        $redacteur = User::firstOrCreate(
            ['email' => 'journaliste@cargo-logistics.com'],
            [
                'name' => 'Claire Delamare',
                'password' => Hash::make('CargoRedac2026!'),
                'role' => 'journaliste',
                'est_actif' => true,
                'bio' => 'Grand reporter spécialisée fret maritime et corridors eurasiatiques.',
            ]
        );

        User::firstOrCreate(
            ['email' => 'abonne@chargeur-global.com'],
            [
                'name' => 'Directeur Logistique Danone',
                'password' => Hash::make('CargoPro2026!'),
                'role' => 'abonne',
                'est_actif' => true,
            ]
        );

        // 2. Création des rubriques éditoriales avec images de fond haute résolution et protection de contraste
        $catMaritime = Categorie::firstOrCreate(
            ['slug' => 'fret-maritime-ports'],
            [
                'nom' => 'Fret Maritime & Ports',
                'description' => 'Armateurs, terminaux à conteneurs, taux de fret spot et alliances mondiales.',
                'image_fond' => 'https://images.unsplash.com/photo-1578575437130-527eed3abbec?auto=format&fit=crop&w=1600&q=80',
                'opacite_overlay' => 65,
                'couleur_overlay' => '#14151C',
                'ordre_affichage' => 1,
            ]
        );
        $catRoutier = Categorie::firstOrCreate(
            ['slug' => 'transport-routier'],
            [
                'nom' => 'Transport Routier',
                'description' => 'Flottes de poids lourds, décarbonation, pénurie de chauffeurs et cabotage.',
                'image_fond' => 'https://images.unsplash.com/photo-1601584115197-04ecc0da31d7?auto=format&fit=crop&w=1600&q=80',
                'opacite_overlay' => 65,
                'couleur_overlay' => '#14151C',
                'ordre_affichage' => 2,
            ]
        );
        $catFerroviaire = Categorie::firstOrCreate(
            ['slug' => 'ferroviaire-intermodal'],
            [
                'nom' => 'Ferroviaire & Intermodal',
                'description' => 'Autoroutes ferroviaires, corridors TEN-T et combiné rail-route.',
                'image_fond' => 'https://images.unsplash.com/photo-1515263487990-61b07816b324?auto=format&fit=crop&w=1600&q=80',
                'opacite_overlay' => 65,
                'couleur_overlay' => '#14151C',
                'ordre_affichage' => 3,
            ]
        );
        $catSupply = Categorie::firstOrCreate(
            ['slug' => 'supply-chain-tech'],
            [
                'nom' => 'Supply Chain Tech',
                'description' => 'WMS, TMS, automatisation d\'entrepôts et visibilité temps réel.',
                'image_fond' => 'https://images.unsplash.com/photo-1586528116311-ad8dd3c8310d?auto=format&fit=crop&w=1600&q=80',
                'opacite_overlay' => 65,
                'couleur_overlay' => '#14151C',
                'ordre_affichage' => 4,
            ]
        );
        $catEntreposage = Categorie::firstOrCreate(
            ['slug' => 'entreposage-logistique'],
            [
                'nom' => 'Entreposage & Hubs',
                'description' => 'Immobilier logistique, plateformes multimodales et entrepôts automatisés.',
                'image_fond' => 'https://images.unsplash.com/photo-1553413077-190dd305871c?auto=format&fit=crop&w=1600&q=80',
                'opacite_overlay' => 65,
                'couleur_overlay' => '#14151C',
                'ordre_affichage' => 5,
            ]
        );
        $catDouane = Categorie::firstOrCreate(
            ['slug' => 'douane-reglementation'],
            [
                'nom' => 'Douane & Réglementation',
                'description' => 'Procédures douanières, TVA à l\'importation, conformité et sanctions internationales.',
                'image_fond' => 'https://images.unsplash.com/photo-1494412574643-ff11b0a5c1c3?auto=format&fit=crop&w=1600&q=80',
                'opacite_overlay' => 65,
                'couleur_overlay' => '#14151C',
                'ordre_affichage' => 6,
            ]
        );

        // 3. Corridors logistiques
        CorridorTransport::firstOrCreate(
            ['slug' => 'rotterdam-anvers-rhin-alpes'],
            [
                'nom' => 'Corridor Rhin-Alpes (Rotterdam - Gênes)',
                'type_flux' => 'ferroviaire',
                'statut_operationnel' => 'ralenti',
                'description' => 'Travaux de signalisation ETCS sur le tronçon allemand.',
                'est_actif' => true,
            ]
        );

        CorridorTransport::firstOrCreate(
            ['slug' => 'mer-rouge-canal-de-suez'],
            [
                'nom' => 'Route Asie-Europe via Suez / Mer Rouge',
                'type_flux' => 'maritime',
                'statut_operationnel' => 'perturbe',
                'description' => 'Détournements majeurs par le Cap de Bonne-Espérance.',
                'est_actif' => true,
            ]
        );

        CorridorTransport::firstOrCreate(
            ['slug' => 'axe-nord-sud-autoroute-a1-france'],
            [
                'nom' => 'Corridor Routier Nord-Sud (A1 Paris-Lille)',
                'type_flux' => 'routier',
                'statut_operationnel' => 'normal',
                'description' => 'Trafic de poids lourds régulier aux plateformes logistiques.',
                'est_actif' => true,
            ]
        );

        // 4. Formules d'abonnement
        FormuleAbonnement::firstOrCreate(
            ['slug' => 'cargo-pro-mensuel'],
            [
                'nom' => 'CARGO PRO Lecteur',
                'description' => 'Accès illimité à toutes les enquêtes, analyses de marché et alertes de fret.',
                'prix_ht' => 49.00,
                'taux_tva' => 20.00,
                'duree_en_jours' => 30,
                'acces_archives_illimite' => true,
                'acces_dossiers_exclusifs' => true,
                'nombre_utilisateurs_max' => 1,
                'est_actif' => true,
                'ordre_affichage' => 1,
            ]
        );

        // 5. Articles de démonstration
        Article::firstOrCreate(
            ['slug' => 'taux-de-fret-maritime-asie-europe-surcapacite-2026'],
            [
                'titre' => 'Taux spot Asie-Europe : Les armateurs face au spectre d\'une surcapacité record',
                'chapeau' => 'L\'arrivée massive de méga-porte-conteneurs de 24 000 EVP conjuguée à la réouverture progressive de Suez bouleverse les grilles tarifaires mondiales.',
                'contenu' => 'Les armateurs mondiaux entament le premier trimestre 2026 dans une situation de tension logistique singulière...',
                'statut' => 'publie',
                'format_article' => 'standard',
                'categorie_id' => $catMaritime->id,
                'auteur_id' => $redacteur->id,
                'mode_transport_principal' => 'maritime',
                'est_a_la_une' => true,
                'est_reserve_abonnes' => true,
                'est_urgent' => false,
                'temps_lecture_minutes' => 6,
                'date_publication' => now(),
            ]
        );

        Article::firstOrCreate(
            ['slug' => 'alerte-flash-greve-inopinee-terminal-anvers'],
            [
                'titre' => 'Alerte Flash : Mouvement de grève inopiné sur les terminaux portuaires d\'Anvers',
                'chapeau' => 'Les opérations de déchargement sur l\'Escaut sont temporairement suspendues depuis ce matin 06h00.',
                'contenu' => 'Les autorités de l\'Alliance Portuaire Anvers-Bruges confirment le blocage des accès camions...',
                'statut' => 'publie',
                'format_article' => 'standard',
                'categorie_id' => $catMaritime->id,
                'auteur_id' => $superAdmin->id,
                'mode_transport_principal' => 'maritime',
                'est_a_la_une' => false,
                'est_reserve_abonnes' => false,
                'est_urgent' => true,
                'temps_lecture_minutes' => 3,
                'date_publication' => now(),
            ]
        );

        // 6. Articles complémentaires pour tous les modes de transport
        $this->call(ArticlesFretSeeder::class);

        // 7. Vidéos logistiques de démonstration pour la barre latérale
        VideoLogistique::firstOrCreate(
            ['slug' => 'automated-container-terminals-maasvlakte-ii'],
            [
                'user_id' => $superAdmin->id,
                'titre' => 'Automated Container Terminals: Maasvlakte II Operations',
                'url_embed' => 'https://www.youtube.com/embed/ScMzIvxBSi4',
                'source' => 'Port of Rotterdam Logistics',
                'duree' => '05:14',
                'description' => 'Inside Europe\'s most automated deep-sea container terminal handling 24,000 TEU ultra-large vessels.',
                'est_actif' => true,
                'ordre_affichage' => 1,
            ]
        );

        VideoLogistique::firstOrCreate(
            ['slug' => 'intermodal-freight-trains-corridor-operations'],
            [
                'user_id' => $redacteur->id,
                'titre' => 'Intermodal Freight Trains: Trans-European Corridor Operations',
                'url_embed' => 'https://www.youtube.com/embed/jNQXAC9IVRw',
                'source' => 'European Rail Logistics Desk',
                'duree' => '04:02',
                'description' => 'High-frequency rail shuttle linking sea ports to dry ports and continental distribution hubs.',
                'est_actif' => true,
                'ordre_affichage' => 2,
            ]
        );
    }
}
