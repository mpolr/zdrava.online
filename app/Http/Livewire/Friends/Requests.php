<?php

namespace App\Http\Livewire\Friends;

use App\Models\Subscription;
use App\Models\User;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Requests extends Component
{
    public $requests;

    public function __construct()
    {
        parent::__construct();
        $this->requests = Auth::user()->subscribers->where('confirmed', 0);
    }

    public function render()
    {
        return view('livewire.friends.requests');
    }

    public function accept(User $user): RedirectResponse
    {
        $subscription = Subscription::where('user_id', Auth::user()->id)
            ->where('subscriber_id', $user->id)
            ->first();
        $subscription->confirmed = true;
        $subscription->save();

        session()->flash('success', __('":user" is now your subscriber', [
            'user' => $user->getFullName()
        ]));

        return redirect()->route('friends.requests');
    }

    public function decline(User $user): RedirectResponse
    {
        $subscription = Subscription::where('user_id', Auth::user()->id)
            ->where('subscriber_id', $user->id)
            ->first();
        $subscription->delete();

        session()->flash('success', __('Subscription request from ":user" declined', [
            'user' => $user->getFullName()
        ]));

        return redirect()->route('friends.requests');
    }
}