<?php

namespace App\Http\Livewire\Admin;

use App\Models\Segment;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;

class Segments extends Component
{
    private Collection $segments;

    public function mount(): void
    {
        $this->segments = Segment::where('name', '!=' ,null)
            ->get('*');
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        $totalSegmentsCount = Segment::count();

        return view('livewire.admin.segments', [
            'segments' => $this->segments,
            'segmentsTotalCount' => $totalSegmentsCount,
        ]);
    }
}
