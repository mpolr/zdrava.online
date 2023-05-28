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
        $activities = Activities::where([
            'users_id' => \Auth::user()->id,
        ])->limit(100)->get();

        return view('athlete.training', ['activities' => $activities]);
    }
}
