<?php

namespace App\Http\Livewire\Admin\News;

use App\Models\News;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Livewire\Component;

class Edit extends Component
{
    public News $news;

    protected array $rules = [
        'news.title' => 'string|min:2|max:64',
        'news.content' => 'string|max:65535',
        'news.published' => 'boolean|nullable',
    ];

    public function mount(?int $id = null): void
    {
        if ($id === null) {
            $this->news = new News();
        } else {
            $this->news = News::findOrFail($id);
        }
    }

    public function render(): View|Application|Factory|\Illuminate\View\View|\Illuminate\Contracts\Foundation\Application
    {
        return view('livewire.admin.news.edit');
    }

    public function save(): RedirectResponse|Redirector
    {
        $this->validate();

        session()->flash('success', __('The changes were successfully saved'));
        $this->news->save();
        return redirect()->route('admin.news');
    }
}
