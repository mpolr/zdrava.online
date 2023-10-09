<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class GpxRecalculate extends Command
{
    protected $signature = 'app:gpx:recalculate {userId?}';

    protected $description = 'Recalculate GPX stats';

    public function handle(?int $userId): void
    {
        if (!empty($user)) {
            $users = User::findOrFail($userId);
        } else {
            $users = User::all();
        }

        foreach ($users as $user) {
            foreach ($user->activities() as $activity) {
                //$activity->
            }
        }
    }
}
