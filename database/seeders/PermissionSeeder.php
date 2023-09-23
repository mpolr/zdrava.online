<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $createEvents = new Permission();
        $createEvents->name = 'Create events';
        $createEvents->slug = 'create-events';
        $createEvents->save();
    }
}
