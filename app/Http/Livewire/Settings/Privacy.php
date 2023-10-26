<?php

namespace App\Http\Livewire\Settings;

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
        auth()->user()->private = $this->private;
        session()->flash('success', __('Privacy settings successfully updated'));

        auth()->user()->save();
    }
}
