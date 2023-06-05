<?php

namespace App\Http\Livewire\Settings;

use Carbon\Carbon;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Livewire\Component;

class Account extends Component
{
    public string $locale;

    public function __construct()
    {
        parent::__construct();
        $this->locale = app()->getLocale();
    }

    public function render(): \Illuminate\View\View|View|Application|Factory
    {
        return view('livewire.settings.account');
    }

    public function save(): RedirectResponse
    {
        if (!in_array($this->locale, config('app.available_locales'))) {
            abort(400);
        }

        app()->setLocale($this->locale);
        Carbon::setLocale($this->locale);
        session()->put('locale', $this->locale);
        // TODO: Сохранение языка в БД
        return redirect()->route('settings.account');
    }
}
