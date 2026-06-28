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
}
