<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Activities;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ApiAthleteController extends Controller
{
    protected function athlete(int $id, Request $request): JsonResponse
    {
        $user = auth('sanctum')->user();

        if ($user === null) {
            return response()->json([
                'success' => false,
                'message' => __('User not found')
            ], 401);
        }

        $user = User::findOrFail($id, ['id', 'nickname', 'photo', 'first_name', 'last_name', 'created_at', 'private']);
        $athlete = [
            'id' => $user->id,
            'nickname' => $user->getNickname(),
            'photo' => $user->photo,
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'created_at' => $user->created_at,
            'private' => $user->private,
        ];

        if (empty($athlete)) {
            return response()->json([
                'success' => false,
                'message' => __('Athlete not found')
            ], 404);
        }

        $athlete['activities'] = $user->activities->count();
        $athlete['subscriptions'] = $user->confirmedSubscriptions()->count();
        $athlete['subscribers'] = $user->confirmedSubscribers()->count();
        $athlete['is_subscribed'] = $request->user()->isSubscriber($user);

        return response()->json([
            'success' => true,
            'athlete' => $athlete,
        ]);
    }

    protected function activities(int $id): JsonResponse
    {
        $user = User::find($id);
        if ($user === null) {
            return response()->json([
                'success' => false,
                'message' => __('User not found')
            ]);
        }

        $activities = Activities::where(static function ($query) use ($user) {
            $query->where('user_id', $user->id)
                ->where('status', Activities::DONE);
        })->orderBy('created_at', 'DESC')->get();

        if (empty($activities)) {
            return response()->json([
                'success' => false,
                'message' => __('Activity not found')
            ], 404);
        }

        return response()->json([
            'success' => true,
            'activities' => $activities,
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
                    $user->notify(new \App\Notifications\NewSubscriptionRequest(auth('sanctum')->user()));
                    break;
                case false:
                    $message = __("Successfully subscribed to ':user'", ['user' => $user->getFullName()]);
                    $user->notify(new \App\Notifications\NewSubscriber(auth('sanctum')->user()));
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

    protected function subscribeConfirm(int $id, Request $request): JsonResponse
    {
        $user = User::find($id);
        if ($user === null) {
            return response()->json([
                'success' => false,
                'message' => __('User not found')
            ]);
        }

        $subscription = Subscription::where([
            'subscriber_id' => $id,
            'user_id' => $request->user()->id,
        ])->first();

        if ($subscription === null) {
            return response()->json([
                'success' => false,
            ]);
        }

        $subscription->confirmed = true;
        $result = $subscription->save();

        if ($notificationId = $request->string('notificationId')) {
            $request->user()->markAsRead($notificationId);
        }

        if ($result) {
            return response()->json([
                'success' => true,
            ]);
        }
    }

    protected function subscribeDecline(int $id, Request $request): JsonResponse
    {
        $user = User::find($id);
        if ($user === null) {
            return response()->json([
                'success' => false,
                'message' => __('User not found')
            ]);
        }

        $subscription = Subscription::where([
            'subscriber_id' => $id,
            'user_id' => $request->user()->id,
        ])->first();

        if ($subscription === null) {
            return response()->json([
                'success' => false,
            ]);
        }

        $result = $subscription->delete();

        if ($notificationId = $request->string('notificationId')) {
            $request->user()->markAsRead($notificationId);
        }

        if ($result) {
            return response()->json([
                'success' => true,
            ]);
        }
    }

    protected function getSubscribers(int $id, Request $request): JsonResponse
    {
        $user = User::find($id);
        if ($user === null) {
            return response()->json([
                'success' => false,
                'message' => __('User not found')
            ]);
        }

        return response()->json([
            'success' => true,
            'athletes' => $user->confirmedSubscribers()
        ]);
    }

    protected function getSubscriptions(int $id, Request $request): JsonResponse
    {
        $user = User::find($id);
        if ($user === null) {
            return response()->json([
                'success' => false,
                'message' => __('User not found')
            ]);
        }

        return response()->json([
            'success' => true,
            'athletes' => $user->confirmedSubscriptions(),
        ]);
    }
}
