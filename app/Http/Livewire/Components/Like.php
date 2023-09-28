<?php

namespace App\Http\Livewire\Components;

use App\Contracts\Likeable;
use Illuminate\Database\Eloquent\Model;
use Livewire\Component;

class Like extends Component
{
    public Model $model;
    public Likeable $likeable;
    protected $listeners = ['refreshComponent' => '$refresh'];

    public function mount(Model $model): void
    {
        $this->model = $model;
        $this->likeable = $model::find($model->id);
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('livewire.components.like');
    }

    public function like(int $id): void
    {
        auth()->user()->like($this->likeable);
        $this->emit('refreshComponent');
    }

    public function unlike(int $id): void
    {
        auth()->user()->unlike($this->likeable);
        $this->emit('refreshComponent');
    }
}
