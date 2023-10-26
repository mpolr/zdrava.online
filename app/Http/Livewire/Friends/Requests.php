<?php

namespace App\Http\Livewire\Friends;

use App\Models\Subscription;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\Redirector;

class Requests extends Component
{
    public $requests;

    public function __construct()
    {
        parent::__construct();
        $this->requests = Auth::user()->subscribers->where('confirmed', 0)->all();
    }

    public function render(): \Illuminate\Contracts\View\View|View
    {
        return view('livewire.friends.requests');
    }

    public function accept(User $user): RedirectResponse|Redirector
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

    public function decline(User $user): RedirectResponse|Redirector
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
