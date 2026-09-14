<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Mail\WelcomeUserMail;
use Illuminate\Support\Facades\Mail;
use App\Models\ActivityLog;

class UserController extends Controller
{
public function index()
    {
        
        $activeUsers = User::whereIn('role', ['admin', 'teknisi'])
                            ->where('account_status', 'active')
                            ->latest()
                            ->get();

        $inactiveUsers = User::whereIn('role', ['admin', 'teknisi'])
                            ->where('account_status', 'inactive')
                            ->latest()
                            ->get();

        return view('admin.users.index', compact('activeUsers', 'inactiveUsers'));
    }

   public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|in:admin,teknisi',
            'phone' => 'nullable|string|max:20',
        ]);

        // Simpan plain password untuk dikirim ke email
        $plainPassword = $request->password;

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($plainPassword),
            'role' => $request->role,
            'phone' => $request->phone,
            'account_status' => 'active',
        ]);

        // Kirim Email di background
        try {
            Mail::to($user->email)->send(new WelcomeUserMail($user, $plainPassword));
            $pesanSukses = 'Pengguna internal berhasil ditambahkan dan Email selamat datang telah dikirim!';
// ...
        } catch (\Exception $e) {
            $pesanSukses = 'Pengguna berhasil ditambahkan, namun gagal mengirim email. Cek konfigurasi Anda.';
        }
        ActivityLog::record('create', "Menambahkan pengguna internal baru: {$request->name}");

        return back()->with('success', $pesanSukses);
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$user->id,
            'role' => 'required|in:admin,teknisi',
            'account_status' => 'required|in:active,inactive',
        ]);

       $user->name = $request->name;
        $user->email = $request->email;
        $user->role = $request->role;
        $user->account_status = $request->account_status;
        $user->phone = $request->phone;
        
        // Matikan lampu online di tabel jika akun di-nonaktifkan
        if ($request->account_status === 'inactive') {
            $user->is_online = false;
        }
        
        // Update password HANYA jika form password diisi
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();
        
        ActivityLog::record('update', "Memperbarui data pengguna: {$request->name}");
        return back()->with('success', 'Data pengguna berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        if (Auth::id() == $user->id) {
            return back()->withErrors(['error' => 'Gagal: Anda tidak bisa memblokir akun yang sedang Anda gunakan!']);
        }
        $user->update([
            'account_status' => 'inactive',
            'is_online' => false
        ]);
        
        return back()->with('success', 'Akun pengguna berhasil dinonaktifkan (Blokir)!');
    }
}