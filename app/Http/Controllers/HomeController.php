<?php

namespace App\Http\Controllers;

use App\Models\Recipe;

class HomeController extends Controller
{
    public function index()
    {
        $recepten = Recipe::orderBy('id')->limit(3)->get();
        $sushiRecept = Recipe::where('title', 'Sushi Bowl met Balls & Glory Challenge')->first();
        return view('home', compact('recepten', 'sushiRecept'));
    }
}
