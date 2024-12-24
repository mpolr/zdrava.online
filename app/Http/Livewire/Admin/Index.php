<?php

namespace App\Http\Livewire\Admin;

use App\Models\Activities;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Psr\Log\LogLevel;

class Index extends Component
{
    public ?Activities $lastActivity;

    public function render(): View|\Illuminate\View\View
    {
        if (!auth()->user()->hasRole('admin')) {
            Log::channel('telegram')->log(LogLevel::NOTICE, auth()->user()->getFullName() . " try access admin panel");
            abort(403, 'Insufficient access rights');
        }

        $this->lastActivity = Activities::where('status', '!=', Activities::PENDING)->latest()->first();

        return view('livewire.admin.index');
    }
}
