<?php

namespace App\Services;

use Illuminate\Http\Client\Pool;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class AlbertHeijnService
{
    private const BASE_URL = 'https://api.ah.nl';
    private const TOKEN_CACHE_KEY = 'ah_anonymous_token';

    /**
     * Search multiple products concurrently. Keys in returned array match keys of $queries.
     */
    public function searchProducts(array $queries): array
    {
        $token = $this->getAnonymousToken();
        if (! $token) {
            return [];
        }

        $responses = Http::pool(function (Pool $pool) use ($queries, $token) {
            foreach ($queries as $key => $query) {
                $pool->as((string) $key)
                    ->timeout(10)
                    ->withToken($token)
                    ->withHeaders([
                        'User-Agent'    => 'Appie/8.22.3',
                        'x-application' => 'AHWEBSHOP',
                    ])
                    ->get(self::BASE_URL . '/mobile-services/product/search/v2', [
                        'query'  => $query,
                        'size'   => 10,
                        'sortOn' => 'RELEVANCE',
                    ]);
            }
        });

        $results = [];

        foreach ($queries as $key => $query) {
            $response = $responses[(string) $key] ?? null;

            if (! $response || ! $response->ok()) {
                $results[$key] = null;
                continue;
            }

            $products = $response->json('products', []);

            if (empty($products)) {
                $results[$key] = null;
                continue;
            }

            $product = collect($products)
                ->sortBy(fn ($p) => $p['currentPrice'] ?? $p['priceBeforeBonus'] ?? PHP_INT_MAX)
                ->first();

            $price = (float) ($product['currentPrice'] ?? $product['priceBeforeBonus'] ?? 0) ?: null;

            $results[$key] = [
                'title' => $product['title'] ?? null,
                'price' => $price,
            ];
        }

        return $results;
    }

    public function searchProduct(string $query): ?array
    {
        $token = $this->getAnonymousToken();

        if (! $token) {
            return null;
        }

        $response = Http::timeout(10)->withToken($token)
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
        if ($cached = Cache::get(self::TOKEN_CACHE_KEY)) {
            return $cached;
        }

        $response = Http::timeout(10)->post(self::BASE_URL . '/mobile-auth/v1/auth/token/anonymous', [
            'clientId' => 'appie',
        ]);

        if (! $response->ok()) {
            return null;
        }

        $token = $response->json('access_token');

        if ($token) {
            Cache::put(self::TOKEN_CACHE_KEY, $token, now()->addMinutes(110));
        }

        return $token;
    }
}
