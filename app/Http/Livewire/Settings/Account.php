<?php

namespace App\Http\Livewire\Settings;

use Carbon\Carbon;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Livewire\Component;
use Livewire\Redirector;

class Account extends Component
{
    public string $locale;
    public ?string $theme;

    public function __construct()
    {
        parent::__construct();
        $this->locale = app()->getLocale();
        $this->theme = session()->get('theme');
    }

    public function render(): \Illuminate\View\View|View|Application|Factory
    {
        return view('livewire.settings.account');
    }

    public function save(): Redirector|\Illuminate\Http\RedirectResponse
    {
        if (!in_array($this->locale, config('app.available_locales'))) {
            abort(400);
        }

        app()->setLocale($this->locale);
        Carbon::setLocale($this->locale);
        session()->put('locale', $this->locale);
        session()->put('theme', $this->theme);
        // TODO: Сохранение языка в БД
        return redirect()->route('settings.account');
    }
}
