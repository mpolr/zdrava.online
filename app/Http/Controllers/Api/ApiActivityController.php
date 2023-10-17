<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Activities;
use Illuminate\Http\JsonResponse;

class ApiActivityController extends Controller
{
    protected function activityComments(int $id): JsonResponse
    {
        $user = auth('sanctum')->user();

        if (empty($user)) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 401);
        }

        $activity = Activities::findOrFail($id);

        if (empty($activity)) {
            return response()->json([
                'success' => false,
                'message' => 'Activity not found'
            ], 404);
        }

        $comments = $activity->comments()->with('user:id,nickname,photo,first_name,last_name,created_at', 'replies.user')->get();

        return response()->json([
            'success' => true,
            'comments' => $comments,
        ]);
    }

    protected function like(int $id): JsonResponse
    {
        $activity = Activities::find($id);
        $user = auth('sanctum')->user();

        if (empty($user)) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 401);
        }

        $user->like($activity);
        return response()->json([
            'success' => true,
            'count' => count($activity->likes),
        ]);
    }

    protected function unlike(int $id): JsonResponse
    {
        $activity = Activities::find($id);
        $user = auth('sanctum')->user();

        if (empty($user)) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 401);
        }

        $user->unlike($activity);
        return response()->json([
            'success' => true,
            'count' => count($activity->likes),
        ]);
    }
}
