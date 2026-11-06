<?php

use App\Http\Controllers\AbonnementControleur;
use App\Http\Controllers\AccueilControleur;
use App\Http\Controllers\ArticleControleur;
use App\Http\Controllers\CommentaireControleur;
use App\Http\Controllers\DocumentRessourceControleur;
use App\Http\Controllers\DossierThematiqueControleur;
use App\Http\Controllers\EvenementLogistiqueControleur;
use App\Http\Controllers\NewsletterControleur;
use App\Http\Controllers\RechercheControleur;
use App\Http\Controllers\Administration\AbonnementAdminControleur;
use App\Http\Controllers\Administration\ArticleAdminControleur;
use App\Http\Controllers\Administration\CategorieAdminControleur;
use App\Http\Controllers\Administration\CommentaireAdminControleur;
use App\Http\Controllers\Administration\ConnexionAdminControleur;
use App\Http\Controllers\Administration\CorridorAdminControleur;
use App\Http\Controllers\Administration\DocumentAdminControleur;
use App\Http\Controllers\Administration\EvenementAdminControleur;
use App\Http\Controllers\Administration\JournalSecuriteAdminControleur;
use App\Http\Controllers\Administration\NewsletterAdminControleur;
use App\Http\Controllers\Administration\TableauBordAdminControleur;
use App\Http\Controllers\Administration\UtilisateurAdminControleur;
use App\Http\Controllers\Redaction\ArticleRedactionControleur;
use App\Http\Controllers\Redaction\ModerationCommentaireControleur;
use App\Http\Middleware\VerifierAccesAdmin;
use Illuminate\Support\Facades\Route;

// --- Pages Publiques du Journal ---
Route::get('/', [AccueilControleur::class, 'index'])->name('accueil');

Route::redirect('/connexion', '/admin/connexion')->name('login');

// Moteur de recherche
Route::get('/recherche', [RechercheControleur::class, 'rechercher'])->name('recherche');

// Articles et flux thématiques
Route::prefix('articles')->name('articles.')->group(function () {
    Route::get('/', [ArticleControleur::class, 'index'])->name('index');
    Route::get('/{slug}', [ArticleControleur::class, 'show'])->name('show');
});

Route::get('/rubriques/{slug}', [ArticleControleur::class, 'parCategorie'])->name('categories.articles');
Route::get('/tags/{slug}', [ArticleControleur::class, 'parTag'])->name('tags.articles');
Route::get('/corridors/{slug}', [ArticleControleur::class, 'parCorridor'])->name('corridors.articles');
Route::get('/zones/{slug}', [ArticleControleur::class, 'parZone'])->name('zones.articles');

// Dossiers thématiques & grandes enquêtes
Route::prefix('dossiers')->name('dossiers.')->group(function () {
    Route::get('/', [DossierThematiqueControleur::class, 'index'])->name('index');
    Route::get('/{slug}', [DossierThematiqueControleur::class, 'show'])->name('show');
});

// Agenda salons et conférences logistiques
Route::prefix('evenements')->name('evenements.')->group(function () {
    Route::get('/', [EvenementLogistiqueControleur::class, 'index'])->name('index');
    Route::get('/{slug}', [EvenementLogistiqueControleur::class, 'show'])->name('show');
});

// Commentaires lecteurs
Route::prefix('commentaires')->name('commentaires.')->group(function () {
    Route::post('/', [CommentaireControleur::class, 'store'])->name('store');
    Route::post('/{id}/voter', [CommentaireControleur::class, 'voter'])->name('voter');
    Route::post('/{id}/signaler', [CommentaireControleur::class, 'signaler'])->name('signaler');
});

// Newsletter & Veille logistique
Route::prefix('newsletter')->name('newsletter.')->group(function () {
    Route::post('/inscription', [NewsletterControleur::class, 'inscrire'])->name('inscrire');
    Route::get('/confirmation/{token}', [NewsletterControleur::class, 'confirmer'])->name('confirmer');
    Route::get('/desinscription/{token}', [NewsletterControleur::class, 'desinscrire'])->name('desinscrire');
});

// Abonnements & Paywall lecteur
Route::prefix('abonnements')->name('abonnements.')->group(function () {
    Route::get('/', [AbonnementControleur::class, 'index'])->name('index');
    Route::post('/souscrire', [AbonnementControleur::class, 'souscrire'])->middleware('auth')->name('souscrire');
    Route::get('/confirmation/{reference}', [AbonnementControleur::class, 'confirmation'])->middleware('auth')->name('confirmation');
});
Route::get('/mon-abonnement', [AbonnementControleur::class, 'monAbonnement'])->middleware('auth')->name('abonnements.espace');

// Livres blancs et ressources téléchargeables
Route::prefix('ressources')->name('ressources.')->group(function () {
    Route::get('/', [DocumentRessourceControleur::class, 'index'])->name('index');
    Route::get('/{slug}/telecharger', [DocumentRessourceControleur::class, 'telecharger'])->name('telecharger');
});

// --- Espace Rédaction et Modération (Authentifié) ---
Route::middleware(['auth'])->prefix('redaction')->name('redaction.')->group(function () {
    // Gestion des articles
    Route::resource('articles', ArticleRedactionControleur::class)->except(['show']);
    Route::post('/articles/{id}/publier', [ArticleRedactionControleur::class, 'publier'])->name('articles.publier');
    Route::post('/articles/{id}/archiver', [ArticleRedactionControleur::class, 'archiver'])->name('articles.archiver');

    // Modération des commentaires
    Route::prefix('commentaires')->name('commentaires.')->group(function () {
        Route::get('/', [ModerationCommentaireControleur::class, 'index'])->name('index');
        Route::post('/{id}/approuver', [ModerationCommentaireControleur::class, 'approuver'])->name('approuver');
        Route::post('/{id}/rejeter', [ModerationCommentaireControleur::class, 'rejeter'])->name('rejeter');
        Route::post('/{id}/spam', [ModerationCommentaireControleur::class, 'marquerSpam'])->name('spam');
        Route::delete('/{id}', [ModerationCommentaireControleur::class, 'destroy'])->name('destroy');
    });
});

// --- Espace Administration Sécurisé (Console CARGO) ---
Route::prefix('admin')->group(function () {
    // Authentification Administrative renforcée (Accès public / formulaire sécurisé)
    Route::get('/connexion', [ConnexionAdminControleur::class, 'afficherFormulaire'])->name('admin.connexion');
    Route::post('/connexion', [ConnexionAdminControleur::class, 'connecter'])->name('admin.connexion.post');
    Route::post('/deconnexion', [ConnexionAdminControleur::class, 'deconnecter'])->name('admin.deconnexion');

    // Console protégée par vérification d'authentification et de rôle administrateur strict
    Route::middleware(['auth', VerifierAccesAdmin::class])->name('admin.')->group(function () {
        // Tableau de bord exécutif
        Route::get('/', [TableauBordAdminControleur::class, 'index'])->name('tableau-bord');

        // Gestion des utilisateurs et permissions
        Route::prefix('utilisateurs')->name('utilisateurs.')->group(function () {
            Route::get('/', [UtilisateurAdminControleur::class, 'index'])->name('index');
            Route::put('/{id}', [UtilisateurAdminControleur::class, 'update'])->name('update');
            Route::post('/{id}/reinitialiser-mdp', [UtilisateurAdminControleur::class, 'reinitialiserMotDePasse'])->name('mdp');
        });

        // Supervision globale des articles & alertes
        Route::prefix('articles')->name('articles.')->group(function () {
            Route::get('/', [ArticleAdminControleur::class, 'index'])->name('index');
            Route::post('/{id}/alerte-urgente', [ArticleAdminControleur::class, 'basculerAlerte'])->name('alerte');
            Route::post('/{id}/paywall-pro', [ArticleAdminControleur::class, 'basculerPro'])->name('pro');
            Route::post('/{id}/a-la-une', [ArticleAdminControleur::class, 'basculerAlaUne'])->name('alaune');
            Route::delete('/{id}', [ArticleAdminControleur::class, 'destroy'])->name('destroy');
        });

        // Corridors logistiques et alertes flux
        Route::prefix('corridors')->name('corridors.')->group(function () {
            Route::get('/', [CorridorAdminControleur::class, 'index'])->name('index');
            Route::post('/', [CorridorAdminControleur::class, 'store'])->name('store');
            Route::put('/{id}/statut', [CorridorAdminControleur::class, 'updateStatut'])->name('statut');
        });

        // Piste d'audit et logs de sécurité
        Route::prefix('securite')->name('securite.')->group(function () {
            Route::get('/', [JournalSecuriteAdminControleur::class, 'index'])->name('index');
            Route::post('/purger', [JournalSecuriteAdminControleur::class, 'purger'])->name('purger');
        });

        // Gestion des rubriques éditoriales
        Route::prefix('categories')->name('categories.')->group(function () {
            Route::get('/', [CategorieAdminControleur::class, 'index'])->name('index');
            Route::post('/', [CategorieAdminControleur::class, 'store'])->name('store');
            Route::put('/{id}', [CategorieAdminControleur::class, 'update'])->name('update');
            Route::post('/{id}/menu', [CategorieAdminControleur::class, 'basculerMenu'])->name('menu');
            Route::delete('/{id}', [CategorieAdminControleur::class, 'destroy'])->name('destroy');
        });

        // Formules et abonnés PRO
        Route::prefix('abonnements')->name('abonnements.')->group(function () {
            Route::get('/', [AbonnementAdminControleur::class, 'index'])->name('index');
            Route::post('/basculer-paywall', [AbonnementAdminControleur::class, 'basculerPaywallGlobal'])->name('basculer-paywall');
            Route::post('/formules', [AbonnementAdminControleur::class, 'storeFormule'])->name('formules.store');
            Route::post('/formules/{id}/basculer', [AbonnementAdminControleur::class, 'basculerFormule'])->name('formules.basculer');
            Route::post('/{id}/statut', [AbonnementAdminControleur::class, 'updateStatutAbonnement'])->name('update-statut');
        });

        // Audience Newsletter
        Route::prefix('newsletter')->name('newsletter.')->group(function () {
            Route::get('/', [NewsletterAdminControleur::class, 'index'])->name('index');
            Route::post('/{id}/basculer', [NewsletterAdminControleur::class, 'basculerEtat'])->name('basculer');
            Route::delete('/{id}', [NewsletterAdminControleur::class, 'destroy'])->name('destroy');
        });

        // Salons et événements logistiques
        Route::prefix('evenements')->name('evenements.')->group(function () {
            Route::get('/', [EvenementAdminControleur::class, 'index'])->name('index');
            Route::post('/', [EvenementAdminControleur::class, 'store'])->name('store');
            Route::post('/{id}/vedette', [EvenementAdminControleur::class, 'basculerVedette'])->name('vedette');
            Route::delete('/{id}', [EvenementAdminControleur::class, 'destroy'])->name('destroy');
        });

        // Livres blancs et documents techniques
        Route::prefix('documents')->name('documents.')->group(function () {
            Route::get('/', [DocumentAdminControleur::class, 'index'])->name('index');
            Route::post('/', [DocumentAdminControleur::class, 'store'])->name('store');
            Route::post('/{id}/pro', [DocumentAdminControleur::class, 'basculerPro'])->name('pro');
            Route::delete('/{id}', [DocumentAdminControleur::class, 'destroy'])->name('destroy');
        });

        // Modération centralisée des commentaires
        Route::prefix('commentaires')->name('commentaires.')->group(function () {
            Route::get('/', [CommentaireAdminControleur::class, 'index'])->name('index');
            Route::post('/{id}/approuver', [CommentaireAdminControleur::class, 'approuver'])->name('approuver');
            Route::post('/{id}/spam', [CommentaireAdminControleur::class, 'marquerSpam'])->name('spam');
            Route::delete('/{id}', [CommentaireAdminControleur::class, 'destroy'])->name('destroy');
        });
    });
});
