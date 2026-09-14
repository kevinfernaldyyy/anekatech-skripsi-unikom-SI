<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Barang;
use App\Models\Transaksi;
use App\Models\TransaksiDetail;
use App\Models\Customer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\AccPOMail;
use App\Mail\TolakPOMail;
use App\Mail\RevisiPOMail;
use App\Models\ActivityLog;

class CartController extends Controller
{
public function add(\Illuminate\Http\Request $request, $id)
{
    $barang = \App\Models\Barang::findOrFail($id);
    $cart = session()->get('cart', []);

    $jumlahProcessorDiCart = 0;
    foreach ($cart as $item) {
        $namaCek = strtolower($item['nama_barang'] ?? '');
        $katCek  = strtolower($item['kategori'] ?? '');
        if (str_contains($namaCek, 'ryzen') || str_contains($namaCek, 'core i') || str_contains($namaCek, 'core ultra') || str_contains($namaCek, 'processor') || str_contains($katCek, 'processor') || str_contains($katCek, 'cpu')) {
            $jumlahProcessorDiCart++;
        }
    }

    $namaBaru = strtolower($barang->nama_barang);
    $katBaru  = strtolower($barang->kategori);
    $isBarangIniProcessor = str_contains($namaBaru, 'ryzen') || str_contains($namaBaru, 'core i') || str_contains($namaBaru, 'core ultra') || str_contains($namaBaru, 'processor') || str_contains($katBaru, 'processor') || str_contains($katBaru, 'cpu');

    $autoUnit = $isBarangIniProcessor ? ($jumlahProcessorDiCart + 1) : max(1, $jumlahProcessorDiCart);

    $cartKey = $id . '_unit_' . $autoUnit;

    if (isset($cart[$cartKey])) {
        // Jika barangnya sama DAN untuk Unit PC yang sama -> baru tambah QTY
        $cart[$cartKey]['qty']++;
    } else {
        // Jika untuk Unit PC yang berbeda -> BUAT BARIS BARU DI BAWAH!
        $cart[$cartKey] = [
            "id"          => $barang->id,
            "nama_barang" => $barang->nama_barang,
            "qty"         => 1,        
            "harga"       => $barang->harga_jual ?? 0, 
            "gambar"      => $barang->gambar,
            "kategori"    => $barang->kategori,
            "stok"        => $barang->stok,
            "nomor_unit"  => $autoUnit // 👈 Terikat otomatis ke Unit PC yang tepat
        ];
    }
    session()->put('cart', $cart);

    // Perhitungan total harga
    $totalHargaBaru = 0;
    foreach ($cart as $item) {
        $totalHargaBaru += ($item['harga'] ?? 0) * ($item['qty'] ?? 1);
    }

    if ($request->ajax() || $request->wantsJson()) {
        return response()->json([
            'success'    => true,
            'cart_count' => count(session('cart')),
            'new_total'  => 'Rp ' . number_format($totalHargaBaru, 0, ',', '.'), 
            'message'    => 'Barang berhasil masuk keranjang!' 
        ]);
    }
    
    return redirect()->back()->with('success', $barang->nama_barang . ' berhasil ditambahkan ke keranjang!');
}
public function index()
{
    $cart = session()->get('cart', []);
    $total = 0;
    
    // 🔥 1. AMBIL ID BARANG YANG BENAR (Tahan terhadap key "8_unit_1")
    $itemIds = collect($cart)->pluck('id')->filter()->unique()->toArray();
    $dbItems = \App\Models\Barang::whereIn('id', $itemIds)->get()->keyBy('id');

    // Hitung total harga & cek apakah ada PC Ready / Paket
    $hasReadyPC = false;
    foreach ($cart as $id => $item) {
        $hargaItem = $item['harga'] ?? $item['price'] ?? 0;
        $total += $hargaItem * $item['qty'];

        $kat   = strtolower(trim($item['kategori'] ?? ''));
        $jenis = strtolower(trim($item['jenis'] ?? ''));
        $dbItem = $dbItems->get($item['id'] ?? null);

        if (($dbItem && strtolower(trim($dbItem->jenis)) == 'paket_ready') ||
            str_contains($kat, 'pc') || str_contains($kat, 'rakitan') || str_contains($kat, 'build') || $jenis == 'paket_ready') {
            $hasReadyPC = true;
        }
    }

    $isClash = false;
    $pesanClashArr = [];
    $clashItemIds  = [];

    // 🔥 2. KELOMPOKKAN KERANJANG BERDASARKAN NOMOR UNIT PC 🔥
    $units = [];
    foreach ($cart as $cartKey => $item) {
        if (isset($item['is_po']) && $item['is_po']) continue;
        $u = $item['nomor_unit'] ?? 1;
        $units[$u][$cartKey] = $item;
    }

    // 🔥 3. ALGORITMA PENGECEKAN MENYELURUH (DILAKUKAN PER UNIT PC) 🔥
    if (count($cart) > 0 && !$hasReadyPC) {
        $ffLevel = ['mini-itx' => 1, 'micro-atx' => 2, 'atx' => 3, 'e-atx' => 4];

        foreach ($units as $noUnit => $unitCart) {
            $mobos = []; $cpus = []; $rams = []; $cases = []; $vgas = []; $psus = []; $coolers = [];

            // Pisahkan kategori khusus untuk unit ini saja
            foreach ($unitCart as $cartKey => $item) {
                $dbItem = $dbItems->get($item['id'] ?? null);
                if (!$dbItem) continue;

                // Simpan cartKey agar bisa di-highlight merah di UI jika clash
                for ($i = 0; $i < $item['qty']; $i++) {
                    $obj = clone $dbItem;
                    $obj->cart_key = $cartKey;

                    $kat = strtolower(trim($dbItem->kategori));
                    if ($kat == 'motherboard') $mobos[] = $obj;
                    elseif ($kat == 'processor') $cpus[] = $obj;
                    elseif ($kat == 'ram') $rams[] = $obj;
                    elseif ($kat == 'casing') $cases[] = $obj;
                    elseif (str_contains($kat, 'vga') || str_contains($kat, 'gpu')) $vgas[] = $obj;
                    elseif ($kat == 'psu') $psus[] = $obj;
                    elseif ($kat == 'cooler') $coolers[] = $obj;
                }
            }

            $labelUnit = count($units) > 1 ? "PC Unit #{$noUnit}: " : "";

            // --- CEK KELENGKAPAN WAJIB PER UNIT ---
            if (count($mobos) == 0 || count($cpus) == 0 || count($rams) == 0) {
                $isClash = true;
                $pesanClashArr[] = "{$labelUnit}Komponen utama tidak lengkap (Butuh min 1 Processor, 1 Motherboard, dan 1 RAM).";
                continue; // Lanjut cek unit berikutnya
            } 
            elseif (count($mobos) !== count($cpus)) {
                $isClash = true;
                $pesanClashArr[] = "{$labelUnit}Jumlah Processor dan Motherboard harus seimbang.";
                continue;
            }

            $unmatchedCPUs  = $cpus;
            $unmatchedRAMs  = $rams;
            $unmatchedCases = $cases;
            $unmatchedPSUs  = $psus;

            foreach ($mobos as $mobo) {
                $moboSocket = trim(strtolower($mobo->socket));
                $moboMem    = trim(strtolower($mobo->tipe_memory));
                $mLevel     = $ffLevel[strtolower($mobo->form_factor)] ?? 2;

                // A. Cek CPU vs Motherboard
                $cpuMatched = null;
                foreach ($unmatchedCPUs as $k => $cpu) {
                    $cpuSocket = trim(strtolower($cpu->socket));
                    if (str_contains($cpuSocket, $moboSocket) || str_contains($moboSocket, $cpuSocket)) {
                        $cpuMatched = $cpu;
                        unset($unmatchedCPUs[$k]);
                        break;
                    }
                }
                if (!$cpuMatched) {
                    $isClash = true;
                    $pesanClashArr[] = "{$labelUnit}Soket Processor dan Motherboard tidak cocok.";
                    $clashItemIds[]  = $mobo->cart_key;
                    foreach ($unmatchedCPUs as $c) $clashItemIds[] = $c->cart_key;
                }

                // B. Cek RAM vs Motherboard
                $ramMatched = null;
                foreach ($unmatchedRAMs as $k => $ram) {
                    $ramMem = trim(strtolower($ram->tipe_memory));
                    if ($ramMem == $moboMem || str_contains($ramMem, $moboMem) || str_contains($moboMem, $ramMem)) {
                        $ramMatched = $ram;
                        unset($unmatchedRAMs[$k]);
                        break;
                    }
                }
                if (!$ramMatched) {
                    $isClash = true;
                    $pesanClashArr[] = "{$labelUnit}Tipe RAM tidak cocok dengan Motherboard (Harus {$mobo->tipe_memory}).";
                    $clashItemIds[]  = $mobo->cart_key;
                    foreach ($unmatchedRAMs as $r) $clashItemIds[] = $r->cart_key;
                }

                // C. Cek Casing vs Motherboard
                if (count($unmatchedCases) > 0) {
                    $caseMatched = null;
                    foreach ($unmatchedCases as $k => $case) {
                        $cLevel = $ffLevel[strtolower($case->form_factor)] ?? 3;
                        if ($mLevel <= $cLevel) { 
                            $caseMatched = $case;
                            unset($unmatchedCases[$k]);
                            break;
                        }
                    }
                    if (!$caseMatched) {
                        $isClash = true;
                        $pesanClashArr[] = "{$labelUnit}Ukuran Motherboard terlalu besar untuk Casing.";
                        $clashItemIds[]  = $mobo->cart_key;
                        foreach ($cases as $c) $clashItemIds[] = $c->cart_key; 
                    }
                }

                // D. Cek Cooler (Soket & Dimensi Casing)
                if (count($coolers) > 0) {
                    foreach ($coolers as $cooler) {
                        $coolerSockets = strtolower($cooler->socket);
                        if ($coolerSockets && !str_contains($coolerSockets, $moboSocket) && !str_contains($moboSocket, $coolerSockets)) {
                            $isClash = true;
                            $pesanClashArr[] = "{$labelUnit}Cooler tidak mendukung soket Motherboard/Processor.";
                            $clashItemIds[]  = $cooler->cart_key;
                        }
                        foreach ($cases as $case) {
                            $coolerLevel = $ffLevel[strtolower($cooler->form_factor)] ?? 2;
                            $caseLevel   = $ffLevel[strtolower($case->form_factor)] ?? 3;
                            if ($coolerLevel > $caseLevel) {
                                $isClash = true;
                                $pesanClashArr[] = "{$labelUnit}Ukuran Cooler/Radiator terlalu besar untuk Casing.";
                                $clashItemIds[]  = $cooler->cart_key;
                                $clashItemIds[]  = $case->cart_key;
                            }
                        }
                    }
                }

                // E. Cek VGA vs Casing
                if (count($vgas) > 0 && count($cases) > 0) {
                    foreach ($vgas as $vga) {
                        foreach ($cases as $case) {
                            $vgaLevel  = $ffLevel[strtolower($vga->form_factor)] ?? 2;
                            $caseLevel = $ffLevel[strtolower($case->form_factor)] ?? 3;
                            if ($vgaLevel > $caseLevel) {
                                $isClash = true;
                                $pesanClashArr[] = "{$labelUnit}VGA terlalu panjang/besar untuk Casing.";
                                $clashItemIds[]  = $vga->cart_key;
                                $clashItemIds[]  = $case->cart_key;
                            }
                        }
                    }
                }

                // F. Cek Daya PSU
                $cpuPower = $cpuMatched->power_watt ?? 65;
                $vgaPower = (count($vgas) > 0) ? ($vgas[0]->power_watt ?? 150) : 0;
                $reqPower = $cpuPower + $vgaPower + 100; 

                if (count($unmatchedPSUs) > 0) {
                    $psuMatched = null;
                    foreach ($unmatchedPSUs as $k => $psu) {
                        if (($psu->power_watt ?? 0) >= $reqPower) {
                            $psuMatched = $psu;
                            unset($unmatchedPSUs[$k]);
                            break;
                        }
                    }
                    if (!$psuMatched) {
                        $isClash = true;
                        $pesanClashArr[] = "{$labelUnit}Kapasitas PSU kurang (Butuh minimal ±{$reqPower}W).";
                        foreach ($unmatchedPSUs as $p) $clashItemIds[] = $p->cart_key;
                    }
                }
            }
        }
    }
    $pesanClashArr = array_unique($pesanClashArr);
    $clashItemIds  = array_unique($clashItemIds);

    return view('customer.cart', compact('cart', 'total', 'isClash', 'pesanClashArr', 'clashItemIds', 'hasReadyPC'));
}
public function addBundle(\Illuminate\Http\Request $request)
{
    $barangIds = $request->input('barang_ids');

    if (!$barangIds || !is_array($barangIds)) {
        return back()->with('error', 'Tidak ada komponen rakitan yang dipilih.');
    }

    $cart = session()->get('cart', []);
    $maxUnit = 0;
    foreach ($cart as $item) {
        if (isset($item['nomor_unit']) && $item['nomor_unit'] > $maxUnit) {
            $maxUnit = $item['nomor_unit'];
        }
    }
    $newUnit = $maxUnit + 1;

    // 2. Looping array ID Barang dan masukkan ke keranjang dengan nomor_unit baru
    $berhasilDitambah = 0;
    foreach ($barangIds as $id) {
        $barang = \App\Models\Barang::find($id);
        
        // Pastikan barang ada dan stoknya tidak kosong
        if ($barang && $barang->stok > 0) {
            
            // Format key sama persis dengan fungsi add() kamu
            $cartKey = $barang->id . '_unit_' . $newUnit;
            
            $cart[$cartKey] = [
                "id"          => $barang->id,
                "nama_barang" => $barang->nama_barang,
                "qty"         => 1,        
                "harga"       => $barang->harga_jual ?? 0, 
                "gambar"      => $barang->gambar,
                "kategori"    => $barang->kategori,
                "stok"        => $barang->stok,
                "nomor_unit"  => $newUnit // 👈 Otomatis jadi 1 kelompok PC
            ];
            $berhasilDitambah++;
        }
    }

    if ($berhasilDitambah > 0) {
        session()->put('cart', $cart);
        // Sesuaikan route redirect ke halaman index keranjang kamu
        return redirect()->back()->with('success', '1 Set PC Rakitan (Unit #' . $newUnit . ') berhasil ditambahkan ke keranjang!');
    }

    return back()->with('error', 'Gagal menambahkan rakitan, stok komponen mungkin kosong.');
}
    public function remove($id)
    {
        $cart = session()->get('cart');
        
        if(isset($cart[$id])) {
            unset($cart[$id]); 
            session()->put('cart', $cart); 
        }
        return redirect()->back()->with('success', 'Komponen berhasil dihapus dari daftar rakitan!');
    }

public function checkout(Request $request)
    {
        // Tetap berlakukan validasi ketat di awal sesuai keinginanmu, Bos!
        $request->validate([
            'metode_pengiriman' => 'required',
            'alamat' => 'required',
            'status_pembayaran' => 'required|in:lunas,dp',
            'is_rakit' => 'required|in:0,1', 
        ]);
        
        $cart = session()->get('cart');
        if (!$cart || count($cart) == 0) {
            return redirect()->back()->with('error', 'Keranjang Anda kosong.');
        }
        
        // 1. CEK APAKAH ADA BARANG CUSTOM (PO) DAN HITUNG TOTAL
        $total = 0;
        $isNegoPO = false;

        foreach ($cart as $item) {
            if (isset($item['is_custom']) && $item['is_custom'] == true) {
                $isNegoPO = true;
            }
            $hargaItem = $item['harga'] ?? $item['price'] ?? 0;
            $total += $hargaItem * $item['qty'];
        }
        
        if ($request->metode_pengiriman == 'technician' && $total < 20000000 && !$isNegoPO) {
            return redirect()->back()->with('error', 'Metode teknisi hanya untuk pesanan di atas 20 Juta.');
        }
        
        // 👇 LOGIKA PENGGABUNGAN AREA LAYANAN & ALAMAT 👇
        $alamatFinal = $request->alamat;
        if ($request->metode_pengiriman === 'technician' && $request->filled('area_layanan')) {
            $namaArea = match($request->area_layanan) {
                'bandung_raya' => 'Bandung Raya',
                'jawa_barat' => 'Jawa Barat Sekitarnya',
                'jabodetabek' => 'Jabodetabek',
                default => 'Area Khusus'
            };
            $alamatFinal = "[Area Layanan: " . $namaArea . "]\n\n" . $alamatFinal;
        }

        // Kalkulasi bayar awal
        $bayar = $request->status_pembayaran == 'dp' ? ($total * 0.50) : $total;
        $no_transaksi = 'TRX-' . date('Ymd') . '-' . strtoupper(\Illuminate\Support\Str::random(4));
        $customer = \App\Models\Customer::where('user_id', \Illuminate\Support\Facades\Auth::id())->first();
        
        $statusOrder = $isNegoPO ? 'menunggu_kalkulasi' : 'belum_bayar';

        \Illuminate\Support\Facades\DB::beginTransaction();
        
        try {
            $transaksi = \App\Models\Transaksi::create([
                'no_transaksi' => $no_transaksi,
                'user_id' => \Illuminate\Support\Facades\Auth::id(),
                'customer_id' => $customer ? $customer->id : null,
                'nama_customer' => \Illuminate\Support\Facades\Auth::user()->name,
                'nomor_telepon' => \Illuminate\Support\Facades\Auth::user()->phone ?? '0000',
                
                'alamat' => $alamatFinal, 
                'catatan' => $request->catatan,
                
                'metode_pengiriman' => $request->metode_pengiriman,
                'status_pembayaran' => $request->status_pembayaran, 
                
                'status_pesanan' => 'belum_bayar', 
                'status_order' => $statusOrder,    
                
                'total_harga' => $total,
                'bayar' => $bayar,
                'kembalian' => 0,
                'is_rakit' => (int) $request->is_rakit,
            ]);

            // 👇 1. HITUNG TOTAL PC LANGSUNG DARI DATABASE BARANG (100% AKURAT) 👇
            $totalUnitPc = 1;
            $cpuCount = 0;
            foreach ($cart as $c) {
                $isCustomCart = isset($c['is_custom']) && $c['is_custom'] == true;
                if (!$isCustomCart && isset($c['id'])) {
                    $cekBarang = \App\Models\Barang::find($c['id']);
                    if ($cekBarang) {
                        $nama = strtolower($cekBarang->nama_barang ?? '');
                        $kat  = strtolower($cekBarang->kategori ?? '');
                        if (str_contains($nama, 'ryzen') || str_contains($nama, 'core i') || str_contains($nama, 'processor') || str_contains($kat, 'processor')) {
                            $cpuCount += $c['qty'];
                        }
                    }
                }
            }
            if ($cpuCount > 1) {
                $totalUnitPc = $cpuCount;
            }

           $currentUnit = 0; // 🔥 Saklar nomor unit PC (otomatis naik jadi 1 saat ketemu Processor pertama)

            foreach ($cart as $item) {
                $isCustom  = isset($item['is_custom']) && $item['is_custom'] == true;
                $hargaItem = $item['harga'] ?? $item['price'] ?? 0;
                $namaItem  = $item['nama_barang'] ?? $item['name'] ?? 'Komponen Pre-Order';

                // --- PENGURANGAN STOK BARANG ---
                if (!$isCustom && isset($item['id'])) {
                    $barangDB = \App\Models\Barang::lockForUpdate()->find($item['id']);
                    if ($barangDB) {
                        if ($barangDB->stok < $item['qty']) {
                            throw new \Exception("Gagal Checkout: Stok '{$barangDB->nama_barang}' hanya tersisa {$barangDB->stok}.");
                        }
                        $barangDB->decrement('stok', $item['qty']);
                        ActivityLog::record(
                            'stock_out', 
                            "Stok '{$barangDB->nama_barang}' dikurangi {$item['qty']} (Checkout Kustomer untuk Transaksi {$transaksi->no_transaksi})", 
                            $barangDB
                        );
                    }
                }

                // --- 🔥 LOGIKA SAKLAR PINTAR: GANTI UNIT TIAP KETEMU PROCESSOR 🔥 ---
                $namaCek = strtolower($item['nama_barang'] ?? $item['name'] ?? '');
                $katCek  = strtolower($item['kategori'] ?? '');

                // Deteksi otomatis apakah item ini adalah PROCESSOR / CPU
                $isProcessor = str_contains($namaCek, 'ryzen') || 
                               str_contains($namaCek, 'core i') || 
                               str_contains($namaCek, 'core ultra') || 
                               str_contains($namaCek, 'processor') || 
                               str_contains($katCek, 'processor') || 
                               str_contains($katCek, 'cpu');

                // Jika ketemu Processor ATAU keranjang baru mulai (unit masih 0) -> Naikkan nomor unit PC!
                if ($isProcessor || $currentUnit === 0) {
                    $currentUnit++;
                }

                // Jika di cart sudah ada tag nomor_unit eksplisit, prioritaskan itu. Jika tidak, pakai saklar $currentUnit
                $unitTujuan = isset($item['nomor_unit']) ? (int) $item['nomor_unit'] : $currentUnit;

                // --- SIMPAN KE DATABASE TRANSAKSI DETAILS ---
                \App\Models\TransaksiDetail::create([
                    'transaksi_id'       => $transaksi->id,
                    'nomor_unit'         => $unitTujuan, // 👈 Otomatis rakitan di bawah CPU ikut nomor unit ini!
                    'barang_id'          => $isCustom ? null : $item['id'],
                    'nama_barang_custom' => $isCustom ? $namaItem : null,
                    'qty'                => $item['qty'],
                    'harga_satuan'       => $hargaItem,
                    'subtotal'           => $hargaItem * $item['qty'],
                    'link'               => $item['link'] ?? null, 
                ]);
            }

            ActivityLog::record(
                'create', 
                "Kustomer membuat transaksi baru: {$transaksi->no_transaksi} (Total: Rp" . number_format($transaksi->total_harga, 0, ',', '.') . ")", 
                $transaksi
            );
            \Illuminate\Support\Facades\DB::commit();
            session()->forget('cart');
            
            $pesanSukses = $isNegoPO 
                ? 'Request PO berhasil dikirim! Menunggu Admin menghitung estimasi biaya.' 
                : 'Pesanan berhasil dibuat! Nomor Transaksi: ' . $no_transaksi;

            // KIRIM EMAIL KE ADMIN
            try {
                $adminEmails = \App\Models\User::where('role', 'admin')->pluck('email')->toArray();
                if (!empty($adminEmails)) {
                    \Illuminate\Support\Facades\Mail::to($adminEmails)
                        ->send(new \App\Mail\PesananBaruAdminMail($transaksi));
                }
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Gagal kirim email Pesanan Baru: ' . $e->getMessage());
            }
                
            return redirect()->route('customer.dashboard')->with('success', $pesanSukses);
            
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
    
public function addCustom(Request $request)
    {
        $request->validate([
            'nama_barang' => 'required|string',
            'qty' => 'required|integer|min:1',
        ]);

        $cart = session()->get('cart', []);
        $customId = 'custom_' . time(); 

        $cart[$customId] = [
            'id' => $customId,
            'nama_barang' => $request->nama_barang . ' (Pre-Order)', // Pakai bahasa Indonesia
            'name' => $request->nama_barang . ' (Pre-Order)', // Cadangan bahasa Inggris
            'harga' => 0, 
            'price' => 0, 
            'qty' => $request->qty,
            'is_custom' => true,
            'is_po' => true,
            'link' => $request->link,
            'gambar' => null 
        ];

        session()->put('cart', $cart);

        return redirect()->route('cart.index')->with('success', 'Barang Custom PO berhasil dimasukkan ke keranjang!');
    }
public function accPO(string $id) 
    {
        $transaksi = \App\Models\Transaksi::where('id', $id)
                        ->where('user_id', \Illuminate\Support\Facades\Auth::id())
                        ->firstOrFail();
                        
        $transaksi->status_order = 'belum_bayar'; 
        $transaksi->status_pesanan = 'belum_bayar'; 
        if ($transaksi->status_pembayaran == 'dp') {
            $transaksi->bayar = $transaksi->total_harga * 0.50; 
        } else {
            $transaksi->bayar = $transaksi->total_harga;
        }
        $transaksi->save();
        ActivityLog::record('update', "Kustomer menyetujui penawaran harga Pre-Order (Siap Bayar) untuk Transaksi {$transaksi->no_transaksi}", $transaksi);
        try {
            $adminEmails = \App\Models\User::where('role', 'admin')->pluck('email')->toArray();
            if (!empty($adminEmails)) {
                Mail::to($adminEmails)->send(new AccPOMail($transaksi));
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Gagal kirim email ACC PO: ' . $e->getMessage());
        }
        
        return redirect()->back()->with('success', 'Penawaran harga disetujui! Silakan lakukan pembayaran sesuai tagihan baru.');
    }

public function tolakPO(string $id) 
    {
        $transaksi = \App\Models\Transaksi::with('details')->where('id', $id)
                        ->where('user_id', \Illuminate\Support\Facades\Auth::id())
                        ->firstOrFail();
        foreach ($transaksi->details as $detail) {
           
            if (!empty($detail->barang_id)) {
                $barang = \App\Models\Barang::find($detail->barang_id);
                if ($barang) {
                    $barang->stok += $detail->qty;
                    $barang->save();
                    ActivityLog::record(
                        'stock_in', 
                        "Pengembalian stok '{$barang->nama_barang}' (+{$detail->qty}) karena Kustomer Menolak PO Transaksi {$transaksi->no_transaksi}", 
                        $barang
                    );
                }
            }
        }
        $transaksi->status_order = 'batal'; 
        $transaksi->status_pesanan = 'batal';
        $transaksi->status_pembayaran = 'batal'; 
        $transaksi->save();
        ActivityLog::record('update', "Kustomer menolak penawaran harga Pre-Order untuk Transaksi {$transaksi->no_transaksi}", $transaksi);
        try {
            $adminEmails = \App\Models\User::where('role', 'admin')->pluck('email')->toArray();
            if (!empty($adminEmails)) {
                Mail::to($adminEmails)->send(new TolakPOMail($transaksi));
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Gagal kirim email Tolak PO: ' . $e->getMessage());
        }
        
        return redirect()->back()->with('error', 'Pesanan telah dibatalkan dan stok telah dikembalikan.');
    }

public function revisiPo(Request $request, $id)
    {
        $transaksi = \App\Models\Transaksi::with('details')->findOrFail($id);

        // 1. UPDATE DATA BARANG & LINK
        if ($request->has('revisi_nama')) {
            foreach ($request->revisi_nama as $detail_id => $nama_baru) {
                $detail = \App\Models\TransaksiDetail::findOrFail($detail_id);         
                $detail->nama_barang_custom = $nama_baru; 
                $detail->link = $request->revisi_link[$detail_id] ?? null;            
                $detail->harga_satuan = 0;
                $detail->subtotal = 0;
                $detail->save();
            }
        }
        if ($request->filled('catatan_revisi')) {
            $transaksi->catatan_revisi = $request->catatan_revisi;
        }
        $transaksi->status_order = 'menunggu_kalkulasi';
        $transaksi->total_harga = 0; 
        $transaksi->save();
        ActivityLog::record('update', "Kustomer mengajukan revisi penawaran harga Pre-Order untuk Transaksi {$transaksi->no_transaksi}", $transaksi);
        try {
            $adminEmails = \App\Models\User::where('role', 'admin')->pluck('email')->toArray();
            
            if (!empty($adminEmails)) {
                \Illuminate\Support\Facades\Mail::to($adminEmails)
                    ->send(new \App\Mail\RevisiPOMail($transaksi)); 
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Gagal kirim email Revisi PO ke Admin: ' . $e->getMessage());
        }

        return redirect()->back()->with('success', 'Revisi berhasil diajukan! Menunggu Admin menghitung ulang estimasi biaya.');
    }
    public function batalkanPesanan(string $id) 
    {
        $transaksi = \App\Models\Transaksi::with('details')->where('id', $id)
                        ->where('user_id', \Illuminate\Support\Facades\Auth::id())
                        ->firstOrFail();
        if (!in_array($transaksi->status_order, ['belum_bayar', 'menunggu_kalkulasi', 'menunggu_acc_customer'])) {
            return redirect()->back()->with('error', 'Pesanan ini sudah diproses dan tidak dapat dibatalkan.');
        }
        foreach ($transaksi->details as $detail) {
            if (!empty($detail->barang_id)) {
                $barang = \App\Models\Barang::find($detail->barang_id);
                if ($barang) {
                    $barang->stok += $detail->qty;
                    $barang->save();
                    ActivityLog::record(
                        'stock_in', 
                        "Pengembalian stok '{$barang->nama_barang}' (+{$detail->qty}) karena Kustomer membatalkan Transaksi {$transaksi->no_transaksi}", 
                        $barang
                    );
                }
            }
        }    
        $transaksi->status_order = 'batal'; 
        $transaksi->status_pesanan = 'batal';
        $transaksi->save();
        ActivityLog::record('update', "Kustomer membatalkan pesanan {$transaksi->no_transaksi}", $transaksi);
        try {
            $adminEmails = \App\Models\User::where('role', 'admin')->pluck('email')->toArray();
            if (!empty($adminEmails)) {
                \Illuminate\Support\Facades\Mail::to($adminEmails)
                    ->send(new \App\Mail\PesananBatalAdminMail($transaksi));
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Gagal kirim email Batal Pesanan: ' . $e->getMessage());
        }

        return redirect()->back()->with('success', 'Pesanan berhasil dibatalkan. Stok telah dikembalikan.');
    }
public function update(Request $request, $id)
    {
        // 1. Ambil session cart (beri pengaman array kosong jika tidak ada)
        $cart = session()->get('cart', []);
        
        // 2. Update jumlah barang (qty)
        if(isset($cart[$id])) {
            $cart[$id]['qty'] = (int) $request->qty;
            session()->put('cart', $cart);
        }
        
        // 3. Kalkulasi ulang total harga
        $total = 0;
        foreach($cart as $item) {
            $harga = $item['harga'] ?? $item['price'] ?? 0;
            $total += $harga * $item['qty'];
        }
        
        // 4. Kirim balikan JSON ke JavaScript
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'cart_count' => count($cart), // 👈 Tambahan ini biar JS tahu jumlah barangnya
                'new_total' => 'Rp ' . number_format($total, 0, ',', '.')
            ]);
        }

        return redirect()->back();
    }
    public function printOffering($id)
    {
        $transaksi = \App\Models\Transaksi::with('details.barang')
                    ->where('id', $id)
                    ->where('user_id', \Illuminate\Support\Facades\Auth::id())
                    ->firstOrFail();
        return view('pdf.print_offering', compact('transaksi')); 
    }

    public function printInvoice($id)
    {
        $transaksi = \App\Models\Transaksi::with('details.barang')
                    ->where('id', $id)
                    ->where('user_id', \Illuminate\Support\Facades\Auth::id())
                    ->firstOrFail();
        return view('pdf.print_invoice', compact('transaksi'));
    }
    public function pesananSelesai($id)
    {
        $transaksi = \App\Models\Transaksi::findOrFail($id);
        
        // Status berubah jadi menunggu konfirmasi akhir dari Admin
        $transaksi->update([
            'status_order' => 'diterima_kustomer',
        ]);
        ActivityLog::record('update', "Kustomer mengonfirmasi telah menerima pesanan {$transaksi->no_transaksi} dengan aman", $transaksi);

        // Kirim Notif Email Sederhana ke Admin
        $adminEmail = 'admin@anekatech.com'; // Sesuaikan dengan email Admin toko kamu
        try {
            \Illuminate\Support\Facades\Mail::raw("Pesanan {$transaksi->no_transaksi} atas nama {$transaksi->nama_customer} telah DITERIMA DENGAN AMAN oleh kustomer. Silakan cek dashboard untuk menyelesaikan pesanan.", function($msg) use ($adminEmail, $transaksi) {
                $msg->to($adminEmail)->subject("✅ Pesanan Diterima Kustomer - {$transaksi->no_transaksi}");
            });
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Gagal kirim email ke admin: ' . $e->getMessage());
        }

        return back()->with('success', 'Konfirmasi berhasil! Menunggu Admin menutup pesanan Anda. Selamat menikmati PC baru Anda! 🎉');
    }

public function ajukanRetur(Request $request, $id) 
    {
        // 1. Validasi BARU (Hanya minta Foto Kerusakan dan Alasan)
        $request->validate([
            'catatan_retur' => 'required|string',
            'foto_kerusakan' => 'required', // Pastikan ada file yg diupload
            'foto_kerusakan.*' => 'image|mimes:jpeg,png,jpg|max:5120' // Tiap file maks 5MB
        ], [
            'foto_kerusakan.required' => 'Anda wajib mengunggah minimal 1 foto kerusakan.',
            'foto_kerusakan.*.image' => 'File harus berupa gambar (JPG/PNG).',
            'foto_kerusakan.*.max' => 'Ukuran masing-masing foto tidak boleh lebih dari 5MB.'
        ]);

        $transaksi = \App\Models\Transaksi::findOrFail($id);

        // 2. Batasi maksimal 5 file secara manual
        $files = $request->file('foto_kerusakan');
        if (is_array($files) && count($files) > 5) {
            return back()->withInput()->withErrors(['foto_kerusakan' => 'Maksimal foto yang bisa diunggah adalah 5 buah.']);
        }

        // 3. Proses Upload Banyak Foto
        $pathFoto = [];
        if ($request->hasFile('foto_kerusakan')) {
            foreach ($files as $file) {
                $pathFoto[] = $file->store('bukti_kerusakan', 'public');
            }
        }

        // 4. Simpan ke Tabel Pengajuan Retur yang Baru Kita Buat
        \App\Models\PengajuanRetur::create([
            'transaksi_id' => $transaksi->id,
            'catatan_retur' => $request->catatan_retur,
            'foto_kerusakan' => $pathFoto, 
            'status' => 'menunggu_verifikasi'
        ]);

        // 5. Kunci status di tabel transaksi utama
        $transaksi->update([
            'status_order' => 'retur_diajukan'
        ]);
        ActivityLog::record('update', "Kustomer mengajukan komplain/retur untuk pesanan {$transaksi->no_transaksi} (Alasan: {$request->catatan_retur})", $transaksi);

        return redirect()->back()->with('success', 'Pengajuan retur berhasil dikirim. Hubungi WA kami untuk mengirimkan video unboxing.');
    }
}
