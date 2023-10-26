<?php

namespace App\Http\Livewire\Athlete;

use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Profile extends Component
{
    public User $user;

    public function mount(?int $id = null): void
    {
        // TODO: Если профиль закрытый и не в друзьях - выводим минимум
        if (empty($id)) {
            $this->user = Auth::user();
        } else {
            $user = User::findOrFail($id);
            $this->user = $user;
        }
    }

    public function render(): View|\Illuminate\View\View
    {
        return view('livewire.athlete.profile', [
            'user' => $this->user,
        ]);
    }
}
