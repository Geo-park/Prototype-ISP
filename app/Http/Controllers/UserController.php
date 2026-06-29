<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use App\Models\Invoice;
use App\Models\Pembayaran;
use App\Models\CatatanPajak;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UserController extends Controller
{
    public function dashboard()
    {
        return Inertia::render('user/Dashboard');
    }

    public function dashboardData()
    {
        $user      = auth()->user();
        $pelanggan = Pelanggan::with('paket')
            ->where('user_id', $user->id)
            ->first();

        if (!$pelanggan) {
            return response()->json([
                'profil'     => [
                    'status_koneksi'  => 'tidak_ada',
                    'nama'            => $user->name,
                    'paket'           => null,
                    'bandwidth'       => null,
                    'tgl_jatuh_tempo' => null,
                ],
                'tagihan'    => [],
                'pembayaran' => [],
                'pajak'      => [],
            ]);
        }

        // 1. Profil
        $profil = [
            'status_koneksi'   => $pelanggan->status,
            'nama'             => $pelanggan->nama,
            'no_pelanggan'     => $pelanggan->no_pelanggan,
            'paket'            => $pelanggan->paket?->nama,
            'bandwidth'        => $pelanggan->paket ? ($pelanggan->paket->bandwidth_down . ' ' . $pelanggan->paket->satuan) : '-',
            'tgl_jatuh_tempo'  => $pelanggan->tgl_jatuh_tempo,
            'tgl_aktivasi'     => $pelanggan->tgl_aktivasi,
            'alamat'           => $pelanggan->alamat,
        ];

        // 2. Tagihan Aktif
        $tagihan = Invoice::where('pelanggan_id', $pelanggan->id)
            ->whereIn('status', ['pending', 'overdue'])
            ->latest()
            ->get();

        // 3. Riwayat Pembayaran
        $pembayaran = Pembayaran::whereHas('invoice', function ($q) use ($pelanggan) {
                $q->where('pelanggan_id', $pelanggan->id);
            })
            ->with('invoice')
            ->where('status', 'success')
            ->latest('tgl_bayar')
            ->get();

        // 4. Riwayat Pajak
        $pajak = CatatanPajak::where('pelanggan_id', $pelanggan->id)
            ->latest('tgl_faktur')
            ->get();

        return response()->json([
            'profil'     => $profil,
            'tagihan'    => $tagihan,
            'pembayaran' => $pembayaran,
            'pajak'      => $pajak,
        ]);
    }

    public function profilKoneksi()
    {
        $user      = auth()->user();
        $pelanggan = Pelanggan::with('paket')
            ->where('user_id', $user->id)
            ->first();

        if (!$pelanggan) {
            return response()->json([
                'status_koneksi' => 'tidak_ada',
                'nama'           => $user->name,
                'paket'          => null,
                'bandwidth'      => null,
                'tgl_jatuh_tempo' => null,
            ]);
        }

        return response()->json([
            'status_koneksi'   => $pelanggan->status,
            'nama'             => $pelanggan->nama,
            'no_pelanggan'     => $pelanggan->no_pelanggan,
            'paket'            => $pelanggan->paket?->nama,
            'bandwidth'        => $pelanggan->paket ? ($pelanggan->paket->bandwidth_down . ' ' . $pelanggan->paket->satuan) : '-',
            'tgl_jatuh_tempo'  => $pelanggan->tgl_jatuh_tempo,
            'tgl_aktivasi'     => $pelanggan->tgl_aktivasi,
            'alamat'           => $pelanggan->alamat,
        ]);
    }

    public function tagihanAktif()
    {
        $user      = auth()->user();
        $pelanggan = Pelanggan::where('user_id', $user->id)->first();

        if (!$pelanggan) {
            return response()->json([]);
        }

        $invoices = Invoice::where('pelanggan_id', $pelanggan->id)
            ->whereIn('status', ['pending', 'overdue'])
            ->latest()
            ->get();

        return response()->json($invoices);
    }

    public function riwayatPembayaran()
    {
        $user      = auth()->user();
        $pelanggan = Pelanggan::where('user_id', $user->id)->first();

        if (!$pelanggan) {
            return response()->json([]);
        }

        $pembayaran = Pembayaran::whereHas('invoice', function ($q) use ($pelanggan) {
                $q->where('pelanggan_id', $pelanggan->id);
            })
            ->with('invoice')
            ->where('status', 'success')
            ->latest('tgl_bayar')
            ->get();

        return response()->json($pembayaran);
    }

    public function riwayatPajak()
    {
        $user      = auth()->user();
        $pelanggan = Pelanggan::where('user_id', $user->id)->first();

        if (!$pelanggan) {
            return response()->json([]);
        }

        $pajak = CatatanPajak::where('pelanggan_id', $pelanggan->id)
            ->latest('tgl_faktur')
            ->get();

        return response()->json($pajak);
    }

    public function beliPaket(Request $request)
    {
        $request->validate([
            'paket_id' => 'required|exists:paket_internet,id',
            'metode'   => 'required|in:QRIS,virtual_account,transfer',
        ]);

        $user      = auth()->user();
        $pelanggan = Pelanggan::with('paket')->where('user_id', $user->id)->first();
        $paket     = \App\Models\PaketInternet::findOrFail($request->paket_id);

        // Hitung nominal
        $subtotal     = $paket->harga;
        $persenPajak  = $paket->persen_pajak;
        $nominalPajak = $subtotal * $persenPajak / 100;
        $total        = $subtotal + $nominalPajak;

        // Buat invoice
        $invoice = \App\Models\Invoice::create([
            'no_invoice'      => 'INV-' . now()->format('Y') . '-' . str_pad(\App\Models\Invoice::count() + 1, 3, '0', STR_PAD_LEFT),
            'pelanggan_id'    => $pelanggan->id,
            'paket_id'        => $paket->id,
            'periode'         => now()->format('Y-m'),
            'tgl_invoice'     => now()->toDateString(),
            'tgl_jatuh_tempo' => now()->addDays(10)->toDateString(),
            'nama_paket'      => $paket->nama,
            'bandwidth'       => $paket->bandwidth_down . ' ' . $paket->satuan,
            'subtotal'        => $subtotal,
            'persen_pajak'    => $persenPajak,
            'nominal_pajak'   => $nominalPajak,
            'total'           => $total,
            'status'          => 'pending',
        ]);

        // Expire pembayaran pending lama
        $invoice->pembayarans()
            ->where('status', 'pending')
            ->update(['status' => 'expired']);

        // Buat pembayaran
        $pembayaran = \App\Models\Pembayaran::create([
            'invoice_id' => $invoice->id,
            'tgl_bayar'  => now(),
            'metode'     => $request->metode,
            'jumlah'     => $total,
            'referensi'  => 'SIM-' . now()->format('YmdHis'),
            'status'     => 'success',
        ]);

        // Update invoice jadi paid
        $invoice->update(['status' => 'paid']);

        // Ganti paket pelanggan
        $pelanggan->update([
            'paket_id'        => $paket->id,
            'tgl_aktivasi'    => now()->toDateString(),
            'tgl_jatuh_tempo' => now()->addDays($paket->masa_aktif)->toDateString(),
        ]);

        // Generate catatan pajak
        \App\Models\CatatanPajak::create([
            'pembayaran_id'  => $pembayaran->id,
            'pelanggan_id'   => $pelanggan->id,
            'no_faktur'      => 'FKT-' . now()->format('Y') . '-' . str_pad(\App\Models\CatatanPajak::count() + 1, 3, '0', STR_PAD_LEFT),
            'tgl_faktur'     => now()->toDateString(),
            'subtotal'       => $subtotal,
            'persen_pajak'   => $persenPajak,
            'nominal_pajak'  => $nominalPajak,
            'total'          => $total,
            'dikirim_mekari' => false,
        ]);

        return response()->json([
            'message'       => 'Pembayaran berhasil',
            'no_invoice'    => $invoice->no_invoice,
            'nama_paket'    => $paket->nama,
            'metode'        => $request->metode,
            'subtotal'      => $subtotal,
            'nominal_pajak' => $nominalPajak,
            'total'         => $total,
        ]);
    }
}
