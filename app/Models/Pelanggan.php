<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pelanggan extends Model
{
    protected $table = 'pelanggan';

    protected $fillable = [
        'user_id',
        'no_pelanggan',
        'nama',
        'alamat',
        'daerah',
        'no_wa',
        'paket_id',
        'odp_id',
        'status',
        'pppoe_username',
        'pppoe_password',
        'tgl_aktivasi',
        'tgl_jatuh_tempo',
        'lat',
        'lng',
    ];

    protected $hidden = ['pppoe_password'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function paket()
    {
        return $this->belongsTo(PaketInternet::class, 'paket_id');
    }

    public function odp()
    {
        return $this->belongsTo(Odp::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function catatanPajak()
    {
        return $this->hasMany(CatatanPajak::class);
    }
}
