<?php

namespace App\Http\Controllers;

use phpGPX\phpGPX;
use Illuminate\Http\Request;
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
        // TODO: Удалить XML и сделать кастомную валидацию
        // https://stackoverflow.com/questions/67958399/laravel-change-mime-type-of-a-file-on-the-request-object
        $this->validate($request, [
            'workout' => 'required|file|mimes:gpx,fit,tcx,xml|max:25000',
        ]);

        $files = $request->allFiles();

        foreach ($files as $uploadedFile) {
            $file = Storage::putFileAs(
                'public/activities/' . $request->user()->id,
                $uploadedFile,
                $uploadedFile->hashName() . '.gpx'
            );

            $gpx = new phpGPX();
//            $gpx = $gpx->parse(Storage::get($file));
//
//            $md = $gpx->creator;
//                die(print_r($md));
//            foreach ($gpx->tracks as $track) {
//                // Statistics for whole track
//                $statsTrack = $track->stats->toArray();
//
//                foreach ($track->segments as $segment) {
//                    // Statistics for segment of track
//                    $statsSegment = $segment->stats->toArray();
//                }
//            }
        }

        session()->flash('success', __('File Upload successfully'));
        return redirect()->back();
    }
}
