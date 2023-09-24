<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Segment extends Model
{
    protected $fillable = [
        'user_id',
        'strava_user_id',
        'name',
        'distance',
        'total_elevation_gain',
        'start_latlng',
        'end_latlng',
        'private',
        'polyline',
    ];

    protected $casts = [
        'distance' => 'float',
        'total_elevation_gain' => 'float',
        'private' => 'boolean',
    ];
}
