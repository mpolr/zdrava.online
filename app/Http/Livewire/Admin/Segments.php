<?php

namespace App\Http\Livewire\Admin;

use App\Models\Segment;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Component;

class Segments extends Component
{
    private Collection|LengthAwarePaginator $segments;
    private int $totalSegmentsCount = 0;
    public string $activityType = 'Ride';
    public string $search = '';

    public function __construct()
    {
        parent::__construct();
        $this->totalSegmentsCount = Segment::count();
        $this->segments = Segment::where('name', '!=', null)
            ->where('activity_type', $this->activityType)
            ->paginate(50);
    }

    public function setActivityType(string $type = 'Ride'): void
    {
        $this->activityType = $type;
    }

    public function search(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        $this->segments = Segment::where('name', 'LIKE', "%{$this->search}%")
            ->where('activity_type', $this->activityType)
            ->paginate(50);

        return view('livewire.admin.segments', [
            'segments' => $this->segments,
            'segmentsTotalCount' => $this->totalSegmentsCount,
        ]);
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        $this->segments = Segment::where('name', 'LIKE', "%{$this->search}%")
            ->where('activity_type', $this->activityType)
            ->paginate(50);

        return view('livewire.admin.segments', [
            'segments' => $this->segments,
            'segmentsTotalCount' => $this->totalSegmentsCount,
        ]);
    }
}
