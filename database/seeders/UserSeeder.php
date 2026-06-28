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
            'daerah'   => null,
        ]);

        User::create([
            'name'     => 'Admin',
            'email'    => 'admin@demo.com',
            'password' => Hash::make('demo1234'),
            'role'     => 'admin',
            'daerah'   => 'Banten',
        ]);

        User::create([
            'name'     => 'Admin Jakarta',
            'email'    => 'admin.jakarta@demo.com',
            'password' => Hash::make('demo1234'),
            'role'     => 'admin',
            'daerah'   => 'Jakarta',
        ]);

        // 3 user pelanggan
        User::create([
            'name'     => 'Budi Santoso',
            'email'    => 'budi@demo.com',
            'password' => Hash::make('demo1234'),
            'role'     => 'user',
            'daerah'   => 'Banten',
        ]);

        User::create([
            'name'     => 'Siti Rahayu',
            'email'    => 'siti@demo.com',
            'password' => Hash::make('demo1234'),
            'role'     => 'user',
            'daerah'   => 'Jakarta',
        ]);

        User::create([
            'name'     => 'Ahmad Fauzi',
            'email'    => 'ahmad@demo.com',
            'password' => Hash::make('demo1234'),
            'role'     => 'user',
            'daerah'   => 'Jakarta',
        ]);
    }
}
