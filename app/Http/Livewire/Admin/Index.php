<?php

namespace App\Http\Livewire\Admin;

use App\Models\Activities;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Psr\Log\LogLevel;

class Index extends Component
{
    public ?Activities $lastActivity;
    public array $userRegistrations = [];

    public function mount(): void
    {
        if (!auth()->user()->hasRole('admin')) {
            Log::channel('telegram')->log(LogLevel::NOTICE, auth()->user()->getFullName() . " try access admin panel");
            abort(403, 'Insufficient access rights');
        }

        $this->lastActivity = Activities::where('status', '!=', Activities::PENDING)->latest()->first();

        $this->fetchUserRegistrations();
    }

    private function fetchUserRegistrations(): void
    {
        $now = Carbon::now();
        $startDate = $now->copy()->subMonth()->startOfDay();
        $endDate = $now->copy()->endOfDay();

        // Получаем данные в виде коллекции с ключами-датами
        $registrations = User::whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->pluck('count', 'date');

        // Формируем последовательность дат за последние 31 день
        $dates = collect(range(0, 30))
            ->map(fn ($i) => $now->copy()->subDays($i)->format('Y-m-d'))
            ->reverse();

        $data = $dates->mapWithKeys(function ($date) use ($registrations) {
            return [$date => $registrations->get($date, 0)];
        });

        $this->userRegistrations = [
            'labels' => $data->keys()->toArray(),
            'values' => $data->values()->toArray(),
        ];
    }

    public function render(): View
    {
        return view('livewire.admin.index', [
            'userRegistrations' => $this->userRegistrations,
        ]);
    }
}
