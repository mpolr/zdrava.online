<?php

namespace App\Services\Auth;

use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;

class GraphqlGuard implements Guard
{
    protected ?Authenticatable $user = null;
    protected Request $request;
    protected UserProvider $provider;

    public function __construct(UserProvider $provider, Request $request)
    {
        $this->provider = $provider;
        $this->request = $request;
    }

    public function user(): ?Authenticatable
    {
        if (!is_null($this->user)) {
            return $this->user;
        }

        $token = $this->request::bearerToken();
        if (!$token) {
            return null;
        }

        $query = DB::table('personal_access_tokens')
            ->where([
                'token' => $token,
                'tokenable_type' => User::class,
            ])
            ->first('tokenable_id');

        if ($query) {
            $this->user = $this->provider->retrieveById($query->tokenable_id);
            return $this->user;
        }

        return null;
    }

    public function check(): bool
    {
        return !is_null($this->user());
    }

    public function guest(): bool
    {
        return !$this->check();
    }

    public function id()
    {
        return $this->user() ? $this->user()->getAuthIdentifier() : null;
    }

    public function validate(array $credentials = []): bool
    {
        return false;
    }

    public function setUser($user): void
    {
        $this->user = $user;
    }

    public function hasUser(): void
    {
        // TODO: Implement hasUser() method.
    }
}
