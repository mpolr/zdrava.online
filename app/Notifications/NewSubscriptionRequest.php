<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewSubscriptionRequest extends Notification
{
    use Queueable;

    private User $subscribeFromUser;

    public function __construct(User $subscribeFromUser)
    {
        $this->subscribeFromUser = $subscribeFromUser;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'message' => __("Subscription request from ':user'", ['user' => $this->subscribeFromUser->getFullName()]),
            'athlete' => $this->subscribeFromUser,
        ];
    }
}
