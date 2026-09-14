<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
protected $fillable = [
        'name',
        'email',
        'password',
        'role',          
        'phone', 
        'account_status',
        'is_online',
        'last_login_at',
        'last_logout_at',
        'address',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function transaksi()
    {
        // Pastikan 'user_id' adalah nama kolom di tabel transaksis yang menyimpan ID pelanggan
        // Kalau di tabelmu namanya 'customer_id', ganti 'user_id' jadi 'customer_id'
        return $this->hasMany(\App\Models\Transaksi::class, 'user_id');
    }
}
