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
    public array $speed = [];
    public array $accuracy = [];
    public array $altitude = [];
    public array $power = [];
    public array $cadence = [];
    public array $heart_rate = [];
    public array $distance = [];
    public string $chartDataJson = '';

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
//            dd($records);

            if (
                !is_array($records['timestamp']) ||
                !is_array($records['position_lat']) ||
                count($records['timestamp']) <= 5
            ) {
                Toaster::error('Very small FIT file');
            } else {
                if (!empty($records['enhanced_altitude'])) {
                    $altitudeSource = $records['enhanced_altitude'];
                } else {
                    $altitudeSource = $records['altitude'];
                }
                foreach ($records['timestamp'] as $key => $timestamp) {
                    $this->speed[] = $records['speed'][$timestamp] ?? 0;
                    $this->accuracy[] = $records['gps_accuracy'][$timestamp] ?? 0;
                    $this->altitude[] = ceil($altitudeSource[$timestamp]);
                    $this->power[] = $records['power'][$timestamp];
                    $this->cadence[] = $records['cadence'][$timestamp];
                    $this->heart_rate[] = $records['heart_rate'][$timestamp];
                    $this->distance[] = $records['distance'][$timestamp];
                }
            }

            // Генерация JSON данных для графика
            $this->chartDataJson = json_encode([
                'labels' => array_keys($this->speed), // Используем индексы для меток
                'speed' => $this->speed,
                'accuracy' => $this->accuracy,
                'altitude' => $this->altitude,
                'power' => $this->power,
                'cadence' => $this->cadence,
                'heart_rate' => $this->heart_rate,
            ], JSON_THROW_ON_ERROR);
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
