<?php

namespace App\Livewire;

use App\Models\Ingredient;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
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
    }

    public function scan(): void
    {
        if (! $this->photo || $this->isScanning) {
            return;
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
                                    'text' => "Je bent een koelkast-scanner. Identificeer alle voedselingrediënten die zichtbaar zijn in deze afbeelding.\n\nRegels:\n- Gebruik altijd Nederlandse namen (\"wortel\" niet \"carrot\")\n- Scan alle schappen en vakken volledig\n- Neem alleen op wat je daadwerkelijk ziet, geen aannames\n- Vloeistoffen: identificeer op kleur/label (oranje sap = sinaasappelsap, transparant = water)\n\nGeef ALLEEN een JSON-array terug: [{\"name\":\"nederlandse naam\",\"qty\":\"hoeveelheid\",\"unit\":\"stuks|gram|liter|\"}]. Geen uitleg, geen markdown.",
                                ],
                            ],
                        ],
                    ],
                    'max_tokens' => 800,
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

            $allIngredients = Cache::remember('ingredients_for_matching', 3600, fn () =>
                Ingredient::all(['id', 'canonical_name'])->toArray()
            );

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
                    id: $ingredient['id'],
                    name: $ingredient['canonical_name'],
                    qty: $item['qty'] ?? '1',
                    unit: $item['unit'] ?? 'stuks',
                );
            }

        } catch (\Throwable) {
            $this->error = 'Er ging iets mis. Probeer opnieuw.';
        } finally {
            $this->isScanning = false;
        }
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
