<?php

namespace SSM\Http\Controllers;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use SSM\Models\Gallery;

class GalleryController extends Controller
{
    public function index()
    {
        return Inertia::render('SSM/Gallery', [
            'items' => Gallery::orderBy('created_at', 'desc')->get(),
        ]);
    }
}
