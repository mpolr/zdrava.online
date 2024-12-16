<?php

namespace App\Http\Livewire\Tools;

use Livewire\Component;

class AntController extends Component
{
    public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\View|\Illuminate\View\View|\Illuminate\Contracts\Foundation\Application
    {
        return view('livewire.tools.antplus');
    }
}
