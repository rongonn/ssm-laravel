<?php

namespace SSM\Http\Controllers;

use App\Http\Controllers\Controller;
use Inertia\Inertia;

class LegalController extends Controller
{
    public function privacy()
    {
        return Inertia::render('SSM/Privacy');
    }

    public function terms()
    {
        return Inertia::render('SSM/Terms');
    }
}
