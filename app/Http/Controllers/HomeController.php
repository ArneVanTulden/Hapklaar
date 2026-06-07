<?php

namespace App\Http\Controllers;

use App\Models\Recipe;

class HomeController extends Controller
{
    public function index()
    {
        $recepten = Recipe::orderBy('id')->limit(3)->get();
        $sushiRecept = Recipe::find(6);
        return view('home', compact('recepten', 'sushiRecept'));
    }
}
