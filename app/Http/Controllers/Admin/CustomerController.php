<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Support\Facades\DB; // 👈 JANGAN LUPA IMPORT DB

class CustomerController extends Controller
{
    public function index()
    {
        $customers = \App\Models\User::whereNotIn('role', ['admin', 'teknisi'])
                    ->withCount('transaksi')
                    ->paginate(10);

        return view('admin.customers.index', compact('customers'));
    }

    public function show($id)
    {
        $customer = \App\Models\User::findOrFail($id); 

        $transaksis = \App\Models\Transaksi::with(['detail_transaksi.barang']) 
                ->where('user_id', $id) 
                ->orderBy('created_at', 'desc')
                ->get();

        $riwayatPerBulan = $transaksis->groupBy(function($item) {
            return \Carbon\Carbon::parse($item->created_at)->translatedFormat('F Y'); 
        });

        // 👇 PERBAIKAN 1: Gunakan 'status_order' karena kolom 'status' tidak ada di DB
        $totalBelanja = $transaksis->whereIn('status_order', ['selesai'])->sum('total_harga');

        // 👇 PERBAIKAN 2: Ambil semua transaksi ID yang punya data Retur
        $trxIds = $transaksis->pluck('id')->toArray();
        $returTrxIds = DB::table('pengajuan_return')
                        ->whereIn('transaksi_id', $trxIds)
                        ->pluck('transaksi_id')
                        ->toArray();

        // Lempar variabel returTrxIds ke view
        return view('admin.customers.show', compact('customer', 'riwayatPerBulan', 'totalBelanja', 'transaksis', 'returTrxIds'));
    }
}