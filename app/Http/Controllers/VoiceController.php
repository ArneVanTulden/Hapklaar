<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use OpenAI;

class VoiceController extends Controller
{
    private const WAKE_WORD = 'hapklaar';

    private const STOP_WORDS = [
        'de', 'het', 'een', 'en', 'van', 'in', 'op', 'aan', 'met', 'voor',
        'naar', 'is', 'dat', 'je', 'dit', 'die', 'te', 'ze', 'ook', 'wanneer',
        'laat', 'zien', 'ga', 'spring', 'waar', 'hoe', 'bij', 'nu', 'dan',
    ];

    public function match(Request $request, int $id): JsonResponse
    {
        $request->validate(['audio' => 'required|file|max:10240']);

        $recipe = Recipe::with(['steps', 'ingredients'])->findOrFail($id);

        $client = OpenAI::client(config('services.openai.key'));

        Storage::makeDirectory('temp');
        $filename = uniqid('voice_') . '.wav';
        $request->file('audio')->storeAs('temp', $filename);
        $handle = fopen(Storage::path('temp/' . $filename), 'r');

        try {
            $response = $client->audio()->transcribe([
                'model'    => 'whisper-1',
                'file'     => $handle,
                'language' => 'nl',
                'prompt'   => $this->buildPrompt($recipe),
            ]);
        } finally {
            is_resource($handle) && fclose($handle);
            Storage::delete('temp/' . $filename);
        }

        $transcript = strtolower(trim($response->text));
        $transcript = strtr($transcript, ['hé ' => 'hey ', 'hè ' => 'hey ', 'he ' => 'hey ']);

        // No wake word → ignore silently
        $wakePos = strpos($transcript, self::WAKE_WORD);
        if ($wakePos === false) {
            return response()->json(['wakeword_found' => false]);
        }

        // Extract command after wake word
        $command = ltrim(substr($transcript, $wakePos + strlen(self::WAKE_WORD)), ':., ');

        if (empty($command)) {
            return response()->json(['wakeword_found' => true, 'command' => '', 'timestamp' => null, 'step' => null]);
        }

        // Priority 1: match against video transcript segments
        if ($recipe->transcript) {
            $segment = $this->matchTranscript($command, $recipe->transcript);
            if ($segment) {
                return response()->json([
                    'wakeword_found' => true,
                    'command'        => $command,
                    'timestamp'      => (int) round($segment['start']),
                    'step'           => null,
                ]);
            }
        }

        // Priority 2: "stap X" in command
        if (preg_match('/\bstap\s+(\d+)\b/i', $command, $m)) {
            $step = $recipe->steps->firstWhere('step_number', (int) $m[1]);
            if ($step?->video_timestamp) {
                return response()->json([
                    'wakeword_found' => true,
                    'command'        => $command,
                    'timestamp'      => $step->video_timestamp,
                    'step'           => $step->step_number,
                ]);
            }
        }

        // Priority 3: word overlap against step descriptions
        $result = $this->matchSteps($command, $recipe->steps);
        if ($result) {
            return response()->json([
                'wakeword_found' => true,
                'command'        => $command,
                'timestamp'      => $result['timestamp'],
                'step'           => $result['step'],
            ]);
        }

        return response()->json(['wakeword_found' => true, 'command' => $command, 'timestamp' => null, 'step' => null]);
    }

    private function buildPrompt(Recipe $recipe): string
    {
        $ingredients = $recipe->ingredients->pluck('canonical_name')->implode(', ');
        $stepWords   = $recipe->steps->pluck('description')
            ->flatMap(fn($d) => $this->tokenize($d))
            ->unique()->implode(', ');

        return "Hey Hapklaar, stap, recept, {$recipe->title}, {$ingredients}, {$stepWords}.";
    }

    private function matchTranscript(string $command, array $segments): ?array
    {
        $commandWords = $this->tokenize($command);
        $best         = null;
        $bestScore    = 0;

        foreach ($segments as $segment) {
            $overlap = count(array_intersect($commandWords, $this->tokenize($segment['text'])));
            if ($overlap > $bestScore) {
                $bestScore = $overlap;
                $best      = $segment;
            }
        }

        return $bestScore >= 1 ? $best : null;
    }

    private function matchSteps(string $command, $steps): ?array
    {
        $commandWords = $this->tokenize($command);
        $bestStep     = null;
        $bestScore    = 0;

        foreach ($steps as $step) {
            if (! $step->video_timestamp) {
                continue;
            }

            $overlap = count(array_intersect($commandWords, $this->tokenize($step->description)));
            if ($overlap > $bestScore) {
                $bestScore = $overlap;
                $bestStep  = $step;
            }
        }

        if ($bestStep && $bestScore >= 1) {
            return ['timestamp' => $bestStep->video_timestamp, 'step' => $bestStep->step_number];
        }

        return null;
    }

    private function tokenize(string $text): array
    {
        return array_diff(
            array_filter(explode(' ', preg_replace('/[^a-z\s]/', '', strtolower($text)))),
            self::STOP_WORDS
        );
    }
}
