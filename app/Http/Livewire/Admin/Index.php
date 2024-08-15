<?php

namespace App\Http\Livewire\Admin;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Psr\Log\LogLevel;

class Index extends Component
{
    public function render(): View|\Illuminate\View\View
    {
        if (!auth()->user()->hasRole('admin')) {
            Log::channel('telegram')->log(LogLevel::NOTICE, auth()->user()->getFullName() . " try access admin panel");
            abort(403, 'Insufficient access rights');
        }

        return view('livewire.admin.index');
    }
}
