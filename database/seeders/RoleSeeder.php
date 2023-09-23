<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $admin = new Role();
        $admin->name = 'Administrator';
        $admin->slug = 'admin';
        $admin->save();

        $moderator = new Role();
        $moderator->name = 'Moderator';
        $moderator->slug = 'moderator';
        $moderator->save();

        $user = new Role();
        $user->name = 'User';
        $user->slug = 'user';
        $user->save();

        $guest = new Role();
        $guest->name = 'Guest';
        $guest->slug = 'guest';
        $guest->save();
    }
}
