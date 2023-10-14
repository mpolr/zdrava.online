<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class ApiController extends Controller
{
    protected function athlete(int $id): JsonResponse
    {
        $user = auth('sanctum')->user();

        if (empty($user)) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 401);
        }

        $athlete = User::findOrFail($id, ['id', 'nickname', 'photo', 'first_name', 'last_name', 'created_at']);

        if (empty($athlete)) {
            return response()->json([
                'success' => false,
                'message' => 'Athlete not found'
            ], 404);
        }

        return response()->json(['athlete' => $athlete]);
    }
}
