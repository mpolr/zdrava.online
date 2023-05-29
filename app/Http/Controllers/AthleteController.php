<?php

namespace App\Http\Controllers;

use App\Models\Activities;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class AthleteController extends Controller
{
    public function training(): Factory|View
    {
        return view('athlete.training', [
            'activities' => \Auth::user()->activities()->limit(100)->get()
        ]);
    }
}
