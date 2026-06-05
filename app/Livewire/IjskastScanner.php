<?php

namespace App\Livewire;

use App\Models\Ingredient;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\RateLimiter;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Encoders\JpegEncoder;
use Intervention\Image\ImageManager;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;

class IjskastScanner extends Component
{
    use WithFileUploads;

    public ?TemporaryUploadedFile $photo = null;
    public bool $isScanning = false;
    public ?string $error = null;

    public function updatedPhoto(): void
    {
        $this->error = null;

        if (! $this->photo) {
            return;
        }

        try {
            $this->validateOnly('photo', ['photo' => 'image|mimes:jpg,jpeg,png,gif,webp|max:20480']);
        } catch (\Illuminate\Validation\ValidationException) {
            $this->error = 'Alleen afbeeldingen (JPG, PNG, WebP) zijn toegestaan. Max 20 MB.';
            $this->photo = null;
        }
    }

    public function scan(): void
    {
        if (! $this->photo || $this->isScanning) {
            return;
        }

        if (! auth()->check() || auth()->user()->role !== 'admin') {
            $key = 'scanner:' . request()->ip();

            if (RateLimiter::tooManyAttempts($key, 5)) {
                $minutes = ceil(RateLimiter::availableIn($key) / 60);
                $this->error = "Je hebt het maximum van 5 scans per halfuur bereikt. Probeer het over {$minutes} minuten opnieuw.";
                return;
            }

            RateLimiter::hit($key, 1800);
        }

        $this->validate(['photo' => 'required|image|max:8192']);

        $this->isScanning = true;
        $this->error = null;

        try {
            ini_set('memory_limit', '512M');

            $manager = new ImageManager(new Driver());
            $encoded = $manager->decode($this->photo->getRealPath())
                ->scaleDown(width: 1024)
                ->encode(new JpegEncoder(80));

            $imageData = base64_encode((string) $encoded);
            $mimeType = 'image/jpeg';

            $allIngredients = Cache::remember('ingredients_for_matching', 3600, fn () =>
                Ingredient::all(['id', 'canonical_name', 'category'])->toArray()
            );

            $ingredientNames = implode(', ', array_column($allIngredients, 'canonical_name'));

            $response = Http::withToken(config('services.openai.key'))
                ->timeout(30)
                ->post('https://api.openai.com/v1/chat/completions', [
                    'model' => 'gpt-4o-mini',
                    'messages' => [
                        [
                            'role' => 'user',
                            'content' => [
                                [
                                    'type' => 'image_url',
                                    'image_url' => [
                                        'url' => "data:{$mimeType};base64,{$imageData}",
                                    ],
                                ],
                                [
                                    'type' => 'text',
                                    'text' => "Je bent een koelkast-scanner. Identificeer alle voedselingrediënten die zichtbaar zijn in deze afbeelding.\n\nBekende ingrediënten (gebruik deze exacte namen als het ingredient daarin staat):\n{$ingredientNames}\n\nRegels:\n- Gebruik EXACT de naam uit de lijst hierboven als het ingredient daarin voorkomt\n- Staat het niet in de lijst? Gebruik dan de meest passende Nederlandse naam\n- Scan alle schappen en vakken volledig\n- Neem alleen op wat je daadwerkelijk ziet, geen aannames\n- Vloeistoffen: identificeer op kleur/label (oranje sap = sinaasappelsap, transparant = water)\n\nGeef ALLEEN een JSON-array terug: [{\"name\":\"naam\",\"qty\":\"hoeveelheid\",\"unit\":\"stuks|g|kg|ml|l\"}]. Gebruik altijd de afkorting (g niet gram, ml niet milliliter). Geen uitleg, geen markdown.",
                                ],
                            ],
                        ],
                    ],
                    'max_tokens' => 1000,
                ]);

            if (! $response->successful()) {
                $this->error = 'Scan mislukt. Probeer opnieuw.';
                return;
            }

            $content = trim($response->json('choices.0.message.content') ?? '');
            $content = preg_replace('/^```(?:json)?\s*|\s*```$/m', '', $content);

            $ingredients = json_decode($content, true);

            if (! is_array($ingredients) || empty($ingredients)) {
                $this->error = 'Kon geen ingrediënten herkennen. Probeer een duidelijkere foto.';
                return;
            }

            foreach ($ingredients as $item) {
                $name = trim($item['name'] ?? '');
                if (! $name) {
                    continue;
                }

                $ingredient = $this->fuzzyMatch($name, $allIngredients);

                if (! $ingredient) {
                    continue;
                }

                $this->dispatch('scanner-ingredient-added',
                    id:       $ingredient['id'],
                    name:     $ingredient['canonical_name'],
                    qty:      $item['qty'] ?? '1',
                    unit:     $this->normalizeUnit($item['unit'] ?? 'stuks'),
                    category: $ingredient['category'] ?? null,
                );
            }

        } catch (\Throwable) {
            $this->error = 'Er ging iets mis. Probeer opnieuw.';
        } finally {
            $this->isScanning = false;
        }
    }

    private function normalizeUnit(string $unit): string
    {
        return match (strtolower(trim($unit))) {
            'gram', 'gr', 'g'   => 'g',
            'kilogram', 'kg'    => 'kg',
            'milliliter', 'ml'  => 'ml',
            'liter', 'l'        => 'l',
            default             => 'stuks',
        };
    }

    private function fuzzyMatch(string $name, array $ingredients): ?array
    {
        $needle = strtolower($name);

        // 1. Exact match
        foreach ($ingredients as $ing) {
            if (strtolower($ing['canonical_name']) === $needle) {
                return $ing;
            }
        }

        // 2. Contains match (both directions)
        foreach ($ingredients as $ing) {
            $haystack = strtolower($ing['canonical_name']);
            if (str_contains($haystack, $needle) || str_contains($needle, $haystack)) {
                return $ing;
            }
        }

        // 3. similar_text with 70% threshold
        $best = null;
        $bestScore = 0.0;
        foreach ($ingredients as $ing) {
            similar_text($needle, strtolower($ing['canonical_name']), $percent);
            if ($percent > 70 && $percent > $bestScore) {
                $bestScore = $percent;
                $best = $ing;
            }
        }

        return $best;
    }

    public function clearPhoto(): void
    {
        $this->photo = null;
        $this->error = null;
    }

    public function render()
    {
        return view('livewire.ijskast-scanner');
    }
}
