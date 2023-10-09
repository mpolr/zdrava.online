<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeviceManufacturers extends Model
{
    public $incrementing = false;

    protected $fillable = [
        'code',
        'manufacturer',
        'name',
        'description',
    ];
}
