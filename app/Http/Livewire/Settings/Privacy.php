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
        // TODO: Добавить поле в БД
        //$this->private = \Auth::user()->private;
    }

    public function render(): \Illuminate\View\View|View|Application|Factory
    {
        return view('livewire.settings.privacy');
    }

    public function save(): void
    {
        // TODO: Сохранение в БД
    }
}
