<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DeviceManufacturersSeeder extends Seeder
{
    public function run(): void
    {
        $csvFilePath = __DIR__.'/DeviceManufacturers.csv';

        $file = fopen($csvFilePath, 'r');

        if ($file) {
            while (($data = fgetcsv($file)) !== false) {
                $manufacturer = new \App\Models\DeviceManufacturers();
                $manufacturer->id = (int)$data[1];
                $manufacturer->manufacturer = $data[0];
                $manufacturer->description = $data[2];
                $manufacturer->save();
            }
            fclose($file);
        } else {
            echo 'Не удалось открыть файл.';
        }
    }
}
