<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RakitanLog extends Model
{
    use HasFactory;

    // --- BAGIAN PENTING YANG KURANG ---
    protected $fillable = [
        'barang_id',
        'qty_rakit',
        'detail_snapshot',
    ];
    
    // Relasi balik ke Barang (Opsional, buat jaga-jaga nanti butuh)
    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }
}