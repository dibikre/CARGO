<?php

namespace Tests\Feature;

use App\Services\ServiceConversionWebp;
use App\Services\ServiceGestionImage;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

/**
 * Tests fonctionnels et unitaires du service de conversion et compression WebP.
 * Verifie que seules les images d'articles sont converties en .webp, avec
 * calcul proportionnel des dimensions et preservation de la qualite visuelle.
 */
class ConversionImageWebpTest extends TestCase
{
    protected ServiceConversionWebp $serviceWebp;
    protected ServiceGestionImage $serviceImage;

    protected function setUp(): void
    {
        parent::setUp();
        $this->serviceWebp = new ServiceConversionWebp();
        $this->serviceImage = new ServiceGestionImage($this->serviceWebp);
    }

    /**
     * Verifie le calcul proportionnel des dimensions pour les grandes images.
     */
    public function test_calcul_dimensions_proportionnelles_grande_image(): void
    {
        // Image 4K 3840x2160 doit etre reduite a 1920x1080 sans deformation
        [$largeur, $hauteur] = $this->serviceWebp->calculerDimensionsRedimensionnement(3840, 2160);

        $this->assertEquals(1920, $largeur);
        $this->assertEquals(1080, $hauteur);
    }

    /**
     * Verifie que les petites images ne sont pas agrandies artificiellement.
     */
    public function test_petites_images_non_redimensionnees(): void
    {
        [$largeur, $hauteur] = $this->serviceWebp->calculerDimensionsRedimensionnement(800, 600);

        $this->assertEquals(800, $largeur);
        $this->assertEquals(600, $hauteur);
    }

    /**
     * Verifie que le dossier 'articles' convertit le fichier en WebP si GD disponible,
     * ou applique le repli robuste si GD est inactif.
     */
    public function test_traitement_image_article_produit_webp_ou_repli_robuste(): void
    {
        $dossierTest = public_path('images/articles');
        if (! File::isDirectory($dossierTest)) {
            File::makeDirectory($dossierTest, 0755, true, true);
        }

        $fichierTemporaire = UploadedFile::fake()->image('navire-porte-conteneur.jpg', 1200, 800);
        $cheminResultat = $this->serviceImage->traiterImageTeleversee($fichierTemporaire, 'articles');

        $this->assertStringStartsWith('images/articles/', $cheminResultat);

        if ($this->serviceWebp->verifierDisponibiliteWebp()) {
            $this->assertStringEndsWith('.webp', $cheminResultat);
            $this->assertFileExists(public_path($cheminResultat));
            // Nettoyage
            @unlink(public_path($cheminResultat));
        } else {
            // En environnement sans extension GD, le repli conserve l'image sans crash
            $this->assertFileExists(public_path($cheminResultat));
            @unlink(public_path($cheminResultat));
        }
    }

    /**
     * Verifie que les images hors articles (ex: categories) conservent leur format d'origine.
     */
    public function test_categories_conservent_format_d_origine_sans_conversion_webp(): void
    {
        $dossierTest = public_path('images/categories');
        if (! File::isDirectory($dossierTest)) {
            File::makeDirectory($dossierTest, 0755, true, true);
        }

        $fichierPng = UploadedFile::fake()->image('banniere-maritime.png', 800, 400);
        $cheminResultat = $this->serviceImage->traiterImageTeleversee($fichierPng, 'categories');

        $this->assertStringStartsWith('images/categories/', $cheminResultat);
        $this->assertStringEndsWith('.png', $cheminResultat);
        $this->assertFileExists(public_path($cheminResultat));

        @unlink(public_path($cheminResultat));
    }
}
