<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $table = 'invoice';

    protected $fillable = [
        'no_invoice',
        'pelanggan_id',
        'paket_id',
        'periode',
        'tgl_invoice',
        'tgl_jatuh_tempo',
        'nama_paket',
        'bandwidth',
        'subtotal',
        'persen_pajak',
        'nominal_pajak',
        'total',
        'status',
        'duitku_ref',
    ];

    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class);
    }

    public function paket()
    {
        return $this->belongsTo(PaketInternet::class, 'paket_id');
    }

    public function pembayarans()
    {
        return $this->hasMany(Pembayaran::class);
    }

    public function pembayaranSukses()
    {
        return $this->hasOne(Pembayaran::class)
                    ->where('status', 'success');
    }

    public function pembayaranPending()
    {
        return $this->hasOne(Pembayaran::class)
                    ->where('status', 'pending')
                    ->latest();
    }
}
