<?php

namespace App\Http\Livewire\Admin;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;

class Users extends Component
{
    private Collection $users;

    public function mount(): void
    {
        $this->users = User::all();
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('livewire.admin.users', [
            'users' => $this->users
        ]);
    }
}
