<?php

namespace App\Http\Controllers;

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
}
