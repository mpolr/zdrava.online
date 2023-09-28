<?php

namespace App\Http\Livewire\Segments;

use App\Models\Segment;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Component;

class Explore extends Component
{
    private Collection|LengthAwarePaginator $segments;

    public function mount(Request $request): void
    {
        $pagination = 11;
        if ($request->get('show') === 'max') {
            $pagination = 9999;
        }

        $this->segments = Segment::select(['id', 'name', 'distance', 'total_elevation_gain', 'polyline', 'start_latlng'])
            ->where('name', '!=', null)
            ->where('private', '!=', 1)
            ->paginate($pagination);
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('livewire.segments.explore', [
            'segments' => $this->segments,
        ]);
    }
}
