<?php

namespace App\Http\Livewire\Segments;

use App\Models\Segment;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;

class Explore extends Component
{
    private Collection $segments;

    public function mount(): void
    {
        $this->segments = Segment::select(['id', 'name', 'distance', 'total_elevation_gain', 'polyline', 'start_latlng'])
            ->where('name', '!=', null)
            ->where('private', '!=', 1)
            ->get('*');
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('livewire.segments.explore', [
            'segments' => $this->segments,
        ]);
    }
}
