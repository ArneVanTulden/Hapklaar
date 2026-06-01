<?php

namespace Database\Seeders;

use App\Models\Ingredient;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Cache;

class IngredientSeeder extends Seeder
{
    public function run(): void
    {
        $ingredients = [
            // Zuivel
            ['canonical_name' => 'Boter',          'category' => 'zuivel', 'name_en' => 'butter'],
            ['canonical_name' => 'Slagroom',        'category' => 'zuivel', 'name_en' => 'heavy whipping cream'],
            ['canonical_name' => 'Melk',            'category' => 'zuivel', 'name_en' => 'whole milk'],
            ['canonical_name' => 'Yoghurt',         'category' => 'zuivel', 'name_en' => 'yogurt, plain, whole milk'],
            ['canonical_name' => 'Griekse yoghurt', 'category' => 'zuivel', 'name_en' => 'yogurt, greek, plain, whole milk', 'gram_per_unit' => 15],
            ['canonical_name' => 'Mozzarella',      'category' => 'zuivel', 'name_en' => 'cheese, mozzarella, whole milk'],
            ['canonical_name' => 'kaas',            'category' => 'zuivel', 'name_en' => 'cheese, blue'],
            ['canonical_name' => 'Parmezaan',       'category' => 'zuivel', 'name_en' => 'cheese, parmesan, hard'],
            ['canonical_name' => 'Feta',            'category' => 'zuivel', 'name_en' => 'cheese, feta'],
            ['canonical_name' => 'Boursin',         'category' => 'zuivel', 'name_en' => 'cream cheese'],
            ['canonical_name' => 'Smeerkaas',       'category' => 'zuivel', 'name_en' => 'cream cheese spread'],
            ['canonical_name' => 'Ei',              'category' => 'zuivel', 'name_en' => 'egg, whole, raw, fresh', 'gram_per_unit' => 60],

            // Groenten
            ['canonical_name' => 'Aardappel',    'category' => 'groenten', 'name_en' => 'potato, raw, skin',               'gram_per_unit' => 150],
            ['canonical_name' => 'Ajuin',        'category' => 'groenten', 'name_en' => 'onion, raw'],
            ['canonical_name' => 'Knoflook',     'category' => 'groenten', 'name_en' => 'garlic, raw',                     'gram_per_unit' => 5],
            ['canonical_name' => 'Wortelen',     'category' => 'groenten', 'name_en' => 'carrot, raw'],
            ['canonical_name' => 'Tomaat',       'category' => 'groenten', 'name_en' => 'tomato, red, ripe, raw',          'gram_per_unit' => 120],
            ['canonical_name' => 'Paprika',      'category' => 'groenten', 'name_en' => 'sweet pepper, red, raw',          'gram_per_unit' => 160],
            ['canonical_name' => 'Komkommer',    'category' => 'groenten', 'name_en' => 'cucumber, peeled, raw'],
            ['canonical_name' => 'Bloemkool',    'category' => 'groenten', 'name_en' => 'cauliflower, raw'],
            ['canonical_name' => 'Broccoli',     'category' => 'groenten', 'name_en' => 'broccoli, raw'],
            ['canonical_name' => 'Spruiten',     'category' => 'groenten', 'name_en' => 'brussels sprouts, raw'],
            ['canonical_name' => 'Kool',         'category' => 'groenten', 'name_en' => 'cabbage, raw'],
            ['canonical_name' => 'Witte kool',   'category' => 'groenten', 'name_en' => 'cabbage, raw'],
            ['canonical_name' => 'Rode kool',    'category' => 'groenten', 'name_en' => 'red cabbage, raw'],
            ['canonical_name' => 'Prei',         'category' => 'groenten', 'name_en' => 'leek, raw'],
            ['canonical_name' => 'Spinazie',     'category' => 'groenten', 'name_en' => 'spinach, raw'],
            ['canonical_name' => 'Snijbonen',    'category' => 'groenten', 'name_en' => 'beans, snap, green, raw'],
            ['canonical_name' => 'Champignons',  'category' => 'groenten', 'name_en' => 'mushrooms, white, raw'],
            ['canonical_name' => 'Aubergine',    'category' => 'groenten', 'name_en' => 'eggplant, raw'],
            ['canonical_name' => 'Courgette',    'category' => 'groenten', 'name_en' => 'zucchini, includes skin, raw'],
            ['canonical_name' => 'Sla',          'category' => 'groenten', 'name_en' => 'lettuce, green leaf, raw'],
            ['canonical_name' => 'Rucola',       'category' => 'groenten', 'name_en' => 'arugula, raw'],
            ['canonical_name' => 'Radijs',       'category' => 'groenten', 'name_en' => 'radish, raw'],
            ['canonical_name' => 'Bieten',       'category' => 'groenten', 'name_en' => 'beet, raw'],
            ['canonical_name' => 'Selder',       'category' => 'groenten', 'name_en' => 'celery, raw'],
            ['canonical_name' => 'Venkel',       'category' => 'groenten', 'name_en' => 'fennel, bulb, raw'],
            ['canonical_name' => 'Witloof',      'category' => 'groenten', 'name_en' => 'endive, raw'],
            ['canonical_name' => 'Knolraap',     'category' => 'groenten', 'name_en' => 'turnip, raw'],
            ['canonical_name' => 'Lente-ui',     'category' => 'groenten', 'name_en' => 'onion, spring or scallion, raw'],
            ['canonical_name' => 'Maïs',         'category' => 'groenten', 'name_en' => 'corn, sweet, yellow, raw'],
            ['canonical_name' => 'Asperges',     'category' => 'groenten', 'name_en' => 'asparagus, raw'],
            ['canonical_name' => 'Pastinaak',    'category' => 'groenten', 'name_en' => 'parsnip, raw'],
            ['canonical_name' => 'Augurk',       'category' => 'groenten', 'name_en' => 'cucumber, pickled'],
            ['canonical_name' => 'Kerstomaten',  'category' => 'groenten', 'name_en' => 'tomato, cherry, red, ripe, raw'],
            ['canonical_name' => 'Rode ui',      'category' => 'groenten', 'name_en' => 'onion, red, raw'],

            // Vlees
            ['canonical_name' => 'Rundvlees',        'category' => 'vlees', 'name_en' => 'beef, ground, 80% lean meat, raw'],
            ['canonical_name' => 'Varkensvlees',     'category' => 'vlees', 'name_en' => 'pork, fresh, loin, whole, raw'],
            ['canonical_name' => 'Kippenvlees',      'category' => 'vlees', 'name_en' => 'chicken, broilers or fryers, meat only, raw'],
            ['canonical_name' => 'Varkensgehakt',    'category' => 'vlees', 'name_en' => 'pork, ground, raw'],
            ['canonical_name' => 'Rundgehakt',       'category' => 'vlees', 'name_en' => 'beef, ground, 80% lean meat, raw'],
            ['canonical_name' => 'Kipengehakt',      'category' => 'vlees', 'name_en' => 'chicken, ground, raw'],
            ['canonical_name' => 'Ham',              'category' => 'vlees', 'name_en' => 'ham, sliced, regular, approx 11% fat'],
            ['canonical_name' => 'Spek',             'category' => 'vlees', 'name_en' => 'pork, cured, bacon, raw'],
            ['canonical_name' => 'Worst',            'category' => 'vlees', 'name_en' => 'sausage, pork'],
            ['canonical_name' => 'Runder steak',     'category' => 'vlees', 'name_en' => 'beef, loin, top loin steak, boneless, raw'],
            ['canonical_name' => 'Kipfilet',         'category' => 'vlees', 'name_en' => 'chicken, broilers or fryers, breast, meat only, raw'],
            ['canonical_name' => 'Kipbout',          'category' => 'vlees', 'name_en' => 'chicken, broilers or fryers, leg, meat and skin, raw'],
            ['canonical_name' => 'Schnitzel',        'category' => 'vlees', 'name_en' => 'pork, fresh, loin, chop, boneless, raw'],
            ['canonical_name' => 'Runderribbetjes',  'category' => 'vlees', 'name_en' => 'beef, ribs, short ribs, boneless, raw'],
            ['canonical_name' => 'Kippenbouten',     'category' => 'vlees', 'name_en' => 'chicken, broilers or fryers, leg, meat and skin, raw'],

            // Vis
            ['canonical_name' => 'Kabeljauw', 'category' => 'vis', 'name_en' => 'fish, cod, atlantic, raw'],
            ['canonical_name' => 'Zeebaars',  'category' => 'vis', 'name_en' => 'fish, sea bass, mixed species, raw'],
            ['canonical_name' => 'Zalm',      'category' => 'vis', 'name_en' => 'fish, salmon, atlantic, wild, raw'],
            ['canonical_name' => 'Mosselen',  'category' => 'vis', 'name_en' => 'mussels, blue, raw'],
            ['canonical_name' => 'Oesters',   'category' => 'vis', 'name_en' => 'oysters, eastern, wild, raw'],
            ['canonical_name' => 'Garnalen',  'category' => 'vis', 'name_en' => 'shrimp, mixed species, raw'],
            ['canonical_name' => 'Scampi',    'category' => 'vis', 'name_en' => 'crustaceans, shrimp, mixed species, raw'],
            ['canonical_name' => 'Inktvis',   'category' => 'vis', 'name_en' => 'squid, mixed species, raw'],
            ['canonical_name' => 'Tonijn',    'category' => 'vis', 'name_en' => 'fish, tuna, fresh, bluefin, raw'],

            // Granen
            ['canonical_name' => 'Brood',           'category' => 'granen', 'name_en' => 'bread, white, commercially prepared',         'gram_per_unit' => 30],
            ['canonical_name' => 'Rijst',            'category' => 'granen', 'name_en' => 'rice, white, long-grain, regular, raw, unenriched'],
            ['canonical_name' => 'Pasta',            'category' => 'granen', 'name_en' => 'pasta, dry, enriched'],
            ['canonical_name' => 'Spaghetti',        'category' => 'granen', 'name_en' => 'spaghetti, dry, enriched'],
            ['canonical_name' => 'Tagliatelle',      'category' => 'granen', 'name_en' => 'pasta, dry, enriched'],
            ['canonical_name' => 'Penne',            'category' => 'granen', 'name_en' => 'pasta, dry, enriched'],
            ['canonical_name' => 'Havermout',        'category' => 'granen', 'name_en' => 'cereals, oats, regular and quick, not fortified, dry'],
            ['canonical_name' => 'Gist',             'category' => 'granen', 'name_en' => 'leavening agents, yeast, bakers, active dry'],
            ['canonical_name' => 'Bloem',            'category' => 'granen', 'name_en' => 'wheat flour, white, all-purpose, unenriched'],
            ['canonical_name' => 'Koek',             'category' => 'granen', 'name_en' => 'cookies, butter',                             'gram_per_unit' => 15],
            ['canonical_name' => 'Tortilla',         'category' => 'granen', 'name_en' => 'tortillas, ready-to-bake or fry, flour',      'gram_per_unit' => 40],
            ['canonical_name' => 'Wrap',             'category' => 'granen', 'name_en' => 'tortillas, ready-to-bake or fry, flour',      'gram_per_unit' => 45],
            ['canonical_name' => 'Couscous',         'category' => 'granen', 'name_en' => 'couscous, dry'],
            ['canonical_name' => 'Croutons',         'category' => 'granen', 'name_en' => 'croutons, plain'],
            ['canonical_name' => 'Paneermeel',       'category' => 'granen', 'name_en' => 'breadcrumbs, dry, grated, plain',             'gram_per_unit' => 10],

            // Kruiden
            ['canonical_name' => 'Peterselie',    'category' => 'kruiden', 'name_en' => 'parsley, fresh',     'gram_per_unit' => 2],
            ['canonical_name' => 'Bieslook',      'category' => 'kruiden', 'name_en' => 'chives, raw',        'gram_per_unit' => 2],
            ['canonical_name' => 'Dille',         'category' => 'kruiden', 'name_en' => 'dill weed, fresh',   'gram_per_unit' => 2],
            ['canonical_name' => 'Basilicum',     'category' => 'kruiden', 'name_en' => 'basil, fresh',       'gram_per_unit' => 2],
            ['canonical_name' => 'Oregano',       'category' => 'kruiden', 'name_en' => 'oregano, fresh',     'gram_per_unit' => 2],
            ['canonical_name' => 'Thijm',         'category' => 'kruiden', 'name_en' => 'thyme, fresh',       'gram_per_unit' => 2],
            ['canonical_name' => 'Rozemarijn',    'category' => 'kruiden', 'name_en' => 'rosemary, fresh',    'gram_per_unit' => 2],
            ['canonical_name' => 'Salie',         'category' => 'kruiden', 'name_en' => 'sage, fresh',        'gram_per_unit' => 2],
            ['canonical_name' => 'Minze',         'category' => 'kruiden', 'name_en' => 'spearmint, fresh',   'gram_per_unit' => 2],
            ['canonical_name' => 'Koriander blad','category' => 'kruiden', 'name_en' => 'coriander leaves, raw', 'gram_per_unit' => 2],
            ['canonical_name' => 'Tamme kervel',  'category' => 'kruiden', 'name_en' => 'chervil, dried',     'gram_per_unit' => 2],
            ['canonical_name' => 'Munt',          'category' => 'kruiden', 'name_en' => 'spearmint, fresh',   'gram_per_unit' => 2],
            ['canonical_name' => 'Laurier',       'category' => 'kruiden', 'name_en' => 'bay leaf'],

            // Specerijen
            ['canonical_name' => 'Peper',          'category' => 'specerijen', 'name_en' => 'pepper, black',                    'gram_per_unit' => 2],
            ['canonical_name' => 'Zwarte peper',   'category' => 'specerijen', 'name_en' => 'pepper, black',                    'gram_per_unit' => 2],
            ['canonical_name' => 'Witte peper',    'category' => 'specerijen', 'name_en' => 'pepper, white',                    'gram_per_unit' => 2],
            ['canonical_name' => 'Rode peper',     'category' => 'specerijen', 'name_en' => 'pepper, hot chili, red, raw',      'gram_per_unit' => 15],
            ['canonical_name' => 'Paprika poeder', 'category' => 'specerijen', 'name_en' => 'paprika',                          'gram_per_unit' => 3],
            ['canonical_name' => 'Chilipeper',     'category' => 'specerijen', 'name_en' => 'pepper, hot chili, red, raw',      'gram_per_unit' => 15],
            ['canonical_name' => 'Cayenne peper',  'category' => 'specerijen', 'name_en' => 'pepper, red or cayenne',           'gram_per_unit' => 2],
            ['canonical_name' => 'Nootmuskaat',    'category' => 'specerijen', 'name_en' => 'nutmeg, ground',                   'gram_per_unit' => 2],
            ['canonical_name' => 'Kaneel',         'category' => 'specerijen', 'name_en' => 'cinnamon, ground',                 'gram_per_unit' => 3],
            ['canonical_name' => 'Kruidnagel',     'category' => 'specerijen', 'name_en' => 'cloves, ground',                   'gram_per_unit' => 2],
            ['canonical_name' => 'Komijn',         'category' => 'specerijen', 'name_en' => 'cumin seed',                       'gram_per_unit' => 3],
            ['canonical_name' => 'Korianderzaad',  'category' => 'specerijen', 'name_en' => 'coriander seed',                   'gram_per_unit' => 3],
            ['canonical_name' => 'Gember',         'category' => 'specerijen', 'name_en' => 'ginger root, raw',                 'gram_per_unit' => 5],
            ['canonical_name' => 'Vanille',        'category' => 'specerijen', 'name_en' => 'vanilla extract',                  'gram_per_unit' => 4],
            ['canonical_name' => 'Saffraanzaad',   'category' => 'specerijen', 'name_en' => 'saffron',                          'gram_per_unit' => 1],
            ['canonical_name' => 'Venkelzaad',     'category' => 'specerijen', 'name_en' => 'fennel seed',                      'gram_per_unit' => 3],
            ['canonical_name' => 'Zout',           'category' => 'specerijen', 'name_en' => 'salt, table',                      'gram_per_unit' => 2],
            ['canonical_name' => 'Chilivlokken',   'category' => 'specerijen', 'name_en' => 'pepper, red or cayenne',           'gram_per_unit' => 2],
            ['canonical_name' => 'Lookpoeder',     'category' => 'specerijen', 'name_en' => 'garlic powder',                    'gram_per_unit' => 3],
            ['canonical_name' => 'Sesamzaad',      'category' => 'specerijen', 'name_en' => 'seeds, sesame seed, whole, dried', 'gram_per_unit' => 3],
            ['canonical_name' => 'Pittenmix',      'category' => 'specerijen', 'name_en' => 'seeds, sunflower seed kernels, dried', 'gram_per_unit' => 10],

            // Peulvruchten
            ['canonical_name' => 'Linzen',       'category' => 'peulvruchten', 'name_en' => 'lentils, raw'],
            ['canonical_name' => 'Kikkererwten', 'category' => 'peulvruchten', 'name_en' => 'chickpeas, raw'],
            ['canonical_name' => 'Witte bonen',  'category' => 'peulvruchten', 'name_en' => 'beans, white, mature seeds, raw'],
            ['canonical_name' => 'Rode bonen',   'category' => 'peulvruchten', 'name_en' => 'beans, kidney, red, mature seeds, raw'],
            ['canonical_name' => 'Zwarte bonen', 'category' => 'peulvruchten', 'name_en' => 'beans, black turtle, mature seeds, raw'],
            ['canonical_name' => 'Bruine bonen', 'category' => 'peulvruchten', 'name_en' => 'beans, pinto, mature seeds, raw'],
            ['canonical_name' => 'Erwten',       'category' => 'peulvruchten', 'name_en' => 'peas, green, raw'],
            ['canonical_name' => 'Sojabonen',    'category' => 'peulvruchten', 'name_en' => 'soybeans, mature seeds, raw'],
            ['canonical_name' => 'Falafel',      'category' => 'peulvruchten', 'name_en' => 'falafel',                          'gram_per_unit' => 20],

            // Fruit
            ['canonical_name' => 'Appel',       'category' => 'fruit', 'name_en' => 'apple, raw, with skin',                         'gram_per_unit' => 182],
            ['canonical_name' => 'Peer',         'category' => 'fruit', 'name_en' => 'pear, raw',                                    'gram_per_unit' => 170],
            ['canonical_name' => 'Banaan',       'category' => 'fruit', 'name_en' => 'banana, raw',                                  'gram_per_unit' => 120],
            ['canonical_name' => 'Aardbei',      'category' => 'fruit', 'name_en' => 'strawberry, raw'],
            ['canonical_name' => 'Bosbes',       'category' => 'fruit', 'name_en' => 'blueberry, raw'],
            ['canonical_name' => 'Framboos',     'category' => 'fruit', 'name_en' => 'raspberry, raw'],
            ['canonical_name' => 'Braam',        'category' => 'fruit', 'name_en' => 'blackberry, raw'],
            ['canonical_name' => 'Zwarte bes',   'category' => 'fruit', 'name_en' => 'currants, european black, raw'],
            ['canonical_name' => 'Rode bes',     'category' => 'fruit', 'name_en' => 'currants, red and white, raw'],
            ['canonical_name' => 'Kiwi',         'category' => 'fruit', 'name_en' => 'kiwi fruit, green, raw',                      'gram_per_unit' => 70],
            ['canonical_name' => 'Mango',        'category' => 'fruit', 'name_en' => 'mango, raw'],
            ['canonical_name' => 'Ananas',       'category' => 'fruit', 'name_en' => 'pineapple, raw, all varieties'],
            ['canonical_name' => 'Sinaasappel',  'category' => 'fruit', 'name_en' => 'orange, raw, all commercial varieties',       'gram_per_unit' => 130],
            ['canonical_name' => 'Citroen',      'category' => 'fruit', 'name_en' => 'lemon, raw, without peel',                    'gram_per_unit' => 58],
            ['canonical_name' => 'Citroensap',   'category' => 'fruit', 'name_en' => 'lemon juice, raw',                            'gram_per_unit' => 5],
            ['canonical_name' => 'Limoen',       'category' => 'fruit', 'name_en' => 'lime, raw',                                   'gram_per_unit' => 45],
            ['canonical_name' => 'Druif',        'category' => 'fruit', 'name_en' => 'grapes, red or green, raw'],
            ['canonical_name' => 'Abrikoos',     'category' => 'fruit', 'name_en' => 'apricot, raw',                                'gram_per_unit' => 35],
            ['canonical_name' => 'Pruim',        'category' => 'fruit', 'name_en' => 'plum, raw',                                   'gram_per_unit' => 66],
            ['canonical_name' => 'Avocado',      'category' => 'fruit', 'name_en' => 'avocado, raw, all commercial varieties',      'gram_per_unit' => 200],

            // Sauzen
            ['canonical_name' => 'Olijfolie',       'category' => 'sauzen', 'name_en' => 'oil, olive, salad or cooking',    'gram_per_unit' => 5],
            ['canonical_name' => 'Zonnebloemolie',  'category' => 'sauzen', 'name_en' => 'oil, sunflower, high linoleic',  'gram_per_unit' => 5],
            ['canonical_name' => 'Sesamolie',       'category' => 'sauzen', 'name_en' => 'oil, sesame, salad or cooking',  'gram_per_unit' => 5],
            ['canonical_name' => 'Azijn',           'category' => 'sauzen', 'name_en' => 'vinegar, distilled',             'gram_per_unit' => 5],
            ['canonical_name' => 'Balsamico azijn', 'category' => 'sauzen', 'name_en' => 'vinegar, balsamic',              'gram_per_unit' => 5],
            ['canonical_name' => 'Rode wijnazijn',  'category' => 'sauzen', 'name_en' => 'vinegar, red wine',              'gram_per_unit' => 5],
            ['canonical_name' => 'Appelazijn',      'category' => 'sauzen', 'name_en' => 'vinegar, cider',                 'gram_per_unit' => 5],
            ['canonical_name' => 'Soja saus',       'category' => 'sauzen', 'name_en' => 'soy sauce, low sodium',          'gram_per_unit' => 15],
            ['canonical_name' => 'Mosterd',         'category' => 'sauzen', 'name_en' => 'mustard, prepared, yellow',      'gram_per_unit' => 5],
            ['canonical_name' => 'Tomatenketsjup',  'category' => 'sauzen', 'name_en' => 'ketchup',                        'gram_per_unit' => 5],
            ['canonical_name' => 'Mayonaise',       'category' => 'sauzen', 'name_en' => 'mayonnaise, regular',            'gram_per_unit' => 15],
            ['canonical_name' => 'Sriracha',        'category' => 'sauzen', 'name_en' => 'sauce, hot chili',               'gram_per_unit' => 5],
            ['canonical_name' => 'Tabasco saus',    'category' => 'sauzen', 'name_en' => 'sauce, hot chili',               'gram_per_unit' => 5],
            ['canonical_name' => 'Honing',          'category' => 'sauzen', 'name_en' => 'honey',                          'gram_per_unit' => 7],
            ['canonical_name' => 'Confituur',       'category' => 'sauzen', 'name_en' => 'jam, preserves, all flavors',    'gram_per_unit' => 20],
            ['canonical_name' => 'Pindakaas',       'category' => 'sauzen', 'name_en' => 'peanut butter, smooth style',   'gram_per_unit' => 15],
            ['canonical_name' => 'Hummus',          'category' => 'sauzen', 'name_en' => 'hummus, commercial',             'gram_per_unit' => 30],

            // Diepvries
            ['canonical_name' => 'Diepvrieserwtjes',          'category' => 'diepvries', 'name_en' => 'peas, green, frozen, unprepared'],
            ['canonical_name' => 'Diepvriesbonen',            'category' => 'diepvries', 'name_en' => 'beans, snap, green, frozen, unprepared'],
            ['canonical_name' => 'Diepvriesspinaze',          'category' => 'diepvries', 'name_en' => 'spinach, frozen, chopped or leaf, unprepared'],
            ['canonical_name' => 'Diepvriesbrokkoli',         'category' => 'diepvries', 'name_en' => 'broccoli, frozen, chopped, unprepared'],
            ['canonical_name' => 'Diepvriesbloemkool',        'category' => 'diepvries', 'name_en' => 'cauliflower, frozen, unprepared'],
            ['canonical_name' => 'Diepvriesfriet',            'category' => 'diepvries', 'name_en' => 'potatoes, french fried, frozen, unprepared'],
            ['canonical_name' => 'Diepvriesgarnaaltjes',      'category' => 'diepvries', 'name_en' => 'shrimp, mixed species, raw'],
            ['canonical_name' => 'Diepvriesvisburger',        'category' => 'diepvries', 'name_en' => 'fish sticks, frozen, unprepared'],
            ['canonical_name' => 'Diepvriesfrikandel',        'category' => 'diepvries', 'name_en' => 'sausage, smoked'],
            ['canonical_name' => 'Diepvriesfruit',            'category' => 'diepvries', 'name_en' => 'fruits, mixed, frozen, unsweetened'],

            // Blik
            ['canonical_name' => 'Blik gehakte tomaten','category' => 'blik', 'name_en' => 'tomatoes, crushed, canned',               'gram_per_unit' => 400],
            ['canonical_name' => 'Blik tomatensaus',    'category' => 'blik', 'name_en' => 'tomato sauce, canned',                    'gram_per_unit' => 400],
            ['canonical_name' => 'Blik tonijn',         'category' => 'blik', 'name_en' => 'tuna, light, canned in water, drained',  'gram_per_unit' => 150],
            ['canonical_name' => 'Blik sardines',       'category' => 'blik', 'name_en' => 'sardine, canned in oil, drained',        'gram_per_unit' => 120],
            ['canonical_name' => 'Blik makreel',        'category' => 'blik', 'name_en' => 'fish, mackerel, canned, drained',        'gram_per_unit' => 200],
            ['canonical_name' => 'Blik witte bonen',    'category' => 'blik', 'name_en' => 'beans, white, canned',                   'gram_per_unit' => 400],
            ['canonical_name' => 'Blik rode bonen',     'category' => 'blik', 'name_en' => 'beans, kidney, red, canned',             'gram_per_unit' => 400],
            ['canonical_name' => 'Blik kikkererwten',   'category' => 'blik', 'name_en' => 'chickpeas, canned',                      'gram_per_unit' => 400],
            ['canonical_name' => 'Blik doperwten',      'category' => 'blik', 'name_en' => 'peas, green, canned, drained',           'gram_per_unit' => 400],
            ['canonical_name' => 'Blik mais',           'category' => 'blik', 'name_en' => 'corn, sweet, yellow, canned, drained',   'gram_per_unit' => 400],
            ['canonical_name' => 'Blik paprika',        'category' => 'blik', 'name_en' => 'peppers, sweet, red, canned, drained',   'gram_per_unit' => 400],
            ['canonical_name' => 'Blik champignons',    'category' => 'blik', 'name_en' => 'mushrooms, canned, drained',             'gram_per_unit' => 400],
            ['canonical_name' => 'Blik pijnboompitten', 'category' => 'blik', 'name_en' => 'pine nuts, dried',                       'gram_per_unit' => 100],
           
            // Dranken
            ['canonical_name' => 'Water',             'category' => 'dranken', 'name_en' => 'water, tap, drinking'],
            ['canonical_name' => 'Sinaasappelsap',    'category' => 'dranken', 'name_en' => 'orange juice, raw'],
            ['canonical_name' => 'Appelsap',          'category' => 'dranken', 'name_en' => 'apple juice, canned or bottled, unsweetened'],
            ['canonical_name' => 'Bier',              'category' => 'dranken', 'name_en' => 'beer, regular'],
            ['canonical_name' => 'Rode wijn',         'category' => 'dranken', 'name_en' => 'wine, table, red'],
            ['canonical_name' => 'Witte wijn',        'category' => 'dranken', 'name_en' => 'wine, table, white'],
            ['canonical_name' => 'Rosé',              'category' => 'dranken', 'name_en' => 'wine, table, rose'],
            ['canonical_name' => 'Champagne',         'category' => 'dranken', 'name_en' => 'wine, sparkling, white'],
            ['canonical_name' => 'Bouillon groenten', 'category' => 'dranken', 'name_en' => 'broth, vegetable, low sodium, ready-to-serve'],
            ['canonical_name' => 'Bouillon vis',      'category' => 'dranken', 'name_en' => 'broth, clam, bottled'],
        ];

        foreach ($ingredients as $data) {
            Ingredient::updateOrCreate(
                ['canonical_name' => $data['canonical_name']],
                [
                    'category'      => $data['category'],
                    'name_en'       => $data['name_en'] ?? null,
                    'gram_per_unit' => $data['gram_per_unit'] ?? null,
                ]
            );
        }

        $seederNames = array_column($ingredients, 'canonical_name');

        Ingredient::whereNotIn('canonical_name', $seederNames)
            ->whereDoesntHave('inventoryItems')
            ->whereDoesntHave('recipeIngredients')
            ->delete();

        Cache::forget('ingredients_for_matching');
    }
}
