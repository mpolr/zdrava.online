<?php

namespace App\Http\Livewire\Admin;

use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;

class Users extends Component
{
    private Collection $users;

    public function mount(): void
    {
        $this->users = User::all();
    }

    public function render(): View|\Illuminate\View\View
    {
        return view('livewire.admin.users', [
            'users' => $this->users
        ]);
    }
}
