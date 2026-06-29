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

    public function dashboardData()
    {
        $user = auth()->user();

        $query = Pelanggan::query()
            ->when($user->role === 'admin', function ($q) use ($user) {
                $q->where('daerah', $user->daerah);
            });

        $stats = [
            'total_pelanggan_aktif'    => (clone $query)->where('status', 'aktif')->count(),
            'total_pelanggan_nonaktif' => (clone $query)->where('status', 'nonaktif')->count(),
            'tagihan_pending'          => \App\Models\Invoice::where('status', 'pending')
                ->when($user->role === 'admin', function ($q) use ($user) {
                    $q->whereHas('pelanggan', function ($pq) use ($user) {
                        $pq->where('daerah', $user->daerah);
                    });
                })->count(),
            'daerah'                   => $user->daerah ?? 'Semua Daerah',
        ];

        $pelanggan = Pelanggan::with('paket')
            ->when($user->role === 'admin', function ($q) use ($user) {
                $q->where('daerah', $user->daerah);
            })
            ->get();

        $laporanKeluhan = [
            ['id' => 1, 'judul' => 'Koneksi lambat', 'pelanggan' => 'Budi Santoso', 'status' => 'open', 'deskripsi' => 'Internet sangat lambat sejak kemarin sore, tidak bisa streaming.'],
            ['id' => 2, 'judul' => 'Tidak bisa konek', 'pelanggan' => 'Siti Rahayu', 'status' => 'open', 'deskripsi' => 'Router menyala tapi tidak ada koneksi internet sama sekali.'],
        ];

        return response()->json([
            'stats'           => $stats,
            'pelanggan'       => $pelanggan,
            'laporan_keluhan' => $laporanKeluhan,
        ]);
    }

    public function getOdps()
    {
        $user = auth()->user();
        $odps = \App\Models\Odp::query()
            ->when($user->role === 'admin', function ($q) use ($user) {
                $q->where('daerah', $user->daerah);
            })
            ->get();

        return response()->json($odps);
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
            'tagihan_pending'          => \App\Models\Invoice::where('status', 'pending')
                ->when($user->role === 'admin', function ($q) use ($user) {
                    $q->whereHas('pelanggan', function ($pq) use ($user) {
                        $pq->where('daerah', $user->daerah);
                    });
                })->count(),
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
            'odp_id'         => 'nullable|exists:odp,id',
            'pppoe_username' => 'required|string|unique:pelanggan,pppoe_username',
            'pppoe_password' => 'required|string',
            'tgl_aktivasi'   => 'required|date',
            'daerah'         => auth()->user()->role === 'superadmin' ? 'required|string' : 'nullable|string',
        ]);

        try {
            \Illuminate\Support\Facades\DB::transaction(function () use ($request) {
                $targetDaerah = auth()->user()->role === 'superadmin' ? $request->daerah : auth()->user()->daerah;

                // Buat user account
                $user = \App\Models\User::create([
                    'name'               => $request->nama,
                    'email'              => $request->email,
                    'password'           => bcrypt('demo1234'),
                    'role'               => 'user',
                    'daerah'             => $targetDaerah,
                    'email_verified_at'  => now(),
                ]);

                // Hitung tanggal jatuh tempo
                $paket = \App\Models\PaketInternet::findOrFail($request->paket_id);
                $tglAktivasi    = $request->tgl_aktivasi;
                $masaAktif      = $paket->masa_aktif ?? 30;
                $tglJatuhTempo  = date('Y-m-d', strtotime($tglAktivasi . ' + ' . $masaAktif . ' days'));

                // Cari ODP untuk koordinat baseline
                $odpId = $request->odp_id;
                $lat = null;
                $lng = null;

                if ($odpId) {
                    $odp = \App\Models\Odp::find($odpId);
                } else {
                    // Cari ODP di daerah tersebut secara acak
                    $odp = \App\Models\Odp::where('daerah', $targetDaerah)->inRandomOrder()->first();
                    if ($odp) {
                        $odpId = $odp->id;
                    }
                }

                if (isset($odp) && $odp) {
                    // Hitung offset acak kecil (+/- 0.0003 derajat, sekitar 30 meter dari ODP)
                    $latOffset = (mt_rand(-300, 300) / 1000000);
                    $lngOffset = (mt_rand(-300, 300) / 1000000);
                    
                    $lat = $odp->lat + $latOffset;
                    $lng = $odp->lng + $lngOffset;
                }

                // Buat data pelanggan
                Pelanggan::create([
                    'user_id'         => $user->id,
                    'no_pelanggan'    => 'PLG-' . now()->format('Ymd') . '-' . str_pad(Pelanggan::count() + 1, 3, '0', STR_PAD_LEFT),
                    'nama'            => $request->nama,
                    'alamat'          => $request->alamat,
                    'daerah'          => $targetDaerah,
                    'no_wa'           => $request->no_wa,
                    'paket_id'        => $request->paket_id,
                    'odp_id'          => $odpId,
                    'status'          => 'aktif',
                    'pppoe_username'  => $request->pppoe_username,
                    'pppoe_password'  => $request->pppoe_password,
                    'tgl_aktivasi'    => $tglAktivasi,
                    'tgl_jatuh_tempo' => $tglJatuhTempo,
                    'lat'             => $lat,
                    'lng'             => $lng,
                ]);
            });

            return response()->json(['message' => 'Pelanggan berhasil ditambahkan']);

        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Gagal menyimpan: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function users(Request $request)
    {
        $user = auth()->user();

        $users = \App\Models\User::when($user->role === 'admin', function ($q) use ($user) {
                $q->where('daerah', $user->daerah)
                  ->where('role', 'user');
            })
            ->when($user->role === 'superadmin', function ($q) {
                $q->whereIn('role', ['admin', 'user']);
            })
            ->with('pelanggan')
            ->get();

        if ($request->wantsJson()) {
            return response()->json($users);
        }

        return Inertia::render('admin/Users');
    }

    public function formTambahUser()
    {
        return Inertia::render('admin/Users');
    }

    public function simpanUser(Request $request)
    {
        $request->validate([
            'name'   => 'required|string',
            'email'  => 'required|email|unique:users,email',
            'role'   => 'required|in:admin,user',
            'daerah' => 'required|string',
        ]);

        $user = \App\Models\User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => bcrypt('demo1234'),
            'role'     => $request->role,
            'daerah'   => $request->daerah,
        ]);

        return response()->json($user);
    }

    public function updateUser(Request $request, $id)
    {
        $request->validate([
            'name'   => 'required|string',
            'email'  => 'required|email|unique:users,email,' . $id,
            'daerah' => 'required|string',
        ]);

        $user = \App\Models\User::findOrFail($id);
        $user->update($request->only('name', 'email', 'daerah'));

        return response()->json($user);
    }

    public function nonaktifkanUser($id)
    {
        $user = \App\Models\User::findOrFail($id);
        $user->update(['is_active' => false]);
        return response()->json(['message' => 'User dinonaktifkan']);
    }

    public function aktifkanUser($id)
    {
        $user = \App\Models\User::findOrFail($id);
        $user->update(['is_active' => true]);
        return response()->json(['message' => 'User diaktifkan']);
    }
}
