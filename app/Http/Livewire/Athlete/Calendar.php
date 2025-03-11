<?php

namespace App\Http\Livewire\Athlete;

use App\Models\Activities;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class Calendar extends Component
{
    public ?int $year;
    public array $stats = [
        'duration' => 0,
        'distance' => 0,
        'activities' => 0,
    ];
    public array $monthlyStats = [];
    public array $weeklyStats = [];

    public function mount(?int $year = null): void
    {
        if ($year === null) {
            $this->year = date('Y');
        } else {
            $this->year = $year;
        }

        // Получаем все тренировки за год
        $activities = Activities::whereUserId(auth()->id())
            ->whereYear('started_at', $this->year)
            ->get();

        // Подготовка массивов для хранения данных
        $monthlyActivities = array_fill(1, 12, ['count' => 0, 'duration' => 0]);
        $weeklyActivities = array_fill(1, 53, ['count' => 0, 'duration' => 0]); // 53 недели на случай високосного года

        foreach ($activities as $activity) {
            // Общая статистика
            $this->stats['duration'] += $activity->duration;
            $this->stats['distance'] += $activity->distance;

            // Статистика по месяцам
            $month = $activity->started_at->month;
            $monthlyActivities[$month]['count']++;
            $monthlyActivities[$month]['duration'] += $activity->duration;

            // Статистика по неделям
            $week = $activity->started_at->weekOfYear;
            $weeklyActivities[$week]['count']++;
            $weeklyActivities[$week]['duration'] += $activity->duration;
        }

        // Подсчет часов для статистики
        $this->stats['duration'] = number_format($this->stats['duration'] / 3600, 1, '.', '');
        $this->stats['activities'] = $activities->count();

        // Заполнение данных
        $this->monthlyStats = $monthlyActivities;
        $this->weeklyStats = $weeklyActivities;
    }

    public function render(): View|\Illuminate\View\View
    {
        return view('livewire.athlete.calendar', [
            'stats' => $this->stats,
            'weeklyStats' => $this->weeklyStats,
            'monthlyStats' => $this->monthlyStats,
        ]);
    }
}
