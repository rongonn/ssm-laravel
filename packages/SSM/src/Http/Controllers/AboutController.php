<?php

namespace SSM\Http\Controllers;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use SSM\Models\Team;

class AboutController extends Controller
{
    public function index()
    {
        return Inertia::render('SSM/About', [
            'team' => Team::all(),
        ]);
    }

    public function teamMember($id)
    {
        return Inertia::render('SSM/About/TeamMember', [
            'id' => $id,
            'member' => Team::find($id),
        ]);
    }
}
