<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PopOlt extends Model
{
    protected $table = 'pop_olt';

    protected $fillable = [
        'nama',
        'kode',
        'lat',
        'lng',
        'status',
        'kapasitas',
    ];

    public function odcs()
    {
        return $this->hasMany(Odc::class);
    }
}
