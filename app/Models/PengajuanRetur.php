<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengajuanRetur extends Model
{
    use HasFactory;
    protected $table = 'pengajuan_return';

    protected $guarded = [];

    // Casting JSON ke Array secara otomatis
    protected $casts = [
        'foto_kerusakan' => 'array',
    ];

    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class);
    }
}