<?php

namespace Database\Seeders;

use App\Models\DietTag;
use App\Models\Ingredient;
use App\Models\Recipe;
use App\Models\RecipeIngredient;
use App\Models\RecipeStep;
use App\Models\User;
use Illuminate\Database\Seeder;

class RecipeSeeder extends Seeder
{
    public function run(): void
    {
        $userId = User::first()?->id ?? 1;

        $vegTag = DietTag::where('name', 'Vegetarisch')->first();


        $recipes = [
            [
                'data' => [
                    'title'                => 'Healthy Smash Burger Tacos',
                    'description'          => 'Smash burger tacos werden viraal omdat ze snel, crunchy en heel satisfying zijn. De gezondere versie met gehakt.',
                    'prep_time_minutes'    => 15,
                    'calories_per_portion' => 480,
                    'afwas_score'          => 2,
                    'image_path'           => 'recepten/smash-burger-tacos.jpg',
                    'badge'                => 'Student Favoriet',
                    'created_by'           => $userId,
                ],
                'diet_tags' => [],
                'ingredients' => [
                    ['canonical_name' => 'Tortilla',        'category' => 'granen',     'quantity' => 2,   'unit' => 'stuks', 'notes' => 'kleine'],
                    ['canonical_name' => 'Gehakt',          'category' => 'vlees',      'quantity' => 150, 'unit' => 'g',     'notes' => 'rund of varken'],
                    ['canonical_name' => 'Ui',              'category' => 'groenten',   'quantity' => 20,  'unit' => 'g'],
                    ['canonical_name' => 'Sla',             'category' => 'groenten',   'quantity' => 30,  'unit' => 'g'],
                    ['canonical_name' => 'Augurk',          'category' => 'groenten',   'quantity' => 20,  'unit' => 'g',     'notes' => '2 à 4 schijfjes'],
                    ['canonical_name' => 'Griekse yoghurt', 'category' => 'zuivel',     'quantity' => 2,   'unit' => 'stuks', 'notes' => 'eetlepels'],
                    ['canonical_name' => 'Ketchup',         'category' => 'sauzen',     'quantity' => 1,   'unit' => 'stuks', 'notes' => 'theelepel'],
                    ['canonical_name' => 'Mosterd',         'category' => 'sauzen',     'quantity' => 1,   'unit' => 'stuks', 'notes' => 'theelepel'],
                    ['canonical_name' => 'Olie',            'category' => 'sauzen',     'quantity' => 1,   'unit' => 'stuks', 'notes' => 'theelepel'],
                    ['canonical_name' => 'Peper',           'category' => 'specerijen', 'quantity' => null, 'unit' => 'stuks', 'notes' => 'naar smaak'],
                    ['canonical_name' => 'Zout',            'category' => 'specerijen', 'quantity' => null, 'unit' => 'stuks', 'notes' => 'snufje'],
                ],
                'steps' => [
                    'Meng yoghurt, ketchup en mosterd tot saus.',
                    'Verdeel het gehakt dun over de tortilla\'s.',
                    'Kruid met peper en een beetje zout.',
                    'Leg de tortilla met de vleeskant naar beneden in een hete pan.',
                    'Bak tot het gehakt gaar en licht krokant is.',
                    'Draai kort om zodat de tortilla warm wordt.',
                    'Werk af met sla, ui, augurk en saus.',
                    'Vouw dicht en serveer meteen.',
                ],
            ],
            [
                'data' => [
                    'title'                => 'Gezonde McWrap Caesar',
                    'description'          => 'Heel herkenbaar voor studenten, maar dan de lichtere versie. Dat maakt het slim én aantrekkelijk.',
                    'prep_time_minutes'    => 15,
                    'calories_per_portion' => 470,
                    'afwas_score'          => 2,
                    'image_path'           => 'recepten/mcwrap-caesar.jpg',
                    'badge'                => 'Gezonde Keuze',
                    'created_by'           => $userId,
                ],
                'diet_tags' => [],
                'ingredients' => [
                    ['canonical_name' => 'Wrap',            'category' => 'granen',     'quantity' => 2,   'unit' => 'stuks'],
                    ['canonical_name' => 'Kipfilet',        'category' => 'vlees',      'quantity' => 120, 'unit' => 'g'],
                    ['canonical_name' => 'Sla',             'category' => 'groenten',   'quantity' => 40,  'unit' => 'g'],
                    ['canonical_name' => 'Griekse yoghurt', 'category' => 'zuivel',     'quantity' => 2,   'unit' => 'stuks', 'notes' => 'eetlepels'],
                    ['canonical_name' => 'Parmezaan',       'category' => 'zuivel',     'quantity' => 10,  'unit' => 'g',     'notes' => 'of geraspte kaas'],
                    ['canonical_name' => 'Citroensap',      'category' => 'fruit',      'quantity' => 1,   'unit' => 'stuks', 'notes' => 'theelepel'],
                    ['canonical_name' => 'Lookpoeder',      'category' => 'specerijen', 'quantity' => 1,   'unit' => 'stuks', 'notes' => 'theelepel'],
                    ['canonical_name' => 'Croutons',        'category' => 'granen',     'quantity' => 15,  'unit' => 'g'],
                    ['canonical_name' => 'Peper',           'category' => 'specerijen', 'quantity' => null, 'unit' => 'stuks', 'notes' => 'naar smaak'],
                    ['canonical_name' => 'Zout',            'category' => 'specerijen', 'quantity' => null, 'unit' => 'stuks', 'notes' => 'snufje'],
                ],
                'steps' => [
                    'Bak de kip goudbruin en kruid licht.',
                    'Meng yoghurt met citroen, lookpoeder, peper en een beetje kaas.',
                    'Snij de sla fijn.',
                    'Verwarm de wraps.',
                    'Beleg met sla, kip, caesarsaus en een beetje crunch.',
                    'Rol strak op.',
                    'Snij middendoor.',
                ],
            ],
            [
                'data' => [
                    'title'                => 'Viral Baked Feta Pasta met Spinazie',
                    'description'          => 'Een bekende viral pasta, maar dan iets frisser en gezonder gemaakt. Ideaal als gerecht dat studenten herkennen van social media.',
                    'prep_time_minutes'    => 25,
                    'calories_per_portion' => 520,
                    'afwas_score'          => 2,
                    'image_path'           => 'recepten/baked-feta-pasta.jpg',
                    'badge'                => 'Viral Hit',
                    'created_by'           => $userId,
                ],
                'diet_tags' => [$vegTag?->id],
                'ingredients' => [
                    ['canonical_name' => 'Pasta',         'category' => 'granen',     'quantity' => 100, 'unit' => 'g'],
                    ['canonical_name' => 'Feta',          'category' => 'zuivel',     'quantity' => 75,  'unit' => 'g'],
                    ['canonical_name' => 'Kerstomaten',   'category' => 'groenten',   'quantity' => 150, 'unit' => 'g',     'notes' => '10 à 12 stuks'],
                    ['canonical_name' => 'Spinazie',      'category' => 'groenten',   'quantity' => 40,  'unit' => 'g'],
                    ['canonical_name' => 'Look',          'category' => 'groenten',   'quantity' => 1,   'unit' => 'stuks', 'notes' => 'teentje'],
                    ['canonical_name' => 'Olijfolie',     'category' => 'sauzen',     'quantity' => 15,  'unit' => 'ml'],
                    ['canonical_name' => 'Chilivlokken',  'category' => 'specerijen', 'quantity' => null, 'unit' => 'stuks', 'notes' => 'optioneel'],
                    ['canonical_name' => 'Peper',         'category' => 'specerijen', 'quantity' => null, 'unit' => 'stuks', 'notes' => 'naar smaak'],
                ],
                'steps' => [
                    'Verwarm de oven/airfryer voor op 200°C.',
                    'Doe de kerstomaten en look in een ovenschaal.',
                    'Leg de feta in het midden.',
                    'Giet er een beetje olijfolie over en kruid met peper en chilivlokken.',
                    'Bak 20 à 25 minuten tot de tomaten openbarsten.',
                    'Kook intussen de pasta.',
                    'Haal de schaal uit de oven en prak feta en tomaten tot saus.',
                    'Voeg de spinazie toe en laat slinken.',
                    'Meng de saus onder de pasta.',
                ],
            ],
            [
                'data' => [
                    'title'                => 'Streetfood Falafel Bowl',
                    'description'          => 'Voelt als iets dat je op een foodmarkt koopt. Interessanter dan gewoon falafel met groenten.',
                    'prep_time_minutes'    => 15,
                    'calories_per_portion' => 500,
                    'afwas_score'          => 2,
                    'image_path'           => 'recepten/falafel-bowl.jpg',
                    'badge'                => 'Vega Winner',
                    'created_by'           => $userId,
                ],
                'diet_tags' => [$vegTag?->id],
                'ingredients' => [
                    ['canonical_name' => 'Couscous',        'category' => 'granen',     'quantity' => 80,  'unit' => 'g',     'notes' => 'of 100g rijst'],
                    ['canonical_name' => 'Falafel',         'category' => 'peulvruchten', 'quantity' => 5, 'unit' => 'stuks'],
                    ['canonical_name' => 'Hummus',          'category' => 'peulvruchten', 'quantity' => 30, 'unit' => 'g'],
                    ['canonical_name' => 'Komkommer',       'category' => 'groenten',   'quantity' => 50,  'unit' => 'g'],
                    ['canonical_name' => 'Tomaat',          'category' => 'groenten',   'quantity' => 60,  'unit' => 'g'],
                    ['canonical_name' => 'Rode ui',         'category' => 'groenten',   'quantity' => 20,  'unit' => 'g'],
                    ['canonical_name' => 'Griekse yoghurt', 'category' => 'zuivel',     'quantity' => 2,   'unit' => 'stuks', 'notes' => 'eetlepels'],
                    ['canonical_name' => 'Citroensap',      'category' => 'fruit',      'quantity' => 1,   'unit' => 'stuks', 'notes' => 'theelepel'],
                    ['canonical_name' => 'Peterselie',      'category' => 'kruiden',    'quantity' => 1,   'unit' => 'stuks', 'notes' => 'theelepel fijngehakt'],
                ],
                'steps' => [
                    'Maak couscous of rijst klaar.',
                    'Bak de falafel krokant.',
                    'Snij komkommer, tomaat en rode ui fijn.',
                    'Meng yoghurt met citroen als saus.',
                    'Doe couscous in een bowl.',
                    'Voeg falafel en groenten toe.',
                    'Werk af met hummus, saus en peterselie.',
                ],
            ],
            [
                'data' => [
                    'title'                => 'Avocado Crunch Toast Deluxe',
                    'description'          => 'Avocado toast met crunchy toppings en een mooi eitje. Voelt veel exclusiever dan de standaard versie.',
                    'prep_time_minutes'    => 10,
                    'calories_per_portion' => 390,
                    'afwas_score'          => 1,
                    'image_path'           => 'recepten/avocado-toast.jpg',
                    'badge'                => 'Gezonde Keuze',
                    'created_by'           => $userId,
                ],
                'diet_tags' => [$vegTag?->id],
                'ingredients' => [
                    ['canonical_name' => 'Brood',        'category' => 'granen',     'quantity' => 2,   'unit' => 'stuks', 'notes' => 'sneetjes'],
                    ['canonical_name' => 'Avocado',      'category' => 'fruit',      'quantity' => 1,   'unit' => 'stuks'],
                    ['canonical_name' => 'Ei',           'category' => 'zuivel',     'quantity' => 1,   'unit' => 'stuks'],
                    ['canonical_name' => 'Kerstomaten',  'category' => 'groenten',   'quantity' => 50,  'unit' => 'g'],
                    ['canonical_name' => 'Chilivlokken', 'category' => 'specerijen', 'quantity' => null, 'unit' => 'stuks', 'notes' => 'naar smaak'],
                    ['canonical_name' => 'Citroen',      'category' => 'fruit',      'quantity' => null, 'unit' => 'stuks', 'notes' => 'scheutje sap'],
                    ['canonical_name' => 'Pittenmix',    'category' => 'kruiden',    'quantity' => null, 'unit' => 'stuks', 'notes' => 'handje'],
                    ['canonical_name' => 'Peper',        'category' => 'specerijen', 'quantity' => null, 'unit' => 'stuks', 'notes' => 'naar smaak'],
                    ['canonical_name' => 'Zout',         'category' => 'specerijen', 'quantity' => null, 'unit' => 'stuks', 'notes' => 'naar smaak'],
                ],
                'steps' => [
                    'Toast het brood.',
                    'Plet de avocado met citroensap, peper en zout.',
                    'Snij de tomaatjes.',
                    'Bak of pocheer een ei.',
                    'Besmeer de toast met avocado.',
                    'Leg het ei en de tomaatjes erop.',
                    'Werk af met pittenmix en chilivlokken.',
                ],
            ],
            [
                'data' => [
                    'title'                => 'Sushi Bowl met Balls & Glory Challenge',
                    'description'          => 'Een sushi bowl met als bonus een gehaktbal gevuld met de bowl zelf. Snij open en toon de binnenkant.',
                    'prep_time_minutes'    => 30,
                    'calories_per_portion' => 550,
                    'afwas_score'          => 3,
                    'image_path'           => 'recepten/sushi-bowl.jpg',
                    'badge'                => 'Viral Hit',
                    'created_by'           => $userId,
                ],
                'diet_tags' => [],
                'ingredients' => [
                    ['canonical_name' => 'Rijst',           'category' => 'granen',     'quantity' => 100, 'unit' => 'g'],
                    ['canonical_name' => 'Kipfilet',        'category' => 'vlees',      'quantity' => 120, 'unit' => 'g',     'notes' => 'of 100g tonijn'],
                    ['canonical_name' => 'Komkommer',       'category' => 'groenten',   'quantity' => 50,  'unit' => 'g'],
                    ['canonical_name' => 'Wortel',          'category' => 'groenten',   'quantity' => 40,  'unit' => 'g'],
                    ['canonical_name' => 'Avocado',         'category' => 'fruit',      'quantity' => null, 'unit' => 'stuks', 'notes' => 'halve'],
                    ['canonical_name' => 'Sojasaus',        'category' => 'sauzen',     'quantity' => 1,   'unit' => 'stuks', 'notes' => 'eetlepel'],
                    ['canonical_name' => 'Griekse yoghurt', 'category' => 'zuivel',     'quantity' => 2,   'unit' => 'stuks', 'notes' => 'eetlepels'],
                    ['canonical_name' => 'Sriracha',        'category' => 'sauzen',     'quantity' => 1,   'unit' => 'stuks', 'notes' => 'theelepel'],
                    ['canonical_name' => 'Sesamzaad',       'category' => 'kruiden',    'quantity' => 1,   'unit' => 'stuks', 'notes' => 'theelepel'],
                    ['canonical_name' => 'Gehakt',          'category' => 'vlees',      'quantity' => 150, 'unit' => 'g',     'notes' => 'voor de gehaktbal'],
                    ['canonical_name' => 'Ei',              'category' => 'zuivel',     'quantity' => 1,   'unit' => 'stuks', 'notes' => 'voor de gehaktbal'],
                    ['canonical_name' => 'Paneermeel',      'category' => 'granen',     'quantity' => 2,   'unit' => 'stuks', 'notes' => 'eetlepels'],
                    ['canonical_name' => 'Lookpoeder',      'category' => 'specerijen', 'quantity' => 1,   'unit' => 'stuks', 'notes' => 'theelepel'],
                    ['canonical_name' => 'Peper',           'category' => 'specerijen', 'quantity' => null, 'unit' => 'stuks', 'notes' => 'naar smaak'],
                    ['canonical_name' => 'Zout',            'category' => 'specerijen', 'quantity' => null, 'unit' => 'stuks', 'notes' => 'naar smaak'],
                    ['canonical_name' => 'Olie',            'category' => 'sauzen',     'quantity' => 1,   'unit' => 'stuks', 'notes' => 'theelepel'],
                ],
                'steps' => [
                    'Kook de rijst volgens de verpakking.',
                    'Bak de kip goudbruin of maak de tonijn klaar.',
                    'Snij de komkommer en avocado in kleine stukjes en rasp de wortel.',
                    'Meng de Griekse yoghurt met sriracha tot een spicy saus.',
                    'Doe de rijst in een kom en verdeel de kip of tonijn, komkommer, wortel en avocado erop.',
                    'Werk af met sojasaus, spicy yoghurtsaus en sesamzaad.',
                    'Neem een klein deel van de sushi bowl apart als vulling voor de gehaktbal.',
                    'Meng het gehakt met ei, paneermeel, lookpoeder, peper en zout.',
                    'Druk het gehakt plat en leg in het midden de vulling.',
                    'Sluit het gehakt rond de vulling en vorm een mooie bal.',
                    'Bak de gehaktbal goudbruin in een pan.',
                    'Snij open en toon de binnenkant.',
                ],
            ],
        ];

        foreach ($recipes as $recipeData) {
            $recipe = Recipe::create($recipeData['data']);

            foreach (array_filter($recipeData['diet_tags']) as $tagId) {
                $recipe->dietTags()->attach($tagId);
            }

            foreach ($recipeData['ingredients'] as $order => $ingredientData) {
                $ingredient = Ingredient::firstOrCreate(
                    ['canonical_name' => $ingredientData['canonical_name']],
                    ['category' => $ingredientData['category']]
                );

                RecipeIngredient::create([
                    'recipe_id'      => $recipe->id,
                    'ingredients_id' => $ingredient->id,
                    'quantity'       => $ingredientData['quantity'] ?? null,
                    'unit'           => $ingredientData['unit'],
                    'notes'          => $ingredientData['notes'] ?? null,
                    'display_order'  => $order + 1,
                ]);
            }

            foreach ($recipeData['steps'] as $index => $description) {
                RecipeStep::create([
                    'recipe_id'   => $recipe->id,
                    'step_number' => $index + 1,
                    'description' => $description,
                ]);
            }
        }
    }
}
