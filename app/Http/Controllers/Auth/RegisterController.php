<?php

namespace App\Http\Controllers\Auth;

use App\Mail\VerifyEmail;
use App\Models\User;
use App\Http\Controllers\Controller;
use App\Services\ReservedUserNames;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Log;
use Psr\Log\LogLevel;

class RegisterController extends Controller
{
    use AuthorizesRequests;
    use ValidatesRequests;
    use ReservedUserNames;

    protected string $redirectTo = '/';

    public function __construct()
    {
        $this->middleware('guest')->except([
            'logout', 'dashboard'
        ]);
    }

    protected function register(Request $request): JsonResponse
    {
        $preferredLocale = $request->getPreferredLanguage();
        if ($preferredLocale !== null) {
            App::setLocale($preferredLocale);
        }

        $validator = Validator::make($request->all(), [
            'first_name' => ['required', 'string' , 'max:250', Rule::notIn(self::rules())],
            'last_name' => ['required', 'string', 'max:250', Rule::notIn(self::rules())],
            'email' => 'required|email|max:250|unique:users',
            'password' => 'required|min:8|confirmed',
            'subscribe_news' => 'boolean',
            'deviceName' => 'string',
        ]);

        if ($validator->fails()) {
            return new JsonResponse([
                'success' => false,
                'message' => $validator->errors()->first(),
            ], 200);
        }

        $validated = $validator->validated();

        $user = User::create([
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'email' => $validated['email'],
            'subscribe_news' => $validated['subscribe_news'] ? 1 : 0,
            'password' => Hash::make($validated['password'])
        ]);

        if ($user) {
            $verify2 = DB::table('password_reset_tokens')->where([
                ['email', $request->all()['email']]
            ]);

            if ($verify2->exists()) {
                $verify2->delete();
            }

            $pin = Str::random(60);

            DB::table('password_reset_tokens')->insert([
                'email' => $request->all()['email'],
                'token' => $pin,
                'created_at' => Carbon::now(),
            ]);

            Mail::to($user->email)->send(new VerifyEmail($pin));

            $token = $user->createToken(config('app.name'))->plainTextToken;

            return new JsonResponse([
                'success' => true,
                'message' => 'Successful created user. Please check your email for a 6-digit pin to verify your email.',
                'token' => $token
            ], 201);
        }

        return new JsonResponse([
            'success' => false,
            'message' => 'Failed creating user.',
        ], 200);

//        $credentials = $request->only(['email', 'password']);
//        Auth::attempt($credentials);
//        $request->session()->regenerate();
//        return redirect()->route('site.dashboard')
//            ->with('success', __('You have successfully registered & logged in!'));
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
            'message' => "Error occurred"
        ], 500);
    }

    public function verifyEmail(?string $token): RedirectResponse
    {
        if ($token) {
            $verify =  DB::table('password_reset_tokens')->where([
                ['token', $token]
            ]);

            if ($verify->exists()) {
                $user = User::where(['email' => $verify->first()->email])->first();
                $user->markEmailAsVerified();
                Log::channel('telegram')->log(LogLevel::INFO, "New user registered: {$user->getFullName()} <{$user->email}>");
                Auth::login($user, true);
                return redirect()->route('site.dashboard');
            }
        }

        return redirect()->back();
    }
}
