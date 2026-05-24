<?php

namespace App\Http\Controllers\Saas\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return $this->redirectByRole();
        }
        return view('saas.auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');
        $remember = $request->boolean('remember');

        if (!Auth::attempt($credentials, $remember)) {
            return back()->withInput()->with('error', 'Invalid credentials. Please try again.');
        }

        $request->session()->regenerate();
        return $this->redirectByRole();
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }

    protected function redirectByRole()
    {
        $user = Auth::user();
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        $tenant = $user->tenant;
        if ($tenant && !$tenant->canManageContent()) {
            if (!$tenant->hasVerifiedPayment()) {
                return redirect()->route('client.subscription.index');
            }
            return redirect()->route('client.expired');
        }

        return redirect()->route('client.dashboard');
    }
}
