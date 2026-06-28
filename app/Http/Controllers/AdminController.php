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
        $user = auth()->user();

        $query = Pelanggan::query()
            ->when($user->role === 'admin', function ($q) use ($user) {
                $q->where('daerah', $user->daerah);
            });

        return response()->json([
            'total_pelanggan_aktif'    => (clone $query)->where('status', 'aktif')->count(),
            'total_pelanggan_nonaktif' => (clone $query)->where('status', 'nonaktif')->count(),
            'tagihan_pending'          => \App\Models\Invoice::where('status', 'pending')->count(),
            'daerah'                   => $user->daerah ?? 'Semua Daerah',
        ]);
    }

    public function pelanggan()
    {
        $user = auth()->user();

        $pelanggan = Pelanggan::with('paket')
            ->when($user->role === 'admin', function ($query) use ($user) {
                $query->where('daerah', $user->daerah);
            })
            ->get();

        return response()->json($pelanggan);
    }

    public function laporanKeluhan()
    {
        return response()->json([
            ['id' => 1, 'judul' => 'Koneksi lambat', 'pelanggan' => 'Budi Santoso', 'status' => 'open', 'deskripsi' => 'Internet sangat lambat sejak kemarin sore, tidak bisa streaming.'],
            ['id' => 2, 'judul' => 'Tidak bisa konek', 'pelanggan' => 'Siti Rahayu', 'status' => 'open', 'deskripsi' => 'Router menyala tapi tidak ada koneksi internet sama sekali.'],
        ]);
    }

    public function matikanKoneksi($id)
    {
        $pelanggan = Pelanggan::findOrFail($id);
        $pelanggan->update(['status' => 'nonaktif']);
        return response()->json(['message' => 'Koneksi dimatikan', 'status' => 'nonaktif']);
    }

    public function hidupkanKoneksi($id)
    {
        $pelanggan = Pelanggan::findOrFail($id);
        $pelanggan->update(['status' => 'aktif']);
        return response()->json(['message' => 'Koneksi dihidupkan', 'status' => 'aktif']);
    }

    public function formTambahPelanggan()
    {
        return Inertia::render('admin/TambahPelanggan');
    }

    public function paket()
    {
        return response()->json(\App\Models\PaketInternet::all());
    }

    public function simpanPelanggan(Request $request)
    {
        $request->validate([
            'nama'           => 'required|string',
            'email'          => 'required|email|unique:users,email',
            'no_wa'          => 'required|string',
            'alamat'         => 'required|string',
            'paket_id'       => 'required|exists:paket_internet,id',
            'pppoe_username' => 'required|string|unique:pelanggan,pppoe_username',
            'pppoe_password' => 'required|string',
            'tgl_aktivasi'   => 'required|date',
        ]);

        try {
            \Illuminate\Support\Facades\DB::transaction(function () use ($request) {
                // Buat user account
                $user = \App\Models\User::create([
                    'name'               => $request->nama,
                    'email'              => $request->email,
                    'password'           => bcrypt('demo1234'),
                    'role'               => 'user',
                    'daerah'             => auth()->user()->daerah,
                    'email_verified_at'  => now(),
                ]);

                // Hitung tanggal jatuh tempo
                $paket = \App\Models\PaketInternet::findOrFail($request->paket_id);
                $tglAktivasi    = $request->tgl_aktivasi;
                $masaAktif      = $paket->masa_aktif ?? 30;
                $tglJatuhTempo  = date('Y-m-d', strtotime($tglAktivasi . ' + ' . $masaAktif . ' days'));

                // Buat data pelanggan
                Pelanggan::create([
                    'user_id'         => $user->id,
                    'no_pelanggan'    => 'PLG-' . now()->format('Ymd') . '-' . str_pad(Pelanggan::count() + 1, 3, '0', STR_PAD_LEFT),
                    'nama'            => $request->nama,
                    'alamat'          => $request->alamat,
                    'daerah'          => auth()->user()->daerah,
                    'no_wa'           => $request->no_wa,
                    'paket_id'        => $request->paket_id,
                    'status'          => 'aktif',
                    'pppoe_username'  => $request->pppoe_username,
                    'pppoe_password'  => $request->pppoe_password,
                    'tgl_aktivasi'    => $tglAktivasi,
                    'tgl_jatuh_tempo' => $tglJatuhTempo,
                ]);
            });

            return response()->json(['message' => 'Pelanggan berhasil ditambahkan']);

        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Gagal menyimpan: ' . $e->getMessage(),
            ], 500);
        }
    }
}
