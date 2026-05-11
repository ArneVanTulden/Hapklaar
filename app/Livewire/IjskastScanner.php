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

        $this->validate(['photo' => 'required|image|max:20480']);

        $this->isScanning = true;
        $this->error = null;

        try {
            $manager = new ImageManager(new Driver());
            $encoded = $manager->decode($this->photo->getRealPath())
                ->scaleDown(width: 1024)
                ->encode(new JpegEncoder(80));

            $imageData = base64_encode((string) $encoded);
            $mimeType = 'image/jpeg';

            $knownNames = Cache::remember('ingredient_canonical_names', 3600, fn () =>
                Ingredient::orderBy('canonical_name')->pluck('canonical_name')->implode(', ')
            );

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
                                    'text' => "Identificeer alle voedselingrediënten die zichtbaar zijn in deze afbeelding. Gebruik ALLEEN namen uit deze lijst: {$knownNames}. Als een ingredient niet exact in de lijst staat, kies dan de dichtstbijzijnde match uit de lijst. Geef ALLEEN een JSON-array terug met objecten met de sleutels: \"name\" (exacte naam uit de lijst), \"qty\" (geschatte hoeveelheid als string, bijv. \"1\", \"200\"), \"unit\" (eenheid zoals \"stuks\", \"gram\", \"liter\", of lege string). Geef alleen de JSON-array terug, geen andere tekst.",
                                ],
                            ],
                        ],
                    ],
                    'max_tokens' => 500,
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

            $validNames = collect($ingredients)
                ->pluck('name')
                ->filter()
                ->map(fn ($n) => strtolower(trim($n)))
                ->all();

            $matched = ! empty($validNames)
                ? Ingredient::whereRaw('LOWER(canonical_name) IN (' . implode(',', array_fill(0, count($validNames), '?')) . ')', $validNames)
                    ->get()
                    ->keyBy(fn ($i) => strtolower($i->canonical_name))
                : collect();

            foreach ($ingredients as $item) {
                $name = trim($item['name'] ?? '');
                if (! $name) {
                    continue;
                }

                $ingredient = $matched[strtolower($name)] ?? null;

                $this->dispatch('scanner-ingredient-added',
                    id: $ingredient?->id,
                    name: $name,
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
