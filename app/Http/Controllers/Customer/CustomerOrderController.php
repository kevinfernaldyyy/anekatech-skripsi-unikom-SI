<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller; 
use Illuminate\Http\Request;
use App\Models\Transaksi;
use Illuminate\Support\Facades\Auth;

class CustomerOrderController extends Controller
{
    public function index()
    {
        $transaksis = Transaksi::where('customer_id', Auth::id())
                        ->orderBy('created_at', 'desc')
                        ->get();

        return view('customer.orders.index', compact('transaksis'));
    }

    public function show($id)
    {
        $transaksi = Transaksi::with(['details.barang'])
                        ->where('id', $id)
                        ->where('customer_id', Auth::id())
                        ->firstOrFail();

        return view('customer.orders.show', compact('transaksi'));
    }
    public function accPO($id) 
    {
        $transaksi = Transaksi::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        $transaksi->status_order = 'belum_bayar'; // Lanjut ke pembayaran
        $transaksi->save();
        
        return back()->with('success', 'Penawaran disetujui! Silakan lakukan transfer pembayaran.');
    }

    public function tolakPO($id) 
    {
        $transaksi = Transaksi::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        $transaksi->status_order = 'batal'; // Batalkan karena harga tidak cocok
        $transaksi->save();
        
        return back()->with('error', 'Pesanan PO dibatalkan. Silakan cari alternatif lain di katalog.');
    }
public function cetakGaransiQc(Request $request, $id)
{
    // 1. Ambil transaksi beserta detail barang yang dibeli Kustomer
    $transaksi = \App\Models\Transaksi::with('details.barang')->findOrFail($id);
    $itemPaket = $transaksi->details->first();
    $paket     = $itemPaket->barang;

    // Ambil nomor unit dari parameter URL (default: 1)
    $nomor_unit = $request->query('unit', 1);

    // 2. Ambil data RakitanLog terbaru untuk PC Ready Stock tersebut
    $log = \App\Models\RakitanLog::with('barang.components')
                ->where('barang_id', $paket->id)
                ->latest()
                ->first();

    // Cek pengaman jika data Log Rakitan belum ada di database
    if (!$log) {
        return back()->with('error', 'Data QC Master Rakitan belum ditemukan untuk produk ini.');
    }

    // 3. Ambil DokumenQc sesuai log & unit (atau fallback model kosong agar view tidak crash)
    $dokumen = \App\Models\DokumenQc::where('rakitan_log_id', $log->id)
                ->where('nomor_unit', $nomor_unit)
                ->first() ?? new \App\Models\DokumenQc();

    // 4. Panggil template pdf.garansi_master yang persis sama dengan Admin!
    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.garansi_master', [
        'paket'     => $paket,
        'dokumen'   => $dokumen,
        'log'       => $log,
        'nomorUnit' => $nomor_unit,
        'transaksi' => $transaksi // Tambahan opsi agar no invoice bisa dipakai di view
    ]);

    return $pdf->stream('KARTU_GARANSI_QC_' . strtoupper(str_replace(' ', '_', $paket->nama_barang)) . '_TRX-' . $transaksi->no_transaksi . '.pdf');
}
}