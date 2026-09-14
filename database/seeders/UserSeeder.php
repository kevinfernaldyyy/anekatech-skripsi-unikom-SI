<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{

    public function run(): void
    {
        User::create([
            'name' => 'Ahmad Djaki',
            'email' => 'ahmad@gmail.com',
            'password' => Hash::make('ahmad123'),
            'role' => 'admin',
            'account_status' => 'active',
            'phone' => '081234567890',
            'address' => 'Kalimantan, Tanjung Selor',
            'is_online' => false,
        ]);
        User::create([
            'name' => 'Akbar Rahmatullah',
            'email' => 'akbar@gmail.com',
            'password' => Hash::make('akbar123'),
            'role' => 'teknisi',
            'account_status' => 'active',
            'phone' => '089876543210',
            'address' => 'Cimahi',
            'is_online' => false,
        ]);
    }
}