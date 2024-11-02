<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Photo extends Model
{
    protected $table = 'photos';

    protected $fillable = [
        'activities_id',
        'users_id',
        'url',
        'description',
    ];

    public function getImage(?int $userId = null, bool $fullUrl = false): ?string
    {
        if (empty($this->url)) {
            return null;
        }

        if (!$userId) {
            $userId = $this->users_id;
        }

        $path = "activities/{$userId}/images/{$this->url}";

        return $fullUrl ? asset(Storage::url($path)) : Storage::url($path);
    }

    public function toArray(): array
    {
        if (!request()?->expectsJson()) {
            return parent::toArray();
        }

        return [
            'id' => $this->id,
            'activities_id' => $this->activities_id,
            'users_id' => $this->users_id,
            'description' => $this->description,
            'url' => $this->getImage(null, true),
            'created_at' => $this->created_at,
        ];
    }
}
