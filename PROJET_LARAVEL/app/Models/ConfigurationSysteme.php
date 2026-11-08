<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;

class ConfigurationSysteme extends Model
{
    use HasFactory;

    protected $table = 'configurations_systeme';

    protected $fillable = [
        'cle',
        'valeur',
        'description',
    ];

    /**
     * Récupérer une valeur de configuration avec mise en cache et repli par défaut.
     */
    public static function obtenirValeur(string $cle, mixed $defaut = null): mixed
    {
        try {
            return Cache::remember("config_sys_{$cle}", 300, function () use ($cle, $defaut) {
                if (! Schema::hasTable('configurations_systeme')) {
                    return $defaut;
                }

                $element = self::where('cle', $cle)->first();
                return $element ? $element->valeur : $defaut;
            });
        } catch (\Throwable) {
            return $defaut;
        }
    }

    /**
     * Définir ou mettre à jour une valeur de configuration.
     */
    public static function definirValeur(string $cle, mixed $valeur, ?string $description = null): void
    {
        try {
            if (Schema::hasTable('configurations_systeme')) {
                self::updateOrCreate(
                    ['cle' => $cle],
                    [
                        'valeur' => (string) $valeur,
                        'description' => $description,
                    ]
                );
            }
            Cache::forget("config_sys_{$cle}");
        } catch (\Throwable) {
            // Repli transparent
        }
    }

    /**
     * Vérifier si les abonnements et le paywall sont actuellement actifs.
     */
    public static function abonnementsSontActifs(): bool
    {
        $valeur = self::obtenirValeur('abonnements_actifs', '1');
        return $valeur === '1' || $valeur === true || $valeur === 1;
    }

    /**
     * Basculer globalement le statut des abonnements (Monétisé <-> 100% Gratuit).
     */
    public static function basculerAbonnements(): bool
    {
        $nouvelEtat = ! self::abonnementsSontActifs();
        self::definirValeur(
            'abonnements_actifs',
            $nouvelEtat ? '1' : '0',
            'Statut global des abonnements et paywall'
        );

        return $nouvelEtat;
    }

    /**
     * Vérifier si les nouvelles souscriptions d'abonnements sont autorisées.
     */
    public static function souscriptionsSontActives(): bool
    {
        $valeur = self::obtenirValeur('souscriptions_actives', '1');
        return $valeur === '1' || $valeur === true || $valeur === 1;
    }

    /**
     * Basculer la possibilité pour les lecteurs de souscrire à un abonnement.
     */
    public static function basculerSouscriptions(): bool
    {
        $nouvelEtat = ! self::souscriptionsSontActives();
        self::definirValeur(
            'souscriptions_actives',
            $nouvelEtat ? '1' : '0',
            'Autorisation de nouvelles souscriptions d\'abonnements'
        );

        return $nouvelEtat;
    }

    /**
     * Vérifier si la fonctionnalité des corridors de fret est active côté visiteur.
     */
    public static function corridorsSontActifs(): bool
    {
        $valeur = self::obtenirValeur('corridors_actifs', '1');
        return $valeur === '1' || $valeur === true || $valeur === 1;
    }

    /**
     * Basculer l'activation publique des corridors de fret et de la gestion des perturbations.
     */
    public static function basculerCorridors(): bool
    {
        $nouvelEtat = ! self::corridorsSontActifs();
        self::definirValeur(
            'corridors_actifs',
            $nouvelEtat ? '1' : '0',
            'Affichage public des corridors de fret et alertes de perturbations'
        );

        return $nouvelEtat;
    }
}
