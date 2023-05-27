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
        'file',
    ];
}
