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
        $sentences = collect($recipe->transcript ?? [])
            ->pluck('text')
            ->map(fn($t) => trim($t))
            ->filter()
            ->values();

        // Pack as many transcript sentences as fit in ~700 chars
        $context = '';
        foreach ($sentences as $sentence) {
            if (mb_strlen($context) + mb_strlen($sentence) + 1 > 700) break;
            $context .= ' ' . $sentence;
        }

        $ingredients = $recipe->ingredients->pluck('canonical_name')->unique()->implode(', ');
        $prefix      = "Hey Hapklaar. {$recipe->title}. {$ingredients}.";

        return mb_substr(trim($prefix . $context), 0, 900);
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
        if (empty($commandWords)) return null;

        $timedSteps = $steps->filter(fn($s) => $s->video_timestamp)->values();

        // IDF: words appearing in fewer steps are more discriminating
        $wordStepCount = [];
        foreach ($timedSteps as $step) {
            foreach (array_unique($this->tokenize($step->description)) as $w) {
                $wordStepCount[$w] = ($wordStepCount[$w] ?? 0) + 1;
            }
        }
        $total = max(1, $timedSteps->count());

        $bestStep  = null;
        $bestScore = 0.0;

        foreach ($timedSteps as $step) {
            $stepWords = $this->tokenize($step->description);
            $score     = 0.0;
            foreach (array_intersect($commandWords, $stepWords) as $w) {
                $score += log($total / ($wordStepCount[$w] ?? 1) + 1);
            }
            if ($score > $bestScore) {
                $bestScore = $score;
                $bestStep  = $step;
            }
        }

        if ($bestStep && $bestScore > 0) {
            return ['timestamp' => $bestStep->video_timestamp, 'step' => $bestStep->step_number];
        }

        return null;
    }

    private function stem(string $word): string
    {
        static $prefixes = ['ge', 'be', 'ver', 'ont', 'her', 'over'];
        static $suffixes = ['enden', 'ingen', 'heid', 'elijk', 'sten', 'nden',
                            'den', 'ten', 'nen', 'ing', 'en', 'de', 'te', 'd', 't', 's'];

        foreach ($prefixes as $p) {
            if (str_starts_with($word, $p) && strlen($word) > strlen($p) + 3) {
                $word = substr($word, strlen($p));
                break;
            }
        }
        foreach ($suffixes as $s) {
            if (str_ends_with($word, $s) && strlen($word) > strlen($s) + 3) {
                return substr($word, 0, -strlen($s));
            }
        }

        return $word;
    }

    private function tokenize(string $text): array
    {
        $words = array_filter(explode(' ', preg_replace('/[^a-z\s]/', '', strtolower($text))));
        $words = array_diff($words, self::STOP_WORDS);

        return array_values(array_map([$this, 'stem'], $words));
    }
}
