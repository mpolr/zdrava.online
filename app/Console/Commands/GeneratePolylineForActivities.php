<?php

namespace App\Console\Commands;

use adriangibbons\phpFITFileAnalysis;
use App\Classes\GpxTools;
use App\Classes\Polyline;
use App\Models\Activities;
use Illuminate\Console\Command;

class GeneratePolylineForActivities extends Command
{
    protected $signature = 'app:fit:generate-polyline';

    protected $description = 'Generate polylines for activities without it';

    /**
     * @throws \Exception
     */
    public function handle(): void
    {
        $activities = Activities::where('polyline', null)->get();

        foreach ($activities as $activity) {
            if ($activity->file && str_ends_with($activity->file, '.fit')) {
                $fullFilePath = \Storage::path('public/activities/' . $activity->user_id . '/' . $activity->file);

                try {
                    $fit = new phpFITFileAnalysis($fullFilePath);
                    $polyline = Polyline::convertFitLocationToPolyline($fit->data_mesgs['record']);
                } catch (\Exception $e) {
                    throw new \Exception("Activity ID: $activity->id - {$e->getMessage()}");
                }

                if (empty($polyline)) {
                    return;
                }

                $activity->polyline = $polyline;

                if (!isset($activity->country, $activity->locality)) {
                    $geo = GpxTools::geocode($activity->start_position_lat, $activity->start_position_long);
                    if ($geo) {
                        $activity->country = $geo['country'];
                        $activity->locality = $geo['locality'];
                    }
                }

                $activity->save();
            }
        }
    }
}
