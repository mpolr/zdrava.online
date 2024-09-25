<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notification;

class ApiNotificationController extends Controller
{
    public function get(Request $request): JsonResponse
    {
        $user = $request->user();
        $notifications = $user->notifications()->get();

        return response()->json([
            'success' => true,
            'notifications' => $notifications,
        ]);
    }

    public function getUnreadCount(Request $request): JsonResponse
    {
        $user = $request->user();
        $count = $user->unreadNotifications()->count();

        return response()->json([
            'success' => true,
            'count' => $count,
        ]);
    }

    public function markAsRead(Request $request): JsonResponse
    {
        $notificationId = $request->string('notificationId');

        $userUnreadNotification = $request->user()
            ->unreadNotifications
            ->where('id', $notificationId)
            ->first();

        if($userUnreadNotification) {
            return response()->json([
                'success' => false,
            ]);
        }

        $userUnreadNotification->markAsRead();
        return response()->json([
            'success' => true,
        ]);
    }
}
