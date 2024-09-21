<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Contracts\Likeable;
use App\Traits\HasRolesAndPermissions;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;
    use HasRolesAndPermissions;
    use CanResetPassword;

    protected $fillable = [
        'first_name',
        'last_name',
        'nickname',
        'photo',
        'email',
        'subscribe_news',
        'password',
        'private',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'private' => 'boolean',
    ];

    public function getPhoto(): ?string
    {
        if (empty($this->photo)) {
            return null;
        }

        if (str_starts_with($this->photo, 'http')) {
            return $this->photo;
        }

        return Storage::url('pictures/athletes/' . $this->id . '/' . $this->photo);
    }

    public function getPhotoUrl(): ?string
    {
        if (empty($this->photo)) {
            return null;
        }

        if (str_starts_with($this->photo, 'http')) {
            return $this->photo;
        }

        return url('storage/pictures/athletes/' . $this->id . '/' . $this->photo);
    }

    public function getNickname(bool $addAtSymbol = false): ?string
    {
        return !empty($this->nickname) ? ($addAtSymbol ? '@' . $this->nickname : $this->nickname) : null;
    }

    public function getFullName(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function getInitials(): string
    {
        return strtoupper(Str::limit($this->first_name, 1, '') . Str::limit($this->last_name, 1, ''));
    }

    public function activities(string $orderBy = 'DESC'): HasMany
    {
        return $this->hasMany(Activities::class)->orderBy('created_at', $orderBy);
    }

    public function subscriptions(): BelongsToMany
    {
        return $this->belongsToMany(
            __CLASS__,
            'subscriptions',
            'subscriber_id',
            'user_id',
        );
    }

    public function subscribers(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    public function likes(): HasMany
    {
        return $this->hasMany(Like::class);
    }

    public function like(Likeable $likeable): self
    {
        if ($this->hasLiked($likeable)) {
            return $this;
        }

        (new Like())
            ->user()->associate($this)
            ->likeable()->associate($likeable)
            ->save();

        return $this;
    }

    public function unlike(Likeable $likeable): self
    {
        if (! $this->hasLiked($likeable)) {
            return $this;
        }

        $likeable->likes()
            ->whereHas('user', fn($q) => $q->whereId($this->id))
            ->delete();

        return $this;
    }

    public function hasLiked(Likeable $likeable): bool
    {
        if (!$likeable->exists) {
            return false;
        }

        return $this->likes->contains(
            fn($like) => $like->likeable_type == get_class($likeable) && $like->likeable_id == $likeable->id
        );
    }

    public function isSubscriber(User $user): ?bool
    {
        if ($this->id === $user->id) {
            return true;
        }

        return Subscription::where('subscriber_id', $this->id)
            ->where('user_id', $user->id)
            ->exists();
    }

    // Метод для преобразования модели в массив с кастомными ключами
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'nickname' => $this->getNickname(),
            'photo' => $this->photo,
            'firstName' => $this->first_name,
            'lastName' => $this->last_name,
            'createdAt' => $this->created_at,
        ];
    }
}
