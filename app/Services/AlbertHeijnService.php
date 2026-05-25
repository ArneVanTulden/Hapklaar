<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class AlbertHeijnService
{
    private const BASE_URL = 'https://api.ah.nl';
    private const TOKEN_CACHE_KEY = 'ah_anonymous_token';

    public function searchProduct(string $query): ?array
    {
        $token = $this->getAnonymousToken();

        if (! $token) {
            return null;
        }

        $response = Http::withToken($token)
            ->withHeaders([
                'User-Agent'    => 'Appie/8.22.3',
                'x-application' => 'AHWEBSHOP',
            ])
            ->get(self::BASE_URL . '/mobile-services/product/search/v2', [
                'query'  => $query,
                'size'   => 10,
                'sortOn' => 'RELEVANCE',
            ]);

        if (! $response->ok()) {
            return null;
        }

        $products = $response->json('products', []);

        if (empty($products)) {
            return null;
        }

        // Pick cheapest product (actual pay price) — avoids multi-packs and premium variants
        $product = collect($products)
            ->sortBy(fn($p) => $p['currentPrice'] ?? $p['priceBeforeBonus'] ?? PHP_INT_MAX)
            ->first();

        $price = (float) ($product['currentPrice'] ?? $product['priceBeforeBonus'] ?? 0) ?: null;

        return [
            'title' => $product['title'] ?? null,
            'price' => $price,
        ];
    }

    private function getAnonymousToken(): ?string
    {
        return Cache::remember(self::TOKEN_CACHE_KEY, now()->addMinutes(110), function () {
            $response = Http::post(self::BASE_URL . '/mobile-auth/v1/auth/token/anonymous', [
                'clientId' => 'appie',
            ]);

            if (! $response->ok()) {
                return null;
            }

            return $response->json('access_token');
        });
    }
}
