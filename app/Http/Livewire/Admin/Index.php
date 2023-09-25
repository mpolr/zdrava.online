<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;

class Index extends Component
{
    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        if (!\auth()->user()->hasRole('admin')) {
            abort(403, 'Insufficient access rights');
        }

        return view('livewire.admin.index');
    }
}
