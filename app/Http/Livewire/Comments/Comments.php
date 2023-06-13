<?php


namespace App\Http\Livewire\Comments;

use Livewire\Component;
use App\Models\Comment;
use App\Models\Activities;
use Illuminate\Database\Eloquent\Collection;

class Comments extends Component
{
    public $activityId;
    public $commentContent;
    public $selectedCommentId;

    protected $rules = [
        'commentContent' => 'required|min:3',
    ];

    public function mount($activityId)
    {
        $this->activityId = $activityId;
    }

    public function render()
    {
        $activity = Activities::findOrFail($this->activityId);
        $comments = $activity->comments()->where('parent_id', null)->with('user', 'replies.user')->get();

        // Transform the comments and replies into an array for passing to the view
        $commentsArray = $comments->map(function ($comment) {
            return [
                'id' => $comment->id,
                'userId' => $comment->user->id,
                'author' => $comment->user->getFullName(),
                'photo' => $comment->user->getPhoto(),
                'date' => $comment->created_at->format('Y-m-d H:i:s'),
                'text' => $comment->content,
                'replies' => $comment->replies->map(function ($reply) {
                    return [
                        'id' => $reply->id,
                        'userId' => $reply->user->id,
                        'author' => $reply->user->getFullName(),
                        'photo' => $reply->user->getPhoto(),
                        'date' => $reply->created_at->format('Y-m-d H:i:s'),
                        'text' => $reply->content,
                    ];
                }),
            ];
        });

        return view('livewire.comments.comments', ['comments' => $commentsArray]);
    }

    public function addComment()
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

        session()->flash('message', 'Комментарий успешно добавлен.');
    }

    public function replyToComment($commentId)
    {
        $this->selectedCommentId = $commentId;
    }
}
