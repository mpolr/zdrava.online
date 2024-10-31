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
            'activity' => $activity,
        ]);
    }

    protected function delete(int $id, Request $request): JsonResponse
    {
        $activity = Activities::find($id);

        if (empty($activity)) {
            return response()->json([
                'success' => false,
                'message' => __('Activity not found')
            ], 404);
        }

        if ($request->user()->id !== $activity->user_id || !auth()->user()->hasRole('admin')) {
            return response()->json([
                'success' => false,
                'message' => __('Not authorized'),
            ]);
        }

        $result = $activity->delete();

        return response()->json([
            'success' => $result,
            'activity' => $activity,
        ]);
    }

    protected function update(int $id, Request $request): JsonResponse
    {
        $activity = Activities::find($id);

        if (empty($activity)) {
            return response()->json([
                'success' => false,
                'message' => __('Activity not found')
            ], 404);
        }

        if ($request->user()->id !== $activity->user_id) {
            return response()->json([
                'success' => false,
                'message' => __('Not authorized'),
            ]);
        }

        $data = $request->validate([
            'name' => 'string|min:1|max:64',
            'description' => 'string|nullable|max:2048',
        ]);

        $activity->name = $data['name'];
        $activity->description = $data['description'];
        $result = $activity->save();

        return response()->json([
            'success' => $result,
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
            ->with('user', 'replies.user')
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

    protected function like(int $id, Request $request): JsonResponse
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

        if ($request->user()->id !== $activity->user_id) {
            $activity->user->notify(new \App\Notifications\NewLike($request->user(), $activity));
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
        $comment->user_id = $request->user()->id;
        $comment->content = $commentText;
        $comment->save();

        if ($request->user()->id !== $activity->user_id) {
            $activity->user->notify(new \App\Notifications\NewComment($request->user(), $activity));
        }

        return response()->json([
            'success' => true,
        ]);
    }
}
