<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ApiUpdate
{
    public function check(Request $request): \Illuminate\Http\JsonResponse
    {
        $version = $request->string('version');
        $latestVersion = '0.3.1';

        if ($version === null) {
            return response()->json([
                'success' => false,
                'message' => 'Version not defined',
            ]);
        }

        $request->validate([
            'version' => 'required|string',
        ]);

        return response()->json([
            'success' => true,
            'version' => $latestVersion,
        ]);
    }
}
