<?php

namespace App\Http\Livewire\Settings;

use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class Profile extends Component
{
    use WithFileUploads;

    public User $user;
    public $photo;

    protected array $rules = [
        'user.nickname' => 'string|max:20',
        'user.first_name' => 'string|min:2|max:64',
        'user.last_name' => 'string|min:2|max:64',
        'user.subscribe_news' => 'boolean',
    ];

    public function mount(): void
    {
        $this->user = \Auth::user();
    }

    public function save(?bool $isAvatar): void
    {
        if ($isAvatar) {
            $this->validate([
                'photo' => 'required|image|max:1024',
            ]);

            $fileName = $this->photo->hashName();
            $this->photo->storePubliclyAs('pictures/athletes/' . $this->user->id, $fileName, 'public');

            if ($this->user->photo) {
                Storage::delete('public/pictures/athletes/' . $this->user->id . '/' . $this->user->photo);
            }

            $this->user->photo = $fileName;
        }

        session()->flash('success', __('Profile successfully updated'));
        $this->user->save();
    }
}
