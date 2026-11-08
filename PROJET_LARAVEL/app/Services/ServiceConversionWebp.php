<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

/**
 * Service dedie a la conversion et compression d'images au format WebP.
 * Responsabilites :
 * - Conversion exclusive des images en format moderne .webp
 * - Compression optimale (qualite 82% par defaut pour rapport taille/nettete optimal)
 * - Preservation absolue du canal alpha (transparence PNG et WebP)
 * - Redimensionnement proportionnel intelligent (max 1920x1080 sans deformation)
 * - Fallback robuste et journalisation avec Log en cas d'impossibilite technique
 */
class ServiceConversionWebp
{
    /**
     * Qualite de compression WebP par defaut (0 a 100).
     * 82 offre une qualite visuelle quasi-parfaite tout en reduisant le poids de 60% a 85%.
     */
    public const QUALITE_DEFAUT = 82;

    /**
     * Dimensions maximales recommandees pour les photos d'en-tete d'articles.
     */
    public const LARGEUR_MAX_DEFAUT = 1920;
    public const HAUTEUR_MAX_DEFAUT = 1080;

    protected int $qualiteCompression;
    protected int $largeurMaximale;
    protected int $hauteurMaximale;

    public function __construct(
        int $qualite = self::QUALITE_DEFAUT,
        int $largeurMax = self::LARGEUR_MAX_DEFAUT,
        int $hauteurMax = self::HAUTEUR_MAX_DEFAUT
    ) {
        $this->qualiteCompression = max(10, min(100, $qualite));
        $this->largeurMaximale = max(320, $largeurMax);
        $this->hauteurMaximale = max(240, $hauteurMax);
    }

    /**
     * Convertir et compresser un fichier televerse en WebP.
     */
    public function convertirEtCompresserFichier(
        UploadedFile $fichier,
        string $dossierCible,
        ?string $nomBase = null
    ): string {
        $nomSlug = $nomBase ?: Str::slug(pathinfo($fichier->getClientOriginalName(), PATHINFO_FILENAME));
        $nomFichierWebp = "{$nomSlug}-" . uniqid() . '.webp';
        $cheminCompletWebp = rtrim($dossierCible, '/') . '/' . $nomFichierWebp;

        if (! File::isDirectory($dossierCible)) {
            File::makeDirectory($dossierCible, 0755, true, true);
        }

        // Tenter la conversion avec GD
        if ($this->verifierDisponibiliteWebp()) {
            $succes = $this->optimiserFichierVersWebp(
                $fichier->getRealPath(),
                $cheminCompletWebp,
                $this->qualiteCompression
            );

            if ($succes && file_exists($cheminCompletWebp)) {
                $tailleInitialeKo = round($fichier->getSize() / 1024, 1);
                $tailleWebpKo = round(filesize($cheminCompletWebp) / 1024, 1);
                Log::info("Image article convertie en WebP : {$nomFichierWebp} ({$tailleInitialeKo} Ko -> {$tailleWebpKo} Ko)");

                return $nomFichierWebp;
            }
        }

        // Repli fiable si WebP non disponible ou echec de conversion
        $extensionOriginale = strtolower($fichier->getClientOriginalExtension() ?: 'jpg');
        $nomRepli = "{$nomSlug}-" . uniqid() . ".{$extensionOriginale}";
        $fichier->move($dossierCible, $nomRepli);

        Log::warning("Repli format original pour l'image article : {$nomRepli} (WebP non applique)");

        return $nomRepli;
    }

    /**
     * Convertir un flux binaire (ex: telechargement HTTP distant) en WebP.
     */
    public function convertirFluxBinaireEnWebp(
        string $contenuBinaire,
        string $dossierCible,
        ?string $nomBase = null
    ): string {
        $nomSlug = $nomBase ?: 'article-' . uniqid();
        $nomFichierWebp = "{$nomSlug}-" . uniqid() . '.webp';
        $cheminCompletWebp = rtrim($dossierCible, '/') . '/' . $nomFichierWebp;

        if (! File::isDirectory($dossierCible)) {
            File::makeDirectory($dossierCible, 0755, true, true);
        }

        if ($this->verifierDisponibiliteWebp()) {
            $imageRessource = @imagecreatefromstring($contenuBinaire);
            if ($imageRessource !== false) {
                $succes = $this->traiterEtSauvegarderRessourceGd($imageRessource, $cheminCompletWebp);
                imagedestroy($imageRessource);

                if ($succes && file_exists($cheminCompletWebp)) {
                    return $nomFichierWebp;
                }
            }
        }

        // Sauvegarde brute en cas d'impossibilite de conversion
        $nomRepli = "{$nomSlug}-" . uniqid() . '.jpg';
        File::put(rtrim($dossierCible, '/') . '/' . $nomRepli, $contenuBinaire);

        return $nomRepli;
    }

    /**
     * Optimiser et convertir une image source vers un fichier WebP cible.
     */
    public function optimiserFichierVersWebp(
        string $cheminSource,
        string $cheminDestination,
        ?int $qualite = null
    ): bool {
        try {
            if (! file_exists($cheminSource)) {
                return false;
            }

            $contenu = @file_get_contents($cheminSource);
            if ($contenu === false) {
                return false;
            }

            $imageSource = @imagecreatefromstring($contenu);
            if (! $imageSource) {
                return false;
            }

            $succes = $this->traiterEtSauvegarderRessourceGd(
                $imageSource,
                $cheminDestination,
                $qualite ?? $this->qualiteCompression
            );

            imagedestroy($imageSource);

            return $succes;
        } catch (\Throwable $erreur) {
            Log::error("Erreur lors de la conversion WebP : " . $erreur->getMessage());
            return false;
        }
    }

    /**
     * Redimensionner si necessaire, preserver la transparence et generer le WebP via GD.
     *
     * @param \GdImage|resource $imageSource
     */
    protected function traiterEtSauvegarderRessourceGd(
        $imageSource,
        string $cheminDestination,
        ?int $qualite = null
    ): bool {
        $largeurSource = imagesx($imageSource);
        $hauteurSource = imagesy($imageSource);

        if ($largeurSource <= 0 || $hauteurSource <= 0) {
            return false;
        }

        [$nouvelleLargeur, $nouvelleHauteur] = $this->calculerDimensionsRedimensionnement(
            $largeurSource,
            $hauteurSource
        );

        $imageCible = imagecreatetruecolor($nouvelleLargeur, $nouvelleHauteur);
        if (! $imageCible) {
            return false;
        }

        // Configuration stricte du canal alpha pour transparence parfaite
        imagealphablending($imageCible, false);
        imagesavealpha($imageCible, true);

        $couleurTransparente = imagecolorallocatealpha($imageCible, 0, 0, 0, 127);
        imagefilledrectangle($imageCible, 0, 0, $nouvelleLargeur, $nouvelleHauteur, $couleurTransparente);

        // Re-echantillonnage de haute precision
        imagecopyresampled(
            $imageCible,
            $imageSource,
            0, 0, 0, 0,
            $nouvelleLargeur,
            $nouvelleHauteur,
            $largeurSource,
            $hauteurSource
        );

        // Ecriture du WebP compresse
        $tauxQualite = $qualite ?? $this->qualiteCompression;
        $succes = imagewebp($imageCible, $cheminDestination, $tauxQualite);

        imagedestroy($imageCible);

        return $succes;
    }

    /**
     * Calculer les dimensions proportionnelles sans agrandir les petites images.
     *
     * @return array{0: int, 1: int}
     */
    public function calculerDimensionsRedimensionnement(int $largeurOrigine, int $hauteurOrigine): array
    {
        if ($largeurOrigine <= $this->largeurMaximale && $hauteurOrigine <= $this->hauteurMaximale) {
            return [$largeurOrigine, $hauteurOrigine];
        }

        $ratioLargeur = $this->largeurMaximale / $largeurOrigine;
        $ratioHauteur = $this->hauteurMaximale / $hauteurOrigine;
        $facteur = min($ratioLargeur, $ratioHauteur);

        $nouvelleLargeur = (int) max(1, round($largeurOrigine * $facteur));
        $nouvelleHauteur = (int) max(1, round($hauteurOrigine * $facteur));

        return [$nouvelleLargeur, $nouvelleHauteur];
    }

    /**
     * Verifier si l'environnement PHP dispose des fonctions natives WebP.
     */
    public function verifierDisponibiliteWebp(): bool
    {
        return extension_loaded('gd') && function_exists('imagewebp');
    }
}
