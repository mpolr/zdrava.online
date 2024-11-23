<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ApiNotificationController extends Controller
{
    public function get(Request $request): JsonResponse
    {
        $user = $request->user();
        $notifications = $user->notifications()->limit(50)->get();

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

    /**
     * @throws \JsonException
     */
    public function markAsRead(Request $request): JsonResponse
    {
        $json = $request->getPayload()->get('json');

        if ($json === null) {
            return response()->json([
                'success' => false,
            ]);
        }

        $notifications = json_decode($json, true, 512, JSON_THROW_ON_ERROR);

        if (!is_array($notifications)) {
            return response()->json([
                'success' => false,
            ]);
        }

        $request->user()->markAsRead($notifications);

        return response()->json([
            'success' => true,
        ]);
    }
}
