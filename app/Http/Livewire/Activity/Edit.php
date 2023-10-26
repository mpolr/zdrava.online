<?php

namespace App\Http\Livewire\Activity;

use App\Models\Activities;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Livewire\Component;

class Edit extends Component
{
    public Activities $activity;

    protected array $rules = [
        'activity.name' => 'string|min:2|max:64',
        'activity.description' => 'string|max:2048',
    ];

    public function mount(int $id): void
    {
        $this->activity = Activities::find($id);
    }

    public function render(): View|\Illuminate\View\View
    {
        return view('livewire.activity.edit');
    }

    public function save(): RedirectResponse|Redirector
    {
        session()->flash('success', __('The changes were successfully saved'));
        $this->activity->save();
        // TODO: Редирект на предыдущую страницу. redirect()->back() редиректит на эту же страницу, разобраться
        return redirect()->route('activities.get', $this->activity->id);
    }
}
