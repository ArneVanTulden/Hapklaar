<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RecipeController;
use App\Http\Controllers\VoiceController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/ontdekken', [RecipeController::class, 'discover'])->name('ontdekken');

Route::view('/mijn-keuken', 'mijn-keuken')->name('mijn-keuken');
Route::view('/boodschappen', 'boodschappen')->middleware('auth')->name('boodschappen');
Route::view('/mijn-voorraad', 'mijn-voorraad')->middleware('auth')->name('mijn-voorraad');

Route::get('/profiel', [ProfileController::class, 'show'])->middleware('auth')->name('profiel');

Route::middleware('guest')->group(function () {
    Route::view('/login', 'login')->name('login');
    Route::view('/register', 'register')->name('register');
    Route::view('/forgot-password', 'forgot-password')->name('forgot-password');
    Route::get('/reset-password/{token}', fn(string $token) => view('reset-password', compact('token')))->name('password.reset');
});

Route::get('/recepten/random', [RecipeController::class, 'random'])->name('recept.random');
Route::get('/recepten/{id}', [RecipeController::class, 'show'])->name('recept');
Route::post('/recepten/{id}/boodschappen', [RecipeController::class, 'addToShoppingList'])->middleware('auth')->name('recept.boodschappen');
Route::post('/recepten/{id}/gemaakt', [RecipeController::class, 'markAsCooked'])->middleware('auth')->name('recept.gemaakt');
Route::post('/recepten/{id}/verwijder-uit-voorraad', [RecipeController::class, 'removeFromInventory'])->middleware('auth')->name('recept.verwijder-uit-voorraad');
Route::post('/recepten/{id}/voice', [VoiceController::class, 'match'])->middleware('throttle:20,1')->name('recept.voice');

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');
