<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeviceManufacturers extends Model
{
    protected $fillable = [
        'code',
        'manufacturer',
        'name',
        'description',
    ];
}
