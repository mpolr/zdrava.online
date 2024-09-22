<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    use HasFactory;

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class)->withDefault();
    }

    public function likeable(): \Illuminate\Database\Eloquent\Relations\MorphTo
    {
        return $this->morphTo();
    }

    // Метод для преобразования модели в массив с кастомными ключами
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'user' => $this->user,
            'likeableId' => $this->likeable->id,
            'createdAt' => $this->created_at,
        ];
    }
}
