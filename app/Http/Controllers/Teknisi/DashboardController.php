<?php

namespace App\Http\Controllers\Teknisi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaksi;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon; // Kita pakai Carbon buat urusan waktu/bulan

class DashboardController extends Controller
{
    public function index()
    {
        $teknisiId = Auth::id();
        $bulanIni = Carbon::now()->month;
        $tahunIni = Carbon::now()->year;

        // 1. Antrean Aktif (Tidak difilter bulan, karena berapapun lamanya wajib dikerjakan)
        $tugasAktif = Transaksi::where('teknisi_id', $teknisiId)
                               ->where('status_order', 'sedang_dirakit')
                               ->count();
                               
        // 2. Pencapaian Selesai KHUSUS BULAN INI
        $selesaiBulanIni = Transaksi::where('teknisi_id', $teknisiId)
                                 ->whereIn('status_order', ['siap_diambil', 'sedang_dikirim', 'selesai'])
                                 ->whereMonth('updated_at', $bulanIni)
                                 ->whereYear('updated_at', $tahunIni)
                                 ->count();

        // 3. Pencapaian Selesai TOTAL (Sepanjang Masa)
        $selesaiTotal = Transaksi::where('teknisi_id', $teknisiId)
                                 ->whereIn('status_order', ['siap_diambil', 'sedang_dikirim', 'selesai'])
                                 ->count();

        // 4. Ambil 5 Antrean Paling Lama (Prioritas)
        $antreanTerbaru = Transaksi::with('customer')
                                   ->where('teknisi_id', $teknisiId)
                                   ->where('status_order', 'sedang_dirakit')
                                   ->orderBy('updated_at', 'asc') // Urutkan dari yang paling lama ngantri
                                   ->take(5)
                                   ->get();

        // 5. Ambil 5 Riwayat Sukses Bulan Ini
        $riwayatTerbaru = Transaksi::with('customer')
                                   ->where('teknisi_id', $teknisiId)
                                   ->whereIn('status_order', ['siap_diambil', 'sedang_dikirim', 'selesai'])
                                   ->whereMonth('updated_at', $bulanIni)
                                   ->whereYear('updated_at', $tahunIni)
                                   ->orderBy('updated_at', 'desc') // Urutkan dari yang paling baru beres
                                   ->take(5)
                                   ->get();

        // Nama bulan untuk ditampilkan di view
        $namaBulan = Carbon::now()->translatedFormat('F Y'); 

        return view('teknisi.dashboard', compact(
            'tugasAktif', 
            'selesaiBulanIni', 
            'selesaiTotal', 
            'antreanTerbaru', 
            'riwayatTerbaru',
            'namaBulan'
        ));
    }
}