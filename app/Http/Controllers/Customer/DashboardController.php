<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Transaksi;
use App\Models\Customer;
use App\Models\TransaksiDetail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail; 
use App\Mail\ProfileUpdatedMail;
use App\Mail\PasswordUpdatedMail;



class DashboardController extends Controller
{
public function index(Request $request)
{
    $user = Auth::user();
    $customer = Customer::where('user_id', $user->id)
                        ->orWhere('email', $user->email)
                        ->first();
                        
    if ($customer && is_null($customer->user_id)) {
        $customer->update(['user_id' => $user->id]);
    }

    $tab = $request->tab ?? 'aktif';
    $pesananAktif = 0;
    $totalBelanja = 0;
    $totalItem = 0;

    // 🔥 FIX UTAMA: Filter ganda (Cek user_id ATAU customer_id) 🔥
    $filterUser = function ($q) use ($user, $customer) {
        $q->where('user_id', $user->id);
        if ($customer) {
            $q->orWhere('customer_id', $customer->id);
        }
    };

    // Query utama transaksi menggunakan $filterUser
    $query = Transaksi::with('details.barang', 'preOrder')
        ->where($filterUser)
        ->latest();
        
    if ($tab == 'aktif') {
        $query->whereNotIn('status_order', ['selesai', 'batal', 'completed', 'cancel']);
    } else {
        $query->whereIn('status_order', ['selesai', 'batal', 'completed', 'cancel']);
    }
    
    $transaksis = $query->paginate(10);

    // Hitung badge & statistik
    $pesananAktif = Transaksi::where($filterUser)
        ->whereNotIn('status_order', ['selesai', 'batal', 'completed', 'cancel'])
        ->count();

    $totalBelanja = Transaksi::where($filterUser)
        ->whereIn('status_order', ['selesai', 'completed'])
        ->sum('total_harga');

    $transaksiSuksesIds = Transaksi::where($filterUser)
        ->whereIn('status_order', ['selesai', 'completed'])
        ->pluck('id'); 

    if ($transaksiSuksesIds->isNotEmpty()) {
        $totalItem = \App\Models\TransaksiDetail::whereIn('transaksi_id', $transaksiSuksesIds)
            ->sum('qty'); 
    }

    if ($tab == 'riwayat') {
        return view('customer.riwayat', compact('transaksis', 'totalBelanja', 'pesananAktif', 'totalItem', 'tab'));
    }

    return view('customer.dashboard', compact(
        'transaksis', 
        'customer', 
        'tab', 
        'pesananAktif', 
        'totalBelanja', 
        'totalItem'
    ));
}
  public function show($id)
{
    $user = Auth::user();
    
    // 1. Cari data customer dengan aman (tanpa firstOrFail biar gak 404 duluan)
    $customer = Customer::where('user_id', $user->id)
                        ->orWhere('email', $user->email)
                        ->first();

    // 2. 🔥 CARI TRANSAKSI PAKAI FILTER GANDA (user_id ATAU customer_id) 🔥
    $transaksi = Transaksi::with(['details.barang', 'preOrder'])
        ->where('id', $id)
        ->where(function ($q) use ($user, $customer) {
            $q->where('user_id', $user->id);
            if ($customer) {
                $q->orWhere('customer_id', $customer->id);
            }
        })
        ->firstOrFail();

    return view('customer.order.show', compact('transaksi'));
}
public function uploadBukti(Request $request, $id)
    {
        $request->validate([
            'bukti_pembayaran' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'bukti_pembayaran.required' => 'Anda belum memilih foto.',
            'bukti_pembayaran.image' => 'File harus berupa gambar.',
        ]);
        $transaksi = Transaksi::where('id', $id)->where('user_id', \Illuminate\Support\Facades\Auth::id())->firstOrFail();
        if ($request->hasFile('bukti_pembayaran')) {
            $file = $request->file('bukti_pembayaran');
            $path = $file->store('bukti_transfer', 'public');
            if (in_array($transaksi->status_order, ['siap_diambil', 'sedang_dikirim', 'ready'])) {
                $transaksi->bukti_pelunasan = $path;
            } else {
                $transaksi->bukti_pembayaran = $path;            
                $transaksi->status_pesanan = 'menunggu_proses'; 
                $transaksi->status_order = 'menunggu_proses'; 
            }
            $transaksi->save();
            try {
                $adminEmails = \App\Models\User::where('role', 'admin')->pluck('email')->toArray();
                
                if (!empty($adminEmails)) {
                    $pembayaranMail = new \App\Mail\PembayaranMasukAdminMail($transaksi);
                    foreach ($adminEmails as $email) {
                        \App\Models\EmailLog::create([
                            'customer_id'     => $transaksi->customer_id,
                            'recipient_email' => $email,
                            'subject'         => $pembayaranMail->envelope()->subject ?? 'Pembayaran Masuk - DiabloTech',
                            'body'            => $pembayaranMail->render(),
                            'status'          => 'sent',
                            'trigger_type'    => 'payment_deposited' // Penanda log
                        ]);
                    }
                    \Illuminate\Support\Facades\Mail::to($adminEmails)->send($pembayaranMail); 
                }
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Gagal kirim notif & log pembayaran ke Admin: ' . $e->getMessage());
            }
        }
        return redirect()->back()->with('success', 'Bukti berhasil dikirim! Menunggu validasi dari Admin.');
    }

public function profile()
    {
        $user = Auth::user();
        $customer = \App\Models\Customer::where('user_id', $user->id)
                        ->orWhere('email', $user->email)
                        ->first();

        return view('customer.profile', compact('user', 'customer'));
    }
public function updateInfo(Request $request)
    {
        // 1. Validasi Inputan
        $request->validate([
            'name' => 'required|string|max:255',
            'perusahaan' => 'nullable|string|max:255',
            'nomor_telepon' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();
        $user->update([
            'name' => $request->name,
            'phone' => $request->nomor_telepon,
            'address' => $request->alamat,
        ]);
        Customer::updateOrCreate(
            ['user_id' => $user->id],
            [
                'nama' => $request->name,
                'email' => $user->email,
                'perusahaan' => $request->perusahaan,
                'nomor_telepon' => $request->nomor_telepon,
                'alamat' => $request->alamat,
            ]
        );
        Mail::to($user->email)->send(new ProfileUpdatedMail($user));

        return back()->with('success', 'Informasi profil berhasil diperbarui!');
    }

    public function updatePassword(Request $request)
    {
        
        $request->validate([
            'current_password' => 'required|current_password',
            'password' => 'required|min:8|confirmed',
        ]);
        /** @var \App\Models\User $user */
        $user = Auth::user();
    
        $user->update([
            'password' => Hash::make($request->password),
        ]);
       Mail::to($user->email)->send(new PasswordUpdatedMail($user));

        return back()->with('success', 'Kata sandi akun Anda berhasil diubah!');
    }
}