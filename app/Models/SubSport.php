<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubSport extends Model
{
    protected $table = 'sub_sport';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'id',
        'name',
    ];

    public function getName(bool $localized = true): string
    {
        return $localized ? __($this->name) : $this->name;
    }
}
