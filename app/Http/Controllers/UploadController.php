<?php

namespace App\Http\Controllers;

use App\Classes\GpxTools;
use App\Http\Requests\StoreWorkoutRequest;
use App\Jobs\ProcessFitFile;
use App\Models\Activities;
use App\Models\User;
use DateTime;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use phpGPX\phpGPX;
use Illuminate\Support\Facades\Storage;
use Throwable;

class UploadController extends Controller
{
    public function workout(StoreWorkoutRequest $request): RedirectResponse
    {
        $result = false;

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
                        $result = $this->processGPX($file, $request, $hashedFileName);
                        break;
                    case 'fit':
                        ProcessFitFile::dispatch($request->user()->id, $hashedFileName)->onQueue('process-fit');
                        $result = true;
                        break;
                    case 'tcx':
                        $result = $this->processTCX($file, $request, $hashedFileName);
                        break;
                    default:
                        session()->flash('error', __('Unsupported file!'));
                        return redirect()->refresh();
                        break;
                }
            }
        }

        if ($result) {
            session()->flash('success', __('File Upload successfully'));
        } else {
            session()->flash('error', __('Error occurred'));
        }

        return redirect()->refresh();
    }

    public function workoutApi(Request $request): JsonResponse
    {
        $result = false;
        $user = $request->user();

        if (empty($user)) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 401);
        }

        foreach ($request->allFiles()['workout'] as $uploadedFile) {
            $name = $uploadedFile->hashName();
            $name = str_replace(['.xml', '.bin'], '', $name);
            $extension = $uploadedFile->getClientOriginalExtension();

            $uploadedFileName = "{$name}.{$extension}";

            $file = Storage::putFileAs(
                'public/activities/' . $user->id,
                $uploadedFile,
                $uploadedFileName,
                'public'
            );

            $result = true;

            if (!empty($file)) {
                $result = $this->processGPXApi($user, $file, $uploadedFileName);
            }
        }

        if ($result) {
            return response()->json([
                'success' => true,
                'message' => 'Upload success'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Upload error'
            ], 400);
        }
    }

    private function processGPX(string $file, StoreWorkoutRequest $request, string $filename): bool
    {
        $gpx = new phpGPX();
        try {
            $gpx = $gpx->parse(Storage::get($file));
        } catch (Throwable $e) {
            Storage::delete($file);
            report($e);
            return false;
        }

        $elevation = 0;
        $maxSpeed = 0;

        foreach ($gpx->tracks as $track) {
            // Statistics for whole track
            $statsTrack[] = $track->stats->toArray();

            foreach ($track->segments as $segment) {
                $maxSpeed = 0;
                $pointElevation = 0;
                $pointElevationMin = 0;
                $pointElevationMax = 0;

                foreach ($segment->points as $point) {
                    $pointSpeed = 0;
                    if ($point->elevation > $pointElevation) {
                        $pointElevation += $point->elevation;
                    }

                    if (!empty($point->extensions->trackPointExtension->speed)) {
                        $pointSpeed = $point->extensions->trackPointExtension->speed;
                    }

//                    dd($point);

                    if ($pointSpeed > $maxSpeed) {
                        $maxSpeed = $pointSpeed;
                        dd($point);
                    }
                }

                $elevation += $pointElevation;
            }
        }

        $stat = array_merge_recursive($statsTrack)[0];
//        dd($maxSpeed);

        $startedAt = new DateTime($stat['startedAt']);

        $finishedAt = new DateTime($stat['finishedAt']);
        $interval = $startedAt->diff($finishedAt);
//        dd($interval);
        $totalDuration = $interval->s + ($interval->i * 60) + ($interval->h * 3600) + ($interval->days * 86400);

        $segment = $track->segments[0];
        $startPoint = $segment->points[0];
        $endPoint = $segment->points[count($segment->points) - 1];
        $startLatitude = $startPoint->latitude;
        $startLongitude = $startPoint->longitude;
        $endLatitude = $endPoint->latitude;
        $endLongitude = $endPoint->longitude;

        $activity = new Activities();
        $activity->user_id = $request->user()->id;
        $activity->name = !empty($gpx->tracks[0]->name) ? $gpx->tracks[0]->name : __('Workout');

        $distance = explode('.', $stat['distance'])[0];
        $kilometers = floor($distance / 1000);
        $meters = substr(round($distance % 1000), 0, 2);
        $distance = $kilometers . '.' . $meters;

        $activity->distance = $distance;
        $activity->avg_speed = $stat['avgSpeed'];
        $activity->max_speed = $maxSpeed;
        $activity->avg_pace = $stat['avgPace'];
        $activity->min_altitude = $stat['minAltitude'];
        $activity->max_altitude = $stat['maxAltitude'];
        $activity->elevation_gain = $elevation;
        $activity->elevation_loss = $stat['cumulativeElevationLoss'];
        $activity->started_at = $stat['startedAt'];
        $activity->finished_at = $stat['finishedAt'];
        $activity->duration = $stat['duration'];
        $activity->duration_total = $totalDuration;
        // TODO: Сделать avg_heart_rate, max_heart_rate, avg_cadence, max_cadence, total_calories
        $activity->avg_heart_rate = 0;
        $activity->max_heart_rate = 0;
        $activity->avg_cadence = 0;
        $activity->max_cadence = 0;
        $activity->total_calories = 0;
        $activity->file = $filename;

        $image = GpxTools::generateImageFromGPX($file, $request->user()->id);
        if (!empty($image)) {
            $activity->image = $filename.'.png';
        }

        $activity->start_position_lat = $startLatitude;
        $activity->start_position_long = $startLongitude;
        $activity->end_position_lat = $endLatitude;
        $activity->end_position_long = $endLongitude;
        $geo = GpxTools::geocode($activity->start_position_lat, $activity->start_position_long);
        if ($geo) {
            $activity->country = $geo['country'];
            $activity->locality = $geo['locality'];
        }

        $activity->save();

        return true;
    }

    private function processTCX(string $file, StoreWorkoutRequest $request, string $filename): bool
    {
        // TODO: Найти парсер TCX файлов
        return true;
    }

    private function processGPXApi(User $user, string $file, string $filename): bool
    {
        $gpx = new phpGPX();
        try {
            $gpx = $gpx->parse(Storage::get($file));
        } catch (Throwable $e) {
            Storage::delete($file);
            report($e);
            return false;
        }

        $elevation = 0;
        $maxSpeed = 0;

        foreach ($gpx->tracks as $track) {
            // Statistics for whole track
            $statsTrack[] = $track->stats->toArray();

            foreach ($track->segments as $segment) {
                $pointElevation = 0;

                foreach ($segment->points as $point) {
                    $pointSpeed = 0;
                    if ($point->elevation > $pointElevation) {
                        $pointElevation += $point->elevation;
                    }

                    if (!empty($point->extensions->speed)) {
                        $pointSpeed = $point->extensions->speed; // Получаем скорость точки
                    }

                    if ($pointSpeed > $maxSpeed) {
                        $maxSpeed = $pointSpeed;
                    }
                }

                $elevation += $pointElevation;
            }
        }

        $stat = array_merge_recursive($statsTrack)[0];

        $startedAt = new DateTime($stat['startedAt']);
        $finishedAt = new DateTime($stat['finishedAt']);
        $interval = $startedAt->diff($finishedAt);
        $totalDuration = $interval->s + ($interval->i * 60) + ($interval->h * 3600) + ($interval->days * 86400);

        $segment = $track->segments[0];
        $startPoint = $segment->points[0];
        $endPoint = $segment->points[count($segment->points) - 1];
        $startLatitude = $startPoint->latitude;
        $startLongitude = $startPoint->longitude;
        $endLatitude = $endPoint->latitude;
        $endLongitude = $endPoint->longitude;

        $activity = new Activities();
        $activity->user_id = $user->id;
        $activity->name = !empty($gpx->tracks[0]->name) ? $gpx->tracks[0]->name : __('Workout');

        $distance = explode('.', $stat['distance'])[0];
        $kilometers = floor($distance / 1000);
        $meters = substr(round($distance % 1000), 0, 2);
        $distance = $kilometers . '.' . $meters;

        $activity->distance = $distance;
        $activity->avg_speed = $stat['avgSpeed'];
        $activity->max_speed = $maxSpeed;
        $activity->avg_pace = $stat['avgPace'];
        $activity->min_altitude = $stat['minAltitude'];
        $activity->max_altitude = $stat['maxAltitude'];
        $activity->elevation_gain = $elevation;
        $activity->elevation_loss = $stat['cumulativeElevationLoss'];
        $activity->started_at = $stat['startedAt'];
        $activity->finished_at = $stat['finishedAt'];
        $activity->duration = $stat['duration'];
        $activity->duration_total = $totalDuration;
        // TODO: Сделать avg_heart_rate, max_heart_rate, avg_cadence, max_cadence, total_calories
        $activity->avg_heart_rate = 0;
        $activity->max_heart_rate = 0;
        $activity->avg_cadence = 0;
        $activity->max_cadence = 0;
        $activity->total_calories = 0;
        $activity->file = $filename;

        $image = GpxTools::generateImageFromGPX($file, $user->id);
        if (!empty($image)) {
            $activity->image = $filename.'.png';
        }

        $activity->start_position_lat = $startLatitude;
        $activity->start_position_long = $startLongitude;
        $activity->end_position_lat = $endLatitude;
        $activity->end_position_long = $endLongitude;
        $geo = GpxTools::geocode($activity->start_position_lat, $activity->start_position_long);
        if ($geo) {
            $activity->country = $geo['country'];
            $activity->locality = $geo['locality'];
        }

        $activity->save();

        return true;
    }
}
