<?php

namespace SSM\Http\Controllers;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use SSM\Models\Service;
use SSM\Models\Product;
use SSM\Models\Team;
use SSM\Models\Testimonial;
use SSM\Models\Gallery;
use SSM\Models\Category;

class HomeController extends Controller
{
    public function index()
    {
        return Inertia::render('SSM/Welcome', [
            'services' => Service::with('categoryItem')->orderBy('created_at', 'desc')->take(10)->get(),
            'products' => Product::with('categoryItem')
                ->withCount(['reviews' => function($q) { $q->where('is_approved', true); }])
                ->withAvg(['reviews' => function($q) { $q->where('is_approved', true); }], 'rating')
                ->where('is_active', true)
                ->orderBy('created_at', 'desc')
                ->take(10)->get(),
            'team' => Team::orderBy('name')->take(4)->get(),
            'testimonials' => Testimonial::take(10)->get(),
            'gallery' => Gallery::with('categoryItem')->orderBy('created_at', 'desc')->take(6)->get(),
            'categories' => Category::withCount('products')->where('is_active', true)->get(),
        ]);
    }
}
