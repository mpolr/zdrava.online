<?php

namespace App\Notifications;

use App\Models\Activities;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewComment extends Notification
{
    use Queueable;

    private User $commentFromUser;
    private Activities $activity;

    public function __construct(User $commentFromUser, Activities $activity)
    {
        $this->commentFromUser = $commentFromUser;
        $this->activity = $activity;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'message' => __(":user comments on discussion :activity", [
                'user' => $this->commentFromUser->getFullName(),
                'activity' => $this->activity->name,
            ]),
            'athlete' => $this->commentFromUser,
            'activity' => $this->activity,
        ];
    }
}
