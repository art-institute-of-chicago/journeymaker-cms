<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $userModel = twillModel('user');

        (new $userModel())->forceFill([
            'name' => 'Admin',
            'email' => 'admin@artic.edu',
            'role' => 'SUPERADMIN',
            'published' => true,
            'registered_at' => now(),
            'password' => Hash::make('password'),
        ])->save();

        $this->call([ThemeSeeder::class]);
    }
}
