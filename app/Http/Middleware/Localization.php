<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class Localization
{
    public function handle(Request $request, Closure $next): Response
    {
        $languageCode = $request->get('hl');

        if ($languageCode && !Session::has('locale')) {
            if (in_array($languageCode, config('app.available_locales'), true)) {
                Carbon::setLocale($languageCode);
                App::setLocale($languageCode);
            }
        } elseif (Session::has('locale')) {
            $locale = Session::get('locale');
            Carbon::setLocale($locale);
            App::setLocale($locale);
        } elseif ($request->getPreferredLanguage() !== null && $request->isJson()) {
            App::setLocale($request->getPreferredLanguage());
        }
        return $next($request);
    }
}
