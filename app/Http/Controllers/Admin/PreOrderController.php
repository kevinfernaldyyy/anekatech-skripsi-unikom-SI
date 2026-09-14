<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PreOrder;
use App\Models\Transaksi;
use App\Models\EmailLog;

class PreOrderController extends Controller
{
    public function index()
    {
        $preOrders = PreOrder::with(['customer', 'items.barang'])->latest()->paginate(10);
        return view('admin.preorders.index', compact('preOrders'));
    }
    public function markAsArrived($id)
    {
        $po = PreOrder::with('customer')->findOrFail($id);
        $po->update(['status' => 'arrived']);
        if ($po->customer && $po->customer->email) {
            EmailLog::create([
                'customer_id' => $po->customer_id,
                'pre_order_id' => $po->id,
                'recipient_email' => $po->customer->email,
                'subject' => "HORE! Barang PO #{$po->no_po} Sudah Ready!",
                'body' => "Halo kak {$po->customer->nama}, barang pesanan kakak sudah sampai di toko kami. Silakan datang untuk pelunasan dan pengambilan ya!",
                'status' => 'pending',
                'trigger_type' => 'barang_ready'
            ]);
        }
        return back()->with('success', 'Status PO berubah jadi Arrived. Notifikasi email dijadwalkan.');
    }
    public function processPelunasan(Request $request, $id)
    {
        $po = PreOrder::findOrFail($id);
        $transaksi = Transaksi::findOrFail($po->transaksi_id);
        $transaksi->update([
            'status_pembayaran' => 'lunas',
            'bayar' => $transaksi->total_harga, 
            'kembalian' => 0, 
        ]);
        $po->update(['status' => 'completed']);
        return back()->with('success', 'Pelunasan Berhasil! Transaksi selesai.');
    }
}