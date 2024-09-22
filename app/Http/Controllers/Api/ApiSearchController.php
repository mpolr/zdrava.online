<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ApiSearchController extends Controller
{
    public function athletes(Request $request): JsonResponse
    {
        $request->validate([
            'query' => [
                'required',
                'string',
                'min:3',
                'max:255',
            ],
        ]);

        $query = $request->string('query');

        $searchResults = User::where('first_name', 'LIKE', "%{$query}%")
            ->orWhere('last_name', 'LIKE', "%{$query}%")
            ->orWhere('nickname', 'LIKE', "%{$query}%")
            ->orWhere('email', 'LIKE', "%{$query}%")
            ->limit(100)
            ->get();

        if ($searchResults->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => "No results found for {$query}",
            ]);
        }

        return response()->json([
            'success' => true,
            'athletes' => $searchResults,
        ]);
    }
}