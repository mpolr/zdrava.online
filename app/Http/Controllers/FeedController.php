<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Faker;

class FeedController extends Controller
{
    protected function getFeed(): JsonResponse
    {
        $faker = Faker\Factory::create('ru_RU');

        $feedItems = [];
        for ($i = 1; $i <= 10; $i++) {
            $feedItems[] = [
                'id' => $i,
                'title' => "Прокатился в {$faker->city}",
                'subtitle' => "Было круто {$faker->emoji}",
                'imageUrl' => 'https://mpolr.ru/images/zdrava-ride.jpg',
                'username' => $faker->name,
                'timestamp' => '2023',
                'commentsCount' => $faker->unique()->randomDigit,
                'likesCount' => 0,
                'sharesCount' => 0,
            ];
        }

        return response()->json($feedItems);
    }
}
