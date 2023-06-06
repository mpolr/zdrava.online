<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Faker;
use Illuminate\Http\Request;

class FeedController extends Controller
{
    protected function feed(Request $request): JsonResponse
    {
        $faker = Faker\Factory::create('ru_RU');

        $token = $request->get('token');
        $user = User::where('remember_token', $token)->first();

        if (empty($user)) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ]);
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
                'username' => $user->getFullName(),
                'distance' => $activity->distance,
                'avg_speed' => $activity->avg_speed,
                'elevation_gain' => $activity->elevation_gain,
                'started_at' => $activity->started_at,
                'commentsCount' => $faker->unique()->randomDigit,
                'likesCount' => 0,
                'sharesCount' => 0,
            ];
        }

        return response()->json(['activities' => $feedItems]);
    }
}
