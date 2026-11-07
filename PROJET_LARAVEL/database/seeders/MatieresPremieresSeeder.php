<?php

namespace Database\Seeders;

use App\Models\MatierePremiere;
use Illuminate\Database\Seeder;

/**
 * Seeder initialisant les matieres premieres brutes pour le bandeau defilant.
 * Respecte la limite stricte de 20 produits max et inclut des donnees de courbe
 * realistes (5 a 10 noeuds chronologiques).
 */
class MatieresPremieresSeeder extends Seeder
{
    public function run(): void
    {
        $baseTs = 1792800000000;
        $intervalle = 259200000; // ~3 jours en ms

        // Creation dynamique compacte des series de 7 noeuds pour 12 matieres
        $matieres = [
            ['nom' => 'Cacao Brut', 'code' => 'COCOA', 'dev' => 'USD', 'u' => 'Tonne', 'img' => 'images/matieres/cacao.png', 'ord' => 1, 'prix' => [5620.04471, 5492.79088, 5463.49258, 5456.59578, 5394.52022, 5457.98716, 5154.56431]],
            ['nom' => 'Pétrole Brent', 'code' => 'BRENT', 'dev' => 'USD', 'u' => 'Baril', 'img' => 'images/matieres/petrole.png', 'ord' => 2, 'prix' => [74.20, 74.95, 75.80, 76.40, 77.10, 76.85, 78.35]],
            ['nom' => 'Gaz Naturel', 'code' => 'NATGAS', 'dev' => 'USD', 'u' => 'MMBtu', 'img' => 'images/matieres/gaz.png', 'ord' => 3, 'prix' => [2.85, 2.78, 2.65, 2.60, 2.52, 2.48, 2.45]],
            ['nom' => 'Huile de Palme', 'code' => 'PALMOIL', 'dev' => 'USD', 'u' => 'Tonne', 'img' => 'images/matieres/huile_palme.png', 'ord' => 4, 'prix' => [910.50, 925.00, 938.40, 932.10, 945.60, 952.80, 965.20]],
            ['nom' => 'Or Fin', 'code' => 'GOLD', 'dev' => 'USD', 'u' => 'Once troy', 'img' => 'images/matieres/or.png', 'ord' => 5, 'prix' => [2680.50, 2692.10, 2705.80, 2698.40, 2720.00, 2735.60, 2748.90]],
            ['nom' => 'Cuivre', 'code' => 'COPPER', 'dev' => 'USD', 'u' => 'Livre', 'img' => 'images/matieres/cuivre.png', 'ord' => 6, 'prix' => [4.35, 4.30, 4.22, 4.28, 4.19, 4.15, 4.12]],
            ['nom' => 'Café Arabica', 'code' => 'COFFEE', 'dev' => 'USD', 'u' => 'Livre', 'img' => 'images/matieres/cafe.png', 'ord' => 7, 'prix' => [2.50, 2.55, 2.62, 2.68, 2.74, 2.80, 2.85]],
            ['nom' => 'Blé Tendre', 'code' => 'WHEAT', 'dev' => 'EUR', 'u' => 'Tonne', 'img' => 'images/matieres/ble.png', 'ord' => 8, 'prix' => [228.00, 224.50, 221.00, 219.50, 218.00, 215.20, 212.80]],
            ['nom' => 'Coton Brut', 'code' => 'COTTON', 'dev' => 'USD', 'u' => 'Livre', 'img' => 'images/matieres/coton.png', 'ord' => 9, 'prix' => [0.72, 0.73, 0.74, 0.75, 0.76, 0.75, 0.78]],
            ['nom' => 'Aluminium', 'code' => 'ALUM', 'dev' => 'USD', 'u' => 'Tonne', 'img' => 'images/matieres/aluminium.png', 'ord' => 10, 'prix' => [2610.00, 2595.00, 2580.00, 2565.00, 2540.00, 2525.00, 2510.00]],
            ['nom' => 'Graine de Soja', 'code' => 'SOYBEAN', 'dev' => 'USD', 'u' => 'Boisseau', 'img' => 'images/matieres/soja.png', 'ord' => 11, 'prix' => [10.15, 10.22, 10.35, 10.42, 10.50, 10.58, 10.65]],
            ['nom' => 'Minerai de Fer', 'code' => 'IRONORE', 'dev' => 'USD', 'u' => 'Tonne', 'img' => 'images/matieres/minerai_fer.png', 'ord' => 12, 'prix' => [108.50, 106.00, 104.20, 102.80, 101.50, 100.20, 98.70]],
        ];

        foreach ($matieres as $m) {
            $seriePoints = [];
            foreach ($m['prix'] as $index => $prixVal) {
                $seriePoints[] = [$baseTs + ($index * $intervalle), $prixVal];
            }

            $pEntree = (float) $m['prix'][0];
            $pSortie = (float) end($m['prix']);
            $variation = $pEntree > 0 ? round((($pSortie - $pEntree) / $pEntree) * 100, 2) : 0;

            MatierePremiere::updateOrCreate(
                ['code_symbole' => $m['code']],
                [
                    'nom' => $m['nom'],
                    'devise' => $m['dev'],
                    'unite_mesure' => $m['u'],
                    'prix_actuel' => $pSortie,
                    'pourcentage_variation' => $variation,
                    'image_png' => $m['img'],
                    'donnees_courbe_json' => $seriePoints,
                    'ordre_affichage' => $m['ord'],
                    'est_actif' => true,
                ]
            );
        }
    }
}
