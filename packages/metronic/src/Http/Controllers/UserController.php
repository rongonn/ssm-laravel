<?php

namespace Isotope\Metronic\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Isotope\Metronic\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public static $permissions = [
        'welcome' => ['user_welcome', 'User Welcome'],
        'index'   => ['user_index', 'User Index'],
        'create'  => ['user_create', 'User Create'],
        'store'   => ['user_store', 'User Store'],
        'edit'    => ['user_edit', 'User Edit'],
        'update'  => ['user_update', 'User Update'],
        'show'    => ['user_show', 'User Show'],
        'destroy' => ['user_destroy', 'User Destroy'],
    ];

    public function welcome()
    {
        return view('isotope::authorization-home');
    }

    public function index(Request $request)
    {
        $search = $request->input('search');
        $users = User::with('role')
                    ->when($search, function ($query) use ($search) {
                        $query->where(function($q) use ($search) {
                            $q->where('name', 'like', "%{$search}%")
                              ->orWhere('email', 'like', "%{$search}%");
                        });
                    })
                    ->when(Auth::user()->role_id != 1, function ($query) {
                        $query->where('id', '!=', 1);
                    })
                    ->paginate(20)
                    ->withQueryString();
        return view('isotope::user.index', compact('users'));
    }

    public function create(Request $request)
    {
        $roles = Role::query()
                    ->when(Auth::user()->role_id != 1, function ($query) {
                        $query->where('id', '!=', 1);
                    })->get();
        return view('isotope::user.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $req = $request->all();

        User::create([
            "uuid"     => Str::uuid(),
            "name"     => $req['name'],
            "email"    => $req['email'],
            "role_id"  => $req['role'],
            "password" => Hash::make($req['password']),
        ]);

        return redirect()->route(tenant() ? 'authorization.users.index' : 'owner.authorization.users.index');
    }

    public function edit(Request $request, $id)
    {
        $user  = User::find($id);
        $roles = Role::query()
                    ->when(Auth::user()->role_id != 1, function ($query) use ($request) {
                        $query->where('id', '!=', 1);
                    })->get();
        return view('isotope::user.edit', compact('roles', 'user'));
    }

    public function update(Request $request, $id)
    {
        $req  = $request->all();
        $user = User::find($id);

        $user->name    = $req['name'];
        $user->email   = $req['email'];
        $user->role_id = $req['role'];
        if (strlen($req['password']) > 0) {
            $user->password = Hash::make($req['password']);
        }
        $user->save();

        return redirect()->route(tenant() ? 'authorization.users.index' : 'owner.authorization.users.index');
    }

    public function destroy($id)
    {
        $user = User::find($id);
        $user->delete();

        return redirect()->route(tenant() ? 'authorization.users.index' : 'owner.authorization.users.index');
    }
}
