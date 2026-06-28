<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class PaketInternet extends Model
{
    protected $table = 'paket_internet';

    protected $fillable = [
        'nama',
        'harga',
        'persen_pajak',
        'bandwidth_up',
        'bandwidth_down',
        'satuan',
        'masa_aktif',
    ];

    protected $appends = ['harga_pajak', 'total_harga'];

    protected function hargaPajak(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->harga * $this->persen_pajak / 100,
        );
    }

    protected function totalHarga(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->harga + ($this->harga * $this->persen_pajak / 100),
        );
    }

    public function pelanggan()
    {
        return $this->hasMany(Pelanggan::class, 'paket_id');
    }
}
