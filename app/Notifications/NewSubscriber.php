<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewSubscriber extends Notification
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
            'message' => __("User ':user' has subscribed to you", ['user' => $this->subscribeFromUser->getFullName()]),
            'athlete' => $this->subscribeFromUser,
        ];
    }
}
