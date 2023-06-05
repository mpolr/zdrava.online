<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;

class DashboardController extends Controller
{
    public function index(): \Illuminate\View\View|View
    {
        return view('dashboard', [
            'activities' => \Auth::user()->activities()
        ]);
    }
}
