<?php

namespace SSM\Http\Controllers;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use SSM\Models\Gallery;
use SSM\Models\Category;

class GalleryController extends Controller
{
    public function index()
    {
        return Inertia::render('SSM/Gallery', [
            'items' => Gallery::with('categoryItem')->orderBy('created_at', 'desc')->get(),
            'categories' => Category::where('is_active', true)->get(),
        ]);
    }
}
