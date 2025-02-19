<?php

namespace App\Http\Middleware;

use Auth;
use Closure;
use Illuminate\Http\Request;

class DynamicDebug
{
    public function handle(Request $request, Closure $next)
    {
        debugbar()->disable();

        $response = $next($request);

        if (Auth::check() && auth()->user()->hasRole('admin')) {
            config([
                'app.debug' => 'true',
            ]);
            debugbar()->enable();
            debugbar()->modifyResponse($request, $response);
        }

        return $response;
    }
}
