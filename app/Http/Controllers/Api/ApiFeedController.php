<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Activities;
use App\Models\Subscription;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ApiFeedController extends Controller
{
    protected function feed(Request $request): JsonResponse
    {
        $user = auth('sanctum')->user();

        if ($user === null) {
            return response()->json([
                'success' => false,
                'message' => __('User not found')
            ], 401);
        }

        $activities = Activities::where('user_id', $user->id)
            ->orWhereIn('user_id', Subscription::select(['user_id'])
                ->where('subscriber_id', $user->id)
                ->where('confirmed', 1))
            ->orderBy('created_at', 'DESC')
            ->get();

        if (empty($activities)) {
            return response()->json([
                'success' => false,
                'message' => __('No activities found')
            ]);
        }

        return response()->json([
            'success' => true,
            'activities' => $activities,
        ]);
    }
}
