<?php

namespace App\Jobs;

use App\Models\Segment;
use App\Models\StravaToken;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Strava;

class ImportStravaSegments implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Количество попыток выполнения задания.
     */
    public $int = 2;
    protected int $userId;
    protected Segment $segment;

    public function __construct(int $userId, Segment $segment)
    {
        $this->userId = $userId;
        $this->segment = $segment;
    }

    public function handle(): void
    {
        $savedToken = StravaToken::where('user_id', $this->userId)->first();
        if (empty($savedToken)) {
            $this->fail('No Strava token found!');
        }

        if ($savedToken->expires_at < Carbon::now()->toDateTimeString()) {
            $refresh = Strava::refreshToken($savedToken->refresh_token);
            $savedToken->access_token = $refresh->access_token;
            $savedToken->refresh_token = $refresh->refresh_token;
            $savedToken->save();
        }

        $segmentData = Strava::segment($savedToken->access_token, $this->segment->strava_segment_id);
        $limits = Strava::getApiLimits();

        if ($limits['usage']['daily'] >= 1000) {
            $this->release(86400);
        }

        if ($limits['usage']['15minutes'] >= 15) {
            $this->release(960);
        }

        if (empty($segmentData)) {
            $this->fail('Received empty Strava segment!');
        }

        $this->segment->activity_type = $segmentData->activity_type;
        $this->segment->name = $segmentData->name;
        $this->segment->distance = $segmentData->distance;
        $this->segment->total_elevation_gain = $segmentData->total_elevation_gain;
        $this->segment->start_latlng = implode(',', $segmentData->start_latlng);
        $this->segment->end_latlng = implode(',', $segmentData->end_latlng);
        $this->segment->private = $segmentData->private;
        $this->segment->hazardous = $segmentData->hazardous;
        $this->segment->polyline = $segmentData->map->polyline;
        $this->segment->created_at = $segmentData->created_at;
        $this->segment->updated_at = $segmentData->updated_at;

        $this->segment->save();

        $this->delete();
    }
}
