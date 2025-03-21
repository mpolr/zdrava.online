<?php

namespace App\GraphQL\Mutations;

use Illuminate\Support\Facades\Auth;

class MarkNotificationsAsReadMutation
{
    public function resolve($root, array $args): array
    {
        $user = Auth::user();
        $user->notifications()->whereIn('id', $args['notificationIds'])->update(['read_at' => now()]);

        return ['success' => true, 'message' => 'Уведомления отмечены как прочитанные'];
    }
}
