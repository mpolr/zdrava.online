<?php

namespace App\Http\Livewire\Upload;

use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class Photo extends Component
{
    use WithFileUploads;

    public $photo;

    public function save(): void
    {
        $this->validate([
            'photo' => 'image|max:1024',
        ]);

        $fileName = $this->photo->hashName();
        $this->photo->storePubliclyAs('pictures/athletes/' . Auth()->user()->id, $fileName, 'public');

        if (Auth()->user()->photo) {
            Storage::delete('public/pictures/athletes/' . Auth()->user()->id . '/' . Auth()->user()->photo);
        }

        Auth()->user()->photo = $fileName;
        Auth()->user()->save();

        session()->flash('success', __('File Upload successfully'));
    }
}
