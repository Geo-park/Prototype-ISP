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
}
