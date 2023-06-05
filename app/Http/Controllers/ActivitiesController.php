<?php

namespace App\Http\Controllers;

use App\Models\Activities;
use Illuminate\Contracts\View\View;

class ActivitiesController extends Controller
{
    public function get(int $id): \Illuminate\View\View|View
    {
        $activity = Activities::where(['id' => $id])->first();
        if (empty($activity)) {
            abort(404);
        }

        return view('activities.view', [
            'activity' => $activity
        ]);
    }
}
