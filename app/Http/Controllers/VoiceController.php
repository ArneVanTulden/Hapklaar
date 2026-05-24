<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenAI;

class VoiceController extends Controller
{
    public function match(Request $request, int $id): JsonResponse
    {
        $request->validate(['audio' => 'required|file|max:10240']);

        $recipe = Recipe::with('steps')->findOrFail($id);

        $client = OpenAI::client(config('services.openai.key'));

        $response = $client->audio()->transcribe([
            'model'    => 'whisper-1',
            'file'     => fopen($request->file('audio')->getRealPath(), 'r'),
            'language' => 'nl',
        ]);

        $transcript = strtolower(trim($response->text));

        // Priority 1: "stap X"
        if (preg_match('/\bstap\s+(\d+)\b/i', $transcript, $m)) {
            $step = $recipe->steps->firstWhere('step_number', (int) $m[1]);
            if ($step?->video_timestamp) {
                return response()->json([
                    'timestamp'  => $step->video_timestamp,
                    'step'       => $step->step_number,
                    'transcript' => $transcript,
                ]);
            }
        }

        // Priority 2: word overlap against step descriptions
        $stopWords  = ['de', 'het', 'een', 'en', 'van', 'in', 'op', 'aan', 'met', 'voor', 'naar', 'is', 'dat', 'je', 'dit', 'die', 'te', 'ze', 'ook'];
        $queryWords = array_diff(
            array_filter(explode(' ', preg_replace('/[^a-z\s]/', '', $transcript))),
            $stopWords
        );

        $bestStep  = null;
        $bestScore = 0;

        foreach ($recipe->steps as $step) {
            if (! $step->video_timestamp) {
                continue;
            }

            $descWords = array_diff(
                array_filter(explode(' ', preg_replace('/[^a-z\s]/', '', strtolower($step->description)))),
                $stopWords
            );

            $overlap = count(array_intersect($queryWords, $descWords));

            if ($overlap > $bestScore) {
                $bestScore = $overlap;
                $bestStep  = $step;
            }
        }

        if ($bestStep && $bestScore >= 1) {
            return response()->json([
                'timestamp'  => $bestStep->video_timestamp,
                'step'       => $bestStep->step_number,
                'transcript' => $transcript,
            ]);
        }

        return response()->json(['timestamp' => null, 'step' => null, 'transcript' => $transcript]);
    }
}
