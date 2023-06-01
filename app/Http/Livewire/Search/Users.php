<?php

namespace App\Http\Livewire\Search;

use App\Models\User;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Livewire\Component;

class Users extends Component
{
    public string $search = '';
    public mixed $users = [];

    protected array $rules = [
        'search' => 'string|min:2|max:64',
    ];

    public function render(): View|Application|Factory|null
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

    public function subscribe(User $user)
    {
        // TODO: Друзья и всё такое
        session()->flash('success', __('Subscription request sent.'));
    }
}
