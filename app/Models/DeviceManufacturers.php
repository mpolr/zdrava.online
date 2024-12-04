<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeviceManufacturers extends Model
{
    public const DEVICE_MANUFACTURERS = [
        'mywhoosh.whooshg' => 'MyWhoosh',
    ];

    public $incrementing = false;

    protected $fillable = [
        'code',
        'manufacturer',
        'name',
        'description',
    ];
}
