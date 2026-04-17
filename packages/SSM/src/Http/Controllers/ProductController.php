<?php

namespace SSM\Http\Controllers;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use SSM\Models\Product;
use SSM\Models\Category;
use SSM\Models\Order;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        return Inertia::render('SSM/Products/Index', [
            'products' => Product::with('categoryItem')->where('is_active', true)->get(),
            'categories' => Category::where('is_active', true)->get(),
        ]);
    }

    public function show($id)
    {
        $product = Product::with('categoryItem')->findOrFail($id);
        
        $relatedQuery = Product::with('categoryItem')
            ->where('is_active', true)
            ->where('id', '!=', $id);

        if ($product->category_id) {
            $relatedQuery->where('category_id', $product->category_id);
        } else {
            $relatedQuery->where('category', $product->category);
        }

        return Inertia::render('SSM/Products/Show', [
            'id' => $id,
            'product' => $product,
            'relatedProducts' => $relatedQuery->limit(6)->get(),
        ]);
    }

    public function purchase(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'customer_address' => 'required|string',
        ]);

        Order::create([
            'product_id' => $id,
            'customer_name' => $request->customer_name,
            'customer_phone' => $request->customer_phone,
            'customer_address' => $request->customer_address,
            'total_price' => $product->price,
            'status' => 'New Order'
        ]);

        return back()->with('success', 'Order placed successfully! We will contact you soon.');
    }
}
