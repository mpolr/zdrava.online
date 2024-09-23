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

    /**
     * @throws \Exception
     */
    protected function subscribe(int $id): JsonResponse
    {
        $user = User::find($id);
        if ($user === null) {
            return response()->json([
                'success' => false,
                'message' => __('User not found')
            ]);
        }

        $isSubscriber = auth()->user()->isSubscriber($user);
        if ($isSubscriber) {
            $confirmed = $user->isSubscriptionConfirmed(auth()->user());

            match ($confirmed) {
                false => $message = __('The user must confirm your subscription request.'),
                true => $message = __('You already subscribed to this user'),
            };

            return response()->json([
                'success' => false,
                'message' => $message
            ]);
        }

        $subscription = new Subscription();
        $subscription->user_id = $id;
        $subscription->subscriber_id = auth()->id();
        $subscription->confirmed = !$user->private;
        $result = $subscription->save();

        if ($result) {
            switch ($user->private) {
                case true:
                    $message = __("Subscription request sent");
                    $user->notify(new \App\Notifications\NewSubscriptionRequest(auth()->user()->id));
                    break;
                case false:
                    $message = __("Successfully subscribed to ':user'", ['user' => $user->getFullName()]);
                    $user->notify(new \App\Notifications\NewSubscriber);
                    break;
            }

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
