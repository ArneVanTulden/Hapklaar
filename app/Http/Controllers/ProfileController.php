<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\RateLimiter;

class ProfileController extends Controller
{
    public function sendPasswordReset()
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        $key = 'password-reset-profiel:' . $user->id;

        if (RateLimiter::tooManyAttempts($key, 3)) {
            $minutes = ceil(RateLimiter::availableIn($key) / 60);
            return redirect()->route('profiel', ['tab' => 'instellingen'])->with('password_reset_error', "Te veel pogingen. Probeer het over {$minutes} minuten opnieuw.");
        }

        RateLimiter::hit($key, 3600);

        Password::sendResetLink(['email' => $user->email]);

        return redirect()->route('profiel', ['tab' => 'instellingen'])->with('password_reset_sent', true);
    }

    public function destroy()
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();
        auth()->logout();
        $user->delete();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect('/')->with('account_deleted', true);
    }

    public function show()
    {
        /** @var User $user */
        $user = auth()->user();
        $favorieten = $user->favoriteRecipes()->with('category')->latest('favorites.created_at')->get();
        $reviews = $user->reviews()->with(['recipe', 'photos'])->latest()->get();
        $cooked = $user->cookedRecipes()->with('category')->latest('cooked_at')->get();
        $cookedCount = $cooked->count();
        return view('profiel', compact('favorieten', 'reviews', 'cookedCount', 'cooked'));
    }
}
