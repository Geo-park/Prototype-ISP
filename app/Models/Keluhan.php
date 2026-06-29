<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Keluhan extends Model
{
    protected $table = 'keluhan';

    protected $fillable = [
        'user_id',
        'pelanggan_id',
        'judul',
        'deskripsi',
        'status',
        'diselesaikan_at',
    ];

    protected $casts = [
        'diselesaikan_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class);
    }
}
