<?php

namespace SSM\Http\Controllers;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use SSM\Models\Service;
use SSM\Models\Product;
use SSM\Models\Team;
use SSM\Models\Testimonial;
use SSM\Models\Gallery;

class HomeController extends Controller
{
    public function index()
    {
        return Inertia::render('SSM/Welcome', [
            'services' => Service::with('categoryItem')->orderBy('created_at', 'desc')->take(10)->get(),
            'products' => Product::with('categoryItem')->where('is_active', true)->orderBy('created_at', 'desc')->take(10)->get(),
            'team' => Team::orderBy('name')->take(4)->get(),
            'testimonials' => Testimonial::take(10)->get(),
            'gallery' => Gallery::with('categoryItem')->orderBy('created_at', 'desc')->take(6)->get(),
        ]);
    }
}
