<?php

namespace SSM\Http\Controllers;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use SSM\Models\Product;

class ProductController extends Controller
{
    public function index()
    {
        return Inertia::render('SSM/Products/Index', [
            'products' => Product::where('is_active', true)->get(),
        ]);
    }

    public function show($id)
    {
        $product = Product::findOrFail($id);
        
        return Inertia::render('SSM/Products/Show', [
            'id' => $id,
            'product' => $product,
            'relatedProducts' => Product::where('category', $product->category)
                ->where('is_active', true)
                ->where('id', '!=', $id)
                ->limit(6)
                ->get(),
        ]);
    }
}
