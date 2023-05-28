<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Activities extends Model
{
    protected $fillable = [
        'users_id',
        'sport',
        'sub_sport',
        'name',
        'description',
        'creator',
        'device_manufacturers_id',
        'distance',
        'avg_speed',
        'max_speed',
        'avg_pace',
        'min_altitude',
        'max_altitude',
        'elevation_gain',
        'elevation_loss',
        'started_at',
        'finished_at',
        'duration',
        'duration_total',
        'avg_heart_rate',
        'max_heart_rate',
        'avg_cadence',
        'max_cadence',
        'total_calories',
        'file',
        'image',
        'start_position_lat',
        'start_position_long',
        'end_position_lat',
        'end_position_long',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'finished_at' => 'datetime',
    ];
}
