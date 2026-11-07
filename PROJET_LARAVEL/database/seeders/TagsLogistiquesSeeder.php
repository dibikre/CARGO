<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Tag;
use Illuminate\Database\Seeder;

class TagsLogistiquesSeeder extends Seeder
{
    /**
     * Alimenter les tags clés (DHL, Lufthansa, FedEx, CARGO, Air France, etc.)
     * et les associer aux articles existants dans la limite stricte de 4 tags par article.
     */
    public function run(): void
    {
        // 1. Définition des tags prioritaires et acteurs industriels du fret
        $definitionsTags = [
            ['nom' => 'DHL', 'slug' => 'dhl', 'description' => 'DHL Global Forwarding, Supply Chain & Express'],
            ['nom' => 'Lufthansa', 'slug' => 'lufthansa', 'description' => 'Lufthansa Cargo & hubs aéroportuaires européens'],
            ['nom' => 'FedEx', 'slug' => 'fedex', 'description' => 'FedEx Express & solutions de fret aérien express mondial'],
            ['nom' => 'CARGO', 'slug' => 'cargo', 'description' => 'Analyses exclusives, flux et intelligence sectorielle CARGO'],
            ['nom' => 'Air France', 'slug' => 'air-france', 'description' => 'Air France-KLM Cargo & hubs de Paris-CDG'],
            ['nom' => 'CMA CGM', 'slug' => 'cma-cgm', 'description' => 'Armateur mondial de porte-conteneurs et logistique multimodale'],
            ['nom' => 'Maersk', 'slug' => 'maersk', 'description' => 'A.P. Moller - Maersk intégrateur logistique maritime et terrestre'],
            ['nom' => 'MSC', 'slug' => 'msc', 'description' => 'Mediterranean Shipping Company premier transporteur maritime mondial'],
            ['nom' => 'Kuehne+Nagel', 'slug' => 'kuehne-nagel', 'description' => 'Commissionnaire de transport mondial maritime, aérien et routier'],
            ['nom' => 'DB Schenker', 'slug' => 'db-schenker', 'description' => 'Réseau terrestre européen et fret ferroviaire'],
            ['nom' => 'SNCF Fret', 'slug' => 'sncf-fret', 'description' => 'Fret ferroviaire, combiné rail-route et autoroutes ferroviaires'],
            ['nom' => 'Boeing Cargo', 'slug' => 'boeing-cargo', 'description' => 'Appareils tout-cargo et conversions B777F et B767-300BCF'],
        ];

        $tagsCrees = [];
        foreach ($definitionsTags as $def) {
            $tag = Tag::firstOrCreate(
                ['slug' => $def['slug']],
                [
                    'nom' => $def['nom'],
                    'description' => $def['description'],
                    'compteur_articles' => 0,
                ]
            );
            $tagsCrees[$def['slug']] = $tag;
        }

        // 2. Association des tags aux articles existants (maximum 4 tags par article)
        $articles = Article::all();

        foreach ($articles as $article) {
            $tagsAAssocier = [];

            // Tout article CARGO intègre le tag de marque CARGO
            $tagsAAssocier[] = $tagsCrees['cargo']->id;

            switch ($article->mode_transport_principal) {
                case 'aerien':
                    // Distribution parmi DHL, Lufthansa, FedEx, Air France
                    $tagsAAssocier[] = $tagsCrees['dhl']->id;
                    $tagsAAssocier[] = $tagsCrees['lufthansa']->id;
                    $tagsAAssocier[] = ($article->id % 2 === 0)
                        ? $tagsCrees['air-france']->id
                        : $tagsCrees['fedex']->id;
                    break;

                case 'maritime':
                    // Distribution parmi CMA CGM, Maersk, MSC, DHL
                    $tagsAAssocier[] = $tagsCrees['cma-cgm']->id;
                    $tagsAAssocier[] = ($article->id % 2 === 0)
                        ? $tagsCrees['maersk']->id
                        : $tagsCrees['msc']->id;
                    $tagsAAssocier[] = $tagsCrees['dhl']->id;
                    break;

                case 'routier':
                    // Distribution routière et express
                    $tagsAAssocier[] = $tagsCrees['fedex']->id;
                    $tagsAAssocier[] = $tagsCrees['db-schenker']->id;
                    $tagsAAssocier[] = $tagsCrees['dhl']->id;
                    break;

                case 'ferroviaire':
                    // Distribution combiné et rail
                    $tagsAAssocier[] = $tagsCrees['sncf-fret']->id;
                    $tagsAAssocier[] = $tagsCrees['db-schenker']->id;
                    $tagsAAssocier[] = $tagsCrees['kuehne-nagel']->id;
                    break;

                default: // multimodal, entreposage, douanes
                    $tagsAAssocier[] = $tagsCrees['dhl']->id;
                    $tagsAAssocier[] = $tagsCrees['kuehne-nagel']->id;
                    $tagsAAssocier[] = ($article->id % 2 === 0)
                        ? $tagsCrees['fedex']->id
                        : $tagsCrees['air-france']->id;
                    break;
            }

            // Dédoublonnage et restriction stricte à 4 tags maximum
            $tagsAAssocier = array_slice(array_unique($tagsAAssocier), 0, 4);

            $article->tags()->sync($tagsAAssocier);
        }

        // 3. Mise à jour des compteurs d'articles dénormalisés sur chaque tag
        foreach ($tagsCrees as $tag) {
            $tag->update(['compteur_articles' => $tag->articles()->count()]);
        }
    }
}
