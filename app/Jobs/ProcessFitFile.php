<?php

namespace App\Jobs;

use adriangibbons\phpFITFileAnalysis;
use App\Classes\GpxTools;
use App\Classes\Polyline;
use App\Models\Activities;
use Carbon\Carbon;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Psr\Log\LogLevel;

class ProcessFitFile implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    protected Activities $activity;
    protected string $fileName;

    public int $tries = 3;
    public int $timeout = 300;

    public function __construct(Activities $activity, string $fileName)
    {
        $this->activity = $activity;
        $this->fileName = $fileName;
    }

    /**
     * @throws Exception
     */
    public function handle(): void
    {
        try {
            $fit = new phpFITFileAnalysis(Storage::path('temp/' . $this->fileName));
            $this->validateFitFile($fit);
            $data = $fit->data_mesgs;

            if (
                !is_array($data['record']['timestamp']) ||
                !is_array($data['record']['position_lat']) ||
                count($data['record']['timestamp']) <= 10
            ) {
                // Всего одна запись, скорее всего нам такое не нужно
                $this->deleteActivity();
                $this->fail('Very small FIT file!');
            }

            if (isset($data['sport'])) {
                $this->activity->sport = $this->getFitData($data['sport'], 'sport');
                $this->activity->sub_sport = $this->getFitData($data['sport'], 'sub_sport');
            } elseif (isset($data['session'])) {
                $this->activity->sport = $this->getFitData($data['session'], 'sport');
                $this->activity->sub_sport = $this->getFitData($data['session'], 'sub_sport');
            }

            if (!empty($data['sport']['name'])) {
                $this->activity->name = trim($data['sport']['name']);
            } elseif (!empty($data['developer_data']['title']['data'])) {
                $this->activity->name = trim($data['developer_data']['title']['data'][0]);
            } else {
                $this->activity->name = trans('Workout');
            }

            if (!empty($data['developer_data_id']) && !empty($data['developer_data_id']['application_id'])) {
                $this->activity->creator = $this->byteArrayToString($data['developer_data_id']['application_id']);
            } else {
                $this->activity->device_manufacturers_id = $data['file_id']['manufacturer'] ?: null;
                if (array_key_exists('product', $data['file_id'])) {
                    $this->activity->device_models_id = $data['file_id']['product'];
                }
                if (isset($data['file_creator']['software_version'])) {
                    $this->activity->device_software_version = $data['file_creator']['software_version'] * 0.01;
                }
            }

            if (isset($data['session']['total_distance'])) {
                $this->activity->distance = $data['session']['total_distance'];
            } elseif (isset($data['lap']['total_distance'])) {
                $this->activity->distance = isset($data['lap']['total_distance']);
            }
            if (isset($data['session']['avg_speed'])) {
                $this->activity->avg_speed = $data['session']['avg_speed'];
            } elseif (isset($data['session']['enhanced_avg_speed'])) {
                $this->activity->avg_speed = $data['session']['enhanced_avg_speed'];
            }

            if (isset($data['session']['max_speed'])) {
                $this->activity->max_speed = $data['session']['max_speed'];
            } elseif (isset($data['session']['enhanced_max_speed'])) {
                $this->activity->max_speed = $data['session']['enhanced_max_speed'];
            } elseif (isset($data['record']['speed'])) {
                $this->activity->max_speed = $this->getMaxSpeed($data['record']['speed']);
            } elseif (isset($data['record']['enhanced_speed'])) {
                $this->activity->max_speed = $this->getMaxSpeed($data['record']['enhanced_speed']);
            }

            if (isset($data['session']['total_ascent'])) {
                $this->activity->elevation_gain = $data['session']['total_ascent'];
            } else {
                $this->activity->elevation_gain = 0;
            }
            if (isset($data['session']['total_descent'])) {
                $this->activity->elevation_loss = $data['session']['total_descent'];
            } else {
                $this->activity->elevation_loss = 0;
            }

            $this->extractTimestamps($fit);

            if (isset($data['session']['total_timer_time'])) {
                $this->activity->duration = $data['session']['total_timer_time'];
                $this->activity->duration_total = $data['session']['total_elapsed_time'];
            }

            $exist = Activities::where('user_id', $this->activity->user_id)
                ->where('started_at', $this->activity->started_at)
                ->where('finished_at', $this->activity->finished_at)
                ->where('duration', $this->activity->duration)
                ->first('id');

            if ($exist !== null) {
                // Такая тренировка уже есть
                $this->deleteActivity();
                $this->fail('Activity already exists.');
            }

            if (isset($data['session']['avg_heart_rate'])) {
                $this->activity->avg_heart_rate = $data['session']['avg_heart_rate'];
                $this->activity->max_heart_rate = $data['session']['max_heart_rate'];
            }

            if (!empty($data['session']['avg_cadence'])) {
                $this->activity->avg_cadence = $data['session']['avg_cadence'];
                $this->activity->max_cadence = $data['session']['max_cadence'];
            }

            if (isset($data['session']['total_calories'])) {
                $this->activity->total_calories = $data['session']['total_calories'];
            }

            $this->activity->file = $this->fileName;
            $this->extractCoordinates($fit);

            if ($this->activity->end_position_lat === 0.0) {
                $this->activity->end_position_lat = end($data['record']['position_lat']);
            }
            if ($this->activity->end_position_long === 0.0) {
                $this->activity->end_position_long = end($data['record']['position_long']);
            }

            $this->activity->polyline = Polyline::convertFitLocationToPolyline($data['record']);

            if ($this->activity->start_position_lat !== 0.0 || $this->activity->start_position_long !== 0.0) {
                $geo = GpxTools::geocode($this->activity->start_position_lat, $this->activity->start_position_long);
                if ($geo) {
                    $this->activity->country = $geo['country'];
                    $this->activity->locality = $geo['locality'];
                }
            }

            $gpx = GpxTools::convertFitToGpx($fit, $this->activity->user_id, $this->fileName);
            if ($gpx) {
                $image = GpxTools::generateImageFromGPX($this->fileName . '.gpx', $this->activity->user_id);
                if (!empty($image)) {
                    $this->activity->image = $this->fileName . '.gpx.png';
                }
            }

            $result = rename(
                Storage::path('temp/' . $this->fileName),
                Storage::path('public/activities/' . $this->activity->user_id . '/' . $this->fileName)
            );

            if (!$result) {
                $this->fail('Cant move FIT file!');
            } else {
                $this->activity->status = Activities::DONE;
                $this->activity->save();
            }
        } catch (Exception $e) {
            Log::channel('telegram')->log(LogLevel::ERROR, [
                'user' => $this->activity->user_id,
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            $this->deleteActivity();
            $this->fail($e->getMessage());
        }
    }

    private function deleteActivity(): void
    {
        $filesToDelete = [
            Storage::path('temp/' . $this->fileName),
        ];

        $gpxFile = Storage::path("public/activities/{$this->activity->user_id}/{$this->fileName}.gpx");
        if (Storage::exists($gpxFile)) {
            $filesToDelete[] = $gpxFile;
        }
        if (!empty($this->activity->image)) {
            $filesToDelete[] = Storage::path("temp/{$this->fileName}.gpx.png");
        }

        Storage::delete($filesToDelete);
        $this->activity->delete();
    }

    private function getFitData(array $data, string $key, $default = null)
    {
        return $data[$key] ?? $default;
    }

    private function extractTimestamps($fit): void
    {
        $session = $fit->data_mesgs['session'] ?? [];
        $this->activity->started_at = $this->getFitData($session, 'start_time')
            ?? $this->getFitData($session, 'timestamp')
            ?? $this->getFitData($fit->data_mesgs['event'], 'timestamp')[0] ?? null;

        if ($this->activity->started_at !== null) {
            // Убедимся, что started_at является экземпляром Carbon
            $this->activity->started_at = Carbon::parse($this->activity->started_at);
            $totalElapsedTime = $this->getFitData($session, 'total_elapsed_time', 0);

            // Используем addSeconds для добавления времени
            $this->activity->finished_at = $this->activity->started_at->copy()->addSeconds($totalElapsedTime);
        }
    }

    private function extractCoordinates($fit): void
    {
        $session = $fit->data_mesgs['session'] ?? [];
        $this->activity->start_position_lat = $this->getFitData($session, 'start_position_lat', 0.0);
        $this->activity->start_position_long = $this->getFitData($session, 'start_position_long', 0.0);
        $this->activity->end_position_lat = $this->getFitData($session, 'end_position_lat', 0.0);
        $this->activity->end_position_long = $this->getFitData($session, 'end_position_long', 0.0);
    }

    private function validateFitFile($fit): void
    {
        if (empty($fit->data_mesgs['record'])) {
            throw new \RuntimeException('No records found in FIT file');
        }
        if (!isset($fit->data_mesgs['session'])) {
            throw new \RuntimeException('No session data found in FIT file');
        }
    }

    private function byteArrayToString(array $byteArray): string
    {
        return pack('C*', ...$byteArray);
    }

    private function getMaxSpeed(array $data): float
    {
        return max($data);
    }
}
