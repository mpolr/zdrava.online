<?php

namespace App\Http\Livewire\Settings;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Livewire\Component;

class Privacy extends Component
{
    public bool $private;

    public function mount(): void
    {
        $this->private = auth()->user()->private;
    }

    public function render(): \Illuminate\View\View|View|Application|Factory
    {
        return view('livewire.settings.privacy');
    }

    public function save(): void
    {
        $user = User::find(auth()->id());
        $user->private = $this->private;

        session()->flash('success', __('Privacy settings successfully updated'));

        $user->save();
    }
}
