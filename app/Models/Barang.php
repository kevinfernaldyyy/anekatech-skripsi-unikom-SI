<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    protected $table = 'barang';
    use HasFactory;

protected $fillable = [
        'jenis',
        'kode_barang',
        'nama_barang',
        'kategori',
        'harga_jual',
        'is_diskon_bundle',
        'stok',
        'satuan',
        'gambar',
        'deskripsi',
        'socket',       
        'tipe_memory',
        'form_factor',
        'power_watt'
    ];
    public function components()
{
    return $this->belongsToMany(Barang::class, 'barang_bundle', 'parent_id', 'child_id')
                ->withPivot('qty');
}
public function details()
    {
     
        return $this->hasMany(TransaksiDetail::class, 'barang_id');
    }
}