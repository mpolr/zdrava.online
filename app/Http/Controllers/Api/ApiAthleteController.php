<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class ApiAthleteController extends Controller
{
    protected function athlete(int $id): JsonResponse
    {
        $user = auth('sanctum')->user();

        if (empty($user)) {
            return response()->json([
                'success' => false,
                'message' => __('User not found')
            ], 401);
        }

        $athlete = User::findOrFail($id, ['id', 'nickname', 'photo', 'first_name', 'last_name', 'created_at']);

        if (empty($athlete)) {
            return response()->json([
                'success' => false,
                'message' => __('Athlete not found')
            ], 404);
        }

        $athlete->isSubscribed = $user->isSubscriber($athlete);

        return response()->json([
            'success' => true,
            'athlete' => $athlete
        ]);
    }

    protected function subscribe(int $id)
    {
        $subscription = new Subscription;
        $subscription->user_id = $id;
        $subscription->subscriber_id = auth('sanctum')->id();
        $result = $subscription->save();

        if ($result) {
            return response()->json([
                'success' => true,
                'message' => __('Subscription request sent'),
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => __('Subscription failed'),
        ]);
    }
}