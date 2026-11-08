<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\UploadedFile;

/**
 * Form Request dédié à la validation robuste des matières premières du bandeau.
 * Gère la validation des métadonnées, du fichier PNG sans fond et des 5 à 10 points JSON.
 */
class MatierePremiereFormRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->estMembreRedaction() ?? false;
    }

    public function rules(): array
    {
        $idMatiere = $this->route('id') ?? $this->route('matiere_premiere');
        $regleImage = $this->isMethod('POST') && ! $this->route('id')
            ? ['required', 'file', 'max:8192', $this->validerFichierPng()]
            : ['nullable', 'file', 'max:8192', $this->validerFichierPng()];

        return [
            'nom' => ['required', 'string', 'max:100'],
            'code_symbole' => ['required', 'string', 'max:20', 'unique:matieres_premieres,code_symbole,' . $idMatiere],
            'devise' => ['required', 'string', 'max:10'],
            'unite_mesure' => ['required', 'string', 'max:50'],
            'ordre_affichage' => ['required', 'integer', 'min:0'],
            'est_actif' => ['sometimes', 'boolean'],
            'image_png' => $regleImage,
            'donnees_courbe_json' => ['required', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'nom.required' => 'Le nom du produit ou de la matière première est obligatoire.',
            'code_symbole.required' => 'Le code symbole (ex. BRENT, CACAO) est obligatoire.',
            'code_symbole.unique' => 'Ce code symbole est déjà attribué à un autre produit.',
            'devise.required' => 'La devise de cotation est obligatoire.',
            'unite_mesure.required' => "L'unité de mesure commerciale est obligatoire.",
            'image_png.required' => "L'image PNG détourée (sans fond) est obligatoire.",
            'image_png.file' => "Le fichier sélectionné n'est pas un fichier valide.",
            'image_png.max' => "Le fichier d'image ne doit pas dépasser 8 Mo.",
            'donnees_courbe_json.required' => 'Le tableau JSON des coordonnées de courbe est obligatoire.',
        ];
    }

    /**
     * Règle de validation résiliente pour garantir un PNG authentique.
     */
    protected function validerFichierPng(): \Closure
    {
        return function ($attribute, $value, $fail) {
            if (! $value) {
                return;
            }

            if (! $value instanceof UploadedFile || ! $value->isValid()) {
                $fail("Le fichier téléversé est corrompu ou incomplet.");
                return;
            }

            $extension = strtolower($value->getClientOriginalExtension());
            if ($extension !== 'png') {
                $fail("L'image doit obligatoirement avoir l'extension .png.");
                return;
            }

            $mime = strtolower((string) ($value->getMimeType() ?? $value->getClientMimeType()));
            $mimesAcceptes = [
                'image/png',
                'image/x-png',
                'application/png',
                'application/x-png',
                'image/octet-stream',
                'application/octet-stream'
            ];

            if (! in_array($mime, $mimesAcceptes) && ! str_starts_with($mime, 'image/')) {
                $fail("Le type MIME ({$mime}) ne correspond pas à une image PNG exploitable.");
            }
        };
    }
}
