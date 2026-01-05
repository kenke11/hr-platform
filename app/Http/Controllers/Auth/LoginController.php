<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\LogoutRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Show login page
     */
    public function show()
    {
        return view('auth.login');
    }

    /**
     * Handle login
     */
    public function login(LoginRequest $request)
    {
        if (!Auth::attempt($request->validated())) {
            return back()
                ->withErrors(['email' => 'Invalid credentials'])
                ->withInput();
        }

        $request->session()->regenerate();

        return redirect()->route('dashboard');
    }

    /**
     * Logout user
     */
    public function logout(LogoutRequest $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
