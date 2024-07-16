<?php

namespace App\Jobs;

use adriangibbons\phpFITFileAnalysis;
use App\Classes\GpxTools;
use App\Models\Activities;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Psr\Log\LogLevel;

class ProcessFitFile implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    protected int $userId;
    protected string $fileName;
    public int $timeout = 300;

    public function __construct(int $userId, string $fileName)
    {
        $this->userId = $userId;
        $this->fileName = $fileName;
    }

    /**
     * @throws Exception
     */
    public function handle(): void
    {
        try {
            $fit = new phpFITFileAnalysis(Storage::path('temp/' . $this->fileName), ['fix_data']);

//            $fit2 = new \Fit\Reader(Storage::path('temp/' . $this->fileName));
//            $fit2->parseFile();
//            $a = $fit2->parseFile();

            $activity = new Activities();
            $activity->user_id = $this->userId;
            if (array_key_exists('sport', $fit->data_mesgs)) {
                $activity->sport = $fit->data_mesgs['sport']['sport'];
                $activity->sub_sport = $fit->data_mesgs['sport']['sub_sport'];
            }
            $activity->name = !empty($fit->data_mesgs['sport']['name']) ? __($fit->data_mesgs['sport']['name']) : __('Workout');
            if ($fit->data_mesgs['device_info']['manufacturer'] === 255 && !empty($fit->data_mesgs['developer_data_id']) && !empty($fit->data_mesgs['developer_data_id']['application_id'])) {
                $activity->creator = $this->byteArrayToString($fit->data_mesgs['developer_data_id']['application_id']);
            } else {
                $activity->device_manufacturers_id = $fit->data_mesgs['file_id']['manufacturer'] ?: null;
                if (array_key_exists('product', $fit->data_mesgs['file_id'])) {
                    $activity->device_models_id = $fit->data_mesgs['file_id']['product'];
                }
                if (isset($fit->data_mesgs['file_creator']['software_version'])) {
                    $activity->device_software_version = $fit->data_mesgs['file_creator']['software_version'] * 0.01;
                }
            }

            if (isset($fit->data_mesgs['session']['total_distance'])) {
                $activity->distance = $fit->data_mesgs['session']['total_distance'];
            } else {
                if (isset($fit->data_mesgs['lap']['total_distance'])) {
                    $activity->distance = isset($fit->data_mesgs['lap']['total_distance']);
                }
            }
            if (isset($fit->data_mesgs['session']['avg_speed'])) {
                $activity->avg_speed = $fit->data_mesgs['session']['avg_speed'];
            } elseif (isset($fit->data_mesgs['session']['enhanced_avg_speed'])) {
                $activity->avg_speed = $fit->data_mesgs['session']['enhanced_avg_speed'];
            }
            if (isset($fit->data_mesgs['session']['max_speed'])) {
                $activity->max_speed = $fit->data_mesgs['session']['max_speed'];
            } elseif (isset($fit->data_mesgs['session']['enhanced_max_speed'])) {
                $activity->max_speed = $fit->data_mesgs['session']['enhanced_max_speed'];
            }
            if (isset($fit->data_mesgs['session']['total_ascent'])) {
                $activity->elevation_gain = $fit->data_mesgs['session']['total_ascent'];
                $activity->elevation_loss = $fit->data_mesgs['session']['total_descent'];
            }
            if (isset($fit->data_mesgs['session']['start_time'])) {
                $activity->started_at = $fit->data_mesgs['session']['start_time'];
                $activity->finished_at = $fit->data_mesgs['session']['start_time'] + $fit->data_mesgs['session']['total_elapsed_time'];
            } elseif (isset($fit->data_mesgs['session']['timestamp'])) {
                $activity->started_at = $fit->data_mesgs['session']['timestamp'];
                $activity->finished_at = $fit->data_mesgs['session']['timestamp'] + $fit->data_mesgs['session']['total_elapsed_time'];
            } elseif (isset($fit->data_mesgs['event']['timestamp'][0])) {
                $activity->started_at = $fit->data_mesgs['event']['timestamp'][0];
                $activity->finished_at = last($fit->data_mesgs['event']['timestamp']);
            }

            $activity->duration = $fit->data_mesgs['session']['total_timer_time'];
            $activity->duration_total = $fit->data_mesgs['session']['total_elapsed_time'];

            if (isset($fit->data_mesgs['session']['avg_heart_rate'])) {
                $activity->avg_heart_rate = $fit->data_mesgs['session']['avg_heart_rate'];
                $activity->max_heart_rate = $fit->data_mesgs['session']['max_heart_rate'];
            }

            if (!empty($fit->data_mesgs['session']['avg_cadence'])) {
                $activity->avg_cadence = $fit->data_mesgs['session']['avg_cadence'];
                $activity->max_cadence = $fit->data_mesgs['session']['max_cadence'];
            }

            if (isset($fit->data_mesgs['session']['total_calories'])) {
                $activity->total_calories = $fit->data_mesgs['session']['total_calories'];
            }

            $activity->file = $this->fileName;

            if (isset($fit->data_mesgs['session']['start_position_lat'])) {
                $activity->start_position_lat = $fit->data_mesgs['session']['start_position_lat'];
                $activity->start_position_long = $fit->data_mesgs['session']['start_position_long'];
            }

            if (isset($fit->data_mesgs['session']['end_position_lat'])) {
                $activity->end_position_lat = $fit->data_mesgs['session']['end_position_lat'];
                $activity->end_position_long = $fit->data_mesgs['session']['end_position_long'];
            } else {
                $activity->end_position_lat = end($fit->data_mesgs['record']['position_lat']);
                $activity->end_position_long = end($fit->data_mesgs['record']['position_long']);
            }

            $geo = GpxTools::geocode($activity->start_position_lat, $activity->start_position_long);
            if ($geo) {
                $activity->country = $geo['country'];
                $activity->locality = $geo['locality'];
            }

            $gpx = GpxTools::convertFitToGpx($fit, $this->userId, $this->fileName);
            if ($gpx) {
                $image = GpxTools::generateImageFromGPX($this->fileName . '.gpx', $this->userId);
                if (!empty($image)) {
                    $activity->image = $this->fileName . '.gpx.png';
                }
            }

            $result = rename(
                Storage::path('temp/' . $this->fileName),
                Storage::path('public/activities/' . $this->userId . '/' . $this->fileName)
            );

            if (!$result) {
                $this->fail('Cant move FIT file!');
            } else {
                $activity->save();
            }
        } catch (Exception $e) {
            Log::channel('telegram')->log(LogLevel::ERROR, [
                'user' => $this->userId,
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            $this->fail($e->getMessage());
        }
    }

    private function byteArrayToString(array $byteArray): string
    {
        return pack('C*', ...$byteArray);
    }
}
