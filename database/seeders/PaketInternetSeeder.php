<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PaketInternet;

class PaketInternetSeeder extends Seeder
{
    public function run(): void
    {
        $paket = [
            [
                'nama'           => 'Paket Warung',
                'harga'          => 150000,
                'persen_pajak'   => 11,
                'bandwidth_up'   => 5,
                'bandwidth_down' => 10,
                'satuan'         => 'Mbps',
                'masa_aktif'     => 30,
            ],
            [
                'nama'           => 'Paket Rumahan',
                'harga'          => 250000,
                'persen_pajak'   => 11,
                'bandwidth_up'   => 10,
                'bandwidth_down' => 20,
                'satuan'         => 'Mbps',
                'masa_aktif'     => 30,
            ],
            [
                'nama'           => 'Paket Bisnis',
                'harga'          => 500000,
                'persen_pajak'   => 11,
                'bandwidth_up'   => 25,
                'bandwidth_down' => 50,
                'satuan'         => 'Mbps',
                'masa_aktif'     => 30,
            ],
        ];

        foreach ($paket as $p) {
            PaketInternet::create($p);
        }
    }
}
