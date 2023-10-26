<?php

namespace App\Http\Livewire\Components;

use App\Contracts\Likeable;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Livewire\Component;

class Like extends Component
{
    public Model $model;
    public Model $likeable;
    protected $listeners = ['refreshComponent' => '$refresh'];

    public function mount(Model $model): void
    {
        $this->model = $model;
        $this->likeable = $model::find($model->id);
    }

    public function render(): View|\Illuminate\View\View
    {
        return view('livewire.components.like');
    }

    public function like(int $id): void
    {
        session()->user()->like($this->likeable);
        $this->emit('refreshComponent');
    }

    public function unlike(int $id): void
    {
        session()->user()->unlike($this->likeable);
        $this->emit('refreshComponent');
    }
}
