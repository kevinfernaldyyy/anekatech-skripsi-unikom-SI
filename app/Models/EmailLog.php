<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailLog extends Model
{
    use HasFactory;

    // INI YANG WAJIB ADA 👇
    protected $fillable = [
        'customer_id',
        'pre_order_id',
        'recipient_email',
        'subject',
        'body',
        'status',
        'trigger_type'
    ];
}