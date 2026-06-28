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
        ]);

        $pop2 = PopOlt::create([
            'nama'      => 'POP Serang Selatan',
            'kode'      => 'POP-SRG-02',
            'lat'       => -6.1450,
            'lng'       => 106.1650,
            'status'    => 'aktif',
            'kapasitas' => 128,
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
        ]);

        $odcL1_2 = Odc::create([
            'pop_olt_id' => $pop2->id,
            'nama'       => 'ODC Serang Kota B',
            'kode'       => 'ODC-SRG-L1-02',
            'level'      => 'L1',
            'lat'        => -6.1400,
            'lng'        => 106.1620,
            'status'     => 'aktif',
            'kapasitas'  => 64,
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
        ]);

        $odcL2_2 = Odc::create([
            'pop_olt_id' => $pop2->id,
            'nama'       => 'ODC Kasemen',
            'kode'       => 'ODC-SRG-L2-02',
            'level'      => 'L2',
            'lat'        => -6.1480,
            'lng'        => 106.1700,
            'status'     => 'nonaktif',
            'kapasitas'  => 32,
        ]);

        // === ODP ===
        $odp1 = Odp::create([
            'odc_id'    => $odcL1_1->id,
            'nama'      => 'ODP Merdeka 01',
            'kode'      => 'ODP-SRG-001',
            'lat'       => -6.1180,
            'lng'       => 106.1510,
            'status'    => 'aktif',
            'kapasitas' => 8,
        ]);

        $odp2 = Odp::create([
            'odc_id'    => $odcL1_1->id,
            'nama'      => 'ODP Merdeka 02',
            'kode'      => 'ODP-SRG-002',
            'lat'       => -6.1170,
            'lng'       => 106.1550,
            'status'    => 'aktif',
            'kapasitas' => 8,
        ]);

        $odp3 = Odp::create([
            'odc_id'    => $odcL1_2->id,
            'nama'      => 'ODP Pahlawan 01',
            'kode'      => 'ODP-SRG-003',
            'lat'       => -6.1350,
            'lng'       => 106.1610,
            'status'    => 'aktif',
            'kapasitas' => 8,
        ]);

        $odp4 = Odp::create([
            'odc_id'    => $odcL2_1->id,
            'nama'      => 'ODP Cipocok 01',
            'kode'      => 'ODP-SRG-004',
            'lat'       => -6.1220,
            'lng'       => 106.1470,
            'status'    => 'aktif',
            'kapasitas' => 8,
        ]);

        $odp5 = Odp::create([
            'odc_id'    => $odcL2_1->id,
            'nama'      => 'ODP Cipocok 02',
            'kode'      => 'ODP-SRG-005',
            'lat'       => -6.1240,
            'lng'       => 106.1500,
            'status'    => 'aktif',
            'kapasitas' => 8,
        ]);

        $odp6 = Odp::create([
            'odc_id'    => $odcL2_2->id,
            'nama'      => 'ODP Kasemen 01',
            'kode'      => 'ODP-SRG-006',
            'lat'       => -6.1500,
            'lng'       => 106.1720,
            'status'    => 'nonaktif',
            'kapasitas' => 8,
        ]);

        // === Assign pelanggan ke ODP ===
        // Pelanggan 1 (Budi) → ODP Merdeka 01
        Pelanggan::where('no_pelanggan', 'PLG-20250101-001')->update([
            'odp_id' => $odp1->id,
            'lat'    => -6.1201,
            'lng'    => 106.1503,
        ]);

        // Pelanggan 2 (Siti) → ODP Pahlawan 01
        Pelanggan::where('no_pelanggan', 'PLG-20250101-002')->update([
            'odp_id' => $odp3->id,
            'lat'    => -6.1340,
            'lng'    => 106.1615,
        ]);

        // Pelanggan 3 (Ahmad) → ODP Cipocok 01
        Pelanggan::where('no_pelanggan', 'PLG-20250101-003')->update([
            'odp_id' => $odp4->id,
            'lat'    => -6.1230,
            'lng'    => 106.1465,
        ]);
    }
}
