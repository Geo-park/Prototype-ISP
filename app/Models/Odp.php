<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Odp extends Model
{
    protected $table = 'odp';

    protected $fillable = [
        'odc_id',
        'nama',
        'kode',
        'lat',
        'lng',
        'status',
        'kapasitas',
        'daerah',
    ];

    public function odc()
    {
        return $this->belongsTo(Odc::class);
    }

    public function pelanggans()
    {
        return $this->hasMany(Pelanggan::class);
    }
}
