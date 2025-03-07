<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use App\Contracts\Likeable;
use App\Models\Concerns\Likes;
use Log;
use Psr\Log\LogLevel;

class Activities extends Model implements Likeable
{
    use Likes;
    use Notifiable;

    public const PENDING = 'pending';
    public const DONE = 'done';
    protected const STATUS = [
        self::PENDING,
        self::DONE
    ];

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
        'polyline',
        'start_position_lat',
        'start_position_long',
        'end_position_lat',
        'end_position_long',
        'country',
        'locality',
        'status'
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
            if (array_key_exists($this->creator, DeviceManufacturers::DEVICE_MANUFACTURERS)) {
                $result = DeviceManufacturers::DEVICE_MANUFACTURERS[$this->creator];
            }
        }

        if (!empty($this->device_manufacturers_id)) {
            try {
                $result = !empty($this->deviceManufacturer->description)
                    ? $this->deviceManufacturer->description
                    : $this->deviceManufacturer->manufacturer;
            } catch (\Exception $e) {
                Log::channel('telegram')->log(LogLevel::ERROR, auth()->user()->getFullName() . " Unknown device_manufacturers_description: ID: {$this->device_manufacturers_id}");
            }
        }

        if (!empty($this->device_models_id)) {
            try {
                $result .= ' ' . $this->deviceModel->description;
            } catch (\Exception $e) {
                Log::channel('telegram')->log(LogLevel::ERROR, auth()->user()->getFullName() . " Unknown device_model_description: ID: {$this->device_models_id}");
            }
        }

        return $result;
    }

    public function deviceManufacturer(): HasOne
    {
        return $this->hasOne(DeviceManufacturers::class, 'id', 'device_manufacturers_id');
    }

    public function deviceModel(): HasOne
    {
        return $this->hasOne(DeviceModel::class, 'model_id', 'device_models_id');
    }

    public function getUser()
    {
        return User::find($this->user_id);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function photos(): HasMany
    {
        return $this->hasMany(Photo::class);
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

    public function getFITFile(bool $onlyFileName = false): ?string
    {
        $fitFile = $this->file;
        if (!strpos($this->file, '.fit')) {
            return null;
        }

        if ($onlyFileName) {
            return $fitFile;
        }
        return Storage::url('public/activities/' . $this->user_id . '/' . $fitFile);
    }

    public function getGPXFile(bool $onlyFileName = false): ?string
    {
        $gpxFile = $this->file;
        if (strpos($this->file, '.fit')) {
            $gpxFile = $this->file . '.gpx';
        }

        if ($onlyFileName) {
            return $gpxFile;
        }
        return Storage::url('public/activities/' . $this->user_id . '/' . $gpxFile);
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

    public function comments(string $orderBy = 'asc'): HasMany
    {
        return $this->hasMany(Comment::class)->orderBy('created_at', $orderBy);
    }

    public static function getSportSvgIcon(?int $sport = null, ?int $subSport = null): string
    {
        return match (true) {
            $sport === 2 && $subSport === 58 => 'images/sport/vr.svg',
            $sport === 1 => 'images/sport/run.svg',
            $sport === 2 => 'images/sport/ride.svg',
            $sport === 11 => 'images/sport/walk.svg',
            $sport === 37 => 'images/sport/sup.svg',
            default => 'images/sport/unknown.svg',
        };
    }

    public function getActivityType(): string
    {
        return match (true) {
            $this->sport === 2 && $this->sub_sport === 58 => __('Virtual ride'),
            $this->sport === 1 => __('Run'),
            $this->sport === 2 => __('Ride'),
            $this->sport === 11 => __('Walking'),
            $this->sport === 37 => __('Sup boarding'),
            default => __('Unknown'),
        };
    }

    // Метод для преобразования модели в массив с кастомными ключами
    public function toArray(): array
    {
        if (!request()?->expectsJson()) {
            return parent::toArray();
        }

        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'image_url' => $this->getImage($this->user_id, true),
            'photos' => $this->photos,
            'polyline' => $this->polyline,
            'user_name' => $this->user->getFullName(),
            'distance' => $this->distance,
            'sport' => $this->sport,
            'avg_speed' => $this->avg_speed,
            'steps' => 0,
            'elevation_gain' => $this->elevation_gain,
            'started_at' => $this->started_at,
            'locality' => $this->locality,
            'user' => $this->user,
            'comments' => $this->comments,
            'likes' => $this->likes,
            'liked_by_me' => auth('sanctum')->user()->hasLiked($this),
            'shares_count' => 0,
            'created_at' => $this->created_at,
        ];
    }
}
