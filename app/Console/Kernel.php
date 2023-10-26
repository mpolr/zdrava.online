<?php

namespace App\Console;

use App\Jobs\ImportStravaSegments;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        /* Prepare segments to import from Strava */
        $schedule->command('app:run-fetch-strava-segments')
            ->timezone('UTC')
            ->at('00:01');

        $schedule->command('queue:work --queue=process-fit')
            ->everySecond()
            ->withoutOverlapping()
            ->runInBackground();

        $schedule->command('queue:work --queue=process-gpx')
            ->everySecond()
            ->withoutOverlapping()
            ->runInBackground();

        /**
         * Run background import segments from Strava to avoid API limits, if not running
         */
        $schedule->command('queue:work --queue=import-strava-segments')
            ->hourly()
            ->withoutOverlapping()
            ->runInBackground();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
