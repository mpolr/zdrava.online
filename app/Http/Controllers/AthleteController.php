<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;

class AthleteController extends Controller
{
    public function training(): \Illuminate\View\View|View
    {
        return view('athlete.training', [
            'activities' => Auth::user()->activities->limit(100)->get()
        ]);
    }
}
