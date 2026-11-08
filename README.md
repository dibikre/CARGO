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

## Identifiants des Comptes de Test (Pré-configurés)

Grâce au seeder automatique (`DatabaseSeeder`), trois comptes de démonstration sont immédiatement prêts :

| Rôle | Email | Mot de passe | Espace d'accès |
| :--- | :--- | :--- | :--- |
| **Super Administrateur** | `admin@cargo-logistics.com` | `CargoAdmin2026!` | `/admin/connexion` (Console Haute Sécurité) |
| **Journaliste / Rédacteur** | `journaliste@cargo-logistics.com` | `CargoRedac2026!` | `/redaction/articles` (Gestion éditoriale) |
| **Abonné PRO** | `abonne@chargeur-global.com` | `CargoPro2026!` | `/` (Articles exclusifs paywall) |

---

## Fonctionnalités Clés à Tester

1. **Portail Public (`/`)** :
   * Une éditoriale avec badges de modes de transport (Maritime, Rail, Route, Air).
   * Bandeau rouge défilant d'alertes urgentes (*Breaking News*).
   * **Bandeau défilant des matières premières brutes (Commodities Ticker)** : bandeau défilant sur fond blanc, non-cliquable, avec icônes PNG transparentes, prix, tracé sparkline (5-10 nœuds) et calcul automatique de l'écart entrée/sortie (-7%, etc.).
   * Bloc des 10 meilleurs tags logistiques dans la barre latérale sous les vidéos YouTube.
   * Tableau de bord en direct de l'état des corridors logistiques mondiaux (Suez, Rhin-Alpes, etc.).
2. **Console Administrative Sécurisée (`/admin/connexion`)** :
   * Gestion complète des matières premières brutes (`/admin/matieres-premieres`) : ajout, modification, actualisation rapide des nœuds JSON et redimensionnement automatique des PNG en 96x96 avec canal alpha préservé.
   * Protection anti-bruteforce (limitation stricte à 5 essais avec lockout de 15 minutes).
   * Champ piège invisible *honeypot* anti-robot.
   * Traçabilité d'audit immuable de toutes les actions et tentatives d'intrusion.
   * Bascule en un clic des alertes urgentes et du statut opérationnel des corridors.
3. **Espace Rédaction (`/redaction/articles`)** :
   * Rédaction d'articles avec formatage, ajout de tags (max 4), corridor et classification par transport.
4. **Modération des Commentaires (`/redaction/commentaires`)** :
   * Approbation, rejet ou signalement antispam des avis lecteurs.

---

## Architecture Technique & Règles de Conception

* **Architecture** : MVC strictly decoupled (Modèles, Vues Blade, Contrôleurs, Services dédiés).
* **Langue du Code** : 100% en français pour les identifiants internes (classes, variables, méthodes, migrations).
* **Interface Graphique** : Textes en anglais professionnel, design sobre avec boutons d'action rouges (`#D4201A`), fond clair et polices Archivo / IBM Plex Sans.
* **Hébergement Partagé** : Respect de la limite MySQL d'index à 191 caractères (`Schema::defaultStringLength(191)`).
* **Rafraîchir la base de données** : `php artisan migrate:fresh --seed`
