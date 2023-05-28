<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function profile(): Factory|View
    {
        return view('settings.profile');
    }

    public function account(): Factory|View
    {
        return view('settings.account');
    }

    public function privacy(): Factory|View
    {
        return view('settings.privacy');
    }

    public function setLocale(Request $request): \Illuminate\Http\RedirectResponse
    {
        $locale = $request->get('locale');

        if (!in_array($locale, config('app.available_locales'))) {
            abort(400);
        }

        app()->setLocale($locale);
        Carbon::setLocale($locale);
        session()->put('locale', $locale);
        return redirect()->back();
    }
}
