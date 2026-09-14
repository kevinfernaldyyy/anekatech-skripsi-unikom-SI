<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

public function login(Request $request)
    {
        // 1. Validasi inputan
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            
            /** @var \App\Models\User $user */
            $user = Auth::user();

            // 👇 KEAMANAN: Cek apakah akun diblokir (inactive) 👇
            if ($user->account_status === 'inactive') {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                
                return back()->withErrors([
                    'email' => 'Akun Anda telah dinonaktifkan. Silakan hubungi Admin.',
                ])->onlyInput('email');
            }

            // 👇 UPDATE AKTIVITAS: Set Online & Waktu Login 👇
            $user->update([
                'is_online' => true,
                'last_login_at' => now()
            ]);

            $request->session()->regenerate();
            $role = $user->role;
            
            if ($role === 'admin') {
                return redirect()->route('admin.dashboard')->with('success', 'Selamat bekerja, Admin!');
            } elseif ($role === 'teknisi') {
                return redirect()->route('teknisi.dashboard')->with('success', 'Semangat merakit, Teknisi!');
            } else {
                return redirect()->route('customer.dashboard')->with('success', 'Selamat datang kembali di DiabloTech!');
            }
        }
        
        return back()->withErrors([
            'email' => 'Email atau password yang Anda masukkan salah.',
        ])->onlyInput('email');
    }
    public function logout(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        if ($user) {
            $user->update(['is_online' => false, 'last_logout_at' => now()]);
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}