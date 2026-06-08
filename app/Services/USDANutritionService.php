<?php

namespace App\Services;

use App\Models\Ingredient;
use App\Models\Recipe;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class USDANutritionService
{
    private const BASE_URL = 'https://api.nal.usda.gov/fdc/v1';

    private const NUTRIENT_MAP = [
        'kcal_per_100g'      => 1008,
        'protein_per_100g'   => 1003,
        'carbs_per_100g'     => 1005,
        'fat_per_100g'       => 1004,
        'fiber_per_100g'     => 1079,
        'sugar_per_100g'     => 2000,
        'sodium_per_100g'    => 1093,
        'potassium_per_100g' => 1092,
        'iron_per_100g'      => 1089,
        'vitamin_a_per_100g' => 1106,
        'vitamin_c_per_100g' => 1162,
    ];

    public function fetchAndStoreForIngredient(Ingredient $ingredient): bool
    {
        if (empty($ingredient->name_en)) {
            return false;
        }

        $cacheKey = 'usda_' . md5(strtolower(trim($ingredient->name_en)));

        if (Cache::has($cacheKey)) {
            $nutrition = Cache::get($cacheKey);
        } else {
            $nutrition = $this->fetchFromUSDA($ingredient->name_en);
            if ($nutrition !== null) {
                Cache::put($cacheKey, $nutrition, now()->addDays(30));
            }
        }

        if ($nutrition === null) {
            return false;
        }

        $ingredient->update($nutrition);
        return true;
    }

    public function calculateForRecipe(Recipe $recipe): array
    {
        $totals = [
            'calories'   => 0.0,
            'protein'    => 0.0,
            'carbs'      => 0.0,
            'fat'        => 0.0,
            'fiber'      => 0.0,
            'sugar'      => 0.0,
            'sodium'     => 0.0,
            'potassium'  => 0.0,
            'iron'       => 0.0,
            'vitamin_a'  => 0.0,
            'vitamin_c'  => 0.0,
        ];

        foreach ($recipe->ingredients as $ingredient) {
            if ($ingredient->kcal_per_100g === null) {
                continue;
            }

            $grams = $this->toGrams(
                (float) ($ingredient->pivot->quantity ?? 0),
                $ingredient->pivot->unit,
                $ingredient->gram_per_unit ? (float) $ingredient->gram_per_unit : null
            );

            if ($grams === null || $grams <= 0) {
                continue;
            }

            $factor = $grams / 100;

            $totals['calories']  += (float) $ingredient->kcal_per_100g      * $factor;
            $totals['protein']   += (float) $ingredient->protein_per_100g   * $factor;
            $totals['carbs']     += (float) $ingredient->carbs_per_100g     * $factor;
            $totals['fat']       += (float) $ingredient->fat_per_100g       * $factor;
            $totals['fiber']     += (float) $ingredient->fiber_per_100g     * $factor;
            $totals['sugar']     += (float) $ingredient->sugar_per_100g     * $factor;
            $totals['sodium']    += (float) $ingredient->sodium_per_100g    * $factor;
            $totals['potassium'] += (float) $ingredient->potassium_per_100g * $factor;
            $totals['iron']      += (float) $ingredient->iron_per_100g      * $factor;
            $totals['vitamin_a'] += (float) $ingredient->vitamin_a_per_100g * $factor;
            $totals['vitamin_c'] += (float) $ingredient->vitamin_c_per_100g * $factor;
        }

        return $totals;
    }

    private function toGrams(float $quantity, string $unit, ?float $gramPerUnit): ?float
    {
        return match ($unit) {
            'g'                             => $quantity,
            'kg'                            => $quantity * 1000,
            'ml'                            => $quantity,
            'l'                             => $quantity * 1000,
            'eetlepel'                      => $quantity * 15,
            'theelepel'                     => $quantity * 5,
            'stuks', 'blikken', 'zakjes'    => $gramPerUnit !== null ? $quantity * $gramPerUnit : null,
            default                         => null,
        };
    }

    private function fetchFromUSDA(string $nameEn): ?array
    {
        $response = Http::get(self::BASE_URL . '/foods/search', [
            'query'    => $nameEn,
            'api_key'  => config('services.usda.key'),
            'dataType' => 'Foundation,SR Legacy',
            'pageSize' => 1,
        ]);

        if ($response->failed() || empty($response->json('foods'))) {
            return null;
        }

        $nutrientFlip = array_flip(self::NUTRIENT_MAP);
        $result       = array_fill_keys(array_keys(self::NUTRIENT_MAP), 0.0);

        foreach ($response->json('foods.0.foodNutrients', []) as $n) {
            $id = $n['nutrientId'] ?? null;
            if (isset($nutrientFlip[$id])) {
                $result[$nutrientFlip[$id]] = (float) ($n['value'] ?? 0);
            }
        }

        return $result;
    }
}
