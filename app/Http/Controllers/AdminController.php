<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AdminController extends Controller
{
    public function dashboard()
    {
        return Inertia::render('admin/Dashboard');
    }

    public function stats()
    {
        return response()->json([
            'total_pelanggan_aktif'    => Pelanggan::where('status', 'aktif')->count(),
            'total_pelanggan_nonaktif' => Pelanggan::where('status', 'nonaktif')->count(),
            'tagihan_pending'          => \App\Models\Invoice::where('status', 'pending')->count(),
        ]);
    }

    public function pelanggan()
    {
        $pelanggan = Pelanggan::with('paket')->get();
        return response()->json($pelanggan);
    }

    public function tiketAktif()
    {
        // Dummy untuk prototype
        return response()->json([
            ['id' => 1, 'judul' => 'Koneksi lambat', 'pelanggan' => 'Budi Santoso', 'status' => 'open'],
            ['id' => 2, 'judul' => 'Tidak bisa konek', 'pelanggan' => 'Siti Rahayu', 'status' => 'open'],
        ]);
    }

    public function matikanKoneksi($id)
    {
        $pelanggan = Pelanggan::findOrFail($id);
        $pelanggan->update(['status' => 'nonaktif']);
        return response()->json(['message' => 'Koneksi dimatikan']);
    }

    public function hidupkanKoneksi($id)
    {
        $pelanggan = Pelanggan::findOrFail($id);
        $pelanggan->update(['status' => 'aktif']);
        return response()->json(['message' => 'Koneksi dihidupkan']);
    }
}
