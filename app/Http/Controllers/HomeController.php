<?php

namespace App\Http\Controllers;

use App\Models\Product;

class HomeController extends Controller
{
    public function index()
    {
        // Latest products for the home page (you can change the number)
        $products = Product::latest()->take(8)->get();

        return view('home', compact('products'));
    }
}
