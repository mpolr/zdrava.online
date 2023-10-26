<?php

namespace App\Http\Livewire\Admin;

use Illuminate\Contracts\View\View;
use Livewire\Component;

class Index extends Component
{
    public function render(): View|\Illuminate\View\View
    {
        if (!auth()->user()->hasRole('admin')) {
            abort(403, 'Insufficient access rights');
        }

        return view('livewire.admin.index');
    }
}
