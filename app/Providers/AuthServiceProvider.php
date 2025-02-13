<?php

namespace App\Providers;

use App\Contracts\Likeable;
use App\Models\User;
use App\Services\Auth\GraphqlGuard;
use Auth;
use Illuminate\Auth\Access\Response;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Request;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        // your policies...
    ];

    public function boot(): void
    {
        $this->registerPolicies();

        Auth::extend('graphql_guard', static function ($app, $name, array $config) {
            return new GraphqlGuard(
                Auth::createUserProvider($config['provider']),
                $app->make(Request::class)
            );
        });

        // $user->can('like', $post)
        Gate::define('like', static function (User $user, Likeable $likeable) {
            if (! $likeable->exists) {
                return Response::deny("Cannot like an object that doesn't exists");
            }

            if ($user->hasLiked($likeable)) {
                return Response::deny("Cannot like the same thing twice");
            }

            return Response::allow();
        });

        // $user->can('unlike', $post)
        Gate::define('unlike', static function (User $user, Likeable $likeable) {
            if (! $likeable->exists) {
                return Response::deny("Cannot unlike an object that doesn't exists");
            }

            if (! $user->hasLiked($likeable)) {
                return Response::deny("Cannot unlike without liking first");
            }

            return Response::allow();
        });
    }
}
