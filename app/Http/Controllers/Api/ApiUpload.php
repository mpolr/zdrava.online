<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ApiUpload
{
    public function avatar(Request $request): \Illuminate\Http\JsonResponse
    {
        if (empty($request->file('avatar'))) {
            return response()->json([
                'success' => false,
                'message' => 'Empty photo',
            ]);
        }

        $request->validate([
            'avatar' => 'required|image|max:1024',
        ]);

        $uploadedFile = $request->file('avatar');

        $user = User::find(auth()->id());

        $fileName = $uploadedFile->hashName();
        $uploadedFile->storePubliclyAs('pictures/athletes/' . $user->id, $fileName, 'public');

        if (!empty($user->photo)) {
            Storage::delete('public/pictures/athletes/' . $user->id . '/' . $user->photo);
        }

        $user->photo = $fileName;
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Photo updated',
            'url' => $user->getPhotoUrl(),
        ]);
    }
}
