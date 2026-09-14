<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User; 
use App\Models\Customer; 
use Illuminate\Support\Facades\Hash; 
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\Mail;
use App\Mail\WelcomeCustomerMail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB; // 👇 TAMBAHKAN INI UNTUK TRANSACTION 👇

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        // 1. Validasi Super Ketat (Cek ke tabel users DAN customers)
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users,email',
            'phone'    => 'required|string|max:15|unique:users,phone|unique:customers,nomor_telepon',
            'password' => 'required|string|min:8|confirmed', 
        ], [    
            'phone.unique' => 'Nomor WhatsApp/Telepon ini sudah terdaftar. Silakan gunakan nomor lain.',
            'email.unique' => 'Email ini sudah terdaftar.'
        ]);

        try {
            // 2. Bungkus dengan Transaction (Sehidup Semati)
            $user = DB::transaction(function () use ($request) {
                
                // Simpan ke tabel Users
                $newUser = User::create([
                    'name'           => $request->name,
                    'email'          => $request->email,
                    'phone'          => $request->phone,
                    'role'           => 'pelanggan',      
                    'account_status' => 'active',
                    'password'       => Hash::make($request->password), 
                ]);

                // Simpan ke tabel Customers
                Customer::create([
                    'user_id'       => $newUser->id,
                    'nama'          => $request->name,
                    'email'         => $request->email,
                    'nomor_telepon' => $request->phone,
                    'perusahaan'    => 'Persorangan' 
                ]);

                return $newUser; // Kembalikan data user untuk proses login
            });

            // 3. Kirim Email Notifikasi
            try {
                Mail::to($user->email)->send(new WelcomeCustomerMail($user));
            } catch (\Exception $e) {
                Log::error('Gagal kirim email welcome: ' . $e->getMessage());
            }

            // 4. Login Otomatis & Redirect
            Auth::login($user);
            return redirect()->route('customer.dashboard')->with('success', 'Selamat datang di DiabloTech! Akun Anda berhasil dibuat.');

        } catch (\Exception $e) {
            // Jika ada error database yang lolos dari validasi
            Log::error('Gagal registrasi akun: ' . $e->getMessage());
            return back()->withInput()->withErrors(['error_sistem' => 'Terjadi kesalahan sistem saat mendaftar. Silakan coba lagi.']);
        }
    }
}