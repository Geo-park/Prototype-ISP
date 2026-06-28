<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Odc extends Model
{
    protected $table = 'odc';

    protected $fillable = [
        'pop_olt_id',
        'nama',
        'kode',
        'level',
        'lat',
        'lng',
        'status',
        'kapasitas',
    ];

    public function popOlt()
    {
        return $this->belongsTo(PopOlt::class);
    }

    public function odps()
    {
        return $this->hasMany(Odp::class);
    }
}
