<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AndroidAppCrashes extends Model
{
    protected $fillable = [
        'user_id',
        'issue_id',
        'report_id',
        'app_version_code',
        'app_version_name',
        'package_name',
        'file_path',
        'phone_model',
        'android_version',
        'build',
        'brand',
        'product',
        'total_mem_size',
        'available_mem_size',
        'custom_data',
        'stack_trace',
        'initial_configuration',
        'crash_configuration',
        'display',
        'user_comment',
        'user_app_start_date',
        'user_crash_date',
        'dumpsys_meminfo',
        'logcat',
        'installation_id',
        'device_features',
        'environment',
        'shared_preferences',
    ];
}
