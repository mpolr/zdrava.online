<?php

namespace App\Http\Livewire\Select;

use Carbon\Carbon;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Livewire\Component;

class Locale extends Component
{
    public string $locale;

    public function mount(): void
    {
        $this->locale = app()->getLocale();
    }

    public function render(): View|Application|Factory
    {
        return view('livewire.select.locale');
    }

    public function save()
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
