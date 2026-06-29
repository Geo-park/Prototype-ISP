<?php

namespace App\Http\Controllers;

use App\Models\Keluhan;
use App\Models\Pelanggan;
use Illuminate\Http\Request;
use Inertia\Inertia;

class KeluhanController extends Controller
{
    // User submit keluhan
    public function store(Request $request)
    {
        $request->validate([
            'judul'     => 'required|string|max:255',
            'deskripsi' => 'required|string',
        ]);

        $user      = auth()->user();
        $pelanggan = Pelanggan::where('user_id', $user->id)->first();

        Keluhan::create([
            'user_id'      => $user->id,
            'pelanggan_id' => $pelanggan?->id,
            'judul'        => $request->judul,
            'deskripsi'    => $request->deskripsi,
            'status'       => 'open',
        ]);

        return response()->json(['message' => 'Keluhan berhasil dikirim']);
    }

    // Admin lihat keluhan di daerahnya
    public function index()
    {
        $user = auth()->user();

        $keluhan = Keluhan::with(['user', 'pelanggan'])
            ->when($user->role === 'admin', function ($q) use ($user) {
                $q->whereHas('pelanggan', function ($q2) use ($user) {
                    $q2->where('daerah', $user->daerah);
                });
            })
            ->latest()
            ->get();

        return response()->json($keluhan);
    }

    // Admin/Superadmin update status keluhan
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:open,proses,selesai',
        ]);

        $keluhan = Keluhan::findOrFail($id);
        $keluhan->update([
            'status'          => $request->status,
            'diselesaikan_at' => $request->status === 'selesai' ? now() : null,
        ]);

        return response()->json($keluhan);
    }

    // Halaman pusat bantuan
    public function pusatBantuan()
    {
        return Inertia::render('shared/PusatBantuan');
    }
}
