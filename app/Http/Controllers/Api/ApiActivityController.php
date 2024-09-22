<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Activities;
use App\Models\Comment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ApiActivityController extends Controller
{
    protected function get(int $id): JsonResponse
    {
        $activity = Activities::find($id);

        if (empty($activity)) {
            return response()->json([
                'success' => false,
                'message' => __('Activity not found')
            ], 404);
        }

        return response()->json([
            'success' => true,
            'athlete' => $activity,
        ]);
    }

    protected function activityComments(int $id): JsonResponse
    {
        $user = auth('sanctum')->user();

        if ($user === null) {
            return response()->json([
                'success' => false,
                'message' => __('User not found')
            ], 401);
        }

        $activity = Activities::find($id);

        if (empty($activity)) {
            return response()->json([
                'success' => false,
                'message' => __('Activity not found')
            ], 404);
        }

        $comments = $activity
            ->comments()
            ->with('user:id,nickname,photo,first_name,last_name,created_at', 'replies.user')
            ->get();

        return response()->json([
            'success' => true,
            'comments' => $comments,
        ]);
    }

    protected function activityLikes(int $id): JsonResponse
    {
        $user = auth('sanctum')->user();

        if ($user === null) {
            return response()->json([
                'success' => false,
                'message' => __('User not found')
            ], 401);
        }

        $activity = Activities::find($id);

        if (empty($activity)) {
            return response()->json([
                'success' => false,
                'message' => __('Activity not found')
            ], 404);
        }

        $likes = $activity
            ->likes()
            ->get();

        return response()->json([
            'success' => true,
            'likes' => $likes,
        ]);
    }

    protected function like(int $id): JsonResponse
    {
        $activity = Activities::find($id);

        if (empty($activity)) {
            return response()->json([
                'success' => false,
                'message' => __('Activity not found')
            ], 404);
        }

        $user = auth('sanctum')->user();

        if ($user === null) {
            return response()->json([
                'success' => false,
                'message' => __('User not found')
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

        if (empty($activity)) {
            return response()->json([
                'success' => false,
                'message' => __('Activity not found')
            ], 404);
        }

        $user = auth('sanctum')->user();

        if ($user === null) {
            return response()->json([
                'success' => false,
                'message' => __('User not found')
            ], 401);
        }

        $user->unlike($activity);
        return response()->json([
            'success' => true,
            'count' => count($activity->likes),
        ]);
    }

    protected function addComment(Request $request, int $id): JsonResponse
    {
        $activity = Activities::find($id);

        if (empty($activity)) {
            return response()->json([
                'success' => false,
                'message' => __('Activity not found')
            ], 404);
        }

        $commentText = $request->get('text');
        if (empty($commentText)) {
            return response()->json([
                'success' => false,
                'message' => __('Empty comment'),
            ]);
        }

        $comment = new Comment();
        $comment->activities_id = $activity->id;
        $comment->user_id = auth()->id();
        $comment->content = $commentText;
        $comment->save();

        return response()->json([
            'success' => true,
        ]);
    }
}
