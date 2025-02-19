<?php

namespace App\Http\Livewire\Activity;

use adriangibbons\phpFITFileAnalysis;
use App\Models\Activities;
use Exception;
use JsonException;
use Livewire\Component;
use Storage;
use Toaster;

class Show extends Component
{
    public Activities $activity;
    private ?array $speed = null;
    private ?array $accuracy = null;
    private ?array $altitude = null;
    private ?array $power = null;
    private ?array $cadence = null;
    private ?array $heart_rate = null;
    private ?array $temperature = null;
    private ?array $distance = null;
    public string $chartType = 'line';
    public string $chartDataJson = '';
    private array $axisX = [];

    /**
     * @throws JsonException
     * @throws Exception
     */
    public function mount(int $id): void
    {
        $this->activity = Activities::findOrFail($id);
        $file = $this->activity->file;

        if (strpos($file, '.fit')) {
            $fit = new phpFITFileAnalysis(
                \Illuminate\Support\Facades\Storage::path(
                    'public/activities/' . $this->activity->user_id . '/' . $file
                )
            );

            $records = $fit->data_mesgs['record'];

            if (
                !is_array($records['timestamp']) ||
                !is_array($records['position_lat']) ||
                count($records['timestamp']) <= 5
            ) {
                Toaster::error('Very small FIT file');
            } else {
                // Определяем источники данных
                $speedSource = $records['enhanced_speed'] ?? $records['speed'];
                $altitudeSource = $records['enhanced_altitude'] ?? $records['altitude'];

                // Заполняем массивы данных для графиков
                if (isset($speedSource)) {
                    foreach ($speedSource as $item => $value) {
                        $speedSource[$item] = number_format($value, 1, '.', '');
                    }
                    $this->speed = array_values($speedSource);
                }
                if (isset($records['power'])) {
                    $this->power = array_values($records['power']);
                }
                if (isset($records['heart_rate'])) {
                    $this->heart_rate = array_values($records['heart_rate']);
                }
                if (isset($records['cadence'])) {
                    $this->cadence = array_values($records['cadence']);
                }
                if (isset($records['gps_accuracy'])) {
                    $this->accuracy = array_values($records['gps_accuracy']);
                }
                if (isset($records['temperature'])) {
                    $this->temperature = array_values($records['temperature']);
                }
                if (isset($records['distance'])) {
                    // Ось X (метки расстояния)
                    $this->axisX = [__(':distance km', ['distance' => 0])]; // Начинаем с 0 км
                    $lastKm = 0; // Последний добавленный километр
                    $maxDistance = 0;

                    foreach ($records['distance'] as $timestamp => $distance) {
                        $maxDistance = max($maxDistance, $distance);
                        $currentKm = (int)floor($distance); // Округляем вниз до целого километра

                        if ($currentKm > $lastKm) {
                            $this->axisX[$timestamp] = __(':distance km', ['distance' => $currentKm]);
                            $lastKm = $currentKm;
                        }
                    }

                    // Добавляем финальную метку с точным пройденным расстоянием
                    $finalLabel = __(':distance km', ['distance' => number_format($maxDistance, 2, '.', '')]);
                    if (!in_array($finalLabel, $this->axisX, true)) {
                        $this->axisX[max($records['timestamp'])] = $finalLabel;
                    }

                    $this->distance = array_values($records['distance']);
                }

                foreach ($records['timestamp'] as $key => $timestamp) {
                    $this->axisX[] = date('H:i', $timestamp);

                    if (isset($altitudeSource)) {
                        $this->altitude[] = ceil($altitudeSource[$key] ?? null);
                    }
                    if (isset($records['distance'][$key])) {
                        $this->distance[] = $records['distance'][$key] * 1000;
                    }
                }

                // Генерация JSON данных для графика
                $this->chartDataJson = json_encode([
                    'labels' => array_values($this->axisX),
                    'distance' => $this->distance,
                    'speed' => $this->speed,
                    'accuracy' => $this->accuracy,
                    'altitude' => $this->altitude,
                    'power' => $this->power,
                    'cadence' => $this->cadence,
                    'heart_rate' => $this->heart_rate,
                    'temperature' => $this->temperature,
                ], JSON_THROW_ON_ERROR);
            }
        }
    }

    public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\View|\Illuminate\View\View|\Illuminate\Contracts\Foundation\Application
    {
        return view('livewire.activity.show', [
            'chartDataJson' => $this->chartDataJson,
        ]);
    }

    public function delete(int $id): \Illuminate\Http\RedirectResponse
    {
        $activity = Activities::findOrFail($id);

        if (auth()->user()->id === $activity->user_id || auth()->user()->hasRole('admin')) {
            $gpxFile = $activity->file;
            if (strpos($activity->file, '.fit')) {
                $gpxFile = $activity->file . '.gpx';
            }

            if ($activity->file !== $gpxFile) {
                Storage::delete('public/activities/' . $activity->user_id . '/' . $gpxFile);
            }

            Storage::delete('public/activities/' . $activity->user_id . '/' . $activity->file);
            Storage::delete('public/activities/' . $activity->user_id . '/' . $activity->image);

            Activities::destroy($id);

            return redirect()->route('site.dashboard')->success(
                __('Activity ":name" successfully deleted', [
                    'name' => $activity->name
                ])
            );
        }

        return redirect()->refresh();
    }
}
