<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengirimanTeknisi extends Model
{
    use HasFactory;

    // Pastikan nama tabelnya sesuai dengan migration
    protected $table = 'pengiriman_teknisi';

    protected $fillable = [
        'transaksi_id',
        'waktu_berangkat',
        'waktu_selesai',
        'foto_bukti_instalasi',
        'dokumen',
        'catatan_lapangan',
    ];

    // Beritahu Laravel kalau dua kolom ini formatnya tanggal/waktu
    protected $casts = [
        'waktu_berangkat' => 'datetime',
        'waktu_selesai' => 'datetime',
    ];

    // Relasi Balik ke Tabel Transaksi
    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class, 'transaksi_id');
    }
}