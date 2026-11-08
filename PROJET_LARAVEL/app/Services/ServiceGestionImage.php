<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ServiceGestionImage
{
    protected ServiceConversionWebp $serviceConversionWebp;

    public function __construct(?ServiceConversionWebp $serviceConversionWebp = null)
    {
        $this->serviceConversionWebp = $serviceConversionWebp ?? app(ServiceConversionWebp::class);
    }

    /**
     * Traiter et stocker un fichier image téléversé dans son dossier structuré.
     * Pour le dossier 'articles', conversion automatique en WebP compressé haute qualité.
     * Pour les autres dossiers (ex: 'categories'), conservation du format d'origine.
     */
    public function traiterImageTeleversee(UploadedFile $fichier, string $dossier = 'articles'): string
    {
        $dossierCible = public_path("images/{$dossier}");
        if (! File::isDirectory($dossierCible)) {
            File::makeDirectory($dossierCible, 0755, true, true);
        }

        $nomOriginal = pathinfo($fichier->getClientOriginalName(), PATHINFO_FILENAME);
        $nomBase = Str::slug($nomOriginal);

        // Seules les images d'articles sont converties et compressées en WebP
        if ($dossier === 'articles') {
            $nomFichierWebp = $this->serviceConversionWebp->convertirEtCompresserFichier(
                $fichier,
                $dossierCible,
                $nomBase
            );

            return "images/{$dossier}/{$nomFichierWebp}";
        }

        // Pour les autres dossiers (categories, etc.), stockage dans leur format d'origine
        $extension = strtolower($fichier->getClientOriginalExtension() ?: 'jpg');
        $nomFichier = "{$nomBase}-" . uniqid() . ".{$extension}";

        $fichier->move($dossierCible, $nomFichier);

        return "images/{$dossier}/{$nomFichier}";
    }

    /**
     * Télécharger une image distante pour la stocker localement (Règle 19).
     * Si l'image est destinée aux articles, conversion automatique en WebP compressé.
     */
    public function telechargerEtStockerImage(string $urlDistante, string $dossier = 'articles'): ?string
    {
        try {
            $reponse = Http::timeout(8)->get($urlDistante);
            if (! $reponse->successful()) {
                return null;
            }

            $dossierCible = public_path("images/{$dossier}");
            if (! File::isDirectory($dossierCible)) {
                File::makeDirectory($dossierCible, 0755, true, true);
            }

            // Si c'est un article, conversion automatique du flux distant en WebP
            if ($dossier === 'articles') {
                $nomFichierWebp = $this->serviceConversionWebp->convertirFluxBinaireEnWebp(
                    $reponse->body(),
                    $dossierCible,
                    'flux-' . uniqid()
                );

                return "images/{$dossier}/{$nomFichierWebp}";
            }

            // Sinon, détection du type MIME pour les autres dossiers
            $typeMime = $reponse->header('Content-Type');
            $extension = match (true) {
                str_contains($typeMime, 'png') => 'png',
                str_contains($typeMime, 'webp') => 'webp',
                str_contains($typeMime, 'svg') => 'svg',
                default => 'jpg',
            };

            $nomFichier = 'flux-' . uniqid() . '.' . $extension;
            File::put("{$dossierCible}/{$nomFichier}", $reponse->body());

            return "images/{$dossier}/{$nomFichier}";
        } catch (\Throwable $e) {
            Log::warning("Échec du rapatriement d'image distante : " . $e->getMessage());
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

    /**
     * Accesseur au service de conversion WebP sous-jacent.
     */
    public function obtenirServiceConversionWebp(): ServiceConversionWebp
    {
        return $this->serviceConversionWebp;
    }
}
