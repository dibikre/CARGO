<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MatierePremiere extends Model
{
    use HasFactory;

    /**
     * Limite stricte de produits dans le bandeau.
     */
    public const NOMBRE_MAX_PRODUITS = 20;

    /**
     * Nom de la table associée.
     *
     * @var string
     */
    protected $table = 'matieres_premieres';

    /**
     * Attributs assignables en masse.
     *
     * @var list<string>
     */
    protected $fillable = [
        'nom',
        'code_symbole',
        'devise',
        'unite_mesure',
        'prix_actuel',
        'pourcentage_variation',
        'image_png',
        'donnees_courbe_json',
        'ordre_affichage',
        'est_actif',
    ];

    /**
     * Conversions de types.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'prix_actuel' => 'float',
            'pourcentage_variation' => 'float',
            'ordre_affichage' => 'integer',
            'est_actif' => 'boolean',
        ];
    }

    /**
     * Portée locale pour les matières actives affichées dans le bandeau défilant (max 20).
     */
    public function scopeActif(Builder $requete): Builder
    {
        return $requete->where('est_actif', true)
            ->orderBy('ordre_affichage')
            ->limit(self::NOMBRE_MAX_PRODUITS);
    }

    /**
     * Décoder les points de la courbe JSON en tableau structuré.
     *
     * @return array<int, array{0: int|float, 1: float}>
     */
    public function obtenirPointsCourbe(): array
    {
        if (empty($this->donnees_courbe_json)) {
            return [];
        }

        $points = json_decode($this->donnees_courbe_json, true);

        return is_array($points) ? $points : [];
    }

    /**
     * Calculer l'écart en pourcentage entre le premier nœud (entrée) et le dernier nœud (sortie).
     */
    public function calculerEcartEntreeSortie(): float
    {
        $points = $this->obtenirPointsCourbe();

        if (count($points) < 2) {
            return (float) $this->pourcentage_variation;
        }

        $premierNoeud = $points[0][1] ?? 0;
        $dernierNoeud = $points[count($points) - 1][1] ?? 0;

        if ($premierNoeud == 0) {
            return 0.0;
        }

        return round((($dernierNoeud - $premierNoeud) / $premierNoeud) * 100, 2);
    }

    /**
     * Générer le tracé SVG (sparkline path) normalisé pour le bandeau défilant.
     */
    public function genererTraceSvg(int $largeur = 60, int $hauteur = 20): string
    {
        $points = $this->obtenirPointsCourbe();

        if (count($points) < 2) {
            return "M 0 {$hauteur} L {$largeur} {$hauteur}";
        }

        $valeurs = array_map(fn ($p) => (float) ($p[1] ?? 0), $points);
        $min = min($valeurs);
        $max = max($valeurs);
        $ecart = ($max - $min) ?: 1.0;

        $margeY = 3;
        $hauteurUtile = max(2, $hauteur - ($margeY * 2));
        $nbPoints = count($valeurs);
        $pasX = $largeur / max(1, $nbPoints - 1);

        $morceaux = [];
        foreach ($valeurs as $index => $val) {
            $x = round($index * $pasX, 1);
            // Inversion de l'axe Y pour le rendu SVG (0 en haut)
            $ratio = ($val - $min) / $ecart;
            $y = round($hauteur - $margeY - ($ratio * $hauteurUtile), 1);

            $morceaux[] = ($index === 0 ? 'M' : 'L') . " {$x} {$y}";
        }

        return implode(' ', $morceaux);
    }

    /**
     * Obtenir l'URL de l'image PNG transparente (ou repli SVG transparent).
     */
    public function getUrlImageAttribute(): string
    {
        if (! empty($this->image_png)) {
            if (str_starts_with($this->image_png, 'http://') || str_starts_with($this->image_png, 'https://')) {
                return $this->image_png;
            }
            return asset($this->image_png);
        }

        return asset('images/modes/multimodal.svg');
    }

    /**
     * Formater le prix pour l'affichage visuel (ex: $5,154.56).
     */
    public function getPrixFormateAttribute(): string
    {
        $dec = ($this->prix_actuel < 10 && $this->prix_actuel > 0) ? 2 : (floor($this->prix_actuel) == $this->prix_actuel ? 0 : 2);
        return ($this->devise ?: '$') . number_format($this->prix_actuel, $dec, '.', ',');
    }

    /**
     * Couleur du tracé et du pourcentage (vert si positif, rouge si négatif).
     */
    public function getCouleurTendanceAttribute(): string
    {
        return $this->pourcentage_variation >= 0 ? '#16A34A' : '#DC2626';
    }
}
