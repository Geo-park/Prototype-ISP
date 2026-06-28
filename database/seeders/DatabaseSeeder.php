<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            PaketInternetSeeder::class,
            UserSeeder::class,
            PelangganSeeder::class,
            InvoiceSeeder::class,
            PopOltSeeder::class,
        ]);
    }
}
