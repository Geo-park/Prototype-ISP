<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pelanggan;

class PelangganSeeder extends Seeder
{
    public function run(): void
    {
        $pelanggan = [
            [
                'user_id'         => 3,
                'no_pelanggan'    => 'PLG-20250101-001',
                'nama'            => 'Budi Santoso',
                'alamat'          => 'Jl. Merdeka No. 1, Serang',
                'no_wa'           => '081234567890',
                'paket_id'        => 1,
                'status'          => 'aktif',
                'pppoe_username'  => 'budi.santoso',
                'pppoe_password'  => 'budi1234',
                'tgl_aktivasi'    => '2025-01-01',
                'tgl_jatuh_tempo' => '2025-07-01',
                'lat'             => -6.1201,
                'lng'             => 106.1503,
            ],
            [
                'user_id'         => 4,
                'no_pelanggan'    => 'PLG-20250101-002',
                'nama'            => 'Siti Rahayu',
                'alamat'          => 'Jl. Pahlawan No. 5, Serang',
                'no_wa'           => '081234567891',
                'paket_id'        => 2,
                'status'          => 'aktif',
                'pppoe_username'  => 'siti.rahayu',
                'pppoe_password'  => 'siti1234',
                'tgl_aktivasi'    => '2025-01-01',
                'tgl_jatuh_tempo' => '2025-07-01',
                'lat'             => -6.1301,
                'lng'             => 106.1603,
            ],
            [
                'user_id'         => 5,
                'no_pelanggan'    => 'PLG-20250101-003',
                'nama'            => 'Ahmad Fauzi',
                'alamat'          => 'Jl. Sudirman No. 10, Serang',
                'no_wa'           => '081234567892',
                'paket_id'        => 3,
                'status'          => 'nonaktif',
                'pppoe_username'  => 'ahmad.fauzi',
                'pppoe_password'  => 'ahmad1234',
                'tgl_aktivasi'    => '2025-01-01',
                'tgl_jatuh_tempo' => '2025-06-01',
                'lat'             => -6.1401,
                'lng'             => 106.1703,
            ],
        ];

        foreach ($pelanggan as $p) {
            Pelanggan::create($p);
        }
    }
}
