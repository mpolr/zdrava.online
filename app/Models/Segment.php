<?php

namespace App\Models;

use ASanikovich\LaravelSpatial\Eloquent\HasSpatial;
use ASanikovich\LaravelSpatial\Geometry\Point;
use Illuminate\Database\Eloquent\Model;

class Segment extends Model
{
    use HasSpatial;

    protected $casts = [
        'distance' => 'float',
        'total_elevation_gain' => 'float',
        'private' => 'boolean',
        'start_latlng' => Point::class,
        'end_latlng' => Point::class,
    ];

    protected $fillable = [
        'user_id',
        'strava_segment_id',
        'activity_type',
        'name',
        'distance',
        'total_elevation_gain',
        'start_latlng',
        'end_latlng',
        'kom',
        'qom',
        'private',
        'hazardous',
        'polyline',
        'star_count',
        'country',
        'state',
        'city',
        'climb_category',
        'average_grade',
        'maximum_grade',
        'elevation_high',
        'elevation_low',
        'created_at',
        'updated_at',
    ];
}
