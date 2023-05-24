<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class AndroidApp extends Model
{
    use Notifiable;

    protected $table = 'android_app';

    protected $fillable = [
        'version',
        'downloads',
        'description',
    ];
}
