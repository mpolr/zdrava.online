<?php

namespace App\Jobs;

use App\Classes\GpxTools;
use App\Models\Segment;
use App\Models\StravaToken;
use ASanikovich\LaravelSpatial\Geometry\Point;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Strava;

class ImportStravaSegments implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public int $tries = 3;
    public int $timeout = 30;

    protected int $userId;
    protected Segment $segment;

    public function __construct(int $userId, Segment $segment)
    {
        $this->userId = $userId;
        $this->segment = $segment;
    }

    public function handle(): bool
    {
        // TODO: Вынести получение SavedToken вовне и передавать его в конструктор чтобы не дёргать БД каждый раз
        $savedToken = StravaToken::where('user_id', $this->userId)->first();
        if (empty($savedToken)) {
            $this->fail('No Strava token found!');
            return false;
        }

        // TODO: Проверку оставляем тут
        if ($savedToken->expires_at < Carbon::now()->toDateTimeString()) {
            $refresh = Strava::refreshToken($savedToken->refresh_token);
            $savedToken->access_token = $refresh->access_token;
            $savedToken->refresh_token = $refresh->refresh_token;
            $savedToken->save();
        }

        try {
            $segmentData = Strava::segment($savedToken->access_token, $this->segment->strava_segment_id);
        } catch (\Exception $e) {
            switch ($e->getCode()) {
                case 429: // 15 minutes limit
                    $this->delete();
                    return false;
                case 403: // Dayly limit
                    $this->delete();
                    return false;
                default:
                    $this->fail($e->getMessage());
                    return false;
            }
        }

        $limits = Strava::getApiLimits();
        if (!empty($limits)) {
            if ($limits['usage']['daily'] >= 1000) {
                $this->delete();
                return false;
            }

            if ($limits['usage']['15minutes'] >= 100) {
                $this->delete();
                return false;
            }
        }

        if (empty($segmentData)) {
            $this->delete();
            return false;
        }

        if (str_starts_with($segmentData->name, 'OLD VERSION')) {
            $this->delete();
            return false;
        }

        $this->segment->activity_type = $segmentData->activity_type;
        $this->segment->name = $segmentData->name;
        $this->segment->distance = $segmentData->distance;
        if (!empty($segmentData->total_elevation_gain)) {
            $this->segment->total_elevation_gain = $segmentData->total_elevation_gain;
        }
        $this->segment->start_latlng = new Point($segmentData->start_latlng[0], $segmentData->start_latlng[1]);
        $this->segment->end_latlng = new Point($segmentData->end_latlng[0], $segmentData->end_latlng[1]);
        if (!empty($segmentData->xoms->kom)) {
            $this->segment->kom = GpxTools::stravaTimeToSeconds($segmentData->xoms->kom);
        }
        if (!empty($segmentData->xoms->qom)) {
            $this->segment->qom = GpxTools::stravaTimeToSeconds($segmentData->xoms->qom);
        }
        $this->segment->private = $segmentData->private;
        $this->segment->hazardous = $segmentData->hazardous;
        $this->segment->polyline = $segmentData->map->polyline;
        $this->segment->star_count = $segmentData->star_count;
        if (!empty($segmentData->country)) {
            $this->segment->country = $segmentData->country;
        }
        if (!empty($segmentData->state)) {
            $this->segment->state = $segmentData->state;
        }
        if (!empty($segmentData->city)) {
            $this->segment->city = $segmentData->city;
        }
        if (!empty($segmentData->climb_category)) {
            $this->segment->climb_category = $segmentData->climb_category;
        }
        if (!empty($segmentData->average_grade)) {
            $this->segment->average_grade = $segmentData->average_grade;
        }
        if (!empty($segmentData->maximum_grade)) {
            $this->segment->maximum_grade = $segmentData->maximum_grade;
        }
        if (!empty($segmentData->elevation_high)) {
            $this->segment->elevation_high = $segmentData->elevation_high;
        }
        if (!empty($segmentData->elevation_low)) {
            $this->segment->elevation_low = $segmentData->elevation_low;
        }
        $this->segment->created_at = $segmentData->created_at;
        $this->segment->updated_at = $segmentData->updated_at;

        $this->segment->save();

        $this->delete();
        return true;
    }
}
