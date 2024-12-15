<?php

namespace App\Http\Livewire\Activity;

use App\Models\Activities;
use Livewire\Component;
use Storage;
use Toaster;

class Show extends Component
{
    public Activities $activity;

    public function mount(int $id): void
    {
        $this->activity = Activities::findOrFail($id);
    }

    public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\View|\Illuminate\View\View|\Illuminate\Contracts\Foundation\Application
    {
        return view('livewire.activity.show');
    }

    public function delete(int $id): \Illuminate\Http\RedirectResponse
    {
        $activity = Activities::findOrFail($id);

        if (auth()->user()->id === $activity->user_id || auth()->user()->hasRole('admin')) {
            $gpxFile = $activity->file;
            if (strpos($activity->file, '.fit')) {
                $gpxFile = $activity->file . '.gpx';
            }

            if ($activity->file !== $gpxFile) {
                Storage::delete('public/activities/' . $activity->user_id . '/' . $gpxFile);
            }

            Storage::delete('public/activities/' . $activity->user_id . '/' . $activity->file);
            Storage::delete('public/activities/' . $activity->user_id . '/' . $activity->image);

            Activities::destroy($id);

            return redirect()->route('site.dashboard')->success(
                __('Activity ":name" successfully deleted', [
                    'name' => $activity->name
                ])
            );
        }

        return redirect()->refresh();
    }
}
