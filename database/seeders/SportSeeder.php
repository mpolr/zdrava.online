<?php

namespace Database\Seeders;

use App\Models\Sport;
use Illuminate\Database\Seeder;

class SportSeeder extends Seeder
{
    public function run(): void
    {
        $values = [
            ['id' => 0, 'name' => 'Generic'],
            ['id' => 1, 'name' => 'Running'],
            ['id' => 2, 'name' => 'Cycling'],
            ['id' => 3, 'name' => 'Transition'],
            ['id' => 4, 'name' => 'Fitness equipment'],
            ['id' => 5, 'name' => 'Swimming'],
            ['id' => 6, 'name' => 'Basketball'],
            ['id' => 7, 'name' => 'Soccer'],
            ['id' => 8, 'name' => 'Tennis'],
            ['id' => 9, 'name' => 'American football'],
            ['id' => 10, 'name' => 'Training'],
            ['id' => 11, 'name' => 'Walking'],
            ['id' => 12, 'name' => 'Cross country skiing'],
            ['id' => 13, 'name' => 'Alpine skiing'],
            ['id' => 14, 'name' => 'Snowboarding'],
            ['id' => 15, 'name' => 'Rowing'],
            ['id' => 16, 'name' => 'Mountaineering'],
            ['id' => 17, 'name' => 'Hiking'],
            ['id' => 18, 'name' => 'Multisport'],
            ['id' => 19, 'name' => 'Paddling'],
            ['id' => 254, 'name' => 'All'],
        ];

        Sport::insert($values);
    }
}
