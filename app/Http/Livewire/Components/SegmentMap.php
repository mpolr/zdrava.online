<?php

namespace App\Http\Livewire\Components;

use Illuminate\View\View;
use JsonException;
use Livewire\Component;

class SegmentMap extends Component
{
    public $segmentsJson;

    /**
     * @throws JsonException
     */
    public function mount($segments): void
    {
        $this->segmentsJson = json_encode($segments->toJson(), JSON_THROW_ON_ERROR);
    }

    public function render(): \Illuminate\Contracts\View\View|View
    {
        return view('livewire.components.segment-map');
    }
}
