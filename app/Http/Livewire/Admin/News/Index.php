<?php

namespace App\Http\Livewire\Admin\News;

use App\Models\News;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Component;

class Index extends Component
{
    private LengthAwarePaginator|News $news;
    public string $search = '';
    protected array $rules = [
        'search' => 'string|min:1|max:64',
    ];

    public function mount(): void
    {
        $this->news = News::orderBy('created_at', 'DESC')->paginate(50);
    }

    public function render(): View|Application|Factory|\Illuminate\View\View|\Illuminate\Contracts\Foundation\Application
    {
        return view('livewire.admin.news.index', [
            'news' => $this->news,
        ]);
    }

    public function search(): void
    {
        $this->validate();

        if (!empty($this->search)) {
            $this->news = News::where('title', 'LIKE', "%{$this->search}%")
                ->orWhere('content', 'LIKE', "%{$this->search}%")
                ->paginate(50);
        }
    }
}
