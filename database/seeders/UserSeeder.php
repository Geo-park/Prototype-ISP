<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name'     => 'Super Admin',
            'email'    => 'superadmin@demo.com',
            'password' => Hash::make('demo1234'),
            'role'     => 'superadmin',
        ]);

        User::create([
            'name'     => 'Admin',
            'email'    => 'admin@demo.com',
            'password' => Hash::make('demo1234'),
            'role'     => 'admin',
        ]);

        // 3 user pelanggan
        User::create([
            'name'     => 'Budi Santoso',
            'email'    => 'budi@demo.com',
            'password' => Hash::make('demo1234'),
            'role'     => 'user',
        ]);

        User::create([
            'name'     => 'Siti Rahayu',
            'email'    => 'siti@demo.com',
            'password' => Hash::make('demo1234'),
            'role'     => 'user',
        ]);

        User::create([
            'name'     => 'Ahmad Fauzi',
            'email'    => 'ahmad@demo.com',
            'password' => Hash::make('demo1234'),
            'role'     => 'user',
        ]);
    }
}
