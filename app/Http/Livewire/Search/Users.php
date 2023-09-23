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

    public function __construct()
    {
        parent::__construct();
        $this->subscriptions = Auth::user()->subscriptions->where('confirmed', 1)->pluck('user_id')->toArray();
        $this->awaiting = Auth::user()->subscriptions->where('confirmed', 0)->pluck('user_id')->toArray();
    }

    public function render()
    {
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

    public function subscribe(User $user): Redirector|RedirectResponse
    {
        $subscription = new Subscription;
        $subscription->user_id = $user->id;
        $subscription->subscriber_id = Auth::user()->id;
        $subscription->save();

        session()->flash('success', __('Subscription request sent'));

        return redirect()->route('friends.find');
    }

    public function unsubscribe(User $user): RedirectResponse
    {
        $subscription = Subscription::where('user_id', $user->id)
            ->where('subscriber_id', Auth::user()->id)
            ->first();
        $subscription->delete();

        session()->flash('success', __('Successfully unsubscribed from ":user"', [
            'user' => $user->getFullName()
        ]));

        return redirect()->route('friends.find');
    }

    public function cancel(User $user): RedirectResponse
    {
        $subscription = Subscription::where('user_id', $user->id)
            ->where('subscriber_id', Auth::user()->id)
            ->first();
        $subscription->delete();

        session()->flash('success', __('Subscription request cancelled', [
            'user' => $user->getFullName()
        ]));

        return redirect()->route('friends.find');
    }
}
