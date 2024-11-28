<?php

namespace App\Jobs;

use adriangibbons\phpFITFileAnalysis;
use App\Classes\GpxTools;
use App\Classes\Polyline;
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

    protected Activities $activity;
    protected string $fileName;
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

            if (!isset($fit->data_mesgs['record'])) {
                throw new \RuntimeException('No records found in FIT file');
            }

            if (array_key_exists('sport', $fit->data_mesgs)) {
                $this->activity->sport = $fit->data_mesgs['sport']['sport'];
                $this->activity->sub_sport = $fit->data_mesgs['sport']['sub_sport'];
            }
            $this->activity->name = !empty($fit->data_mesgs['sport']['name']) ? __($fit->data_mesgs['sport']['name']) : __('Workout');
            if ($fit->data_mesgs['device_info']['manufacturer'] === 255 && !empty($fit->data_mesgs['developer_data_id']) && !empty($fit->data_mesgs['developer_data_id']['application_id'])) {
                $this->activity->creator = $this->byteArrayToString($fit->data_mesgs['developer_data_id']['application_id']);
            } else {
                $this->activity->device_manufacturers_id = $fit->data_mesgs['file_id']['manufacturer'] ?: null;
                if (array_key_exists('product', $fit->data_mesgs['file_id'])) {
                    $this->activity->device_models_id = $fit->data_mesgs['file_id']['product'];
                }
                if (isset($fit->data_mesgs['file_creator']['software_version'])) {
                    $this->activity->device_software_version = $fit->data_mesgs['file_creator']['software_version'] * 0.01;
                }
            }

            if (isset($fit->data_mesgs['session']['total_distance'])) {
                $this->activity->distance = $fit->data_mesgs['session']['total_distance'];
            } else {
                if (isset($fit->data_mesgs['lap']['total_distance'])) {
                    $this->activity->distance = isset($fit->data_mesgs['lap']['total_distance']);
                }
            }
            if (isset($fit->data_mesgs['session']['avg_speed'])) {
                $this->activity->avg_speed = $fit->data_mesgs['session']['avg_speed'];
            } elseif (isset($fit->data_mesgs['session']['enhanced_avg_speed'])) {
                $this->activity->avg_speed = $fit->data_mesgs['session']['enhanced_avg_speed'];
            }
            if (isset($fit->data_mesgs['session']['max_speed'])) {
                $this->activity->max_speed = $fit->data_mesgs['session']['max_speed'];
            } elseif (isset($fit->data_mesgs['session']['enhanced_max_speed'])) {
                $this->activity->max_speed = $fit->data_mesgs['session']['enhanced_max_speed'];
            }
            if (isset($fit->data_mesgs['session']['total_ascent'])) {
                $this->activity->elevation_gain = $fit->data_mesgs['session']['total_ascent'];
            } else {
                $this->activity->elevation_gain = $this->calculateElevationGain($fit->data_mesgs['record']['enhanced_altitude']);
            }
            if (isset($fit->data_mesgs['session']['total_descent'])) {
                $this->activity->elevation_loss = $fit->data_mesgs['session']['total_descent'];
            } else {
                $this->activity->elevation_loss = $this->calculateElevationLoss($fit->data_mesgs['record']['enhanced_altitude']);
            }
            if (isset($fit->data_mesgs['session']['start_time'])) {
                $this->activity->started_at = $fit->data_mesgs['session']['start_time'];
                $this->activity->finished_at = $fit->data_mesgs['session']['start_time'] + $fit->data_mesgs['session']['total_elapsed_time'];
            } elseif (isset($fit->data_mesgs['session']['timestamp'])) {
                $this->activity->started_at = $fit->data_mesgs['session']['timestamp'];
                $this->activity->finished_at = $fit->data_mesgs['session']['timestamp'] + $fit->data_mesgs['session']['total_elapsed_time'];
            } elseif (isset($fit->data_mesgs['event']['timestamp'][0])) {
                $this->activity->started_at = $fit->data_mesgs['event']['timestamp'][0];
                $this->activity->finished_at = last($fit->data_mesgs['event']['timestamp']);
            }

            $this->activity->duration = $fit->data_mesgs['session']['total_timer_time'];
            $this->activity->duration_total = $fit->data_mesgs['session']['total_elapsed_time'];

            if (isset($fit->data_mesgs['session']['avg_heart_rate'])) {
                $this->activity->avg_heart_rate = $fit->data_mesgs['session']['avg_heart_rate'];
                $this->activity->max_heart_rate = $fit->data_mesgs['session']['max_heart_rate'];
            }

            if (!empty($fit->data_mesgs['session']['avg_cadence'])) {
                $this->activity->avg_cadence = $fit->data_mesgs['session']['avg_cadence'];
                $this->activity->max_cadence = $fit->data_mesgs['session']['max_cadence'];
            }

            if (isset($fit->data_mesgs['session']['total_calories'])) {
                $this->activity->total_calories = $fit->data_mesgs['session']['total_calories'];
            }

            $this->activity->file = $this->fileName;

            if (isset($fit->data_mesgs['session']['start_position_lat'])) {
                $this->activity->start_position_lat = $fit->data_mesgs['session']['start_position_lat'];
                $this->activity->start_position_long = $fit->data_mesgs['session']['start_position_long'];
            }

            if (isset($fit->data_mesgs['session']['end_position_lat'])) {
                $this->activity->end_position_lat = $fit->data_mesgs['session']['end_position_lat'];
                $this->activity->end_position_long = $fit->data_mesgs['session']['end_position_long'];
            } else {
                if (
                    !is_array($fit->data_mesgs['record']['timestamp']) ||
                    !is_array($fit->data_mesgs['record']['position_lat']) ||
                    count($fit->data_mesgs['record']['timestamp']) <= 10
                ) {
                    // Всего одна запись, скорее всего нам такое не нужно
                    $this->fail('Very small FIT file!');
                }
                $this->activity->end_position_lat = end($fit->data_mesgs['record']['position_lat']);
                $this->activity->end_position_long = end($fit->data_mesgs['record']['position_long']);
            }

            $this->activity->polyline = Polyline::convertFitLocationToPolyline($fit->data_mesgs['record']);

            $geo = GpxTools::geocode($this->activity->start_position_lat, $this->activity->start_position_long);
            if ($geo) {
                $this->activity->country = $geo['country'];
                $this->activity->locality = $geo['locality'];
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

            $this->fail($e->getMessage());
        }
    }

    private function calculateElevationGain(array $elevationData = [], bool $hasBarometricData = false): float
    {
        if (empty($elevationData)) {
            throw new \RuntimeException('No elevation data found in the FIT file.');
        }

        $totalGain = 0;
        $previousElevation = null;
        $elevationThreshold = $hasBarometricData ? 2 : 10; // 2 м с барометрией, 10 м без

        // Промежуточное накопление высоты для проверки порога
        $elevationAccumulator = 0;

        foreach ($elevationData as $currentElevation) {
            if ($previousElevation !== null && $currentElevation > $previousElevation) {
                // Накопление высоты
                $elevationAccumulator += ($currentElevation - $previousElevation);

                // Если накопленный подъём превышает порог, добавляем в общий набор высоты
                if ($elevationAccumulator >= $elevationThreshold) {
                    $totalGain += $elevationAccumulator;
                    $elevationAccumulator = 0; // Сбрасываем накопление после учёта
                }
            } else {
                // Сбрасываем накопление, если высота перестала увеличиваться
                $elevationAccumulator = 0;
            }

            $previousElevation = $currentElevation;
        }

        return $totalGain;
    }

    public function calculateElevationLoss(array $elevationData = [], bool $hasBarometricData = false): float
    {
        if (empty($elevationData)) {
            throw new \RuntimeException('No elevation data found in the FIT file.');
        }

        $totalLoss = 0;
        $previousElevation = null;
        $elevationThreshold = $hasBarometricData ? 2 : 10; // 2 м с барометрией, 10 м без

        // Промежуточное накопление высоты для проверки порога
        $elevationAccumulator = 0;

        foreach ($elevationData as $currentElevation) {
            if ($previousElevation !== null && $currentElevation < $previousElevation) {
                // Накопление высоты
                $elevationAccumulator += ($previousElevation - $currentElevation);

                // Если накопленный спуск превышает порог, добавляем в общий набор высоты
                if ($elevationAccumulator >= $elevationThreshold) {
                    $totalLoss += $elevationAccumulator;
                    $elevationAccumulator = 0; // Сбрасываем накопление после учёта
                }
            } else {
                // Сбрасываем накопление, если высота перестала уменьшаться
                $elevationAccumulator = 0;
            }

            $previousElevation = $currentElevation;
        }

        return $totalLoss;
    }

    private function byteArrayToString(array $byteArray): string
    {
        return pack('C*', ...$byteArray);
    }
}
