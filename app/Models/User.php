<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

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
        return !empty($this->photo) ? Storage::url('pictures/athletes/'. $this->id .'/'. $this->photo) : null;
    }

    public function getNickname(bool $addAtSymbol = false): ?string
    {
        return !empty($this->nickname) ? ($addAtSymbol ? '@'.$this->nickname : $this->nickname) : null;
    }

    public function getFullName(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function getInitials(): string
    {
        return strtoupper(Str::limit($this->first_name, 1, '').Str::limit($this->last_name, 1, ''));
    }

    public function activities(): HasMany
    {
        return $this->hasMany(Activities::class);
    }

    public function subscriptions(): BelongsToMany
    {
        return $this->belongsToMany(
            User::class,
            'subscriptions',
            'user_id',
            'subscriber_id'
        );
    }

    public function subscribers(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }
}
