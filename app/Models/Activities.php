<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Activities extends Model
{
    protected $fillable = [
        'user_id',
        'sport',
        'sub_sport',
        'name',
        'description',
        'creator',
        'device_manufacturers_id',
        'distance',
        'avg_speed',
        'max_speed',
        'avg_pace',
        'min_altitude',
        'max_altitude',
        'elevation_gain',
        'elevation_loss',
        'started_at',
        'finished_at',
        'duration',
        'duration_total',
        'avg_heart_rate',
        'max_heart_rate',
        'avg_cadence',
        'max_cadence',
        'total_calories',
        'file',
        'image',
        'start_position_lat',
        'start_position_long',
        'end_position_lat',
        'end_position_long',
        'country',
        'locality',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'finished_at' => 'datetime',
    ];

    public function getDeviceManufacturer(): ?string
    {
        if (!empty($this->device_manufacturers_id) && is_numeric($this->device_manufacturers_id)) {
            return DeviceManufacturers::where([
                'code' => $this->device_manufacturers_id
            ])->first()->description;
        }

        return null;
    }

    public function getUser(): ?User
    {
        return User::find($this->user_id);
    }

    public function getTrackCenter(): array
    {
        $startLat = $this->start_position_lat;
        $startLong = $this->start_position_long;
        $endLat = $this->end_position_lat;
        $endLong = $this->end_position_long;
        // Процент промежуточной точки
        // (0.0 - начальная точка, 1.0 - конечная точка)
        $percentage = 0.5;

        return [
            'lat' => $startLat + ($endLat - $startLat) * $percentage,
            'long' => $startLong + ($endLong - $startLong) * $percentage
        ];
    }

    public function getGPXFile(bool $onlyFileName = false): string
    {
        $gpxFile = $this->file;
        if (strpos($this->file, '.fit')) {
            $gpxFile = $this->file.'.gpx';
        }

        if ($onlyFileName) {
            return $gpxFile;
        }
        return Storage::url('public/activities/'. $this->user_id .'/'. $gpxFile);
    }

    public function getAverageSpeed(): string
    {
        return number_format($this->avg_speed, 1, ',');
    }

    public function getElevationGain(): string
    {
        return number_format($this->elevation_gain, 1, ',');
    }

    public function getMaxSpeed(): string
    {
        return number_format($this->max_speed, 1, ',');
    }

    public function getDuration(): bool|string
    {
        return date('H:i:s', $this->duration);
    }

    public function getDurationTotal(): bool|string
    {
        return date('H:i:s', $this->duration_total);
    }

    public function getDistance(): string
    {
        return number_format($this->distance, 2, ',');
    }

    public function getCountry(): string
    {
        if (empty($this->country)) {
            return 'Страна не указана';
        }

        // TODO: Сделать таблицу с сопоставлением стран
        $countries = [
            'RU' => 'Россия',
        ];

        if (array_key_exists($this->country, $countries)) {
            return $countries[$this->country];
        }

        return $this->country;
    }

    public function getLongStartDate(): string
    {
        return Carbon::parse($this->started_at)->translatedFormat('d F Y г, l, H:i');
    }

    public function getShortStartDate(): string
    {
        return Carbon::parse($this->started_at)->translatedFormat('l, d.m.Y');
    }

    public function getCount(): int
    {
        return $this->count();
    }

    public function getImage(?int $userId = null, bool $fullUrl = false): ?string
    {
        if (empty($this->image)) {
            return null;
        }

        if (empty($userId)) {
            $userId = auth()->user()->id;
        }

        $path = "activities/{$userId}/{$this->image}";

        return $fullUrl ? asset(Storage::url($path)) : Storage::url($path);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}
