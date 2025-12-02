<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;


class AuthController extends Controller
{
    // Show register page
    public function register()
    {
        if (Auth::check()) {
            return redirect()->route('home');
        }

        return view('registerPage');
    }

    // Handle register
    public function register_store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|confirmed|min:6'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'user' // default
        ]);

        Auth::login($user);
        return redirect()->route('home');
    }

    // Show login page
    public function login()
    {
        if (Auth::check()) {
            return redirect()->route('home');
        }

        return view('loginPage');
    }

    // Process login
    public function login_process(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required'
        ]);

        if (Auth::attempt($request->only('email', 'password'))) {
            $request->session()->regenerate();

            return redirect()->route('home');
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ]);
    }

    // Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    public function profile()
    {
        $user = Auth::user();
        return view('profilePage', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        $user->update([
            'name' => $request->name
        ]);

        return redirect()->back()->with('success', 'Profile updated successfully!');
    }
}
