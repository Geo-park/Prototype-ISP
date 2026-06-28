<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class NotifikasiController extends Controller
{
    public function index()
    {
        return Inertia::render('notifikasi/TemplateWA');
    }

    public function templates()
    {
        $templates = [
            [
                'id'       => 'invoice',
                'nama'     => 'Invoice Tagihan',
                'kategori' => 'billing',
                'pesan'    => "Halo [Nama],\n\nBerikut tagihan internet Anda:\n📋 No. Invoice: [NoInvoice]\n💰 Total: [Total]\n📅 Jatuh Tempo: [Tanggal]\n\nSilakan lakukan pembayaran sebelum jatuh tempo.\n\nTerima kasih,\nISP Manager",
            ],
            [
                'id'       => 'reminder_h3',
                'nama'     => 'Reminder H-3',
                'kategori' => 'reminder',
                'pesan'    => "Halo [Nama],\n\n⏰ Pengingat: Tagihan internet Anda sebesar [Total] akan jatuh tempo dalam *3 hari* ([Tanggal]).\n\nSegera lakukan pembayaran agar layanan tidak terganggu.\n\nTerima kasih,\nISP Manager",
            ],
            [
                'id'       => 'reminder_h1',
                'nama'     => 'Reminder H-1',
                'kategori' => 'reminder',
                'pesan'    => "Halo [Nama],\n\n🚨 Tagihan internet Anda sebesar [Total] akan jatuh tempo *besok* ([Tanggal]).\n\nSegera bayar agar koneksi tetap aktif.\n\nTerima kasih,\nISP Manager",
            ],
            [
                'id'       => 'overdue',
                'nama'     => 'Tagihan Terlambat',
                'kategori' => 'billing',
                'pesan'    => "Halo [Nama],\n\n⚠️ Tagihan internet Anda sebesar [Total] sudah *melewati jatuh tempo* ([Tanggal]).\n\nKoneksi Anda akan dinonaktifkan jika tidak segera dibayar.\n\nHubungi kami jika ada kendala.\n\nISP Manager",
            ],
            [
                'id'       => 'koneksi_mati',
                'nama'     => 'Koneksi Dinonaktifkan',
                'kategori' => 'status',
                'pesan'    => "Halo [Nama],\n\n🔴 Koneksi internet Anda telah *dinonaktifkan* karena tagihan yang belum dibayar.\n\nSilakan lunasi tagihan untuk mengaktifkan kembali layanan Anda.\n\nISP Manager",
            ],
            [
                'id'       => 'koneksi_hidup',
                'nama'     => 'Koneksi Diaktifkan',
                'kategori' => 'status',
                'pesan'    => "Halo [Nama],\n\n🟢 Koneksi internet Anda telah *diaktifkan kembali*.\n\nPaket: [Paket]\nKecepatan: [Bandwidth]\n\nSelamat menggunakan layanan kami!\n\nISP Manager",
            ],
            [
                'id'       => 'bukti_bayar',
                'nama'     => 'Bukti Pembayaran',
                'kategori' => 'billing',
                'pesan'    => "Halo [Nama],\n\n✅ Pembayaran Anda telah kami terima.\n\n📋 No. Invoice: [NoInvoice]\n💰 Jumlah: [Total]\n📅 Tanggal Bayar: [Tanggal]\n🧾 No. Faktur: [NoFaktur]\n\nTerima kasih atas pembayaran Anda!\n\nISP Manager",
            ],
        ];

        return response()->json($templates);
    }

    public function simulasiKirim(Request $request)
    {
        return response()->json([
            'message' => 'Pesan berhasil dikirim ke ' . ($request->no_wa ?? '08xxxxxxxxxx'),
            'status'  => 'success',
        ]);
    }
}
