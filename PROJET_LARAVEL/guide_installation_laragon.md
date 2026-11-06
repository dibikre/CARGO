# Guide de Déploiement et Test Local avec Laragon — Projet CARGO

Ce guide détaille pas à pas la mise en route du site de publication logistique **CARGO** sur votre environnement local Windows utilisant **Laragon**.

---

## 1. Prérequis

Assurez-vous de disposer de :
* **Laragon** (version Full recommandée) avec :
  * **PHP** : 8.2 ou 8.3 (activé dans Laragon > PHP > Version)
  * **MySQL** ou **MariaDB**
  * **Apache** ou **Nginx**
* **Composer** (inclus par défaut dans Laragon)
* **Node.js / NPM** (optionnel pour la compilation des assets, les vues Blade utilisant Tailwind CSS CDN)

---

## 2. Emplacement du Projet

1. Copiez ou clonez le dossier `PROJET_LARAVEL` dans le répertoire web racine de Laragon :
   ```text
   C:\laragon\www\journal
   ```
   *(Le nom de dossier `journal` correspond à l'URL générée automatiquement par Laragon `http://journal.test`).*
2. Lancez Laragon et cliquez sur le bouton **« Start All »** (Démarrer tout).
3. Laragon va automatiquement détecter le projet et générer le VirtualHost :
   * URL locale automatique : **`http://journal.test`**
   * URL de secours sans VirtualHost : `http://localhost/journal/public`

---

## 3. Création de la Base de Données

1. Dans la fenêtre principale de Laragon, cliquez sur le bouton **« Database »** pour ouvrir **HeidiSQL**.
2. Connectez-vous avec les identifiants locaux par défaut :
   * **Hôte** : `127.0.0.1` ou `localhost`
   * **Utilisateur** : `root`
   * **Mot de passe** : *(laisser vide)*
   * **Port** : `3306`
3. Faites un clic droit sur la connexion > **Créer un nouveau** > **Base de données**.
4. Nommez la base : `cargo_db` avec l'interclassement `utf8mb4_unicode_ci`.

---

## 4. Configuration du Fichier `.env`

Ouvrez le fichier `C:\laragon\www\journal\.env` (s'il n'existe pas, dupliquez `.env.example` et renommez-le en `.env`).
Vérifiez ou adaptez les variables suivantes :

```env
APP_NAME="CARGO"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_TIMEZONE=Europe/Paris
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

---

## 5. Exécution des Commandes dans le Terminal Laragon

Dans Laragon, cliquez sur le bouton **« Terminal »** (qui ouvre une invite de commande pré-configurée avec PHP et Composer), puis déplacez-vous dans le dossier du projet :

```bash
cd C:\laragon\www\journal
```

Exécutez ensuite la séquence suivante :

```bash
# 1. Installation des dépendances PHP
composer install

# 2. Génération de la clé secrète de chiffrement
php artisan key:generate

# 3. Exécution des migrations SQL et du seeder de données de test
php artisan migrate --seed

# 4. Création du lien symbolique pour le stockage des médias
php artisan storage:link

# 5. Optimisation et nettoyage du cache local
php artisan optimize:clear
```

---

## 6. Comptes de Démonstration Pré-configurés

La commande `php artisan migrate --seed` a généré automatiquement trois comptes de test :

| Profil / Rôle | Adresse Email | Mot de passe | Accès dédié |
| :--- | :--- | :--- | :--- |
| **Super Administrateur** | `admin@cargo-logistics.com` | `CargoAdmin2026!` | `http://journal.test/admin/connexion` |
| **Journaliste / Rédacteur** | `journaliste@cargo-logistics.com` | `CargoRedac2026!` | `http://journal.test/redaction/articles` |
| **Abonné PRO (Lecteur)** | `abonne@chargeur-global.com` | `CargoPro2026!` | `http://journal.test` (Articles réservés PRO) |

---

## 7. Parcours de Test Recommandé

1. **Portail Public (`http://journal.test`)** :
   * Consultez la Une du journal logistique, le bandeau flash d'alerte urgente en rouge, et le widget des corridors maritimes et ferroviaires.
2. **Console Administrateur Haute Sécurité (`http://journal.test/admin/connexion`)** :
   * Connectez-vous avec `admin@cargo-logistics.com`.
   * Testez la bascule en temps réel des corridors logistiques (Fluide &rarr; Bloqué).
   * Testez la bascule d'une alerte urgente flash ou du verrouillage paywall PRO.
   * Consultez le journal d'audit de sécurité (`/admin/securite`) qui trace les connexions et changements d'états.
3. **Espace Rédaction (`http://journal.test/redaction/articles`)** :
   * Créez un nouvel article avec catégorie, corridor de transport et mode de fret.

---

## 8. Dépannage Fréquent sous Laragon

* **Erreur 500 ou `No application encryption key has been specified`** :
  Exécutez `php artisan key:generate` puis `php artisan config:clear`.
* **Erreur `SQLSTATE[42000]: Syntax error or access violation: 1071 Specified key was too long`** :
  Notre projet intègre déjà `Schema::defaultStringLength(191)` dans `AppServiceProvider.php` pour une compatibilité totale avec MySQL et MariaDB sur hébergement partagé.
* **Le domaine `.test` ne répond pas** :
  Assurez-vous que Laragon est démarré en mode administrateur pour lui permettre de renseigner le fichier `C:\Windows\System32\drivers\etc\hosts`. À défaut, utilisez `http://localhost/journal/public`.
