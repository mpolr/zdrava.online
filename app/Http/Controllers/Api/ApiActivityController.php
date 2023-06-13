<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Activities;
use App\Models\Subscription;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ApiActivityController extends Controller
{
    protected function activityComments(Request $request, int $id): JsonResponse
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

        $comments = $activity->comments()->with('user', 'replies.user')->get();

        return response()->json(['comments' => $comments]);
    }
}
