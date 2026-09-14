<?php

namespace App\Http\Controllers\Admin; 

use App\Http\Controllers\Controller; 
use Illuminate\Http\Request;
use App\Models\Transaksi;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanController extends Controller
{
    public function penjualan(Request $request)
    {
        $bulan = $request->input('bulan', date('m'));
        $tahun = $request->input('tahun', date('Y'));
        
        // 👇 UPDATE: Panggil juga relasi pengajuanRetur 👇
        $transaksiBulanIni = Transaksi::with(['details.barang', 'pengajuanRetur']) 
            ->whereIn('status_order', ['selesai', 'lunas'])
            ->whereMonth('created_at', $bulan)
            ->whereYear('created_at', $tahun)
            ->orderBy('created_at', 'desc')
            ->get();

        // 👇 JURUS AKUNTANSI: Hitung Pendapatan Bersih per transaksi 👇
        $transaksiBulanIni->map(function ($trx) {
            // 1. Kurangi total harga dengan ongkir
            $pendapatan = $trx->total_harga - $trx->ongkos_kirim;
            
            // 2. Jika ada retur dan ada uang akumulasi yang dikembalikan, kurangi lagi
            if ($trx->pengajuanRetur && $trx->pengajuanRetur->nominal_refund) {
                $pendapatan -= $trx->pengajuanRetur->nominal_refund;
            }
            
            // 3. Simpan jadi variabel sementara
            $trx->pendapatan_bersih = $pendapatan;
            return $trx;
        });

        $laporanHarian = $transaksiBulanIni->groupBy(function($item) {
            return $item->created_at->format('Y-m-d');
        });

        // 👇 Ganti sum jadi dari pendapatan_bersih 👇
        $totalPendapatan = $transaksiBulanIni->sum('pendapatan_bersih'); 
        $totalTransaksi = $transaksiBulanIni->count();

        return view('admin.logs.laporan', compact(
            'laporanHarian', 'bulan', 'tahun', 'totalPendapatan', 'totalTransaksi'
        ));
    }
    
    public function cetakPdf(Request $request)
    {
        $bulan = (int) $request->input('bulan', date('m'));
        $tahun = (int) $request->input('tahun', date('Y'));
        $tanggal = $request->input('tanggal'); 

        // 👇 UPDATE: Panggil relasi pengajuanRetur 👇
        $query = Transaksi::with(['details.barang', 'pengajuanRetur'])->whereIn('status_order', ['selesai', 'lunas']);

        if ($tanggal) {
            $query->whereDate('created_at', $tanggal);
            $jenis = 'Harian';
            $periode = \Carbon\Carbon::parse($tanggal)->translatedFormat('l, d F Y');
        } else {
            $query->whereMonth('created_at', $bulan)->whereYear('created_at', $tahun);
            $jenis = 'Bulanan';
            $periode = \Carbon\Carbon::create()->month((int) $bulan)->translatedFormat('F') . ' ' . $tahun;
        }
        
        $transaksi = $query->orderBy('created_at', 'asc')->get();
        
        // 👇 Hitung Pendapatan Bersih untuk versi PDF 👇
        $transaksi->map(function ($trx) {
            $pendapatan = $trx->total_harga - $trx->ongkos_kirim;
            if ($trx->pengajuanRetur && $trx->pengajuanRetur->nominal_refund) {
                $pendapatan -= $trx->pengajuanRetur->nominal_refund;
            }
            $trx->pendapatan_bersih = $pendapatan;
            return $trx;
        });

        // 👇 Ganti juga sum-nya di sini 👇
        $totalPendapatan = $transaksi->sum('pendapatan_bersih');
        
        $pdf = Pdf::loadView('pdf.pdf', compact('transaksi', 'jenis', 'periode', 'totalPendapatan'));
        return $pdf->stream('Laporan_Penjualan_AnekaTech_'.$jenis.'.pdf');
    }
}