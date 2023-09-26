<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StravaToken extends Model
{
    protected $primaryKey = 'strava_user_id';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'strava_user_id',
        'access_token',
        'refresh_token',
        'expires_at',
    ];
}
