<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $recepten = \App\Models\Recipe::orderBy('id')->limit(3)->get();
    return view('home', compact('recepten'));
})->name('home');
Route::get('/ontdekken', function (Illuminate\Http\Request $request) {
    $query = \App\Models\Recipe::with(['dietTags', 'creator']);

    foreach ($request->input('diets', []) as $diet) {
        $query->whereHas('dietTags', fn($q) => $q->where('name', $diet));
    }

    $maxCal = $request->integer('max_calories', 1000);
    if ($maxCal < 1000) {
        $query->where('calories_per_portion', '<=', $maxCal);
    }

    if ($request->filled('max_afwas')) {
        $query->where('afwas_score', '<=', $request->integer('max_afwas'));
    }

    $recipes = $query->orderByDesc('avg_rating')->get();
    return view('ontdekken', compact('recipes'));
})->name('ontdekken');
Route::get('/mijn-keuken', fn() => view('mijn-keuken'))->name('mijn-keuken');
Route::get('/boodschappen', fn() => view('boodschappen'))->middleware('auth')->name('boodschappen');
Route::get('/mijn-voorraad', fn () => view('mijn-voorraad'))->middleware('auth')->name('mijn-voorraad');
Route::get('/profiel', function () {
    $user = auth()->user();
    $favorieten = $user->favoriteRecipes()->with('category')->latest('favorites.created_at')->get();
    return view('profiel', compact('favorieten'));
})->middleware('auth')->name('profiel');

Route::middleware('guest')->group(function () {
    Route::get('/login', fn() => view('login'))->name('login');
    Route::get('/register', fn() => view('register'))->name('register');
    Route::get('/forgot-password', fn() => view('forgot-password'))->name('forgot-password');
    Route::get('/reset-password/{token}', fn(string $token) => view('reset-password', compact('token')))->name('password.reset');
});

Route::get('/recepten/{id}', function (int $id) {
    $recipe = \App\Models\Recipe::with(['ingredients', 'nutritionInfo'])->findOrFail($id);
    return view('recept', compact('recipe'));
})->name('recept');

Route::post('/logout', function () {
    Auth::logout();
    session()->invalidate();
    session()->regenerateToken();
    return redirect()->route('home');
})->middleware('auth')->name('logout');
