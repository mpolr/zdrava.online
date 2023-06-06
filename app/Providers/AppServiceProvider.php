<?php

namespace App\Providers;

use Event;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Event::listen(
            \Illuminate\Auth\Events\Authenticated::class,
            function () {
                if (\Auth::id() != 1) {
                    \Debugbar::disable();
                }
            }
        );
        URL::forceScheme('https');
    }
}
