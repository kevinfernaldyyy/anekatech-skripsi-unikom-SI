<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\Barang;
use App\Models\User;
use App\Models\PengajuanRetur; // 👈 TAMBAHKAN INI BOS!
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $bulanIni = Carbon::now()->month;
        $tahunIni = Carbon::now()->year;

        // 1. Pesanan Baru Bulan Ini
        $pesananBaru = Transaksi::whereMonth('created_at', $bulanIni)
                                ->whereYear('created_at', $tahunIni)
                                ->count();

        // 2. HITUNG PENDAPATAN KOTOR (Hanya yang status ordernya SELESAI)
        $pendapatanKotor = Transaksi::whereMonth('created_at', $bulanIni)
                                ->whereYear('created_at', $tahunIni)
                                ->whereIn('status_order', ['selesai']) 
                                ->sum('total_harga');

        // 3. HITUNG TOTAL REFUND DARI RETUR BULAN INI
        // (Asumsi status retur yang uangnya udah dikembalikan adalah 'selesai' atau 'disetujui')
        $totalRefund = PengajuanRetur::whereMonth('created_at', $bulanIni)
                                ->whereYear('created_at', $tahunIni)
                                ->where('status', 'selesai') // 👈 Sesuaikan dengan status retur di aplikasimu
                                ->sum('nominal_refund');

        // 4. PENDAPATAN BERSIH = PENDAPATAN KOTOR - REFUND
        $pendapatanBulanIni = $pendapatanKotor - $totalRefund;

        // 5. Proses Rakit / Aktif
        $prosesRakit = Transaksi::where('status_order', 'sedang_dirakit')->count();

        // 6. Total Pelanggan Aktif
        $totalPelanggan = User::where('role', 'pelanggan')->count();

        // 7. Peringatan Stok Menipis
        $stokMenipis = Barang::where('stok', '<=', 5)->orderBy('stok', 'asc')->get();
        $countStokMenipis = $stokMenipis->count();

        // 8. Aktivitas Transaksi Terbaru
        $aktivitasTerbaru = Transaksi::orderBy('created_at', 'desc')->take(5)->get();

        // 9. Top 5 Barang Paling Laku
        $barangLaris = DB::table('barang')
            ->leftJoin('transaksi_details', 'barang.id', '=', 'transaksi_details.barang_id')
            ->select('barang.nama_barang', 'barang.stok', DB::raw('COALESCE(SUM(transaksi_details.qty), 0) as total_terjual'))
            ->groupBy('barang.id', 'barang.nama_barang', 'barang.stok')
            ->orderBy('total_terjual', 'desc')
            ->take(5)
            ->get();

        // 10. Top 5 Barang Kurang Laku
        $barangKurangLaku = DB::table('barang')
            ->leftJoin('transaksi_details', 'barang.id', '=', 'transaksi_details.barang_id')
            ->select('barang.nama_barang', 'barang.stok', DB::raw('COALESCE(SUM(transaksi_details.qty), 0) as total_terjual'))
            ->groupBy('barang.id', 'barang.nama_barang', 'barang.stok')
            ->orderBy('total_terjual', 'asc')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'pesananBaru', 
            'pendapatanBulanIni', 
            'totalRefund', // 👈 JANGAN LUPA PASSING VARIABEL INI KE VIEW
            'prosesRakit', 
            'totalPelanggan',
            'stokMenipis', 
            'countStokMenipis',
            'aktivitasTerbaru',
            'barangLaris',
            'barangKurangLaku'
        ));
    }
}