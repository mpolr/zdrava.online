<?php

namespace App\Http\Livewire\Settings\Account;

use App\Models\Activities;
use Carbon\Carbon;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Livewire\Component;
use Livewire\Redirector;

class Delete extends Component
{
    public function render(): \Illuminate\View\View|View|Application|Factory
    {
        return view('livewire.settings.account.delete');
    }

    public function delete(): Redirector|\Illuminate\Http\RedirectResponse
    {
        // TODO: Email с запросом удаления
        //auth()->user()->delete();
        //return redirect()->route('index');
    }
}