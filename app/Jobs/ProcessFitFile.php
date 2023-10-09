<?php

namespace App\Jobs;

use adriangibbons\phpFITFileAnalysis;
use App\Classes\GpxTools;
use App\Models\Activities;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class ProcessFitFile implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected int $userId;
    protected string $fileName;

    public function __construct(int $userId, string $fileName)
    {
        $this->userId = $userId;
        $this->fileName = $fileName;
    }

    /**
     * @throws \Exception
     */
    public function handle(): void
    {
        try {
            $fit = new phpFITFileAnalysis(Storage::path('temp/' . $this->fileName));
        } catch (\Exception $e) {
            $this->fail($e->getMessage());
        }

        $activity = new Activities();
        $activity->user_id = $this->userId;
        $activity->sport = $fit->data_mesgs['sport']['sport'];
        $activity->sub_sport = $fit->data_mesgs['sport']['sub_sport'];
        $activity->name = !empty($fit->data_mesgs['sport']['name']) ? $fit->data_mesgs['sport']['name'] : __('Workout');
        $activity->creator = !empty($fit->data_mesgs['file_id']['manufacturer']) ? $fit->data_mesgs['file_id']['manufacturer'] : 'Zdrava';
        $activity->device_manufacturers_id = $fit->data_mesgs['file_id']['manufacturer'];
        $activity->distance = $fit->data_mesgs['session']['total_distance'];
        $activity->avg_speed = $fit->data_mesgs['session']['avg_speed'];
        $activity->max_speed = $fit->data_mesgs['session']['max_speed'];
        $activity->elevation_gain = $fit->data_mesgs['session']['total_ascent'];
        $activity->elevation_loss = $fit->data_mesgs['session']['total_descent'];
        $activity->started_at = $fit->data_mesgs['session']['start_time'];
        $activity->finished_at = $fit->data_mesgs['session']['start_time'] + $fit->data_mesgs['session']['total_elapsed_time'];
        $activity->duration = $fit->data_mesgs['session']['total_timer_time'];
        $activity->duration_total = $fit->data_mesgs['session']['total_elapsed_time'];
        $activity->avg_heart_rate = $fit->data_mesgs['session']['avg_heart_rate'];
        $activity->max_heart_rate = $fit->data_mesgs['session']['max_heart_rate'];
        if (!empty($fit->data_mesgs['session']['avg_cadence'])) {
            $activity->avg_cadence = $fit->data_mesgs['session']['avg_cadence'];
            $activity->max_cadence = $fit->data_mesgs['session']['max_cadence'];
        }
        $activity->total_calories = $fit->data_mesgs['session']['total_calories'];
        $activity->file = $this->fileName;
        $activity->start_position_lat = $fit->data_mesgs['session']['start_position_lat'];
        $activity->start_position_long = $fit->data_mesgs['session']['start_position_long'];
        if (is_float($fit->data_mesgs['lap']['end_position_lat'])) {
            $activity->end_position_lat = $fit->data_mesgs['lap']['end_position_lat'];
            $activity->end_position_long = $fit->data_mesgs['lap']['end_position_long'];
        } elseif (is_array($fit->data_mesgs['lap']['end_position_lat'])) {
            $activity->end_position_lat = last($fit->data_mesgs['lap']['end_position_lat']);
            $activity->end_position_long = last($fit->data_mesgs['lap']['end_position_long']);
        }
        $geo = GpxTools::geocode($activity->start_position_lat, $activity->start_position_long);
        $activity->country = $geo['country'];
        $activity->locality = $geo['locality'];

        GpxTools::convertFitToGpx($fit, $this->userId, $this->fileName);
        $image = GpxTools::generateImageFromGPX($this->fileName . '.gpx', $this->userId);
        if (!empty($image)) {
            $activity->image = $this->fileName . '.gpx.png';
        }

        $activity->save();
    }
}
