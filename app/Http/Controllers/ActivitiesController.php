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
        if (!empty($activity->device_manufacturers_id) && is_numeric($activity->device_manufacturers_id)) {
            $deviceManufacturer = DeviceManufacturers::where([
                'code' => $activity->device_manufacturers_id
            ])->first()->description;
        } else {
            $deviceManufacturer = null;
        }

        $startLat = $activity->start_position_lat;
        $startLong = $activity->start_position_long;
        $endLat = $activity->end_position_lat;
        $endLong = $activity->end_position_long;
        // Процент промежуточной точки (0.0 - начальная точка, 1.0 - конечная точка)
        $percentage = 0.5;

        $mapCenter = [
            'lat' => $startLat + ($endLat - $startLat) * $percentage,
            'long' => $startLong + ($endLong - $startLong) * $percentage
        ];

        return view('activities.view', [
            'activity' => $activity,
            'manufacturer' => $deviceManufacturer,
            'mapCenter' => $mapCenter,
        ]);
    }
}
