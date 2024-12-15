<?php

namespace App\Http\Livewire\Admin;

use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Component;

class Users extends Component
{
    private LengthAwarePaginator $users;
    public string $search = '';
    protected array $rules = [
        'search' => 'string|min:1|max:64',
    ];

    public function __construct()
    {
        parent::__construct();
        $this->users = User::paginate(50);
    }

    public function search(): void
    {
        $this->validate();

        if (!empty($this->search)) {
            $this->users = User::where('first_name', 'LIKE', "%{$this->search}%")
                ->orWhere('last_name', 'LIKE', "%{$this->search}%")
                ->orWhere('nickname', 'LIKE', "%{$this->search}%")
                ->orWhere('email', 'LIKE', "%{$this->search}%")
                ->paginate(50);
        }
    }

    public function render(): View|\Illuminate\View\View
    {
        return view('livewire.admin.users', [
            'users' => $this->users,
        ]);
    }
}
