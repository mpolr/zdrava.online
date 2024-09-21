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

        $feedItems = [];
        foreach ($activities as $activity) {
            $comments = $activity->comments;

            $user = $activity->getUser();

            $feedItems[] = [
                'id' => $activity->id,
                'user' => [
                    'id' => $user->id,
                    'nickname' => $user->getNickname(),
                    'photo' => $user->photo,
                    'firstName' => $user->first_name,
                    'lastName' => $user->last_name,
                    'createdAt' => $user->created_at,
                ],
                'name' => $activity->name,
                'description' => $activity->description,
                'imageUrl' => $activity->getImage($activity->user_id, true),
                'userName' => $activity->getUser()->getFullName(),
                'distance' => $activity->distance,
                'avgSpeed' => $activity->avg_speed,
                'elevationGain' => $activity->elevation_gain,
                'startedAt' => $activity->started_at,
                'locality' => $activity->locality,
                'comments' => [
                    'count' => count($comments),
                    'items' => $comments
                ],
                'likes' => [
                    'count' => count($activity->likes),
                    'likedByMe' => $user->hasLiked($activity),
                ],
                'sharesCount' => 0,
            ];
        }

        return response()->json([
            'success' => true,
            'activities' => $feedItems,
        ]);
    }
}
