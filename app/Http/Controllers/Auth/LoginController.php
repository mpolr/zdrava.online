<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\VerifyEmail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class LoginController extends Controller
{
    public function logout(Request $request): RedirectResponse
    {
        \Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('index');
    }

    public function authenticateApi(Request $request): JsonResponse
    {
        $user = null;

        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email'],
            'password' => ['required'],
            'deviceName' => 'required',
        ]);

        if ($validator->fails()) {
            return new JsonResponse([
                'success' => false,
                'message' => $validator->errors()
            ], 422);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => __('Your provided credentials do not match in our records')
            ]);
        }

        if ($user->hasVerifiedEmail() === false) {
            return response()->json([
                'success' => false,
                'message' => __('Please verify your email address')
            ]);
        }

        $token = $user->createToken($request->deviceName)->plainTextToken;
        return response()->json([
            'success' => true,
            'message' => __('Logged in successfully'),
            'token' => $token,
            'user_id' => $user->id,
        ]);
    }

    public static function resendPin(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'string', 'email', 'max:255'],
        ]);

        if ($validator->fails()) {
            return new JsonResponse([
                'success' => false,
                'message' => $validator->errors()
            ], 422);
        }

        $verify =  DB::table('password_reset_tokens')->where([
            ['email', $request->all()['email']]
        ]);

        if ($verify->exists()) {
            $verify->delete();
        }

        $pin = Str::random(60);

        $password_reset = DB::table('password_reset_tokens')->insert([
            'email' => $request->all()['email'],
            'token' =>  $pin,
            'created_at' => Carbon::now(),
        ]);

        if ($password_reset) {
            Mail::to($request->all()['email'])->send(new VerifyEmail($pin));

            return new JsonResponse([
                'success' => true,
                'message' => "A verification mail has been resent"
            ], 200);
        }

        return new JsonResponse([
            'success' => false,
            'message' => 'Error occurred'
        ], 422);
    }
}
