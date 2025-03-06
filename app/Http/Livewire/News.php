<?php

namespace App\Http\Livewire;

use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Livewire\Component;
use App\Models\News as NewsModel;

class News extends Component
{
    public $news;
    private ?int $newsId = null;

    public function mount(?int $newsId = null): void
    {
        if (!$newsId) {
            $this->news = NewsModel::where('published', 1)->orderBy('created_at', 'asc')->get();
        } else {
            $this->newsId = $newsId;
            $this->news = NewsModel::where('published', 1)->findOrFail($newsId);
        }
    }

    public function render(): View|Application|Factory|\Illuminate\View\View|\Illuminate\Contracts\Foundation\Application
    {
        if (!$this->newsId) {
            return view('livewire.news.index');
        }

        return view('livewire.news.view');
    }
}
