<?php

namespace App\Services;

use App\Models\MatierePremiere;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use InvalidArgumentException;

/**
 * Service metier gerant les matieres premieres brutes pour le bandeau defilant.
 * Responsabilites : validation des 5 a 10 noeuds JSON, calcul d'ecart entree-sortie,
 * redimensionnement haute precision des images PNG avec preservation de la transparence
 * alpha, stockage securise et enforcement du quota (max 20).
 */
class ServiceMatierePremiere
{
    /**
     * Dimensions cibles standard pour les icones du bandeau (format carre 80x80).
     */
    public const LARGEUR_CIBLE_PNG = 80;
    public const HAUTEUR_CIBLE_PNG = 80;
    public const LARGEUR_MAX_PNG = 80;
    public const HAUTEUR_MAX_PNG = 80;

    /**
     * Valider et normaliser une chaine ou un tableau de noeuds de courbe.
     *
     * @param string|array|null $donnees
     * @return array{points: array, prixActuel: float, variation: float, jsonNormalise: string}
     */
    public function validerEtTraiterPointsCourbe(string|array|null $donnees): array
    {
        if (empty($donnees)) {
            throw new InvalidArgumentException('Curve data JSON cannot be empty.');
        }

        $noeuds = is_string($donnees) ? json_decode(trim($donnees), true) : $donnees;

        if (! is_array($noeuds)) {
            throw new InvalidArgumentException('Invalid JSON format. Expected an array of [timestamp, value] points.');
        }

        $nbPoints = count($noeuds);
        if ($nbPoints < 5 || $nbPoints > 10) {
            throw new InvalidArgumentException("Curve must contain between 5 and 10 points (currently {$nbPoints} points provided).");
        }

        $pointsValides = [];
        foreach ($noeuds as $index => $point) {
            if (! is_array($point) || count($point) < 2 || ! is_numeric($point[1])) {
                throw new InvalidArgumentException("Invalid point at index {$index}. Expected [timestamp, numeric_value].");
            }
            $timestamp = (int) $point[0];
            $valeur = (float) $point[1];
            $pointsValides[] = [$timestamp, $valeur];
        }

        $premier = $pointsValides[0][1];
        $dernier = $pointsValides[$nbPoints - 1][1];
        $variation = ($premier != 0) ? round((($dernier - $premier) / $premier) * 100, 2) : 0.0;

        return [
            'points' => $pointsValides,
            'prixActuel' => $dernier,
            'variation' => $variation,
            'jsonNormalise' => json_encode($pointsValides, JSON_PRETTY_PRINT),
        ];
    }

    public function validerEtParserNoeudsCourbe(string|array|null $donnees): array
    {
        return $this->validerEtTraiterPointsCourbe($donnees)['points'];
    }

    public function calculerEcartEntreeSortie(array $points): float
    {
        if (count($points) < 2) {
            return 0.0;
        }
        $premier = (float) ($points[0][1] ?? 0);
        $dernier = (float) ($points[count($points) - 1][1] ?? 0);

        return ($premier != 0) ? round((($dernier - $premier) / $premier) * 100, 2) : 0.0;
    }

    /**
     * Traiter, redimensionner en taille optimale (96x96 max) tout en conservant
     * la transparence alpha complète du PNG, puis stocker le fichier.
     */
    public function traiterImagePng(UploadedFile $fichier, ?string $codeSymbole = null): string
    {
        $dossier = public_path('images/matieres');
        if (! file_exists($dossier)) {
            mkdir($dossier, 0755, true);
        }

        $nomFichier = ($codeSymbole ? Str::slug($codeSymbole) : 'matiere_' . time()) . '_' . uniqid() . '.png';
        $cheminComplet = $dossier . '/' . $nomFichier;

        // Tenter le redimensionnement avec GD si disponible
        if (extension_loaded('gd') && function_exists('imagecreatefrompng')) {
            $redimensionne = $this->redimensionnerPngAvecTransparence(
                $fichier->getRealPath(),
                $cheminComplet,
                self::LARGEUR_MAX_PNG,
                self::HAUTEUR_MAX_PNG
            );

            if ($redimensionne) {
                return 'images/matieres/' . $nomFichier;
            }
        }

        // Repli fiable si GD n'est pas actif
        $fichier->move($dossier, $nomFichier);

        return 'images/matieres/' . $nomFichier;
    }

    /**
     * Redimensionne et résout un PNG en exactement 80x80 pixels avec canal alpha préservé.
     */
    protected function redimensionnerPngAvecTransparence(string $sourcePath, string $destPath, int $largeurCible = self::LARGEUR_CIBLE_PNG, int $hauteurCible = self::HAUTEUR_CIBLE_PNG): bool
    {
        try {
            $info = @getimagesize($sourcePath);
            if (! $info || $info[0] <= 0 || $info[1] <= 0) {
                return false;
            }

            [$largeurSource, $hauteurSource] = $info;
            $ratio = min($largeurCible / $largeurSource, $hauteurCible / $hauteurSource);
            $nouvelleLargeur = (int) max(1, round($largeurSource * $ratio));
            $nouvelleHauteur = (int) max(1, round($hauteurSource * $ratio));
            $posX = (int) max(0, round(($largeurCible - $nouvelleLargeur) / 2));
            $posY = (int) max(0, round(($hauteurCible - $nouvelleHauteur) / 2));

            $imageSource = @imagecreatefrompng($sourcePath);
            if (! $imageSource) {
                return false;
            }

            $imageCible = imagecreatetruecolor($largeurCible, $hauteurCible);
            imagealphablending($imageCible, false);
            imagesavealpha($imageCible, true);

            $transparent = imagecolorallocatealpha($imageCible, 0, 0, 0, 127);
            imagefilledrectangle($imageCible, 0, 0, $largeurCible, $hauteurCible, $transparent);

            imagecopyresampled(
                $imageCible, $imageSource,
                $posX, $posY, 0, 0,
                $nouvelleLargeur, $nouvelleHauteur,
                $largeurSource, $hauteurSource
            );

            $succes = imagepng($imageCible, $destPath, 8);
            imagedestroy($imageSource);
            imagedestroy($imageCible);

            return $succes;
        } catch (\Throwable) {
            return false;
        }
    }

    public function creerMatierePremiere(array $donnees, UploadedFile $fichierImage): MatierePremiere
    {
        if (! $this->peutAjouterProduit()) {
            throw new \RuntimeException('The maximum limit of 20 commodities has been reached.');
        }

        $analyse = $this->validerEtTraiterPointsCourbe($donnees['donnees_courbe_json'] ?? null);
        $cheminImage = $this->traiterImagePng($fichierImage, $donnees['code_symbole']);

        return MatierePremiere::create([
            'nom' => $donnees['nom'],
            'code_symbole' => strtoupper($donnees['code_symbole']),
            'devise' => strtoupper($donnees['devise'] ?? 'USD'),
            'unite_mesure' => $donnees['unite_mesure'],
            'prix_actuel' => $analyse['prixActuel'],
            'pourcentage_variation' => $analyse['variation'],
            'image_png' => $cheminImage,
            'donnees_courbe_json' => $analyse['points'],
            'ordre_affichage' => (int) ($donnees['ordre_affichage'] ?? 0),
            'est_actif' => (bool) ($donnees['est_actif'] ?? true),
        ]);
    }

    public function mettreAJourMatierePremiere(MatierePremiere $matiere, array $donnees, ?UploadedFile $nouveauFichier = null): MatierePremiere
    {
        $analyse = $this->validerEtTraiterPointsCourbe($donnees['donnees_courbe_json'] ?? $matiere->donnees_courbe_json);

        $champs = [
            'nom' => $donnees['nom'],
            'code_symbole' => strtoupper($donnees['code_symbole']),
            'devise' => strtoupper($donnees['devise'] ?? 'USD'),
            'unite_mesure' => $donnees['unite_mesure'],
            'prix_actuel' => $analyse['prixActuel'],
            'pourcentage_variation' => $analyse['variation'],
            'donnees_courbe_json' => $analyse['points'],
            'ordre_affichage' => (int) ($donnees['ordre_affichage'] ?? 0),
            'est_actif' => (bool) ($donnees['est_actif'] ?? true),
        ];

        if ($nouveauFichier) {
            $champs['image_png'] = $this->traiterImagePng($nouveauFichier, $champs['code_symbole']);
        }

        $matiere->update($champs);

        return $matiere;
    }

    public function actualiserDonneesCourbe(MatierePremiere $matiere, string|array $donneesJson): MatierePremiere
    {
        $analyse = $this->validerEtTraiterPointsCourbe($donneesJson);

        $matiere->update([
            'prix_actuel' => $analyse['prixActuel'],
            'pourcentage_variation' => $analyse['variation'],
            'donnees_courbe_json' => $analyse['points'],
        ]);

        return $matiere;
    }

    public function supprimerMatierePremiere(MatierePremiere $matiere): bool
    {
        return (bool) $matiere->delete();
    }

    public function peutAjouterProduit(): bool
    {
        return MatierePremiere::count() < MatierePremiere::NOMBRE_MAX_PRODUITS;
    }
}
