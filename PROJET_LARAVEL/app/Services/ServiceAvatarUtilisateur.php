<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ServiceAvatarUtilisateur
{
    /**
     * Traite et redimensionne la photo de profil du journaliste, et génère la vignette 40x40.
     *
     * @return array{avatar: string, avatar_vignette: string}
     */
    public function enregistrerAvatar(UploadedFile $fichier, User $utilisateur): array
    {
        $dossierAvatars = public_path('images/avatars');
        $dossierVignettes = public_path('images/avatars/vignettes');

        if (! File::isDirectory($dossierAvatars)) {
            File::makeDirectory($dossierAvatars, 0755, true, true);
        }
        if (! File::isDirectory($dossierVignettes)) {
            File::makeDirectory($dossierVignettes, 0755, true, true);
        }

        // Supprimer les anciens fichiers d'avatar de l'utilisateur pour éviter les orphelins
        $this->supprimerAnciensFichiers($utilisateur);

        $identifiant = $utilisateur->id . '-' . Str::random(8);
        $nomFichier = "avatar-{$identifiant}.webp";

        $cheminAvatarComplet = "{$dossierAvatars}/{$nomFichier}";
        $cheminVignetteComplet = "{$dossierVignettes}/{$nomFichier}";

        // Traitement GD avec conversion en WebP haute performance
        $this->genererImagesGd($fichier->getRealPath(), $cheminAvatarComplet, $cheminVignetteComplet);

        $cheminRelatifAvatar = "images/avatars/{$nomFichier}";
        $cheminRelatifVignette = "images/avatars/vignettes/{$nomFichier}";

        // Mise à jour de l'utilisateur
        $utilisateur->update([
            'avatar' => $cheminRelatifAvatar,
        ]);

        return [
            'avatar' => $cheminRelatifAvatar,
            'avatar_vignette' => $cheminRelatifVignette,
        ];
    }

    /**
     * Génère l'image principale (max 300x300) et la vignette carrée 40x40 avec GD.
     */
    protected function genererImagesGd(string $sourcePath, string $cibleAvatar, string $cibleVignette): void
    {
        $contenu = file_get_contents($sourcePath);
        if (! $contenu) {
            throw new \RuntimeException("Unable to read uploaded avatar file.");
        }

        $source = @imagecreatefromstring($contenu);
        if (! $source) {
            throw new \RuntimeException("Invalid image file format. GD cannot parse image.");
        }

        $largeurSource = imagesx($source);
        $hauteurSource = imagesy($source);

        // 1. Génération de la vignette carrée stricte 40x40 pixels
        $minDimension = min($largeurSource, $hauteurSource);
        $srcX = (int) (($largeurSource - $minDimension) / 2);
        $srcY = (int) (($hauteurSource - $minDimension) / 2);

        $ressourceVignette = imagecreatetruecolor(40, 40);
        imagealphablending($ressourceVignette, false);
        imagesavealpha($ressourceVignette, true);
        $transparent = imagecolorallocatealpha($ressourceVignette, 0, 0, 0, 127);
        imagefilledrectangle($ressourceVignette, 0, 0, 40, 40, $transparent);

        imagecopyresampled(
            $ressourceVignette,
            $source,
            0,
            0,
            $srcX,
            $srcY,
            40,
            40,
            $minDimension,
            $minDimension
        );

        // 2. Génération de l'avatar profil standard (max 300x300 carré centré)
        $ressourceProfil = imagecreatetruecolor(300, 300);
        imagealphablending($ressourceProfil, false);
        imagesavealpha($ressourceProfil, true);
        imagefilledrectangle($ressourceProfil, 0, 0, 300, 300, $transparent);

        imagecopyresampled(
            $ressourceProfil,
            $source,
            0,
            0,
            $srcX,
            $srcY,
            300,
            300,
            $minDimension,
            $minDimension
        );

        // Sauvegarde en WebP (ou repli PNG si WebP absent)
        if (function_exists('imagewebp')) {
            imagewebp($ressourceProfil, $cibleAvatar, 88);
            imagewebp($ressourceVignette, $cibleVignette, 90);
        } else {
            imagepng($ressourceProfil, $cibleAvatar, 8);
            imagepng($ressourceVignette, $cibleVignette, 8);
        }

        imagedestroy($ressourceVignette);
        imagedestroy($ressourceProfil);
        imagedestroy($source);
    }

    /**
     * Supprime les anciens fichiers avatar d'un utilisateur.
     */
    public function supprimerAnciensFichiers(User $utilisateur): void
    {
        if (empty($utilisateur->avatar)) {
            return;
        }

        $cheminPrincipal = public_path($utilisateur->avatar);
        $cheminVignette = public_path(str_replace('images/avatars/', 'images/avatars/vignettes/', $utilisateur->avatar));

        if (File::exists($cheminPrincipal)) {
            @unlink($cheminPrincipal);
        }
        if (File::exists($cheminVignette)) {
            @unlink($cheminVignette);
        }
    }
}
