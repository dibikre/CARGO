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
            } catch (\Throwable) {
                $vue->with('categoriesMenu', collect());
            }
        });
    }
}
