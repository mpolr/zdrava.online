<?php

namespace App\Http\Controllers;

use App\Models\Activities;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

class DashboardController extends Controller
{
    public function index(): Factory|View
    {
        $activities = Activities::get()->split(25);

        return view('dashboard', ['activities' => $activities]);
    }
}
