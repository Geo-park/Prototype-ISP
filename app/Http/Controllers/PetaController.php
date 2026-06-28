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
        $user = auth()->user();

        $queryPop = PopOlt::query();
        $queryOdc = Odc::query();
        $queryOdp = Odp::query();
        $queryPelanggan = Pelanggan::with('paket')
            ->whereNotNull('lat')
            ->whereNotNull('lng');

        if ($user->role === 'admin') {
            $queryPop->where('daerah', $user->daerah);
            $queryOdc->where('daerah', $user->daerah);
            $queryOdp->where('daerah', $user->daerah);
            $queryPelanggan->where('daerah', $user->daerah);
        }

        $popOlt = $queryPop->get();
        $odc    = $queryOdc->get();
        $odp    = $queryOdp->get();

        $pelanggan = $queryPelanggan->get()
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
        $user = auth()->user();
        $query = PopOlt::query();
        if ($user->role === 'admin') {
            $query->where('daerah', $user->daerah);
        }
        return response()->json($query->get());
    }

    public function odc()
    {
        $user = auth()->user();
        $query = Odc::query();
        if ($user->role === 'admin') {
            $query->where('daerah', $user->daerah);
        }
        return response()->json($query->get());
    }

    public function odp()
    {
        $user = auth()->user();
        $query = Odp::query();
        if ($user->role === 'admin') {
            $query->where('daerah', $user->daerah);
        }
        return response()->json($query->get());
    }

    public function pelanggan()
    {
        $user = auth()->user();
        $query = Pelanggan::with('paket')
            ->whereNotNull('lat')
            ->whereNotNull('lng');
        if ($user->role === 'admin') {
            $query->where('daerah', $user->daerah);
        }
        return response()->json($query->get());
    }
}
