<?php

namespace App\Http\Livewire\Toast;

use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class Success extends Component
{
    public function render(): View|Application|Factory
    {
        return view('livewire.toast.success');
    }
}
