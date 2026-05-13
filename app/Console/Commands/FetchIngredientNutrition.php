<?php

namespace App\Console\Commands;

use App\Models\Ingredient;
use App\Services\USDANutritionService;
use Illuminate\Console\Command;

class FetchIngredientNutrition extends Command
{
    protected $signature = 'ingredients:fetch-nutrition
                            {--force : Herbereken ook ingrediënten die al data hebben}';

    protected $description = 'Haal macronutriënten op via USDA API en sla op per ingrediënt (per 100g)';

    public function handle(USDANutritionService $service): int
    {
        $query = Ingredient::whereNotNull('name_en');

        if (!$this->option('force')) {
            $query->whereNull('kcal_per_100g');
        }

        $ingredients = $query->get();

        if ($ingredients->isEmpty()) {
            $this->info('Alle ingrediënten hebben al nutritiedata. Gebruik --force om te herberekenen.');
            return self::SUCCESS;
        }

        $this->info("Nutritie ophalen voor {$ingredients->count()} ingrediënten...");
        $bar = $this->output->createProgressBar($ingredients->count());
        $bar->start();

        $success = 0;
        $failed  = [];

        foreach ($ingredients as $ingredient) {
            if ($service->fetchAndStoreForIngredient($ingredient)) {
                $success++;
            } else {
                $failed[] = $ingredient->canonical_name;
            }
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info("Opgeslagen: {$success}/{$ingredients->count()}");

        if (!empty($failed)) {
            $this->warn('Niet gevonden in USDA: ' . implode(', ', $failed));
        }

        return self::SUCCESS;
    }
}
