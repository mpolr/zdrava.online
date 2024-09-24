<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ApiNotificationController extends Controller
{
    public function get(Request $request): JsonResponse
    {
        $user = $request->user();
        $notifications = $user->unreadNotifications()->get();
        
        return response()->json([
            'success' => true,
            'notifications' => $notifications,
        ]);
    }
}
