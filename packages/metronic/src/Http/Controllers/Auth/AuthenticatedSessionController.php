<?php

namespace Isotope\Metronic\Http\Controllers\Auth;

use Exception;
use App\Models\User;
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Isotope\Metronic\Http\Requests\LoginRequest;

class AuthenticatedSessionController extends Controller
{

    public function profile(): View
    {
        $user = User::find(Auth::user()->id);
        return view('isotope::auth.profile', compact('user'));
    }

    public function profileEdit(): View
    {
        $user = User::find(Auth::user()->id);
        return view('isotope::auth.profile-edit', compact('user'));
    }

    public function profileUpdate(Request $request)
    {
        try {
            $user = User::find(Auth::user()->id);
            $user->update([
                'name'  => $request->input('name'),
                'email' => $request->input('email'),
            ]);
            if ($request->has('avatar')) {
                $user->deleteFile($user->avatarImage);
                $user->saveFile($request->file('avatar'), 'avatar');
            }
            return redirect()->route('profile')->with('success', 'Profile Updated');
        } catch (Exception $e) {
            return redirect()->back()->withErrors($e->getMessage());
        }
    }

    public function create(): View
    {
        return view('isotope::auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        $user = auth()->user();
        
        // Admin Redirection
        if ($user->isSuperAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->intended('/admin');
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/login');
    }

    public function changeLocale($locale = 'en')
    {
        session(['locale' => $locale]);
        $user = Auth::user();
        if ($user && $user->locale) {
            $user->update(['locale' => $locale]);
        }
        return redirect()->back();
    }
}
