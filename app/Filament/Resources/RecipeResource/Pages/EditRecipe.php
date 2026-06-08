<?php

namespace App\Filament\Resources\RecipeResource\Pages;

use App\Filament\Resources\RecipeResource;
use App\Models\NutritionInfo;
use App\Services\USDANutritionService;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditRecipe extends EditRecord
{
    protected static string $resource = RecipeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('recalculate_nutrition')
                ->label('Herbereken voedingswaarden')
                ->icon('heroicon-o-arrow-path')
                ->color('warning')
                ->action(function () {
                    $this->recalculateNutrition();
                    Notification::make()
                        ->title('Voedingswaarden herberekend')
                        ->success()
                        ->send();
                }),
            Actions\DeleteAction::make(),
        ];
    }

    protected function afterSave(): void
    {
        $this->recalculateNutrition();
    }

    private function recalculateNutrition(): void
    {
        $recipe = $this->getRecord()->load('ingredients');
        $totals = app(USDANutritionService::class)->calculateForRecipe($recipe);

        NutritionInfo::updateOrCreate(['recipe_id' => $recipe->id], $totals);
        $recipe->update(['calories_per_portion' => round($totals['calories'])]);
    }
}
