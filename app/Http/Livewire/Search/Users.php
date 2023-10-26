<?php

namespace App\Http\Livewire\Search;

use App\Models\Subscription;
use App\Models\User;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Redirector;

class Users extends Component
{
    public string $search = '';
    public mixed $users = [];
    public $subscriptions;
    public $awaiting;

    protected array $rules = [
        'search' => 'string|min:2|max:64',
    ];

    public function render(): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        if (empty($this->search)) {
            $this->users = User::inRandomOrder()->limit(10)->get();
            ;
        }

        $this->subscriptions = Subscription::where('subscriber_id', auth()->id())
            ->where('confirmed', 1)
            ->pluck('user_id')
            ->toArray();
        $this->awaiting = Subscription::where('subscriber_id', auth()->id())
            ->where('confirmed', 0)
            ->pluck('user_id')
            ->toArray();

        return view('livewire.search.users');
    }

    public function search(): void
    {
        $this->validate();

        if (!empty($this->search)) {
            $this->users = User::where('first_name', 'LIKE', "%{$this->search}%")
                ->orWhere('last_name', 'LIKE', "%{$this->search}%")
                ->orWhere('nickname', 'LIKE', "%{$this->search}%")
                ->orWhere('email', 'LIKE', "%{$this->search}%")
                ->limit(100)
                ->get();
        }
    }

    public function subscribe(User $user): void
    {
        $subscription = new Subscription();
        $subscription->user_id = $user->id;
        $subscription->subscriber_id = Auth::user()->id;
        $subscription->save();

        session()->flash('success', __('Subscription request sent'));
    }

    public function unsubscribe(User $user): void
    {
        $subscription = Subscription::where('user_id', $user->id)
            ->where('subscriber_id', Auth::user()->id)
            ->first();
        $subscription->delete();

        session()->flash('success', __('Successfully unsubscribed from ":user"', [
            'user' => $user->getFullName()
        ]));
    }

    public function cancel(User $user): void
    {
        $subscription = Subscription::where('user_id', $user->id)
            ->where('subscriber_id', Auth::user()->id)
            ->first();
        $subscription->delete();

        session()->flash('success', __('Subscription request cancelled', [
            'user' => $user->getFullName()
        ]));
    }
}
