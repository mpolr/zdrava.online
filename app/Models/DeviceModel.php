<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeviceModel extends Model
{
    public $incrementing = false;

    protected $fillable = [
        'device_manufacturers_id',
        'model_id',
        'description',
    ];
}
