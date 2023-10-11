<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreWorkoutRequest;
use App\Jobs\ProcessFitFile;
use App\Jobs\ProcessGpxFile;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;

class UploadController extends Controller
{
    public function workout(StoreWorkoutRequest $request): RedirectResponse|JsonResponse
    {
        $result = false;

        try {
            $user = $request->user();
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 401);
        }

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
                        ProcessGpxFile::dispatch($user->id, $hashedFileName)->onQueue('process-gpx');
                        $result = true;
                        break;
                    case 'fit':
                        ProcessFitFile::dispatch($user->id, $hashedFileName)->onQueue('process-fit');
                        $result = true;
                        break;
                    case 'tcx':
                        $result = $this->processTCX($file, $request, $hashedFileName);
                        break;
                    default:
                        session()->flash('error', __('Unsupported file!'));
                        return redirect()->refresh();
                }
            }
        }

        if ($result) {
            session()->flash('success', __('File Upload successfully'));
        } else {
            session()->flash('error', __('Error occurred'));
        }

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Upload success'
            ]);
        } else {
            return redirect()->refresh();
        }
    }

    private function processTCX(string $file, StoreWorkoutRequest $request, string $filename): bool
    {
        // TODO: Найти парсер TCX файлов
        return false;
    }
}
