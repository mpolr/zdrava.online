<?php

namespace App\Http\Controllers;

use App\Models\Activities;
use Illuminate\Contracts\View\View;

class ActivitiesController extends Controller
{
    public function get(int $id): \Illuminate\View\View|View
    {
        $activity = Activities::findOrFail($id);

        return view('activities.view', [
            'activity' => $activity
        ]);
    }
}
