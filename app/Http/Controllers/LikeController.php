<?php

namespace App\Http\Controllers;

use App\Http\Requests\LikeRequest;
use App\Http\Requests\UnlikeRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

class LikeController extends Controller
{
    public function like(LikeRequest $request): JsonResponse|RedirectResponse
    {
        $request->user()->like($request->likeable());

        if ($request->ajax()) {
            return response()->json([
                'likes' => $request->likeable()->likes()->count(),
            ]);
        }

        return redirect()->back();
    }

    public function unlike(UnlikeRequest $request): JsonResponse|RedirectResponse
    {
        $request->user()->unlike($request->likeable());

        if ($request->ajax()) {
            return response()->json([
                'likes' => $request->likeable()->likes()->count(),
            ]);
        }

        return redirect()->back();
    }
}
