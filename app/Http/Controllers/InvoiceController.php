<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Pembayaran;
use App\Models\CatatanPajak;
use Illuminate\Http\Request;
use Inertia\Inertia;

class InvoiceController extends Controller
{
    public function index()
    {
        $invoices = Invoice::with('pelanggan')
            ->latest()
            ->get();

        return Inertia::render('invoice/Index', [
            'invoices' => $invoices,
        ]);
    }

    public function show($id)
    {
        $invoice = Invoice::with(['pelanggan', 'pembayarans', 'pembayaranSukses'])
            ->findOrFail($id);

        $user = auth()->user();
        if ($user->role === 'user') {
            $pelanggan = \App\Models\Pelanggan::where('user_id', $user->id)->first();
            if (!$pelanggan || $invoice->pelanggan_id !== $pelanggan->id) {
                abort(403, 'Akses ditolak.');
            }
        }

        return Inertia::render('invoice/Detail', [
            'invoice' => $invoice,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'pelanggan_id' => 'required|exists:pelanggan,id',
        ]);

        $pelanggan    = \App\Models\Pelanggan::with('paket')->findOrFail($request->pelanggan_id);
        $paket        = $pelanggan->paket;

        $subtotal     = $paket->harga;
        $persenPajak  = $paket->persen_pajak;
        $nominalPajak = $subtotal * $persenPajak / 100;
        $total        = $subtotal + $nominalPajak;

        $invoice = Invoice::create([
            'no_invoice'      => 'INV-' . now()->format('Y') . '-' . str_pad(Invoice::count() + 1, 3, '0', STR_PAD_LEFT),
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

        return response()->json($invoice);
    }

    public function simulasiBayar(Request $request, $id)
    {
        $invoice = Invoice::with('pelanggan')->findOrFail($id);

        $user = auth()->user();
        if ($user->role === 'user') {
            $pelanggan = \App\Models\Pelanggan::where('user_id', $user->id)->first();
            if (!$pelanggan || $invoice->pelanggan_id !== $pelanggan->id) {
                abort(403, 'Akses ditolak.');
            }
        }

        $invoice->pembayarans()
            ->where('status', 'pending')
            ->update(['status' => 'expired']);

        $pembayaran = Pembayaran::create([
            'invoice_id' => $invoice->id,
            'tgl_bayar'  => now(),
            'metode'     => $request->metode ?? 'QRIS',
            'jumlah'     => $invoice->total,
            'referensi'  => 'SIM-' . now()->format('YmdHis'),
            'status'     => 'success',
        ]);

        $invoice->update(['status' => 'paid']);

        CatatanPajak::create([
            'pembayaran_id'  => $pembayaran->id,
            'pelanggan_id'   => $invoice->pelanggan_id,
            'no_faktur'      => 'FKT-' . now()->format('Y') . '-' . str_pad(CatatanPajak::count() + 1, 3, '0', STR_PAD_LEFT),
            'tgl_faktur'     => now()->toDateString(),
            'subtotal'       => $invoice->subtotal,
            'persen_pajak'   => $invoice->persen_pajak,
            'nominal_pajak'  => $invoice->nominal_pajak,
            'total'          => $invoice->total,
            'dikirim_mekari' => false,
        ]);

        return response()->json([
            'message'       => 'Pembayaran berhasil',
            'no_invoice'    => $invoice->no_invoice,
            'nama_paket'    => $invoice->nama_paket,
            'metode'        => $request->metode ?? 'QRIS',
            'subtotal'      => $invoice->subtotal,
            'nominal_pajak' => $invoice->nominal_pajak,
            'total'         => $invoice->total,
        ]);
    }
}
