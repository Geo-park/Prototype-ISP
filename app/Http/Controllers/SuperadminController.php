<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Pelanggan;
use App\Models\Pembayaran;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SuperadminController extends Controller
{
    public function dashboard()
    {
        return Inertia::render('superadmin/Dashboard');
    }

    public function dashboardData()
    {
        // 1. Stats
        $stats = [
            'total_pelanggan_aktif'    => Pelanggan::where('status', 'aktif')->count(),
            'total_pelanggan_nonaktif' => Pelanggan::where('status', 'nonaktif')->count(),
            'revenue_bulan_ini'        => Invoice::where('status', 'paid')
                                            ->whereMonth('tgl_invoice', now()->month)
                                            ->sum('total'),
            'tagihan_pending'          => Invoice::where('status', 'pending')->count(),
            'tagihan_overdue'          => Invoice::where('status', 'overdue')->count(),
        ];

        // 2. Revenue Chart (6 months)
        $revenueChart = [];
        for ($i = 5; $i >= 0; $i--) {
            $bulan = now()->subMonths($i);
            $revenueChart[] = [
                'bulan'   => $bulan->format('M Y'),
                'revenue' => Invoice::where('status', 'paid')
                                ->whereMonth('tgl_invoice', $bulan->month)
                                ->whereYear('tgl_invoice', $bulan->year)
                                ->sum('total'),
            ];
        }

        // 3. Status Pembayaran
        $statusPembayaran = [
            'paid'    => Invoice::where('status', 'paid')->count(),
            'pending' => Invoice::where('status', 'pending')->count(),
            'overdue' => Invoice::where('status', 'overdue')->count(),
        ];

        // 4. Invoice Terbaru
        $invoiceTerbaru = Invoice::with('pelanggan')
            ->latest()
            ->take(10)
            ->get();

        // 5. Aktivitas Log
        $aktivitasLog = [
            ['aksi' => 'Invoice INV-2025-001 dibayar', 'waktu' => now()->subHours(1)],
            ['aksi' => 'Pelanggan baru ditambahkan', 'waktu' => now()->subHours(3)],
            ['aksi' => 'Status koneksi Ahmad dimatikan', 'waktu' => now()->subHours(5)],
        ];

        // 6. Statistik Daerah
        $daerahs = Pelanggan::select('daerah')
            ->distinct()
            ->pluck('daerah');

        $statistikDaerah = $daerahs->map(function ($daerah) {
            return [
                'daerah'   => $daerah,
                'aktif'    => Pelanggan::where('daerah', $daerah)->where('status', 'aktif')->count(),
                'nonaktif' => Pelanggan::where('daerah', $daerah)->where('status', 'nonaktif')->count(),
                'total'    => Pelanggan::where('daerah', $daerah)->count(),
            ];
        });

        return response()->json([
            'stats'            => $stats,
            'revenue_chart'    => $revenueChart,
            'status_pembayaran'=> $statusPembayaran,
            'invoice_terbaru'  => $invoiceTerbaru,
            'aktivitas_log'    => $aktivitasLog,
            'statistik_daerah' => $statistikDaerah,
        ]);
    }

    public function stats()
    {
        return response()->json([
            'total_pelanggan_aktif'    => Pelanggan::where('status', 'aktif')->count(),
            'total_pelanggan_nonaktif' => Pelanggan::where('status', 'nonaktif')->count(),
            'revenue_bulan_ini'        => Invoice::where('status', 'paid')
                                            ->whereMonth('tgl_invoice', now()->month)
                                            ->sum('total'),
            'tagihan_pending'          => Invoice::where('status', 'pending')->count(),
            'tagihan_overdue'          => Invoice::where('status', 'overdue')->count(),
        ]);
    }

    public function revenueChart()
    {
        $data = [];
        for ($i = 5; $i >= 0; $i--) {
            $bulan = now()->subMonths($i);
            $data[] = [
                'bulan'   => $bulan->format('M Y'),
                'revenue' => Invoice::where('status', 'paid')
                                ->whereMonth('tgl_invoice', $bulan->month)
                                ->whereYear('tgl_invoice', $bulan->year)
                                ->sum('total'),
            ];
        }
        return response()->json($data);
    }

    public function pelangganChart()
    {
        $data = [];
        for ($i = 5; $i >= 0; $i--) {
            $bulan = now()->subMonths($i);
            $data[] = [
                'bulan'     => $bulan->format('M Y'),
                'pelanggan' => Pelanggan::whereMonth('created_at', $bulan->month)
                                    ->whereYear('created_at', $bulan->year)
                                    ->count(),
            ];
        }
        return response()->json($data);
    }

    public function statusPembayaran()
    {
        return response()->json([
            'paid'    => Invoice::where('status', 'paid')->count(),
            'pending' => Invoice::where('status', 'pending')->count(),
            'overdue' => Invoice::where('status', 'overdue')->count(),
        ]);
    }

    public function invoiceTerbaru()
    {
        $invoices = Invoice::with('pelanggan')
            ->latest()
            ->take(10)
            ->get();

        return response()->json($invoices);
    }

    public function aktivitasLog()
    {
        // Dummy untuk prototype
        return response()->json([
            ['aksi' => 'Invoice INV-2025-001 dibayar', 'waktu' => now()->subHours(1)],
            ['aksi' => 'Pelanggan baru ditambahkan', 'waktu' => now()->subHours(3)],
            ['aksi' => 'Status koneksi Ahmad dimatikan', 'waktu' => now()->subHours(5)],
        ]);
    }

    public function statistikDaerah()
    {
        $daerahs = Pelanggan::select('daerah')
            ->distinct()
            ->pluck('daerah');

        $data = $daerahs->map(function ($daerah) {
            return [
                'daerah'   => $daerah,
                'aktif'    => Pelanggan::where('daerah', $daerah)->where('status', 'aktif')->count(),
                'nonaktif' => Pelanggan::where('daerah', $daerah)->where('status', 'nonaktif')->count(),
                'total'    => Pelanggan::where('daerah', $daerah)->count(),
            ];
        });

        return response()->json($data);
    }

    public function users()
    {
        $users = \App\Models\User::whereIn('role', ['admin', 'user'])
            ->with('pelanggan')
            ->get();

        return response()->json($users);
    }

    public function simpanAdmin(Request $request)
    {
        $request->validate([
            'name'   => 'required|string',
            'email'  => 'required|email|unique:users,email',
            'daerah' => 'required|string',
            'no_wa'  => 'required|string',
            'alamat' => 'required|string',
        ]);

        $user = \App\Models\User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => bcrypt('demo1234'),
            'role'     => 'admin',
            'daerah'   => $request->daerah,
            'no_wa'    => $request->no_wa,
            'alamat'   => $request->alamat,
            'is_active'=> true,
        ]);

        return response()->json($user);
    }

    public function usersPage()
    {
        return Inertia::render('superadmin/Users');
    }

    public function tambahAdminPage()
    {
        return Inertia::render('superadmin/TambahAdmin');
    }

    public function tambahUserPage()
    {
        return Inertia::render('superadmin/TambahUser');
    }
}
