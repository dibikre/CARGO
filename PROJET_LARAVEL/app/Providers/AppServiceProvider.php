<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Limite pour compatibilité stricte MySQL/MariaDB sur hébergement partagé (InnoDB 767 octets max pour index utf8mb4)
        Schema::defaultStringLength(191);

        // Partager les rubriques actives du menu avec le layout et les vues du journal
        \Illuminate\Support\Facades\View::composer(['layouts.journal', 'journal.*'], function ($vue) {
            try {
                if (Schema::hasTable('categories')) {
                    $rubriques = \App\Models\Categorie::menu()->get();
                    if ($rubriques->isEmpty()) {
                        $rubriques = \App\Models\Categorie::actif()->limit(6)->get();
                    }
                    $vue->with('categoriesMenu', $rubriques);
                } else {
                    $vue->with('categoriesMenu', collect());
                }

                // Partager les matieres premieres brutes pour le bandeau defilant (max 20)
                if (Schema::hasTable('matieres_premieres')) {
                    $matieresPremieres = \App\Models\MatierePremiere::actif()
                        ->limit(\App\Models\MatierePremiere::NOMBRE_MAX_PRODUITS)
                        ->get();
                    $vue->with('matieresPremieres', $matieresPremieres);
                } else {
                    $vue->with('matieresPremieres', collect());
                }
            } catch (\Throwable) {
                $vue->with('categoriesMenu', collect());
                $vue->with('matieresPremieres', collect());
            }
        });

        // Partager le statut des abonnements (mode 100% gratuit si inactif)
        \Illuminate\Support\Facades\View::composer(['layouts.*', 'journal.*', 'administration.*'], function ($vue) {
            try {
                $abonnementsActifs = \App\Models\ConfigurationSysteme::abonnementsSontActifs();
            } catch (\Throwable) {
                $abonnementsActifs = true;
            }
            $vue->with('abonnementsActifs', $abonnementsActifs);
        });
    }
}
