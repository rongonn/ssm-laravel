<?php

namespace SSM\Http\Controllers;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use SSM\Models\Product;
use SSM\Models\Category;
use SSM\Models\Order;
use SSM\Models\ProductReview;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        return Inertia::render('SSM/Products/Index', [
            'products' => Product::with('categoryItem')
                ->withCount(['reviews' => function($q) { $q->where('is_approved', true); }])
                ->withAvg(['reviews' => function($q) { $q->where('is_approved', true); }], 'rating')
                ->where('is_active', true)->get(),
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
            'reviews' => ProductReview::where('product_id', $id)->where('is_approved', true)->latest()->get(),
            'relatedProducts' => $relatedQuery->limit(6)->get(),
        ]);
    }

    public function storeReview(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'mobile' => 'nullable|string|max:20',
            'age' => 'nullable|integer|min:1',
            'rating' => 'required|integer|min:1|max:5',
            'review_text' => 'nullable|string',
        ]);

        ProductReview::create([
            'product_id' => $id,
            'name' => $request->name,
            'mobile' => $request->mobile,
            'age' => $request->age,
            'rating' => $request->rating,
            'review_text' => $request->review_text,
            'is_approved' => false,
        ]);

        return back()->with('success', 'Review submitted successfully!');
    }

    public function checkout()
    {
        return Inertia::render('SSM/Checkout/Index');
    }

    public function purchase(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'customer_address' => 'required|string',
            'items' => 'required|array|min:1',
            'items.*.id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        $subtotal = 0;
        $orderItems = [];

        foreach ($request->items as $item) {
            $product = Product::findOrFail($item['id']);
            $price = ((float) $product->offer_price > 0) ? (float) $product->offer_price : (float) $product->price;
            $quantity = (int) $item['quantity'];
            $subtotal += $price * $quantity;
            
            $orderItems[] = [
                'product_id' => $product->id,
                'quantity' => $quantity,
                'price' => $price,
            ];
        }

        // For now, delivery charge and discount are 0 from customer side
        // Admin will update them later as per requirement
        $delivery_charge = 0;
        $discount = 0;
        $total_price = $subtotal + $delivery_charge - $discount;

        \DB::transaction(function () use ($request, $orderItems, $subtotal, $total_price) {
            $order = Order::create([
                'customer_name' => $request->customer_name,
                'customer_phone' => $request->customer_phone,
                'customer_address' => $request->customer_address,
                'subtotal' => $subtotal,
                'delivery_charge' => 0,
                'discount' => 0,
                'total_price' => $total_price,
                'status' => 'New Order',
                'source' => 'Website'
            ]);

            foreach ($orderItems as $itemData) {
                $order->items()->create($itemData);
            }
        });

        return back()->with('success', 'Order placed successfully! We will contact you soon.');
    }
}
