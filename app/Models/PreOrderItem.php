<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class PreOrderItem extends Model
{
    protected $guarded = ['id'];
    
    public function barang() {
        return $this->belongsTo(Barang::class);
    }
}