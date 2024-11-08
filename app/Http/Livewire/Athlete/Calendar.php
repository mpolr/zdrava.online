<?php

namespace App\Http\Livewire\Athlete;

use App\Models\Activities;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Calendar extends Component
{
    public ?int $year;
    public array $stats = [
        'duration' => 0,
        'distance' => 0,
        'activities' => 0,
    ];
    public array $monthlyStats = []; // Массив для хранения количества тренировок по месяцам

    public function mount(?int $year = null): void
    {
        if ($year === null) {
            $this->year = Date('Y');
        } else {
            $this->year = $year;
        }

        // Получаем все тренировки за год
        $activities = Activities::whereUserId(\auth()->id())
            ->whereYear('started_at', $this->year)
            ->get();

        // Массив для подсчета количества тренировок и продолжительности по месяцам
        $monthlyActivities = array_fill(1, 12, ['count' => 0, 'duration' => 0]);

        foreach ($activities as $activity) {
            // Добавляем данные в общие статистики
            $this->stats['duration'] += $activity->duration;
            $this->stats['distance'] += $activity->distance;

            // Увеличиваем счетчик для месяца
            $month = $activity->started_at->month;
            $monthlyActivities[$month]['count']++; // Увеличиваем количество тренировок
            $monthlyActivities[$month]['duration'] += $activity->duration; // Добавляем продолжительность тренировки
        }

        // Подсчитываем общее количество часов для статистики
        $this->stats['duration'] = number_format($this->stats['duration'] / 3600, 1, '.', '');
        $this->stats['activities'] = $activities->count();

        // Заполняем статистику по месяцам
        $this->monthlyStats = $monthlyActivities;
    }

    public function render(): View|\Illuminate\View\View
    {
        return view('livewire.athlete.calendar', [
            'stats' => $this->stats,
            'monthlyStats' => $this->monthlyStats, // Передаем статистику по месяцам в представление
        ]);
    }
}
