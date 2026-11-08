# CARGO — Plateforme Journalistique Spécialisée en Logistique & Supply Chain

CARGO est une plateforme professionnelle de publication d'articles d'analyse, d'alertes en temps réel de perturbations de fret et de veille stratégique pour les acteurs de la supply chain internationale (maritime, ferroviaire, routier, aérien et multimodal).

Le projet est conçu pour une robustesse maximale, une compatibilité avec les hébergements mutualisés/partagés (MySQL utf8mb4 191 chars, sessions et cache en base de données), et dispose d'une console administrative haute sécurité.

---

## Guide Rapide : Tester en Local avec Laragon

### 1. Prérequis
* **Laragon** (PHP 8.2 ou 8.3, MySQL ou MariaDB, Apache).
* **Composer** (intégré à Laragon).

### 2. Installation dans Laragon
1. Placez le dossier du projet dans votre répertoire Laragon :
   ```text
   C:\laragon\www\journal
   ```
2. Cliquez sur **« Start All »** dans l'interface de Laragon.
   * L'URL locale automatique générée est : **`http://journal.test`**
   * Ou en accès direct : `http://localhost/journal/public`

### 3. Création de la Base de Données
1. Cliquez sur **« Database »** dans Laragon pour ouvrir **HeidiSQL**.
2. Connectez-vous en `root` (sans mot de passe).
3. Créez une nouvelle base de données nommée : **`cargo_db`** (interclassement : `utf8mb4_unicode_ci`).

### 4. Configuration `.env`
Assurez-vous que le fichier `.env` à la racine contient :
```env
APP_NAME="CARGO"
APP_ENV=local
APP_URL=http://journal.test

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=cargo_db
DB_USERNAME=root
DB_PASSWORD=

SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=database
```

### 5. Commandes d'Initialisation (Terminal Laragon)
Dans Laragon, cliquez sur le bouton **« Terminal »**, puis saisissez :
```bash
cd C:\laragon\www\journal
composer install
php artisan key:generate
php artisan migrate --seed
php artisan storage:link
php artisan optimize:clear
```

---

## Les 3 Portails de Connexion Distincts

La plateforme CARGO sépare strictement ses trois publics d'utilisateurs avec des portails de connexion autonomes, dotés de designs spécifiques, de contrôles RBAC dédiés et de redirections adaptées :

| Portail | URL d'accès | Public visé & Rôles | Caractéristiques |
| :--- | :--- | :--- | :--- |
| **Portail Rédaction (Newsroom Desk)** | **`/redaction/connexion`** | **Journalistes & Rédacteurs** (`journaliste`, `redacteur`, `redacteur_en_chef`) | Workspace éditorial, création/modification d'articles, calendrier de publication, embeds vidéo et modération. *Rejette les abonnés non habilités.* |
| **Console Administration** | **`/admin/connexion`** | **Administrateurs & Super Admins** (`super_admin`, `administrateur`) | Console haute sécurité sombre, surveillance opérationnelle, gestion des utilisateurs, audit, abonnements et corridors. |
| **Portail Abonnés PRO (Lecteurs)** | **`/login`** | **Abonnés & Lecteurs** (`abonne`, `lecteur`) | Déverrouillage des analyses exclusives sous paywall PRO, consultation des dossiers et gestion de son abonnement (`/mon-abonnement`). |

---

## Identifiants des Comptes de Test (Pré-configurés)

Grâce au seeder automatique (`DatabaseSeeder`), trois comptes de démonstration sont immédiatement prêts :

| Rôle | Email | Mot de passe | Portail de connexion dédié | Destination post-connexion |
| :--- | :--- | :--- | :--- | :--- |
| **Super Administrateur** | `admin@cargo-logistics.com` | `CargoAdmin2026!` | **`/admin/connexion`** | Console d'Administration (`/admin`) |
| **Journaliste / Rédacteur** | `journaliste@cargo-logistics.com` | `CargoRedac2026!` | **`/redaction/connexion`** | Desk Éditorial (`/redaction/articles`) |
| **Abonné PRO** | `abonne@chargeur-global.com` | `CargoPro2026!` | **`/login`** | Espace Abonné (`/mon-abonnement`) |

---

## Fonctionnalités Clés à Tester

1. **Portail Public (`/`)** :
   * Une éditoriale avec badges de modes de transport (Maritime, Rail, Route, Air).
   * Bandeau rouge défilant d'alertes urgentes (*Breaking News*).
   * **Bandeau défilant des matières premières brutes (Commodities Ticker)** : bandeau défilant sur fond blanc, non-cliquable, avec icônes PNG transparentes, prix, tracé sparkline (5-10 nœuds) et calcul automatique de l'écart entrée/sortie (-7%, etc.).
   * Bloc des 10 meilleurs tags logistiques dans la barre latérale sous les vidéos YouTube.
   * Tableau de bord en direct de l'état des corridors logistiques mondiaux (Suez, Rhin-Alpes, etc.).
2. **Console Administrative Sécurisée (`/admin/connexion`)** :
   * **Gestion des Utilisateurs & Contrôle d'Accès RBAC (`/admin/utilisateurs`)** :
     - Ajout complet de nouveaux comptes avec attribution de rôle (Super Admin, Administrateur, Rédacteur en chef, Journaliste, Rédacteur, Abonné, Lecteur).
     - Modification des profils utilisateurs, coordonnées, rôles et réinitialisation de mot de passe.
     - Activation / désactivation en un clic (`est_actif`) bloquant immédiatement l'accès au site et au backoffice.
     - Suppression de compte avec protection *SoftDeletes* préservant l'intégrité des articles et de l'historique d'audit.
     - Garde-fous de sécurité stricts (interdiction de s'auto-rétrograder, s'auto-désactiver ou s'auto-supprimer ; protection du dernier Super Administrateur).
   * **Gestion des Formules d'Abonnement & Contrôle des Souscriptions (`/admin/abonnements`)** :
     - **Ajout de formules d'abonnement** (`storeFormule`) : nom, durée en jours (30, 90, 365, etc.), prix HT et TVA, sièges simultanés, privilèges archives et dossiers exclusifs.
     - **Modification complète** (`updateFormule`) : ajustement des tarifs, durées, descriptions, avec régénération automatique du slug unique.
     - **Suppression sécurisée** (`destroyFormule`) : suppression définitive autorisée uniquement si aucun abonné n'est lié au forfait (préservation de l'intégrité contractuelle et comptable).
     - **Désactivation du fait de s'abonner** :
       * *Par formule* : bascule rapide en 1 clic de l'état `est_actif` pour retirer instantanément une formule de la grille publique des tarifs.
       * *À l'échelle de la plateforme* : interrupteur global `souscriptions_actives` dans `ConfigurationSysteme` bloquant tout nouveau paiement/souscription (`POST /abonnements/souscrire`) tout en maintenant l'accès des abonnés en cours.
       * *Bascule Paywall Global* : interrupteur `abonnements_actifs` rendant le média 100% gratuit et ouvert sans paywall.
   * Gestion complète des matières premières brutes (`/admin/matieres-premieres`) : ajout, modification, actualisation rapide des nœuds JSON et redimensionnement automatique des PNG en 80x80 avec canal alpha préservé.
   * Protection anti-bruteforce (limitation stricte à 5 essais avec lockout de 15 minutes).
   * Champ piège invisible *honeypot* anti-robot.
   * Traçabilité d'audit immuable de toutes les actions et tentatives d'intrusion.
   * **Supervision des Corridors & Commutateur de Visibilité (`/admin/corridors`)** :
     - Surveillance opérationnelle (fluide, ralenti, congestionné, bloqué).
     - Commutateur de visibilité publique `corridors_actifs` pour activer ou désactiver d'un seul clic la consultation des corridors côté visiteur (coupure 404 et masquage des badges).
   * Bascule en un clic des alertes urgentes (*Breaking News*).
3. **Espace Rédaction (`/redaction/articles`) & Desk Éditorial** :
   * **Page de connexion dédiée (`/redaction/connexion`)** : portail d'authentification réservé aux journalistes et rédacteurs, distinct de l'administration et des abonnés PRO.
   * Rédaction d'articles avec formatage WYSIWYG/HTML, ajout de tags (max 4), corridor et classification par transport.
   * **Conversion & Compression WebP Automatique (Exclusif aux Articles)** :
     - Toute image d'article téléversée (`fichier_image`) ou rapatriée depuis une URL distante est automatiquement interceptée par `ServiceGestionImage` et convertie au format WebP compressé (`.webp`) via `ServiceConversionWebp`.
     - **Taux de compression optimal** : encodage à 82% de qualité, réduisant le poids des images de 60% à 85% par rapport au JPEG/PNG natif, sans dégradation visuelle perceptible.
     - **Préservation de la transparence** : gestion complète du canal alpha (couche RGBA) pour les fichiers PNG transparents.
     - **Redimensionnement proportionnel intelligent** : limitation automatique des images démesurées (max 1920x1080) sans déformation de ratio ni agrandissement artificiel des petites images.
     - **Ciblage strict** : s'applique exclusivement aux images du dossier `articles`. Les bannières de rubriques (`categories`) et les icônes de matières premières (`matieres`, PNG 80x80 alpha) conservent intacts leurs formats spécifiques.
     - **Résilience & Logs** : mécanisme de repli transparent (fallback) avec journalisation détaillée via `Log::info()` et `Log::warning()`.
4. **Modération des Commentaires (`/redaction/commentaires`)** :
   * Approbation, rejet ou signalement antispam des avis lecteurs.

---

## Architecture Technique & Règles de Conception

* **Architecture** : MVC strictly decoupled (Modèles, Vues Blade, Contrôleurs, Services dédiés).
* **Langue du Code** : 100% en français pour les identifiants internes (classes, variables, méthodes, migrations).
* **Interface Graphique** : Textes en anglais professionnel, design sobre avec boutons d'action rouges (`#D4201A`), fond clair et polices Archivo / IBM Plex Sans.
* **Hébergement Partagé** : Respect de la limite MySQL d'index à 191 caractères (`Schema::defaultStringLength(191)`).
* **Rafraîchir la base de données** : `php artisan migrate:fresh --seed`
