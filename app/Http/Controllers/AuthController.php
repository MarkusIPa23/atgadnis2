<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // REGISTER PAGE
    public function showRegister()
    {
        return view('auth.register');
    }

    // REGISTER
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        Auth::login($user);

        return redirect('/');
    }

    // LOGIN PAGE
    public function showLogin()
    {
        return view('auth.login');
    }

    // LOGIN
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            return redirect('/');
        }

        return back()->withErrors([
            'email' => 'Nepareizi dati'
        ]);
    }

    // LOGOUT
    public function logout()
    {
        Auth::logout();
        return redirect('/');
    }
}