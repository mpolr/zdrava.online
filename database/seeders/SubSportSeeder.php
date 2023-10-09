<?php

namespace Database\Seeders;

use App\Models\SubSport;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SubSportSeeder extends Seeder
{
    public function run(): void
    {
        $values = [
            ['id' => 0, 'name' => 'Generic'],
            ['id' => 1, 'name' => 'Treadmill'],
            ['id' => 2, 'name' => 'Street'],
            ['id' => 3, 'name' => 'Trail'],
            ['id' => 4, 'name' => 'Track'],
            ['id' => 5, 'name' => 'Spin'],
            ['id' => 6, 'name' => 'Indoor cycling'],
            ['id' => 7, 'name' => 'Road'],
            ['id' => 8, 'name' => 'Mountain'],
            ['id' => 9, 'name' => 'Downhill'],
            ['id' => 10, 'name' => 'Recumbent'],
            ['id' => 11, 'name' => 'Cyclocross'],
            ['id' => 12, 'name' => 'Hand cycling'],
            ['id' => 13, 'name' => 'Track cycling'],
            ['id' => 14, 'name' => 'Indoor rowing'],
            ['id' => 15, 'name' => 'Elliptical'],
            ['id' => 16, 'name' => 'Stair climbing'],
            ['id' => 17, 'name' => 'Lap swimming'],
            ['id' => 18, 'name' => 'Open water'],
            ['id' => 19, 'name' => 'Flexibility training'],
            ['id' => 20, 'name' => 'Strength training'],
            ['id' => 21, 'name' => 'Warm up'],
            ['id' => 22, 'name' => 'Match'],
            ['id' => 23, 'name' => 'Exercise'],
            ['id' => 24, 'name' => 'Challenge'],
            ['id' => 25, 'name' => 'Indoor skiing'],
            ['id' => 26, 'name' => 'Cardio training'],
            ['id' => 27, 'name' => 'Indoor walking'],
            ['id' => 28, 'name' => 'E-Bike Fitness'],
            ['id' => 254, 'name' => 'All'],
        ];

        SubSport::insert($values);
    }
}
