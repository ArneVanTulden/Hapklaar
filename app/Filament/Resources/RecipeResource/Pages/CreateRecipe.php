<?php

namespace App\Filament\Resources\RecipeResource\Pages;

use App\Filament\Resources\RecipeResource;
use App\Models\NutritionInfo;
use App\Services\USDANutritionService;
use Filament\Resources\Pages\CreateRecord;

class CreateRecipe extends CreateRecord
{
    protected static string $resource = RecipeResource::class;

    protected function afterCreate(): void
    {
        $recipe = $this->getRecord()->load('ingredients');
        $totals = app(USDANutritionService::class)->calculateForRecipe($recipe);

        NutritionInfo::updateOrCreate(['recipe_id' => $recipe->id], $totals);

        $recipe->update(['calories_per_portion' => round($totals['calories'])]);
    }
}
