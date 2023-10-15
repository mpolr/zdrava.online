<?php

namespace App\Http\Livewire\Admin;

use App\Models\AndroidAppCrashes;
use Illuminate\Support\Collection;
use Livewire\Component;

class CrashLogs extends Component
{
    public Collection $reports;

    public function mount(): void
    {
        $this->reports = AndroidAppCrashes::all();
    }
}
