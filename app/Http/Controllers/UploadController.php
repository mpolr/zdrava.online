<?php

namespace App\Http\Controllers;

use App\Classes\GpxTools;
use App\Http\Requests\StoreWorkoutRequest;
use App\Jobs\ProcessFitFile;
use App\Jobs\ProcessGpxFile;
use App\Models\Activities;
use App\Models\Photo;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use phpGPX\phpGPX;

class UploadController extends Controller
{
    public function workout(StoreWorkoutRequest $request): RedirectResponse|JsonResponse
    {
        $formApp = $request->header('Zdrava-app', false);

        $result = false;

        try {
            $user = $request->user();
        } catch (\Exception $e) {
            if ($formApp) {
                return response()->json([
                    'success' => false,
                    'message' => __('User not found')
                ], 401);
            }

            abort(401);
        }

        $activityId = $request->get('activity_id');
        $activity = Activities::find($activityId);
        if (!$activityId || !$activity) {
            $activity = new Activities();
            $activity->user_id = $user->id;
            $activity->status = Activities::PENDING;
        }

        if ($request->files->all('workout')) {
            $activity->save();
            foreach ($request->allFiles()['workout'] as $uploadedFile) {
                $name = $uploadedFile->hashName();
                $name = str_replace(['.xml', '.bin'], '', $name);
                $extension = $uploadedFile->getClientOriginalExtension();

                $hashedFileName = "{$name}.{$extension}";

                $file = Storage::putFileAs(
                    'temp',
                    $uploadedFile,
                    $hashedFileName,
                    'private'
                );

                if (!empty($file)) {
                    switch ($extension) {
                        case 'gpx':
                            ProcessGpxFile::dispatch($user->id, $hashedFileName, $activity->id)->onQueue('process-gpx');
                            Artisan::call('queue:work', [
                                '--queue' => 'process-gpx',
                                '--stop-when-empty' => true,
                            ]);
                            $result = true;
                            break;
                        case 'fit':
                            ProcessFitFile::dispatch($activity, $hashedFileName)->onQueue('process-fit');
                            Artisan::call('queue:work', [
                                '--queue' => 'process-fit',
                                '--stop-when-empty' => true,
                            ]);
                            $result = true;
                            break;
                        case 'tcx':
                            $result = $this->processTCX($file, $request, $hashedFileName);
                            break;
                        default:
                            $activity->delete();
                            session()->flash('error', __('Unsupported file!'));
                            return redirect()->refresh();
                    }
                }
            }
        }

        if ($request->files->all('image')) {
            $activity->save();
            foreach ($request->allFiles()['image'] as $imageFile) {
                $name = $imageFile->hashName();

                $file = Storage::putFileAs(
                    'public/activities/' . $activity->user_id . '/images',
                    $imageFile,
                    $name,
                    'public'
                );

                if (!empty($file)) {
                    $photo = new Photo();
                    $photo->activities_id = $activity->id;
                    $photo->users_id = $user->id;
                    $photo->url = $name;
                    $photo->save();
                }

                $result = true;
            }
        }

        if ($result) {
            session()->flash('success', __('File Upload successfully'));
        } else {
            session()->flash('error', __('Error occurred'));
        }

        if ($formApp) {
            return response()->json([
                'success' => true,
                'message' => 'Upload success'
            ]);
        }

        return redirect()->refresh();
    }

    private function processTCX(string $file, StoreWorkoutRequest $request, string $filename): bool
    {
        // TODO: Найти парсер TCX файлов
        return false;
    }
}
