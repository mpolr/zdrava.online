<?php

namespace App\Models;

use App\Contracts\Likeable;
use App\Models\Concerns\Likes;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Comment extends Model implements Likeable
{
    use Likes;

    protected $fillable = [
        'activities_id',
        'user_id',
        'parent_id',
        'content',
    ];

    protected $casts = [
        'created_at'  => 'datetime:d-m-Y H:i',
    ];

    // Автоматическое добавление этих полей в JSON
    protected $appends = ['replies_count'];

    public function activity(): BelongsTo
    {
        return $this->belongsTo(Activities::class, 'activities_id', 'id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(__CLASS__, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(__CLASS__, 'parent_id');
    }

    public function replies(): HasMany
    {
        return $this->hasMany(Reply::class, 'parent_id');
    }

    // Аксессор для количества ответов
    public function getRepliesCountAttribute(): int
    {
        return $this->replies()->count();
    }

    // Метод для преобразования модели в массив с кастомными ключами
    public function toArray(): array
    {
        if (!request()?->expectsJson()) {
            return parent::toArray();
        }

        return [
            'id' => $this->id,
            'activities_id' => $this->activity->id,
            'userId' => $this->user->id,
            'parentId' => $this->parent_id,
            'content' => $this->content,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'replies_count' => $this->replies_count, // Включаем аксессор replies_count
            'user' => $this->user,
            'replies' => $this->replies->map(function ($reply) {
                return [
                    'id' => $reply->id,
                    'content' => $reply->content,
                    'user' => $reply->user,
                    'created_at' => $reply->created_at,
                ];
            }),
        ];
    }
}
