<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $table = 'customers';
    protected $guarded = ['id'];

protected $fillable = [
    'nama', 'perusahaan', 'email', 'nomor_telepon', 'alamat', 'catatan', 
    'user_id'
];

    public function transaksis()
    {
        return $this->hasMany(Transaksi::class);
    }
}