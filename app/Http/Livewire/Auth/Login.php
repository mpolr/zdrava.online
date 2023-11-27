<?php

namespace App\Http\Livewire\Auth;

use App\Mail\VerifyEmail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\Redirector;

class Login extends Component
{
    public string $email;
    public string $password;
    public bool $rememberMe = true;

    protected array $rules = [
        'email' => 'required|email|max:250',
        'password' => 'required|string|min:8',
        'rememberMe' => 'boolean',
    ];

    public function updated($propertyName): void
    {
        $this->validateOnly($propertyName, $this->rules);
    }

    public function login(): void
    {
        $this->validate($this->rules);

        $user = User::where([
            'email' => $this->email,
        ])->firstOrFail();

        if (!\Hash::check($this->password, $user->password)) {
            $this->addError('login.failed', 'Login failed');
        } else {
            if (!$user->hasVerifiedEmail()) {
                $this->resendPin();
            } else {
                if (\Auth::attempt([
                    'email' => $this->email,
                    'password' => $this->password
                ], $this->rememberMe)) {
                    $user = User::where('email', $this->email)->first();
                    $token = $user->createToken(config('app.name'))->plainTextToken;

                    $this->loginRedirect();
                }
            }
        }
    }

    private function loginRedirect(): RedirectResponse|Redirector
    {
        return redirect()->route('site.dashboard');
    }

    public function resendPin(): void
    {
        $this->validate([
            'email' => 'required|string|email|max:250',
        ]);

        $verify =  DB::table('password_reset_tokens')->where([
            ['email', $this->email]
        ]);

        if ($verify->exists()) {
            $verify->delete();
        }

        $pin = Str::random(60);

        $password_reset = DB::table('password_reset_tokens')->insert([
            'email' => $this->email,
            'token' =>  $pin,
            'created_at' => Carbon::now(),
        ]);

        if ($password_reset) {
            Mail::to($this->email)->send(new VerifyEmail($pin));

            session()->flash('success', 'A verification mail has been resent');
        }
    }
}
