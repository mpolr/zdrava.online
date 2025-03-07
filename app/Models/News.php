<?php

namespace App\Models;

use App\Models\Concerns\Likes;
use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    use Likes;

    protected $fillable = [
        'title',
        'content',
        'published',
    ];
}
