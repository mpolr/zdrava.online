<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class LoginController extends Controller
{
    public function authenticate(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $remember = $request->boolean('remember_me');

        if (\Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            return redirect()->intended(route('site.dashboard'));
        }

        return back()->withErrors([
            'email' => 'Your provided credentials do not match in our records.',
        ])->onlyInput('email');
    }

    public function logout(Request $request): RedirectResponse
    {
        \Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('index');
    }

    public function authenticateApi(Request $request): JsonResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (\Auth::attempt($credentials)) {
            return response()->json([
                'success' => true,
                'message' => 'Login success',
                'token' => \Auth::user()->remember_token
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'User does not exist'
        ]);
    }
}
