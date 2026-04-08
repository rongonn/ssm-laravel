<?php

namespace Isotope\Metronic\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\Rules\Password;

class PasswordController extends Controller
{
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validateWithBag('updatePassword', [
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('status', 'password-updated');
    }

    public function showChangePasswordForm()
    {
        $user = User::find(Auth::user()->id);
        return view('isotope::auth.change_password', compact('user'));
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|current_password:web',
            'password'         => 'required|confirmed|min:6',
        ]);
        $user = User::find(Auth::user()->id);
        $user->update([
            'password' => Hash::make($request->input('password'))
        ]);
        return redirect()->route(tenant() ? 'showChangePasswordForm' : 'owner.showChangePasswordForm')->with('success', 'Password Updated');
    }
}
