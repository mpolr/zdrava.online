<?php

namespace App\Http\Controllers;

use App\Classes\GpxTools;
use App\Http\Requests\StoreWorkoutRequest;
use App\Jobs\ProcessFitFile;
use App\Jobs\ProcessGpxFile;
use App\Models\Activities;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use phpGPX\phpGPX;

class UploadController extends Controller
{
    public function upload(StoreWorkoutRequest $request): JsonResponse
    {
        try {
            $user = $request->user();
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => __('User not found')
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

            try {
                $gpx = phpGPX::load(Storage::path('temp/' . $hashedFileName));
            } catch (Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => __('Parsing error')
                ], 400);
            }

            if (empty($gpx->tracks) || count($gpx->tracks[0]->segments) === 0) {
                return response()->json([
                    'success' => false,
                    'message' => __('Upload error. Empty GPX file')
                ], 400);
            }

            $maxSpeed = 0.0;
            $maxHearthRate = 0;
            $totalMovingTime = 0;
            $statsTrack = [];
            $totalDistance = 0.0;

            foreach ($gpx->tracks as $track) {
                $statsTrack[] = $track->stats->toArray();

                foreach ($track->segments as $segment) {
                    $points = $segment->getPoints();

                    for ($i = 0; $i < count($points) - 1; $i++) {
                        $point1 = $points[$i];
                        $point2 = $points[$i + 1];

                        // Проверяем, двигается ли точка
                        if (!empty($point1->extensions->trackPointExtension)) {
                            if ($point1->extensions->trackPointExtension->speed > 0) {
                                $movingTime = $point2->time->getTimestamp() - $point1->time->getTimestamp();
                                $totalMovingTime += $movingTime;
                                if ($point1->extensions->trackPointExtension->speed > $maxSpeed) {
                                    $maxSpeed = $point1->extensions->trackPointExtension->speed;
                                }
                            }

                            if ($point1->extensions->trackPointExtension->hr > $maxHearthRate) {
                                $maxHearthRate = $point1->extensions->trackPointExtension->hr;
                            }
                        } else {
                            if (!empty($point1->distance)) {
                                $totalDistance = $point1->distance;
                            }
                            $timeDifference = $point2->time->getTimestamp() - $point1->time->getTimestamp();
                            if ($timeDifference > 0) {
                                $speed = $point2->difference / $timeDifference;
                                if ($speed > $maxSpeed) {
                                    $maxSpeed = $speed;
                                }
                            }
                        }
                    }
                }
            }

            $stat = array_merge_recursive($statsTrack)[0];

            if ($totalMovingTime === 0) {
                $totalMovingTime = $stat['duration'];
            }

            $segment = $gpx->tracks[0]->segments[0];
            $startPoint = $segment->points[0];
            $endPoint = $segment->points[array_key_last($segment->points)];
            $startLatitude = $startPoint->latitude;
            $startLongitude = $startPoint->longitude;
            $endLatitude = $endPoint->latitude;
            $endLongitude = $endPoint->longitude;

            $activity = new Activities();
            $activity->user_id = $user->id;
            $activity->name = !empty($gpx->tracks[0]->name) ? $gpx->tracks[0]->name : __('Workout');
            if (!empty($gpx->creator)) {
                $activity->creator = $gpx->creator;
            }
            if (key_exists('distance', $stat)) {
                $totalDistance = $stat['distance'];
            }
            $distance = explode('.', $totalDistance)[0];
            $kilometers = floor($distance / 1000);
            $meters = substr(round($distance % 1000), 0, 2);
            $distance = (float)($kilometers . '.' . $meters);
            $activity->distance = $distance;
            $activity->avg_speed = $stat['avgSpeed'];
            if ($maxSpeed === 0) {
                $maxSpeed = $stat['avgSpeed'] * 3.6;
            } else {
                $maxSpeed = $maxSpeed * 3.6;
            }
            $activity->max_speed = $maxSpeed;
            $activity->avg_pace = $stat['avgPace'];
            $activity->min_altitude = $stat['minAltitude'];
            $activity->max_altitude = $stat['maxAltitude'];
            $activity->elevation_gain = $stat['cumulativeElevationGain'];
            $activity->elevation_loss = $stat['cumulativeElevationLoss'];
            $activity->started_at = $stat['startedAt'];
            $activity->finished_at = $stat['finishedAt'];
            $activity->duration = (int)$totalMovingTime;
            $activity->duration_total = $stat['duration'];
            // TODO: Сделать avg_heart_rate, avg_cadence, max_cadence, total_calories
            $activity->avg_heart_rate = 0;
            $activity->max_heart_rate = $maxHearthRate;
            $activity->avg_cadence = 0;
            $activity->max_cadence = 0;
            $activity->total_calories = 0;
            $activity->file = $hashedFileName;
            $activity->start_position_lat = $startLatitude;
            $activity->start_position_long = $startLongitude;
            $activity->end_position_lat = $endLatitude;
            $activity->end_position_long = $endLongitude;

            $image = GpxTools::generateImageFromGPX($hashedFileName, $user->id, true);
            if (!empty($image)) {
                $activity->image = $hashedFileName . '.png';
            }

            $geo = GpxTools::geocode($activity->start_position_lat, $activity->start_position_long);
            if ($geo) {
                $activity->country = $geo['country'];
                $activity->locality = $geo['locality'];
            }

            $result = rename(
                Storage::path('temp/' . $hashedFileName),
                Storage::path('public/activities/' . $user->id . '/' . $hashedFileName)
            );

            if (!$result) {
                return response()->json([
                    'success' => false,
                    'message' => __('Upload error')
                ], 400);
            }

            $activity->save();
        }

        return response()->json([
            'success' => true,
            'message' => __('Upload success')
        ]);
    }

    public function workout(StoreWorkoutRequest $request, bool $formApp = false): RedirectResponse|JsonResponse
    {
        $result = false;

        try {
            $user = $request->user();
        } catch (\Exception $e) {
            if ($formApp) {
                return response()->json([
                    'success' => false,
                    'message' => __('User not found')
                ], 401);
            } else {
                abort(401);
            }
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

        if ($formApp) {
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
