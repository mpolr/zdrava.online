<?php

namespace App\Http\Controllers\Auth;

use App\Mail\UserRegistrationConfirmation;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    use AuthorizesRequests, ValidatesRequests;

    protected string $redirectTo = '/';

    public function __construct()
    {
        $this->middleware('guest')->except([
            'logout', 'dashboard'
        ]);
    }

    protected function register(Request $request): RedirectResponse
    {
        $request->validate([
            'first_name' => 'required|string|max:250',
            'last_name' => 'required|string|max:250',
            'email' => 'required|email|max:250|unique:users',
            'password' => 'required|min:8|confirmed',
            'subscribe_news' => 'boolean',
        ]);

        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'subscribe_news' => $request->subscribe_news ? 1 : 0,
            'password' => Hash::make($request->password)
        ]);

        //Mail::to($user->email)->send(new UserRegistrationConfirmation($user));

        // TODO: Не пускать без подтверждения почты

        $credentials = $request->only(['email', 'password']);
        Auth::attempt($credentials);
        $request->session()->regenerate();
        return redirect()->route('site.dashboard')
            ->withSuccess('You have successfully registered & logged in!');
    }
}
