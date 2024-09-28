<?php

namespace App\Jobs;

use App\Classes\GpxTools;
use App\Models\Activities;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use phpGPX\phpGPX;
use Psr\Log\LogLevel;

class ProcessGpxFile implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    protected int $userId;
    protected string $fileName;
    public int $timeout = 180;

    public function __construct(int $userId, string $fileName)
    {
        $this->userId = $userId;
        $this->fileName = $fileName;
    }

    /**
     * @throws Exception
     */
    public function handle(): void
    {
        $gpxObj = new phpGPX();
        $gpxObj::$APPLY_ELEVATION_SMOOTHING = true;
        $gpxObj::$ELEVATION_SMOOTHING_THRESHOLD = 1;
        $gpxObj::$ELEVATION_SMOOTHING_SPIKES_THRESHOLD = 16;
//        $gpxObj::$APPLY_DISTANCE_SMOOTHING = true;
        $gpxObj::$DISTANCE_SMOOTHING_THRESHOLD = 3;

        try {
            $gpx = $gpxObj::load(Storage::path('temp/' . $this->fileName));
        } catch (Exception $e) {
            Log::channel('telegram')->log(LogLevel::ERROR, "User: {$this->userId}; {$e->getMessage()}");
            $this->fail($e->getMessage());
        }

        if (empty($gpx->tracks) || count($gpx->tracks[0]->segments) === 0) {
            $this->fail('Empty GPX file!');
        }

        $maxSpeed = 0.0;
        $maxHearthRate = 0;
        $totalMovingTime = 0;
        $statsTrack = [];
        $totalDistance = 0.0;
        $locations['locations'] = [];

        foreach ($gpx->tracks as $track) {
            $statsTrack[] = $track->stats->toArray();

            foreach ($track->segments as $segment) {
                $points = $segment->getPoints();

                for ($i = 0; $i < count($points) - 1; $i++) {
                    $point1 = $points[$i];
                    $point2 = $points[$i + 1];

                    $locations['locations'][] = [
                        'latitude' => $point1->latitude,
                        'longitude' => $point1->longitude
                    ];

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

        $locationsJSON = json_encode($locations, JSON_THROW_ON_ERROR);
        $response = Http::withBody($locationsJSON)
            ->post('http://openelevation/api/v1/lookup');

        if ($response->successful() && $response->status() === 200) {
            $results = $response->json()['results'];

            $i = 0;
            foreach ($gpx->tracks as $track) {
                foreach ($track->segments as $segment) {
                    foreach ($segment->points as $point) {
                        if (array_key_exists($i, $results)) {
                            $point->elevation = $results[$i]['elevation'];
                            $i++;
                        }
                    }
                }

                $track->recalculateStats();
            }
        } else {
            Log::channel('telegram')->log(LogLevel::ERROR, auth()->user()->getFullName() . " - Open elevation API: Error {$response->status()} '{$response->reason()}'");
        }

        $statsTrack[0] = $track->segments[0]->stats->toArray();
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
        $activity->user_id = $this->userId;
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
            $maxSpeed *= 3.6;
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
        $activity->file = $this->fileName;
        $activity->start_position_lat = $startLatitude;
        $activity->start_position_long = $startLongitude;
        $activity->end_position_lat = $endLatitude;
        $activity->end_position_long = $endLongitude;

        $image = GpxTools::generateImageFromGPX($this->fileName, $this->userId, true);
        if (!empty($image)) {
            $activity->image = $this->fileName . '.png';
        }

        $geo = GpxTools::geocode($activity->start_position_lat, $activity->start_position_long);
        if ($geo) {
            $activity->country = $geo['country'];
            $activity->locality = $geo['locality'];
        }

        $result = rename(
            Storage::path('temp/' . $this->fileName),
            Storage::path('public/activities/' . $this->userId . '/' . $this->fileName)
        );

        if (!$result) {
            $this->fail('Cant move GPX file!');
        } else {
            $activity->save();
        }
    }
}
