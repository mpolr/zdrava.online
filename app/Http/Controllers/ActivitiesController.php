<?php

namespace App\Http\Controllers;

use App\Models\Activities;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class ActivitiesController extends Controller
{
    public function get(int $id): Factory|View
    {
        $activity = Activities::where(['id' => $id])->first();

        return view('activities.view', ['activity' => $activity]);
    }
}
