<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;
protected $table = 'transaksi';
    protected $guarded = ['id'];

    public function details()
    {
        return $this->hasMany(TransaksiDetail::class);
    }
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function preOrder()
    {
        return $this->hasOne(PreOrder::class);
    }
    public function dokumenQc()
    {
        return $this->hasOne(DokumenQc::class);
    }
    public function teknisi()
    {
        return $this->belongsTo(User::class, 'teknisi_id');
    }
    public function pengajuanRetur()
{
    return $this->hasOne(PengajuanRetur::class, 'transaksi_id');
}
public function pengirimanTeknisi()
    {
        return $this->hasOne(PengirimanTeknisi::class, 'transaksi_id');
    }
    public function detail_transaksi()
{
    return $this->hasMany(TransaksiDetail::class, 'transaksi_id');
}
public function dokumenQcs()
{
    return $this->hasMany(DokumenQc::class, 'transaksi_id')->orderBy('nomor_unit', 'asc');
}
}
