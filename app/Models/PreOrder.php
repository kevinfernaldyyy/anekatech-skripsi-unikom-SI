<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class PreOrder extends Model
{
    protected $guarded = ['id'];

    public function items() {
        return $this->hasMany(PreOrderItem::class);
    }
    public function customer() {
        return $this->belongsTo(Customer::class);
    }
    public function transaksi() {
        return $this->belongsTo(Transaksi::class);
    }
}