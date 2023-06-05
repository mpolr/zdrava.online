<?php

namespace App\Http\Livewire\Toast;

use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Livewire\Component;

class Errors extends Component
{
    public function render(): \Illuminate\View\View|View|Application|Factory
    {
        return view('livewire.toast.errors');
    }
}
