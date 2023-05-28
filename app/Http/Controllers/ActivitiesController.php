<?php

namespace App\Http\Controllers;

use App\Models\Activities;
use App\Models\DeviceManufacturers;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class ActivitiesController extends Controller
{
    public function get(int $id): Factory|View
    {
        $activity = Activities::where(['id' => $id])->first();
        if (!empty($activity->creator) && is_numeric($activity->creator)) {
            $deviceManufacturer = DeviceManufacturers::where([
                'code' => $activity->creator
            ])->first()->description;
        } else {
            $deviceManufacturer = $activity->creator;
        }

        return view('activities.view', [
            'activity' => $activity,
            'manufacturer' => $deviceManufacturer,
        ]);
    }
}
