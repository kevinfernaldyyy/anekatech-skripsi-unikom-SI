<?php
namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\PengajuanRetur;
use App\Mail\KomplainMasukAdmin;
use App\Mail\ResiReturAdmin;
use Illuminate\Support\Facades\Mail;

class AjukanReturn extends Controller
{
public function store(Request $request, $id)
    {
        // 1. Validasi manual yang lebih aman
        $request->validate([
            'catatan_retur' => 'required|string',
            'foto_kerusakan' => 'required', // Pastikan ada file yg diupload
            'foto_kerusakan.*' => 'image|mimes:jpeg,png,jpg|max:5120' // Tiap file maks 5MB
        ], [
            'foto_kerusakan.required' => 'Anda wajib mengunggah minimal 1 foto kerusakan.',
            'foto_kerusakan.*.image' => 'File harus berupa gambar (JPG/PNG).',
            'foto_kerusakan.*.max' => 'Ukuran masing-masing foto tidak boleh lebih dari 5MB.'
        ]);

        $transaksi = Transaksi::findOrFail($id);

        // 2. Batasi maksimal 5 file secara manual
        $files = $request->file('foto_kerusakan');
        if (is_array($files) && count($files) > 5) {
            return back()->withInput()->withErrors(['foto_kerusakan' => 'Maksimal foto yang bisa diunggah adalah 5 buah.']);
        }

        // 3. Proses Upload
        $pathFoto = [];
        if ($request->hasFile('foto_kerusakan')) {
            foreach ($files as $file) {
                $pathFoto[] = $file->store('bukti_kerusakan', 'public');
            }
        }

        // 4. Simpan ke Database
        PengajuanRetur::create([
            'transaksi_id' => $transaksi->id,
            'catatan_retur' => $request->catatan_retur,
            'foto_kerusakan' => $pathFoto, 
            'status' => 'menunggu_verifikasi'
        ]);

        // 5. Kunci status di tabel transaksi
        $transaksi->update(['status_order' => 'retur_diajukan']);

        $admin = \App\Models\User::where('role', 'admin')->first(); 
    
    if ($admin && $admin->email) {
        Mail::to($admin->email)->send(new KomplainMasukAdmin($transaksi, $request->catatan_retur));
    }

        return redirect()->back()->with('success', 'Pengajuan retur berhasil dikirim. Hubungi WA kami untuk mengirimkan video unboxing.');
    }
public function kirimResi(\Illuminate\Http\Request $request, $id)
{
    // 1. Tambahkan validasi untuk bank dan rekening
    $request->validate([
        'resi_pengiriman_balik' => 'required|string',
        'foto_resi_balik' => 'required|image|mimes:jpeg,png,jpg|max:5120',
        'foto_packing_balik' => 'required|image|mimes:jpeg,png,jpg|max:5120',
        'nama_bank' => 'required|string',      // 👈 TAMBAHAN BARU
        'no_rekening' => 'required|string'     // 👈 TAMBAHAN BARU
    ], [
        'foto_resi_balik.image' => 'File resi harus berupa gambar.',
        'foto_packing_balik.image' => 'File packing harus berupa gambar.',
        'foto_resi_balik.max' => 'Ukuran maksimal foto 5MB.',
        'foto_packing_balik.max' => 'Ukuran maksimal foto 5MB.'
    ]);

    $retur = \App\Models\PengajuanRetur::findOrFail($id);
    
    $pathResi = $request->file('foto_resi_balik')->store('bukti_resi_retur', 'public');
    $pathPacking = $request->file('foto_packing_balik')->store('bukti_packing_retur', 'public');
    
    // 2. Masukkan datanya ke proses update!
    $retur->update([
        'resi_pengiriman_balik' => $request->resi_pengiriman_balik,
        'foto_resi_balik' => $pathResi,
        'foto_packing_balik' => $pathPacking, 
        'nama_bank' => $request->nama_bank,       // 👈 TAMBAHAN BARU
        'no_rekening' => $request->no_rekening,   // 👈 TAMBAHAN BARU
        'status' => 'barang_dikirim'
    ]);

    $admin = \App\Models\User::where('role', 'admin')->first(); 

    if ($admin && $admin->email) {
        Mail::to($admin->email)->send(new ResiReturAdmin($retur));
    }
    
    return redirect()->back()->with('success', 'Resi retur dan foto packing berhasil diunggah. Tim kami akan mengecek paket saat tiba di toko.');
}
}