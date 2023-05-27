<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Activities extends Model
{
    protected $fillable = [
        'users_id',
        'type',
        'name',
        'description',
        'creator',
        'distance',
        'avg_speed',
        'avg_pace',
        'min_altitude',
        'max_altitude',
        'elevation_gain',
        'elevation_loss',
        'started_at',
        'finished_at',
        'duration',
        'file',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'finished_at' => 'datetime',
    ];
}
