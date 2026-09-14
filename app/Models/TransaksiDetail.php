<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransaksiDetail extends Model
{
    protected $guarded = ['id'];
    protected $fillable = [
    'transaksi_id',
    'nomor_unit', // 👈 TAMBAHKAN INI
    'barang_id',
    'nama_barang_custom',
    'link',
    'harga_satuan',
    'qty',
    'subtotal',
];
    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }
}