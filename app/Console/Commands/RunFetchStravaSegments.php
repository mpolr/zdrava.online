<?php

namespace App\Console\Commands;

use App\Jobs\ImportStravaSegments;
use App\Models\Segment;
use Illuminate\Console\Command;

class RunFetchStravaSegments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:run-fetch-strava-segments';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Place segments in queue';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $segments = Segment::where('strava_segment_id', 'IS NOT', null)
            ->where('country', null)
            ->limit(1000)
            ->get();

        if ($segments->count() == 0) {
            return;
        }

        $delay = 0;
        $delayDay = 0;

        $i = 1;
        foreach ($segments as $segment) {
            if ($i%100 === 0) {
                $delay += 960;
            }
            $delay += $delayDay;
            ImportStravaSegments::dispatch(1, $segment)
                ->onQueue('import-strava-segments')
                ->delay(now()->addSeconds($delay));
            $i++;
        }
    }
}
