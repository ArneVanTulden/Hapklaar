<?php

namespace App\Livewire;

use App\Models\Ingredient;
use Illuminate\Support\Facades\Http;
use Livewire\Component;
use Livewire\WithFileUploads;

class IjskastScanner extends Component
{
    use WithFileUploads;

    public $photo = null;
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

        $this->isScanning = true;
        $this->error = null;

        try {
            $imageData = base64_encode(file_get_contents($this->photo->getRealPath()));
            $mimeType = $this->photo->getMimeType();

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
                                    'text' => 'Identificeer alle voedselingrediënten die zichtbaar zijn in deze afbeelding. Geef ALLEEN een JSON-array terug met objecten met de sleutels: "name" (naam in het Nederlands, kleine letters), "qty" (geschatte hoeveelheid als string, bijv. "1", "200"), "unit" (eenheid zoals "stuks", "gram", "liter", of lege string). Voorbeeld: [{"name":"melk","qty":"1","unit":"liter"},{"name":"ei","qty":"6","unit":"stuks"}]. Geef alleen de JSON-array terug, geen andere tekst.',
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

            foreach ($ingredients as $item) {
                $name = trim($item['name'] ?? '');
                if (! $name) {
                    continue;
                }

                $ingredient = Ingredient::whereRaw('LOWER(canonical_name) = ?', [strtolower($name)])->first();

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
