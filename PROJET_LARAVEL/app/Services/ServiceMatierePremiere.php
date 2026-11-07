<?php

namespace App\Services;

use App\Models\MatierePremiere;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use InvalidArgumentException;

class ServiceMatierePremiere
{
    /**
     * Valider et normaliser une chaîne JSON de points de courbe.
     * Doit contenir entre 5 et 10 nœuds [[timestamp, valeur], ...].
     *
     * @param string|null $json
     * @return array{points: array, prixActuel: float, variation: float, jsonNormalise: string}
     */
    public function validerEtTraiterPointsCourbe(?string $json): array
    {
        if (empty($json)) {
            throw new InvalidArgumentException('Curve data JSON cannot be empty.');
        }

        $donnees = json_decode(trim($json), true);

        if (! is_array($donnees)) {
            throw new InvalidArgumentException('Invalid JSON format. Expected an array of [timestamp, value] points.');
        }

        $nbPoints = count($donnees);
        if ($nbPoints < 5 || $nbPoints > 10) {
            throw new InvalidArgumentException("Curve must contain between 5 and 10 points (currently {$nbPoints} points provided).");
        }

        $pointsValides = [];
        foreach ($donnees as $index => $point) {
            if (! is_array($point) || count($point) < 2 || ! is_numeric($point[1])) {
                throw new InvalidArgumentException("Invalid point at index {$index}. Expected [timestamp, numeric_value].");
            }
            $timestamp = (int) $point[0];
            $valeur = (float) $point[1];
            $pointsValides[] = [$timestamp, $valeur];
        }

        // Calcul de l'écart entre entrée (1er point) et sortie (dernier point)
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

    /**
     * Traiter et stocker une image PNG transparente téléversée.
     */
    public function traiterImagePng(UploadedFile $fichier, ?string $codeSymbole = null): string
    {
        $dossier = public_path('images/matieres');
        if (! file_exists($dossier)) {
            mkdir($dossier, 0755, true);
        }

        $nomFichier = ($codeSymbole ? Str::slug($codeSymbole) : 'matiere_' . time()) . '_' . uniqid() . '.png';
        $fichier->move($dossier, $nomFichier);

        return 'images/matieres/' . $nomFichier;
    }

    /**
     * Vérifier si la limite maximale de 20 produits est atteinte.
     */
    public function peutAjouterProduit(): bool
    {
        return MatierePremiere::count() < MatierePremiere::NOMBRE_MAX_PRODUITS;
    }
}
