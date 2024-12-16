<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PermissionsPolicyMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Добавляем заголовок Permissions-Policy
        $response->headers->set('Permissions-Policy', 'usb=(self)');

        return $response;
    }
}
