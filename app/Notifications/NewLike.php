<?php

namespace App\Notifications;

use App\Models\Activities;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewLike extends Notification
{
    use Queueable;

    private User $likeFromUser;
    private Activities $activity;

    public function __construct(User $likeFromUser, Activities $activity)
    {
        $this->likeFromUser = $likeFromUser;
        $this->activity = $activity;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        if (count($this->activity->likes) === 0) {
            $message = __(":activity: athlete :athlete liked you", [
                'athlete' => $this->likeFromUser->getFullName(),
                'activity' => $this->activity->name,
            ]);
        } else {
            $message = __(":activity: athlete :athlete and :count others liked you", [
                'athlete' => $this->likeFromUser->getFullName(),
                'activity' => $this->activity->name,
                'count' => count($this->activity->likes),
            ]);
        }

        return [
            'message' => $message,
            'athlete' => $this->likeFromUser,
            'activity' => $this->activity,
        ];
    }
}
