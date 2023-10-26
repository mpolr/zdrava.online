<?php

namespace App\Http\Livewire\Components;

use Illuminate\View\View;
use Livewire\Component;

class SegmentMap extends Component
{
    public $segmentsJson;

    public function mount($segments): void
    {
        $this->segmentsJson = json_encode($segments->toJson());
    }

    public function render(): \Illuminate\Contracts\View\View|View
    {
        return view('livewire.components.segment-map');
    }
}
