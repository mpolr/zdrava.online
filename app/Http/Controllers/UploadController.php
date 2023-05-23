<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Storage;

class UploadController extends Controller
{
    public function avatar(Request $request): string
    {
        $this->validate($request, [
            'avatar' => 'required|file|mimes:jpg,jpeg,png|max:2048',
        ]);

        $file = $request->file('avatar');
        $fileName = $file->hashName();
        $file->move(public_path('pictures/athletes/' . $request->user()->id), $fileName);

        $user = $request->user();
        if ($user->photo) {
            unlink(public_path('pictures/athletes/' . $request->user()->id . '/' . $user->photo));
        }
        $user->photo = $fileName;
        $user->save();

        session()->flash('success', __('File Upload successfully'));

        return redirect()->back();
    }

    public function workout(Request $request): string
    {
        // TODO
    }
}
