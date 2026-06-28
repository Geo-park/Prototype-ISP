<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PopOlt;
use App\Models\Odc;
use App\Models\Odp;
use App\Models\Pelanggan;

class PopOltSeeder extends Seeder
{
    public function run(): void
    {
        // === POP/OLT ===
        $pop1 = PopOlt::create([
            'nama'      => 'POP Serang Utara',
            'kode'      => 'POP-SRG-01',
            'lat'       => -6.1100,
            'lng'       => 106.1500,
            'status'    => 'aktif',
            'kapasitas' => 256,
            'daerah'    => 'Banten',
        ]);

        $pop2 = PopOlt::create([
            'nama'      => 'POP Jakarta Selatan',
            'kode'      => 'POP-JKT-01',
            'lat'       => -6.2600,
            'lng'       => 106.7810,
            'status'    => 'aktif',
            'kapasitas' => 128,
            'daerah'    => 'Jakarta',
        ]);

        // === ODC Level 1 ===
        $odcL1_1 = Odc::create([
            'pop_olt_id' => $pop1->id,
            'nama'       => 'ODC Serang Kota A',
            'kode'       => 'ODC-SRG-L1-01',
            'level'      => 'L1',
            'lat'        => -6.1150,
            'lng'        => 106.1530,
            'status'     => 'aktif',
            'kapasitas'  => 64,
            'daerah'     => 'Banten',
        ]);

        $odcL1_2 = Odc::create([
            'pop_olt_id' => $pop2->id,
            'nama'       => 'ODC Jakarta Selatan A',
            'kode'       => 'ODC-JKT-L1-01',
            'level'      => 'L1',
            'lat'        => -6.2620,
            'lng'        => 106.7830,
            'status'     => 'aktif',
            'kapasitas'  => 64,
            'daerah'     => 'Jakarta',
        ]);

        // === ODC Level 2 ===
        $odcL2_1 = Odc::create([
            'pop_olt_id' => $pop1->id,
            'nama'       => 'ODC Cipocok Jaya',
            'kode'       => 'ODC-SRG-L2-01',
            'level'      => 'L2',
            'lat'        => -6.1200,
            'lng'        => 106.1480,
            'status'     => 'aktif',
            'kapasitas'  => 32,
            'daerah'     => 'Banten',
        ]);

        $odcL2_2 = Odc::create([
            'pop_olt_id' => $pop2->id,
            'nama'       => 'ODC Kebayoran',
            'kode'       => 'ODC-JKT-L2-01',
            'level'      => 'L2',
            'lat'        => -6.2650,
            'lng'        => 106.7850,
            'status'     => 'nonaktif',
            'kapasitas'  => 32,
            'daerah'     => 'Jakarta',
        ]);

        // === ODP Banten ===
        $odp1 = Odp::create([
            'odc_id'    => $odcL1_1->id,
            'nama'      => 'ODP Merdeka 01',
            'kode'      => 'ODP-SRG-001',
            'lat'       => -6.1180,
            'lng'       => 106.1510,
            'status'    => 'aktif',
            'kapasitas' => 8,
            'daerah'    => 'Banten',
        ]);

        $odp2 = Odp::create([
            'odc_id'    => $odcL1_1->id,
            'nama'      => 'ODP Merdeka 02',
            'kode'      => 'ODP-SRG-002',
            'lat'       => -6.1170,
            'lng'       => 106.1550,
            'status'    => 'aktif',
            'kapasitas' => 8,
            'daerah'    => 'Banten',
        ]);

        $odp3 = Odp::create([
            'odc_id'    => $odcL2_1->id,
            'nama'      => 'ODP Cipocok 01',
            'kode'      => 'ODP-SRG-003',
            'lat'       => -6.1220,
            'lng'       => 106.1470,
            'status'    => 'aktif',
            'kapasitas' => 8,
            'daerah'    => 'Banten',
        ]);

        // === ODP Jakarta ===
        $odp4 = Odp::create([
            'odc_id'    => $odcL1_2->id,
            'nama'      => 'ODP Jaksel 01',
            'kode'      => 'ODP-JKT-001',
            'lat'       => -6.2610,
            'lng'       => 106.7825,
            'status'    => 'aktif',
            'kapasitas' => 8,
            'daerah'    => 'Jakarta',
        ]);

        $odp5 = Odp::create([
            'odc_id'    => $odcL1_2->id,
            'nama'      => 'ODP Jaksel 02',
            'kode'      => 'ODP-JKT-002',
            'lat'       => -6.2635,
            'lng'       => 106.7840,
            'status'    => 'aktif',
            'kapasitas' => 8,
            'daerah'    => 'Jakarta',
        ]);

        $odp6 = Odp::create([
            'odc_id'    => $odcL2_2->id,
            'nama'      => 'ODP Kebayoran 01',
            'kode'      => 'ODP-JKT-003',
            'lat'       => -6.2660,
            'lng'       => 106.7860,
            'status'    => 'nonaktif',
            'kapasitas' => 8,
            'daerah'    => 'Jakarta',
        ]);

        // === Assign pelanggan ke ODP ===
        Pelanggan::where('no_pelanggan', 'PLG-20250101-001')->update([
            'odp_id' => $odp1->id,
            'lat'    => -6.1185,
            'lng'    => 106.1513,
        ]);

        Pelanggan::where('no_pelanggan', 'PLG-20250101-002')->update([
            'odp_id' => $odp4->id,
            'lat'    => -6.2615,
            'lng'    => 106.7828,
        ]);

        Pelanggan::where('no_pelanggan', 'PLG-20250101-003')->update([
            'odp_id' => $odp5->id,
            'lat'    => -6.2638,
            'lng'    => 106.7843,
        ]);
    }
}
