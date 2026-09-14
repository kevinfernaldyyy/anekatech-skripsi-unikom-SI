<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $tab = $request->query('tab', 'semua');
        
        $query = ActivityLog::with('user')->latest();
        
        if ($tab == 'stok') {
            $query->whereIn('action', ['stock_in', 'stock_out']);
        } elseif ($tab == 'aktivitas') {
            $query->whereNotIn('action', ['stock_in', 'stock_out']);
        } elseif ($tab == 'audit') {
            $query->whereIn('action', ['create', 'update', 'delete', 'stock_in', 'stock_out'])
                  ->whereHas('user', function($q) {
                      $q->where('role', 'admin');
                  })
                  // Filter Sapu Jagat
                  ->where('description', 'not like', '%Transaksi%')
                  ->where('description', 'not like', '%Pesanan%')
                  ->where('description', 'not like', '%Retur%')
                  ->where('description', 'not like', '%Ongkos kirim%')
                  // 👇 TAMBAHIN SATU BARIS INI BUAT NENDANG LOG PENGGUNA 👇
                  ->where('description', 'not like', '%pengguna%');
        }
        
        $logs = $query->paginate(20)->withQueryString();
        
        return view('admin.logs.index', compact('logs', 'tab'));
    }

public function cetakPdf()
    {
        $logs = ActivityLog::with('user')
            ->whereIn('action', ['create', 'update', 'delete', 'stock_in', 'stock_out'])
            ->whereHas('user', function($q) {
                $q->where('role', 'admin');
            })
            // 👇 Samain filternya buat PDF 👇
            ->where('description', 'not like', '%Transaksi%')
            ->where('description', 'not like', '%Pesanan%')
            ->where('description', 'not like', '%Retur%')
            ->where('description', 'not like', '%Ongkos kirim%')
            ->where('description', 'not like', '%pengguna%')
            ->latest()
            ->get();

        $pdf = Pdf::loadView('pdf.logs', compact('logs'));
        
        return $pdf->stream('Laporan_Audit_Master_Barang_AnekaTech.pdf');
    }
    
}