<?php

namespace App\Services;

use App\Models\MatierePremiere;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use InvalidArgumentException;

/**
 * Service metier gerant les matieres premieres brutes pour le bandeau defilant.
 * Responsabilites : validation des 5 a 10 noeuds JSON, calcul d'ecart entree-sortie,
 * stockage securise des images PNG transparentes et enforcement du quota (max 20).
 */
class ServiceMatierePremiere
{
    /**
     * Valider et normaliser une chaine ou un tableau de noeuds de courbe.
     * Doit contenir entre 5 et 10 noeuds [[timestamp, valeur], ...].
     *
     * @param string|array|null $donnees
     * @return array{points: array, prixActuel: float, variation: float, jsonNormalise: string}
     */
    public function validerEtTraiterPointsCourbe(string|array|null $donnees): array
    {
        if (empty($donnees)) {
            throw new InvalidArgumentException('Curve data JSON cannot be empty.');
        }

        if (is_string($donnees)) {
            $noeuds = json_decode(trim($donnees), true);
            if (! is_array($noeuds)) {
                throw new InvalidArgumentException('Invalid JSON format. Expected an array of [timestamp, value] points.');
            }
        } else {
            $noeuds = $donnees;
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

        // Calcul de l'ecart entre entree (1er point) et sortie (dernier point)
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
     * Alias de validation pour retro-compatibilite des tests.
     */
    public function validerEtParserNoeudsCourbe(string|array|null $donnees): array
    {
        return $this->validerEtTraiterPointsCourbe($donnees)['points'];
    }

    /**
     * Calculer l'ecart entre le premier noeud et le dernier noeud.
     */
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
     * Traiter et stocker une image PNG transparente televersee.
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
     * Creer une matiere premiere a partir des donnees valides.
     */
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

    /**
     * Mettre a jour une matiere premiere existante.
     */
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

    /**
     * Actualiser uniquement les donnees de courbe.
     */
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

    /**
     * Supprimer une matiere premiere.
     */
    public function supprimerMatierePremiere(MatierePremiere $matiere): bool
    {
        return (bool) $matiere->delete();
    }

    /**
     * Verifier si la limite maximale de 20 produits est atteinte.
     */
    public function peutAjouterProduit(): bool
    {
        return MatierePremiere::count() < MatierePremiere::NOMBRE_MAX_PRODUITS;
    }
}
