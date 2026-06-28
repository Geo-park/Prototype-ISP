<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CatatanPajak extends Model
{
    protected $table = 'catatan_pajak';

    protected $fillable = [
        'pembayaran_id',
        'pelanggan_id',
        'no_faktur',
        'tgl_faktur',
        'subtotal',
        'persen_pajak',
        'nominal_pajak',
        'total',
        'dikirim_mekari',
    ];

    protected $casts = [
        'tgl_faktur'     => 'date',
        'dikirim_mekari' => 'boolean',
    ];

    public function pembayaran()
    {
        return $this->belongsTo(Pembayaran::class);
    }

    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class);
    }
}
