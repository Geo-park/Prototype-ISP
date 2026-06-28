<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    protected $table = 'pembayaran';

    protected $fillable = [
        'invoice_id',
        'tgl_bayar',
        'metode',
        'jumlah',
        'referensi',
        'status',
        'callback_raw',
    ];

    protected $casts = [
        'callback_raw' => 'array',
        'tgl_bayar'    => 'datetime',
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function catatanPajak()
    {
        return $this->hasOne(CatatanPajak::class);
    }
}
