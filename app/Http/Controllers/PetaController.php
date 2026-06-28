<?php

namespace App\Http\Controllers;

use App\Models\PopOlt;
use App\Models\Odc;
use App\Models\Odp;
use App\Models\Pelanggan;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PetaController extends Controller
{
    public function index()
    {
        return Inertia::render('superadmin/Peta');
    }

    public function semua()
    {
        $popOlt = PopOlt::all();
        $odc    = Odc::all();
        $odp    = Odp::all();

        $pelanggan = Pelanggan::with('paket')
            ->whereNotNull('lat')
            ->whereNotNull('lng')
            ->get()
            ->map(function ($p) {
                // Determine payment status from latest invoice
                $latestInvoice = $p->invoices()->latest()->first();
                return [
                    'id'                => $p->id,
                    'nama'              => $p->nama,
                    'no_pelanggan'      => $p->no_pelanggan,
                    'alamat'            => $p->alamat,
                    'no_wa'             => $p->no_wa,
                    'paket'             => $p->paket?->nama,
                    'bandwidth'         => $p->paket ? ($p->paket->bandwidth_down . ' ' . $p->paket->satuan) : '-',
                    'status_koneksi'    => $p->status,
                    'status_pembayaran' => $latestInvoice?->status ?? 'tidak_ada',
                    'odp_id'            => $p->odp_id,
                    'lat'               => $p->lat,
                    'lng'               => $p->lng,
                ];
            });

        return response()->json([
            'pop_olt'   => $popOlt,
            'odc'       => $odc,
            'odp'       => $odp,
            'pelanggan' => $pelanggan,
        ]);
    }

    public function popOlt()
    {
        return response()->json(PopOlt::all());
    }

    public function odc()
    {
        return response()->json(Odc::all());
    }

    public function odp()
    {
        return response()->json(Odp::all());
    }

    public function pelanggan()
    {
        return response()->json(
            Pelanggan::with('paket')
                ->whereNotNull('lat')
                ->whereNotNull('lng')
                ->get()
        );
    }
}
