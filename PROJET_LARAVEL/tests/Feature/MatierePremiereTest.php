<?php

namespace Tests\Feature;

use App\Models\MatierePremiere;
use App\Models\User;
use App\Services\ServiceMatierePremiere;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

/**
 * Tests fonctionnels et unitaires du module de bandeau des matieres premieres.
 * Verifie la validation des 5-10 noeuds JSON, le calcul d'ecart entree-sortie,
 * le quota strict de 20 produits et le bon affichage du bandeau.
 */
class MatierePremiereTest extends TestCase
{
    protected ServiceMatierePremiere $serviceMatiere;

    protected function setUp(): void
    {
        parent::setUp();
        $this->serviceMatiere = app(ServiceMatierePremiere::class);
    }

    /**
     * Verifie le calcul de l'ecart entre l'entree et la sortie.
     */
    public function test_calcul_ecart_entree_sortie_conforme_exemple_utilisateur(): void
    {
        // Exemple utilisateur exact
        $points = [
            [1792800000000, 5620.04471],
            [1793059200000, 5492.79088],
            [1793253600000, 5463.49258],
            [1793444400000, 5456.59578],
            [1793700000000, 5394.52022],
            [1793892600000, 5457.98716],
            [1794088800000, 5154.56431],
        ];

        $ecart = $this->serviceMatiere->calculerEcartEntreeSortie($points);
        // (5154.56431 - 5620.04471) / 5620.04471 * 100 = -8.28%
        $this->assertEquals(-8.28, $ecart);
    }

    /**
     * Verifie que les courbes avec moins de 5 noeuds sont rejetees.
     */
    public function test_rejet_courbe_avec_moins_de_cinq_noeuds(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $jsonMoinsDe5 = json_encode([
            [1792800000000, 100],
            [1793059200000, 105],
            [1793253600000, 110],
            [1793444400000, 108],
        ]);

        $this->serviceMatiere->validerEtParserNoeudsCourbe($jsonMoinsDe5);
    }

    /**
     * Verifie que les courbes avec plus de 10 noeuds sont rejetees.
     */
    public function test_rejet_courbe_avec_plus_de_dix_noeuds(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $points11 = [];
        for ($i = 0; $i < 11; $i++) {
            $points11[] = [1792800000000 + ($i * 86400000), 100 + $i];
        }

        $this->serviceMatiere->validerEtParserNoeudsCourbe(json_encode($points11));
    }

    /**
     * Verifie la generation du trace SVG valide.
     */
    public function test_generation_trace_svg(): void
    {
        $matiere = new MatierePremiere([
            'nom' => 'Test Cacao',
            'code_symbole' => 'TEST_COCOA',
            'donnees_courbe_json' => [
                [1792800000000, 100],
                [1793059200000, 120],
                [1793253600000, 110],
                [1793444400000, 130],
                [1793700000000, 125],
            ],
        ]);

        $trace = $matiere->genererTraceSvg(60, 20);
        $this->assertStringStartsWith('M', $trace);
        $this->assertStringContainsString('L', $trace);
    }

    /**
     * Verifie que la limite stricte de 20 produits max est respectee.
     */
    public function test_limite_maximale_de_vingt_produits(): void
    {
        $this->assertEquals(20, MatierePremiere::NOMBRE_MAX_PRODUITS);
    }
}
