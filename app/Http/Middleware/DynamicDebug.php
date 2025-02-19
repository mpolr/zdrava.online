<?php

namespace App\Http\Middleware;

use Auth;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

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
