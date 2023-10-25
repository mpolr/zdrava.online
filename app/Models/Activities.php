<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use App\Contracts\Likeable;
use App\Models\Concerns\Likes;

class Activities extends Model implements Likeable
{
    use Likes;

    protected $fillable = [
        'user_id',
        'sport',
        'sub_sport',
        'name',
        'description',
        'creator',
        'device_manufacturers_id',
        'device_models_id',
        'device_software_version',
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

    public function getDeviceManufacturer(): string
    {
        $result = 'Zdrava android app';

        if (!empty($this->creator) && empty($this->device_manufacturers_id) && empty($this->device_models_id)) {
            $result = $this->creator;
        }
        if (!empty($this->device_manufacturers_id)) {
            $result = $this->deviceManufacturer->description;
        }
        if (!empty($this->device_models_id)) {
            $result .= ' ' . $this->deviceModel->description;
        }

        return $result;
    }

    public function deviceManufacturer(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(DeviceManufacturers::class, 'id', 'device_manufacturers_id');
    }

    public function deviceModel(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(DeviceModel::class, 'model_id', 'device_models_id');
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
        return $this->avg_speed ? (string)$this->avg_speed : "-";
    }

    public function getElevationGain(): string
    {
        return number_format($this->elevation_gain, 1, ',');
    }

    public function getMaxSpeed(): string
    {
        return $this->max_speed ? (string)$this->max_speed : "-";
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
            $userId = $this->user_id;
        }

        $path = "activities/{$userId}/{$this->image}";

        return $fullUrl ? asset(Storage::url($path)) : Storage::url($path);
    }

    public function comments(string $orderBy = 'asc'): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Comment::class)->orderBy('created_at', $orderBy);
    }
}
