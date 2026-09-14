<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DokumenQc extends Model
{
    use HasFactory;

    protected $fillable = [
        'jenis_qc',         // 👈 TAMBAHAN KRUSIAL ('transaksi' / 'produksi')
        'transaksi_id',
        'barang_id',        // 👈 ID Master Paket PC
        'rakitan_log_id',   // 👈 ID Batch Produksi
        'nomor_unit',
        'nama_pc',
        'foto_depan',
        'foto_belakang',
        'foto_menyala',
        'catatan_teknisi',
        'file_pdf'
    ];

    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class);
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'barang_id');
    }

    public function rakitanLog()
    {
        return $this->belongsTo(RakitanLog::class, 'rakitan_log_id');
    }
}