<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class ServiceGestionImage
{
    /**
     * Traiter et stocker un fichier image téléversé dans son dossier structuré.
     */
    public function traiterImageTeleversee(UploadedFile $fichier, string $dossier = 'articles'): string
    {
        $dossierCible = public_path("images/{$dossier}");
        if (! File::isDirectory($dossierCible)) {
            File::makeDirectory($dossierCible, 0755, true, true);
        }

        $nomOriginal = pathinfo($fichier->getClientOriginalName(), PATHINFO_FILENAME);
        $extension = strtolower($fichier->getClientOriginalExtension() ?: 'jpg');
        $nomFichier = Str::slug($nomOriginal) . '-' . uniqid() . '.' . $extension;

        $fichier->move($dossierCible, $nomFichier);

        return "images/{$dossier}/{$nomFichier}";
    }

    /**
     * Télécharger une image distante pour la stocker localement (Règle 19).
     */
    public function telechargerEtStockerImage(string $urlDistante, string $dossier = 'articles'): ?string
    {
        try {
            $reponse = Http::timeout(8)->get($urlDistante);
            if (! $reponse->successful()) {
                return null;
            }

            $typeMime = $reponse->header('Content-Type');
            $extension = match (true) {
                str_contains($typeMime, 'png') => 'png',
                str_contains($typeMime, 'webp') => 'webp',
                str_contains($typeMime, 'svg') => 'svg',
                default => 'jpg',
            };

            $dossierCible = public_path("images/{$dossier}");
            if (! File::isDirectory($dossierCible)) {
                File::makeDirectory($dossierCible, 0755, true, true);
            }

            $nomFichier = 'flux-' . uniqid() . '.' . $extension;
            File::put("{$dossierCible}/{$nomFichier}", $reponse->body());

            return "images/{$dossier}/{$nomFichier}";
        } catch (\Throwable $e) {
            report($e);
            return null;
        }
    }

    /**
     * Obtenir l'URL publique absolue de l'image avec repli élégant sur les illustrations vectorielles.
     */
    public function obtenirUrlImage(?string $chemin, ?string $mode = null): string
    {
        if (! empty($chemin)) {
            if (Str::startsWith($chemin, ['http://', 'https://'])) {
                return $chemin;
            }

            if (file_exists(public_path($chemin))) {
                return asset($chemin);
            }

            if (file_exists(public_path('storage/' . $chemin))) {
                return asset('storage/' . $chemin);
            }
        }

        // Repli sur l'illustration sectorielle SVG par défaut
        $modeValide = in_array($mode, ['maritime', 'routier', 'ferroviaire', 'aerien', 'multimodal']) ? $mode : 'multimodal';
        return asset("images/modes/{$modeValide}.svg");
    }
}
