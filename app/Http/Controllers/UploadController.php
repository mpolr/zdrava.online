<?php

namespace App\Http\Controllers;

use adriangibbons\phpFITFileAnalysis;
use App\Http\Requests\StoreWorkoutRequest;
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

            $uploadedFileName = "{$name}.{$extension}";

            $file = Storage::putFileAs(
                'public/activities/' . $request->user()->id,
                $uploadedFile,
                $uploadedFileName,
                'public'
            );

            if (!empty($file)) {
                switch ($extension) {
                    case 'gpx':
                        $result = $this->processGPX($file, $request, $uploadedFileName);
                        break;
                    case 'fit':
                        $result = $this->processFIT($file, $request, $uploadedFileName);
                        break;
                    case 'tcx':
                        $result = $this->processTCX($file, $request, $uploadedFileName);
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

    /**
     * @throws \Exception
     */
    private function processFIT(string $file, StoreWorkoutRequest $request, string $filename): bool
    {
        // TODO: Рассчитать avg_pace, min_altitude, max_altitude
        try {
            $fit = new phpFITFileAnalysis(Storage::path($file));
        } catch (Throwable $e) {
            Storage::delete($file);
            report($e);
            return false;
        }

        $activity = new Activities();
        $activity->user_id = $request->user()->id;
        $activity->sport = $fit->data_mesgs['sport']['sport'];
        $activity->sub_sport = $fit->data_mesgs['sport']['sub_sport'];
        $activity->name = !empty($fit->data_mesgs['sport']['name']) ? $fit->data_mesgs['sport']['name'] : __('Workout');
        $activity->creator = !empty($fit->data_mesgs['file_id']['manufacturer']) ? $fit->data_mesgs['file_id']['manufacturer'] : 'Zdrava';
        $activity->device_manufacturers_id = $fit->data_mesgs['file_id']['manufacturer'];
        $activity->distance = $fit->data_mesgs['session']['total_distance'];
        $activity->avg_speed = $fit->data_mesgs['session']['avg_speed'];
        $activity->max_speed = $fit->data_mesgs['session']['max_speed'];
        $activity->elevation_gain = $fit->data_mesgs['session']['total_ascent'];
        $activity->elevation_loss = $fit->data_mesgs['session']['total_descent'];
        $activity->started_at = $fit->data_mesgs['session']['start_time'];
        $activity->finished_at = $fit->data_mesgs['session']['start_time'] + $fit->data_mesgs['session']['total_elapsed_time'];
        $activity->duration = $fit->data_mesgs['session']['total_timer_time'];
        $activity->duration_total = $fit->data_mesgs['session']['total_elapsed_time'];
        $activity->avg_heart_rate = $fit->data_mesgs['session']['avg_heart_rate'];
        $activity->max_heart_rate = $fit->data_mesgs['session']['max_heart_rate'];
        $activity->avg_cadence = $fit->data_mesgs['session']['avg_cadence'];
        $activity->max_cadence = $fit->data_mesgs['session']['max_cadence'];
        $activity->total_calories = $fit->data_mesgs['session']['total_calories'];
        $activity->file = $filename;
        $activity->start_position_lat = $fit->data_mesgs['session']['start_position_lat'];
        $activity->start_position_long = $fit->data_mesgs['session']['start_position_long'];
        $activity->end_position_lat = last($fit->data_mesgs['lap']['end_position_lat']);
        $activity->end_position_long = last($fit->data_mesgs['lap']['end_position_long']);
        $geo = $this->geocode($activity->start_position_lat, $activity->start_position_long);
        $activity->country = $geo['country'];
        $activity->locality = $geo['locality'];

        $activity->save();

        $this->convertFitToGpx($fit, $request->user()->id, $filename);

        return true;
    }

    private function convertFitToGpx(phpFITFileAnalysis $fit, int $userId, string $filename): void
    {
        $rootNode = new \SimpleXMLElement('<?xml version="1.0" encoding="UTF-8" standalone="no"?>
            <gpx xmlns="http://www.topografix.com/GPX/1/1" version="1.1"></gpx>');
        $trkNode = $rootNode->addChild('trk');
        $trksegNode = $trkNode->addChild('trkseg');

        try {
            foreach ($fit->data_mesgs['record']['timestamp'] as $timestamp) {
                $trkptNode = $trksegNode->addChild('trkpt');
                $trkptNode->addAttribute('lat', $fit->data_mesgs['record']['position_lat'][$timestamp]);
                $trkptNode->addAttribute('lon', $fit->data_mesgs['record']['position_long'][$timestamp]);
                if (!empty($fit->data_mesgs['record']['altitude'][$timestamp])) {
                    $trkptNode->addChild('ele', $fit->data_mesgs['record']['altitude'][$timestamp]);
                }
                $trkptNode->addChild('time', date('Y-m-d\TH:i:s.000\Z', $timestamp));
            }

            Storage::write('public/activities/'. $userId .'/'. $filename .'.gpx', $rootNode->asXML());
        } catch (Throwable $e) {
            report($e);
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

        foreach ($gpx->tracks as $track) {
            // Statistics for whole track
            $statsTrack[] = $track->stats->toArray();

            foreach ($track->segments as $segment) {
                $maxSpeed = 0;

                foreach ($segment->points as $point) {
                    $speed = 0;
                    if (!empty($point->extensions->speed)) {
                        $speed = $point->extensions->speed; // Получаем скорость точки
                    }

                    if ($speed > $maxSpeed) {
                        $maxSpeed = $speed;
                    }
                }
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

//        die(print_r($gpx->tracks[0]));
//        die(print_r($stat));

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
        $activity->elevation_gain = $stat['cumulativeElevationGain'];
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
        $activity->start_position_lat = $startLatitude;
        $activity->start_position_long = $startLongitude;
        $activity->end_position_lat = $endLatitude;
        $activity->end_position_long = $endLongitude;
        $geo = $this->geocode($activity->start_position_lat, $activity->start_position_long);
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

        foreach ($gpx->tracks as $track) {
            // Statistics for whole track
            $statsTrack[] = $track->stats->toArray();

            foreach ($track->segments as $segment) {
                $maxSpeed = 0;

                foreach ($segment->points as $point) {
                    $speed = 0;
                    if (!empty($point->extensions->speed)) {
                        $speed = $point->extensions->speed; // Получаем скорость точки
                    }

                    if ($speed > $maxSpeed) {
                        $maxSpeed = $speed;
                    }
                }
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

//        die(print_r($gpx->tracks[0]));
//        die(print_r($stat));

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
        $activity->elevation_gain = $stat['cumulativeElevationGain'];
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
        $activity->start_position_lat = $startLatitude;
        $activity->start_position_long = $startLongitude;
        $activity->end_position_lat = $endLatitude;
        $activity->end_position_long = $endLongitude;
        $geo = $this->geocode($activity->start_position_lat, $activity->start_position_long);
        if ($geo) {
            $activity->country = $geo['country'];
            $activity->locality = $geo['locality'];
        }

        $activity->save();

        return true;
    }

    private function geocode($latitude, $longitude): ?array
    {
        try {
            $geo = app('geocoder')
                ->using('nominatim')
                ->reverse($latitude, $longitude)
                ->get()
                ->first();
        } catch (Throwable $e) {
            return null;
        }

        return [
            'country' => $geo->getCountry()->getCode(),
            'locality' => $geo->getLocality()
        ];
    }
}
