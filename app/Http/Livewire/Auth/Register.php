<?php

namespace App\Http\Livewire\Auth;

use App\Mail\VerifyEmail;
use App\Models\User;
use App\Services\ReservedUserNames;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Livewire\Component;

class Register extends Component
{
    use AuthorizesRequests;
    use ReservedUserNames;

    public string $firstName;
    public string $lastName;
    public string $email;
    public string $password;
    public string $password_confirmation;
    public bool $subscribeNews = false;

    protected array $rules = [
        'firstName' => 'required|string|min:2|max:64|not_in:ReservedUserNames::rules()',
        'lastName' => 'required|string|min:2|max:64|not_in:ReservedUserNames::rules()',
        'email' => 'required|email|max:250|unique:users',
        'password' => 'required|min:8|confirmed',
        'subscribeNews' => 'boolean',
    ];

    public function updated($propertyName): void
    {
        $this->validateOnly($propertyName, $this->rules);
    }

    public function register(): void
    {
        $this->validate($this->rules);

        $user = User::create([
            'first_name' => $this->firstName,
            'last_name' => $this->lastName,
            'email' => $this->email,
            'subscribe_news' => $this->subscribeNews ? 1 : 0,
            'password' => Hash::make($this->password)
        ]);

        if ($user) {
            $verify2 = DB::table('password_reset_tokens')->where([
                ['email', $this->email]
            ]);

            if ($verify2->exists()) {
                $verify2->delete();
            }

            $pin = Str::random(60);

            DB::table('password_reset_tokens')->insert([
                'email' => $this->email,
                'token' => $pin,
                'created_at' => Carbon::now(),
            ]);

            Mail::to($user->email)->send(new VerifyEmail($pin));
            $user->createToken(config('app.name'))->plainTextToken;

            session()->flash('success', 'Successful created user. Please check your email for a link to verify your email.');
            $this->firstName = '';
            $this->lastName = '';
            $this->email = '';
            $this->password = '';
            $this->password_confirmation = '';
        } else {
            session()->flash('error', 'Failed creating user.');
        }
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
                return redirect()->route('site.dashboard');
            }
        }
    }
}
