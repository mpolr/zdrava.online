<?php

namespace App\Http\Livewire\Components;

use App\Models\Segment;
use Livewire\Component;

class SegmentMap extends Component
{
    public $segmentsJson;

    public function mount($segments): void
    {
        $this->segmentsJson = json_encode($segments->toJson());
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('livewire.components.segment-map');
    }
}
