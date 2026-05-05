<?php

use Illuminate\Support\Facades\Route;

Route::get('/', fn() => view('home'))->name('home');
Route::get('/ontdekken', fn() => view('ontdekken'))->name('ontdekken');
Route::get('/mijn-keuken', fn() => view('mijn-keuken'))->name('mijn-keuken');
Route::get('/boodschappen', fn() => view('boodschappen'))->name('boodschappen');
Route::get('/profiel', fn() => view('profiel'))->name('profiel');
Route::get('/login', fn() => view('login'))->name('login');
