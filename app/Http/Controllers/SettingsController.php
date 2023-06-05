<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;

class SettingsController extends Controller
{
    public function profile(): \Illuminate\View\View|View
    {
        return view('settings.profile');
    }

    public function account(): \Illuminate\View\View|View
    {
        return view('settings.account');
    }

    public function privacy(): \Illuminate\View\View|View
    {
        return view('settings.privacy');
    }
}
