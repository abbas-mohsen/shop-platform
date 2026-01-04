<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category');

        // Read the search term from ?q=...
        $search = trim($request->get('q', ''));

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%');
            });
        }

        // You can add more filters here later (by category, etc.)

        $products = $query
            ->orderBy('created_at', 'desc')
            ->paginate(12)
            ->withQueryString(); // keep ?q= in pagination links

        return view('products.index', compact('products'));
    }

    public function show(Product $product)
    {
        $product->load('category');

        return view('products.show', compact('product'));
    }
}
