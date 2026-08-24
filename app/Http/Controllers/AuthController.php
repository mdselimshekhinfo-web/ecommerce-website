<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();

            if (Auth::user()->isAdmin()) {
                return redirect()->intended(route('admin.dashboard'));
            }

            return redirect()->intended(route('customer.dashboard'));
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => ['required', 'regex:/^(?:\+88|88)?(01[3-9]\d{8})$/'],
            'district' => 'required|string',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'district' => $request->district,
            'password' => Hash::make($request->password),
            'role' => 'customer',
            'is_admin' => false,
        ]);

        Auth::login($user);

        return redirect()->route('customer.dashboard')->with('success', 'Account created successfully! Welcome to NEXUS DOKAN.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'Logged out successfully.');
    }

    public function quickLogin($role)
    {
        if ($role === 'admin') {
            $user = User::where('email', 'admin@nexusdokan.bd')->first();
            if ($user) {
                Auth::login($user);
                return redirect()->route('admin.dashboard')->with('success', 'Logged in as Admin demo account!');
            }
        } elseif ($role === 'customer') {
            $user = User::where('email', 'customer@nexusdokan.bd')->first();
            if ($user) {
                Auth::login($user);
                return redirect()->route('customer.dashboard')->with('success', 'Logged in as Customer demo account!');
            }
        }

        return redirect()->route('login');
    }
}
