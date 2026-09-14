<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\ActivityLog; // 👈 Pastikan ActivityLog di-import
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\RakitanLog; 
use App\Models\DokumenQc;

class BundleController extends Controller
{
public function edit(string $id)
{
    $paket = Barang::findOrFail($id);
    if($paket->jenis != 'paket') {
        return back()->with('error', 'Hanya jenis Paket PC yang bisa diatur komponennya.');
    }
    $komponens = Barang::where('jenis', 'komponen')->get();
                       
    return view('admin.bundle.edit', compact('paket', 'komponens'));
}

    public function update(Request $request, string $id)
    {
        $paket = Barang::findOrFail($id);
        
        $syncData = [];
        if ($request->components) {
            foreach ($request->components as $index => $childId) {
                $qty = $request->qtys[$index] ?? 1;
                if ($qty > 0 && $childId && $childId != $id) { 
                    $syncData[$childId] = ['qty' => $qty];
                }
            }
        }
        
        // 1. Sinkronisasi resep komponen ke database
        $paket->components()->sync($syncData);
        $paket->load('components');    

        // 2. Hitung total harga normal komponen (eceran)
        $totalHargaKomponen = 0;
        foreach ($paket->components as $komponen) {
            $totalHargaKomponen += ($komponen->harga_jual * $komponen->pivot->qty);
        }

        // 3. Cek apakah admin mengaktifkan switch diskon bundle 10%
        $isDiskon = $request->has('is_diskon_bundle') ? 1 : 0;

        if ($isDiskon) {
            // Otomatis potong 10% dari total komponen
            $hargaJualPaket = $totalHargaKomponen - ($totalHargaKomponen * 0.10);
        } else {
            // Jika tidak diskon, pakai input manual Admin ATAU default total komponen
            $hargaJualPaket = $request->harga_jual > 0 ? $request->harga_jual : $totalHargaKomponen;
        }

        $paket->update([
            'harga_jual'       => $hargaJualPaket,
            'is_diskon_bundle' => $isDiskon
        ]);

        return back()->with('success', 'Komposisi komponen dan promo bundle berhasil diperbarui!');
    }

public function build(Request $request, string $id)
{
    $masterPaket = Barang::with('components')->findOrFail($id);
    $qtyBuild = $request->qty_build;
    if ($qtyBuild < 1) return back()->with('error', 'Jumlah rakit minimal 1 unit.');
    
    DB::beginTransaction();
    try {
        $snapshotKomponen = [];        
        
        // 1. Cek ketersediaan stok semua komponen dulu
        foreach ($masterPaket->components as $komponen) {
            $butuhTotal = $komponen->pivot->qty * $qtyBuild;
            if ($komponen->stok < $butuhTotal) {
                DB::rollBack(); 
                return back()->with('error', "Stok '{$komponen->nama_barang}' kurang. Butuh: $butuhTotal, Ada: {$komponen->stok}");
            }
        }
        
        // 2. Potong stok komponen & CATAT LOG AUDIT (stock_out)
        foreach ($masterPaket->components as $komponen) {
            $butuhTotal = $komponen->pivot->qty * $qtyBuild;
            $stokLama = $komponen->stok;
            
            $komponen->decrement('stok', $butuhTotal);
            $snapshotKomponen[] = "{$komponen->nama_barang} (-{$butuhTotal})";
            
            // 👇 Catat audit log pemotongan stok komponen akibat bundling
            ActivityLog::record(
                'stock_out',
                "Stok berkurang {$butuhTotal} unit untuk produksi/bundling paket: {$masterPaket->nama_barang}",
                $komponen,
                ['stok' => $stokLama],
                ['stok' => $stokLama - $butuhTotal]
            );
        }            
        
        // 3. Tambah stok paket PC yang sudah jadi & CATAT LOG AUDIT (stock_in)
        $stokMasterLama = $masterPaket->stok;
        $masterPaket->increment('stok', $qtyBuild);
        
        ActivityLog::record(
            'stock_in',
            "Stok bertambah +{$qtyBuild} unit hasil produksi/rakitan paket PC",
            $masterPaket,
            ['stok' => $stokMasterLama],
            ['stok' => $stokMasterLama + $qtyBuild]
        );
        
        // 4. Catat di tabel log khusus rakitan (biar riwayat internal tetap ada)
        $logProduksi = RakitanLog::create([
            'barang_id' => $masterPaket->id,
            'qty_rakit' => $qtyBuild,
            'detail_snapshot' => "Merakit {$qtyBuild} unit. Komponen dipotong: " . implode(', ', $snapshotKomponen),
        ]);

        // 👇 5. OTOMATIS BUAT SLOT ANTRIES QC PER-UNIT UNTUK READY STOCK 👇
        for ($u = 1; $u <= $qtyBuild; $u++) {
            DokumenQc::create([
                'jenis_qc'       => 'produksi',
                'transaksi_id'   => null,
                'barang_id'      => $masterPaket->id,
                'rakitan_log_id' => $logProduksi->id,
                'nomor_unit'     => $u,
                'nama_pc'        => $masterPaket->nama_barang . " (Ready Stock Batch #" . $logProduksi->id . " - Unit #" . $u . ")",
                'catatan_teknisi'=> 'Unit Ready Stock Display Toko - Lolos Pengecekan Internal.'
            ]);
        }

        $totalHargaKomponen = 0;
        foreach ($masterPaket->components as $comp) {
            $totalHargaKomponen += $comp->harga_jual * $comp->pivot->qty;
        }

        $isDiskon = $request->has('is_diskon_bundle') ? 1 : 0;
        $hargaJualResmi = ($isDiskon == 1) ? ($totalHargaKomponen * 0.9) : $totalHargaKomponen;

        $masterPaket->update([
            'is_diskon_bundle' => $isDiskon,
            'harga_jual'       => $hargaJualResmi
        ]);

        // 7. Log rangkuman proses BUILD
        $totalJenis = count($snapshotKomponen);
        ActivityLog::record(
            'BUILD',
            "Berhasil memproduksi {$qtyBuild} unit '{$masterPaket->nama_barang}' ({$totalJenis} jenis komponen terpakai). Harga Resmi: Rp " . number_format($hargaJualResmi, 0, ',', '.'),
            $masterPaket
        );
        
        DB::commit();
        return redirect()->route('admin.barang.index', ['jenis' => 'paket_ready'])
                         ->with('success', "Sukses! {$qtyBuild} unit '{$masterPaket->nama_barang}' berhasil dirakit & dokumen QC siap dicetak.");
    } catch (\Exception $e) {
        DB::rollBack();
        return back()->with('error', 'Gagal merakit: ' . $e->getMessage());
    }
}

}