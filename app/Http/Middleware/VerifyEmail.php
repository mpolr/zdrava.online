<?php

namespace App\Http\Middleware;

use App\Http\Controllers\Auth\RegisterController;
use App\Http\Livewire\Auth\Login;
use App\Models\User;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class VerifyEmail
{
    public function handle(Request $request, Closure $next): JsonResponse
    {
        $user = User::where('email', $request->all()['email'])->first();

        if ($user && Hash::check($request->all()['password'], $user->password)) {
            if (!$user->hasVerifiedEmail()) {
                Login::resendPin($request);

                return new JsonResponse([
                    'success' => false,
                    'message' => 'Please verify your email before you can continue'
                ], 401);
            }
        }

        return $next($request);
    }
}
