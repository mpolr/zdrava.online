<?php

namespace App\Http\Livewire;

use App\Models\Activities;
use App\Models\Subscription;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;

class Dashboard extends Component
{
    public $activities;

    public function mount(): void
    {
        $subscriptions = Subscription::select('user_id')
            ->where('subscriber_id', auth()->user()->id)
            ->where('confirmed', 1)
            ->pluck('user_id');

        $user = auth()->user();

        $this->activities = Activities::where(static function ($query) use ($user) {
            $query->where('user_id', $user->id)
                ->where('status', Activities::DONE);
        })
            ->orWhereIn('user_id', $subscriptions)
            ->with('user')
            ->orderBy('created_at', 'DESC')
            ->get();
    }
}
