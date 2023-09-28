<?php

namespace App\Http\Livewire;

use App\Models\Activities;
use App\Models\Subscription;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;

class Dashboard extends Component
{
    public Collection $activities;

    public function mount(): void
    {
        $this->activities = Activities::where('user_id', auth()->user()->id)
            ->orWhereIn('user_id', Subscription::select(['user_id'])
                ->where('subscriber_id', auth()->user()->id)
                ->where('confirmed', 1))
            ->orderBy('created_at', 'DESC')
            ->get();
    }
}
