<?php

namespace App\Http\Controllers;

use App\Models\Recipe;

class HomeController extends Controller
{
    public function index()
    {
        $recepten = Recipe::orderBy('id')->limit(3)->get();
        return view('home', compact('recepten'));
    }
}
