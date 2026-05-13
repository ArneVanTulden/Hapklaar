<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', fn() => view('home'))->name('home');
Route::get('/ontdekken', function () {
    $recipes = \App\Models\Recipe::with(['dietTags', 'creator'])->orderByDesc('avg_rating')->get();
    return view('ontdekken', compact('recipes'));
})->name('ontdekken');
Route::get('/mijn-keuken', fn() => view('mijn-keuken'))->name('mijn-keuken');
Route::get('/boodschappen', fn() => view('boodschappen'))->middleware('auth')->name('boodschappen');
Route::get('/mijn-voorraad', fn () => view('mijn-voorraad'))->middleware('auth')->name('mijn-voorraad');
Route::get('/profiel', fn() => view('profiel'))->middleware('auth')->name('profiel');

Route::middleware('guest')->group(function () {
    Route::get('/login', fn() => view('login'))->name('login');
    Route::get('/register', fn() => view('register'))->name('register');
    Route::get('/forgot-password', fn() => view('forgot-password'))->name('forgot-password');
    Route::get('/reset-password/{token}', fn(string $token) => view('reset-password', compact('token')))->name('password.reset');
});

Route::get('/recepten/{id}', fn(int $id) => view('recept', compact('id')))->name('recept');

Route::post('/logout', function () {
    Auth::logout();
    session()->invalidate();
    session()->regenerateToken();
    return redirect()->route('home');
})->middleware('auth')->name('logout');
