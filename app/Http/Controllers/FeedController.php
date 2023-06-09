<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FeedController extends Controller
{
    protected function feed(Request $request): JsonResponse
    {
        $user = auth('sanctum')->user();

        if (empty($user)) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 401);
        }

        $activities = $user->activities;

        if (empty($activities)) {
            return response()->json([
                'success' => false,
                'message' => 'No activities found'
            ]);
        }

        $feedItems = [];
        foreach ($activities as $activity) {
            $feedItems[] = [
                'id' => $activity->id,
                'user_id' => $activity->user_id,
                'name' => $activity->name,
                'description' => $activity->description,
                'imageUrl' => 'https://mpolr.ru/images/zdrava-ride.jpg',
                'userName' => $user->getFullName(),
                'distance' => $activity->distance,
                'avgSpeed' => $activity->avg_speed,
                'elevationGain' => $activity->elevation_gain,
                'startedAt' => $activity->started_at,
                'commentsCount' => 0,
                'likesCount' => 0,
                'sharesCount' => 0,
            ];
        }

        return response()->json(['activities' => $feedItems]);
    }
}
