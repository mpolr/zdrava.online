<?php

namespace App\Http\Livewire\Comments;

use Illuminate\Contracts\View\View;
use Livewire\Component;
use App\Models\Comment;
use App\Models\Activities;
use Illuminate\Database\Eloquent\Collection;

class Comments extends Component
{
    public int $activityId;
    public $commentContent;
    public $selectedCommentId;
    public bool $onlyLast = false;

    protected $rules = [
        'commentContent' => 'required|min:3',
    ];

    public function mount(int $activityId, bool $onlyLast = false): void
    {
        $this->activityId = $activityId;
        $this->onlyLast = $onlyLast;
    }

    public function render(): View|\Illuminate\View\View
    {
        $activity = Activities::findOrFail($this->activityId);

        if ($this->onlyLast) {
            $commentsArray = $activity->comments()
                ->where('parent_id', null)
                ->latest()
                ->get()
                ->map(function ($comment): array {
                    return [
                        'id' => $comment->id,
                        'userId' => $comment->user->id,
                        'userInitials' => $comment->user->getInitials(),
                        'author' => $comment->user->getFullName(),
                        'photo' => $comment->user->getPhoto(),
                        'date' => $comment->created_at->format('d-m-Y H:i:s'),
                        'text' => $comment->content,
                        'replies' => [],
                ];
            });
        } else {
            $commentsArray = $activity->comments()
                ->where('parent_id', null)
                ->with('user', 'replies.user')
                ->get()
                ->map(function ($comment): array {
                    return [
                        'id' => $comment->id,
                        'userId' => $comment->user->id,
                        'userInitials' => $comment->user->getInitials(),
                        'author' => $comment->user->getFullName(),
                        'photo' => $comment->user->getPhoto(),
                        'date' => $comment->created_at->format('d-m-Y H:i:s'),
                        'text' => $comment->content,
                        'replies' => $comment->replies->map(function ($reply): array {
                            return [
                                'id' => $reply->id,
                                'userId' => $reply->user->id,
                                'userInitials' => $reply->user->getInitials(),
                                'author' => $reply->user->getFullName(),
                                'photo' => $reply->user->getPhoto(),
                                'date' => $reply->created_at->format('d-m-Y H:i:s'),
                                'text' => $reply->content,
                            ];
                        }),
                    ];
            });
        }

        return view('livewire.comments.comments', ['comments' => $commentsArray]);
    }

    public function addComment(): void
    {
        $this->validate();

        $comment = new Comment();
        $comment->activities_id = $this->activityId;
        $comment->user_id = auth()->user()->id;
        $comment->parent_id = $this->selectedCommentId;
        $comment->content = $this->commentContent;
        $comment->save();

        $this->commentContent = '';
        $this->selectedCommentId = null;

        session()->flash('message', __('Comment added successfully'));
    }

    public function replyToComment(int $commentId): void
    {
        $this->selectedCommentId = $commentId;
    }
}
