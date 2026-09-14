<?php

namespace App\Http\Controllers\Teknisi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\DokumenQc;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderReadyMail;


class OrderController extends Controller
{
    public function index()
    {
        $transaksis = Transaksi::with(['customer'])
            ->where('teknisi_id', Auth::id())
            ->where('status_order', 'sedang_dirakit')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
       return view('teknisi.orders.index', compact('transaksis'));
    }
    public function show($id)
    {
        $transaksi = Transaksi::with(['details.barang', 'customer', 'dokumenQc'])->findOrFail($id);
        if ($transaksi->teknisi_id != Auth::id()) {
            abort(403, 'Akses Ditolak: Ini bukan tugas rakitan Anda.');
        }
        $teknisis = [];
        return view('teknisi.orders.show', compact('transaksi', 'teknisis'));
    }

public function history(Request $request)
    {
        $query = Transaksi::with(['customer'])
            ->where('teknisi_id', Auth::id())
            ->whereIn('status_order', ['siap_diambil', 'sedang_dikirim', 'selesai']);
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('no_transaksi', 'like', '%' . $search . '%')
                  ->orWhere('nama_customer', 'like', '%' . $search . '%');
            });
        }
        $bulan = $request->has('bulan') ? $request->bulan : date('m');
        $tahun = $request->has('tahun') ? $request->tahun : date('Y');
        if ($bulan != 'all') {
            $query->whereMonth('updated_at', $bulan)
                  ->whereYear('updated_at', $tahun);
        }
        $transaksis = $query->orderBy('updated_at', 'desc')->paginate(10);
        $transaksis->appends($request->all());
        return view('teknisi.orders.history', compact('transaksis', 'bulan')); 
    }
public function storeQc(Request $request, $id)
{
    // 1. Validasi input modular per unit
    $request->validate([
        'nomor_unit'      => 'required|integer',
        'nama_pc'         => 'required|string',
        'foto_depan'      => 'required|image|max:2048',
        'foto_belakang'   => 'required|image|max:2048',
        'foto_menyala'    => 'required|image|max:2048',
        'catatan_teknisi' => 'nullable|string'
    ]);

    // Eager loading data transaksi, customer, dan detail barang
    $transaksi = Transaksi::with(['customer', 'details.barang'])->findOrFail($id);

    // 2. Simpan foto ke folder khusus per-unit
    $pathDepan    = $request->file('foto_depan')->store('qc/unit_' . $request->nomor_unit, 'public');
    $pathBelakang = $request->file('foto_belakang')->store('qc/unit_' . $request->nomor_unit, 'public');
    $pathMenyala  = $request->file('foto_menyala')->store('qc/unit_' . $request->nomor_unit, 'public');

    // 3. Simpan atau Update QC KHUSUS untuk unit tersebut
    $dokumen = DokumenQc::updateOrCreate(
        [
            'transaksi_id' => $transaksi->id,
            'nomor_unit'   => $request->nomor_unit,
        ],
        [
            'nama_pc'         => $request->nama_pc,
            'foto_depan'      => $pathDepan,
            'foto_belakang'   => $pathBelakang,
            'foto_menyala'    => $pathMenyala,
            'catatan_teknisi' => $request->catatan_teknisi,
        ]
    );

    // 4. Generate PDF Garansi KHUSUS untuk Unit Ini
    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.garansi', [
        'transaksi' => $transaksi,
        'dokumen'   => $dokumen,
        'nomorUnit' => $request->nomor_unit
    ]);

    $namaFilePdf = 'GARANSI_' . $transaksi->no_transaksi . '_UNIT-' . $request->nomor_unit . '.pdf';
    \Illuminate\Support\Facades\Storage::disk('public')->put('qc/pdf/' . $namaFilePdf, $pdf->output());

    $dokumen->update(['file_pdf' => 'qc/pdf/' . $namaFilePdf]);

    // 👇 FIX: LANGSUNG BACA TOTAL UNIT DARI DATABASE (TIDAK PERLU TEBAK NAMA CPU LAGI) 👇
    $totalUnitDibutuhkan = $transaksi->details->max('nomor_unit') ?: 1;
    $totalQcSelesai      = $transaksi->dokumenQcs()->count();

    // Jika SEMUA unit sudah selesai di-QC
    if ($totalQcSelesai >= $totalUnitDibutuhkan && $transaksi->status_order == 'sedang_dirakit') {
        
        if ($transaksi->metode_pengiriman == 'technician') {
            $statusBaru = 'siap_diambil'; 
        } elseif ($transaksi->metode_pengiriman == 'delivery') {
            $statusBaru = 'sedang_dikirim';
        } else {
            $statusBaru = 'siap_diambil';
        }
        
        $transaksi->update(['status_order' => $statusBaru]);

        // Kirim email notifikasi ke customer
        $customer = $transaksi->customer;
        if ($customer && !empty($customer->email)) {
            try {
                $readyMail = new \App\Mail\OrderReadyEmail($transaksi);
                \App\Models\EmailLog::create([
                    'customer_id'     => $customer->id,
                    'recipient_email' => $customer->email,
                    'subject'         => $readyMail->envelope()->subject,
                    'body'            => $readyMail->render(),
                    'status'          => 'sent',
                    'trigger_type'    => 'order_ready'
                ]);

                \Illuminate\Support\Facades\Mail::to($customer->email)->send($readyMail);
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Gagal kirim OrderReadyEmail di Teknisi: ' . $e->getMessage());
            }
        }

        return redirect()->back()->with('success', "QC Unit #" . $request->nomor_unit . " selesai! SELURUH UNIT (" . $totalQcSelesai . "/" . $totalUnitDibutuhkan . ") TELAH BERES DI-QC!");
    }

    return redirect()->back()->with('success', "QC & Garansi untuk Unit #" . $request->nomor_unit . " berhasil diterbitkan! (" . $totalQcSelesai . "/" . $totalUnitDibutuhkan . " Unit Selesai)");
}

    public function cetakGaransiProduksi($log_id, $nomor_unit)
    {
        $log = \App\Models\RakitanLog::with('barang.components')->findOrFail($log_id);
        $paket = $log->barang;
        $dokumen = \App\Models\DokumenQc::where('rakitan_log_id', $log_id)
                    ->where('nomor_unit', $nomor_unit)
                    ->firstOrFail();
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.garansi_master', [
            'paket'     => $paket,
            'dokumen'   => $dokumen,
            'log'       => $log,
            'nomorUnit' => $nomor_unit
        ]);

        return $pdf->stream('QC_INTERNAL_' . str_replace(' ', '_', strtoupper($paket->nama_barang)) . '_BATCH-' . $log_id . '_UNIT-' . $nomor_unit . '.pdf');
    }

    public function tandaiOtw($id)
    {
        $transaksi = \App\Models\Transaksi::findOrFail($id);
        \App\Models\PengirimanTeknisi::create([
            'transaksi_id' => $transaksi->id,
            'waktu_berangkat' => now(), 
        ]);

        $transaksi->update([
            'status_order' => 'sedang_dikirim'
        ]);

        try {
            $emailCustomer = $transaksi->user->email; 
            \Illuminate\Support\Facades\Mail::to($emailCustomer)->send(new \App\Mail\TeknisiOtwMail($transaksi));
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Gagal kirim email OTW ke pelanggan: ' . $e->getMessage());
        }

        return redirect()->back()->with('success', 'Status diperbarui: Anda sedang OTW ke lokasi pelanggan. Hati-hati di jalan!');
    }
public function selesaikanTugas(Request $request, $id)
    {
        // 1. Validasi tetap sama (Minta PDF & Gambar)
        $request->validate([
            'foto_bukti_instalasi' => 'required|image|max:2048', 
            'dokumen' => 'required|mimes:pdf|max:5120',          
        ]);

        $transaksi = \App\Models\Transaksi::findOrFail($id);
        $pengiriman = \App\Models\PengirimanTeknisi::where('transaksi_id', $transaksi->id)->first();

        if ($pengiriman) {
            // 2. Simpan File ke Storage
            $pathInstalasi = $request->file('foto_bukti_instalasi')->store('bukti_lapangan', 'public');
            $pathDokumen = $request->file('dokumen')->store('dokumen_pengiriman', 'public');

            // 3. Update tabel pengiriman_teknisi (Semua file masuk ke sini!)
            $pengiriman->update([
                'waktu_selesai' => now(),
                'foto_bukti_instalasi' => $pathInstalasi,
                'dokumen' => $pathDokumen,
            ]);

            // 4. Update tabel transaksi (Cuma ganti status jadi selesai)
            $transaksi->update([
                'status_order' => 'selesai'
            ]);

            return redirect()->back()->with('success', 'Luar Biasa! Tugas instalasi selesai dan dokumen telah diarsipkan.');
        }

        return redirect()->back()->with('error', 'Data pengiriman tidak ditemukan.');
    }
}