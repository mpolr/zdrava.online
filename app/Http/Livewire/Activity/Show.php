<?php

namespace App\Http\Livewire\Activity;

use adriangibbons\phpFITFileAnalysis;
use App\Models\Activities;
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
    private ?array $distance = null;
    private ?array $temperature = null;
    public string $chartDataJson = '';
    private array $axisX = [];

    /**
     * @throws \JsonException
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

                // Массив для оси X (distance или timestamp)
                $labels = [];
                $distance = $records['distance'] ?? null;
                $lastDistance = 0;
                $kmMark = 0; // Счётчик километров
                $minuteMark = 0; // Счётчик минут для времени

                if ($distance) {
                    $labels[] = '0 km';
                }

                if (isset($speedSource)) {
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

                foreach ($records['timestamp'] as $key => $timestamp) {
                    // Если есть массив distance, используем его для оси X
                    if ($distance) {
                        $currentDistance = isset($records['distance'][$timestamp]) ? (int)($records['distance'][$timestamp] * 1000) : 0;

                        if ($currentDistance - $lastDistance >= 1000) { // Каждые 1000 метров (1 км)
                            $labels[] = number_format($currentDistance / 1000, 1) . ' km';
                            $lastDistance = $currentDistance;
                        } else {
                            $labels[] = '';
                        }
                    } else {
                        // Если нет массива distance, используем timestamp в формате часы:минуты
                        $formattedTime = date('H:i', strtotime($timestamp));
                        if ($minuteMark % 60 === 0) { // Добавляем метки через каждый час
                            $labels[] = $formattedTime;
                        }
                        $minuteMark++;
                    }

                    $this->axisX[] = date('H:i', $timestamp);

                    // Заполняем данные для графиков
                    if (isset($altitudeSource)) {
                        $this->altitude[] = ceil($altitudeSource[$timestamp] ?? null);
                    }
                    if (isset($records['distance'])) {
                        $this->distance[] = $records['distance'][$timestamp] * 1000;
                    }
                }

                if ($distance) {
                    $labels[] = '';
                }

                // Генерация JSON данных для графика
                $this->chartDataJson = json_encode([
                    //'labels' => $labels, // Используем labels как ось X
                    'labels' => array_values($this->axisX),
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
