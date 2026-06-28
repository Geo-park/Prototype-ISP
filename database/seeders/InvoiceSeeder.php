<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Invoice;
use App\Models\Pembayaran;
use App\Models\CatatanPajak;

class InvoiceSeeder extends Seeder
{
    public function run(): void
    {
        // Invoice PAID - Budi
        $invoice1 = Invoice::create([
            'no_invoice'      => 'INV-2025-001',
            'pelanggan_id'    => 1,
            'paket_id'        => 1,
            'periode'         => '2025-06',
            'tgl_invoice'     => '2025-06-01',
            'tgl_jatuh_tempo' => '2025-06-10',
            'nama_paket'      => 'Paket Warung',
            'bandwidth'       => '10 Mbps',
            'subtotal'        => 150000,
            'persen_pajak'    => 11,
            'nominal_pajak'   => 16500,
            'total'           => 166500,
            'status'          => 'paid',
        ]);

        $pembayaran1 = Pembayaran::create([
            'invoice_id' => $invoice1->id,
            'tgl_bayar'  => '2025-06-05 10:00:00',
            'metode'     => 'QRIS',
            'jumlah'     => 166500,
            'referensi'  => 'REF-001',
            'status'     => 'success',
        ]);

        CatatanPajak::create([
            'pembayaran_id'  => $pembayaran1->id,
            'pelanggan_id'   => 1,
            'no_faktur'      => 'FKT-2025-001',
            'tgl_faktur'     => '2025-06-05',
            'subtotal'       => 150000,
            'persen_pajak'   => 11,
            'nominal_pajak'  => 16500,
            'total'          => 166500,
            'dikirim_mekari' => false,
        ]);

        // Invoice PENDING - Siti
        Invoice::create([
            'no_invoice'      => 'INV-2025-002',
            'pelanggan_id'    => 2,
            'paket_id'        => 2,
            'periode'         => '2025-06',
            'tgl_invoice'     => '2025-06-01',
            'tgl_jatuh_tempo' => '2025-06-10',
            'nama_paket'      => 'Paket Rumahan',
            'bandwidth'       => '20 Mbps',
            'subtotal'        => 250000,
            'persen_pajak'    => 11,
            'nominal_pajak'   => 27500,
            'total'           => 277500,
            'status'          => 'pending',
        ]);

        // Invoice OVERDUE - Ahmad
        Invoice::create([
            'no_invoice'      => 'INV-2025-003',
            'pelanggan_id'    => 3,
            'paket_id'        => 3,
            'periode'         => '2025-05',
            'tgl_invoice'     => '2025-05-01',
            'tgl_jatuh_tempo' => '2025-05-10',
            'nama_paket'      => 'Paket Bisnis',
            'bandwidth'       => '50 Mbps',
            'subtotal'        => 500000,
            'persen_pajak'    => 11,
            'nominal_pajak'   => 55000,
            'total'           => 555000,
            'status'          => 'overdue',
        ]);
    }
}
