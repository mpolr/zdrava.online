<?php

namespace Database\Seeders;

use App\Models\Country;
use Illuminate\Database\Seeder;

class CountrySeeder extends Seeder
{
    public const COUNTRY_RU = 1;

    public function run(): void
    {
        $values = [
            ['id' => 0, 'name' => null, 'code' => null],
            ['id' => self::COUNTRY_RU, 'name' => 'Russia', 'code' => 'RU'],
        ];

        foreach ($values as $item => $value) {
            $sport = Country::firstOrCreate([
                'id' => $value['id']
            ], [
                'id' => $value['id'],
                'name' => $value['name'],
            ]);

            $sport->save();
        }
    }
}
