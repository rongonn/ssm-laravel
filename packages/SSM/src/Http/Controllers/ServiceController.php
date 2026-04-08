<?php

namespace SSM\Http\Controllers;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use SSM\Models\Service;

class ServiceController extends Controller
{
    public function index()
    {
        return Inertia::render('SSM/Services/Index', [
            'services' => Service::all(),
        ]);
    }

    public function show($id)
    {
        return Inertia::render('SSM/Services/Show', [
            'id' => $id,
            'service' => Service::find($id),
        ]);
    }
}
