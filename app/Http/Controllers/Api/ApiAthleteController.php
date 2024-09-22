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

        if ($user === null) {
            return response()->json([
                'success' => false,
                'message' => __('User not found')
            ], 401);
        }

        $user = User::findOrFail($id, ['id', 'nickname', 'photo', 'first_name', 'last_name', 'created_at']);
        $athlete = [
            'id' => $user->id,
            'nickname' => $user->getNickname(),
            'photo' => $user->photo,
            'firstName' => $user->first_name,
            'lastName' => $user->last_name,
            'createdAt' => $user->created_at,
        ];

        if (empty($athlete)) {
            return response()->json([
                'success' => false,
                'message' => __('Athlete not found')
            ], 404);
        }

        $athlete['activities'] = $user->activities->count();
        $athlete['subscriptions'] = $user->subscriptions->count();
        $athlete['subscribers'] = $user->subscribers->count();
        $athlete['isSubscribed'] = $user->isSubscriber($user);

        return response()->json([
            'success' => true,
            'athlete' => $athlete,
        ]);
    }

    protected function subscribe(int $id): JsonResponse
    {
        $check = Subscription::where(['user_id' => $id, 'subscriber_id' => auth()->id()])->first();
        if ($check !== null) {
            match ($check->confirmed) {
                false => $message = __('The user must confirm your subscription request.'),
                true => $message = __('Already subscribed'),
            };

            return response()->json([
                'success' => false,
                'message' => $message
            ]);
        }

        $user = User::find($id);
        if ($user === null) {
            return response()->json([
                'success' => false,
                'message' => __('User not found')
            ]);
        }

        $subscription = new Subscription();
        $subscription->user_id = $id;
        $subscription->subscriber_id = auth('sanctum')->id();
        $subscription->confirmed = !$user->private;
        $result = $subscription->save();

        if ($result) {
            match ($user->private) {
                true => $message = __('Subscription request sent'),
                false => $message = __('Successfully subscribed to ":user"', ['user' => $user->getFullName()])
            };

            return response()->json([
                'success' => true,
                'message' => $message,
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => __('Subscription failed'),
        ]);
    }
}
