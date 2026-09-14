<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Barang;
use App\Models\Transaksi;
use App\Models\TransaksiDetail;
use App\Models\Customer;
use App\Models\PreOrder;
use App\Models\EmailLog; 
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Mail\PreOrderInvoice; 
use App\Models\User; 
use Illuminate\Support\Facades\Hash; 
use Illuminate\Support\Facades\Mail;
use App\Mail\PaymentApprovedMail;
use App\Models\PengajuanRetur;
use App\Mail\ReturDisetujui;
use App\Mail\ReturDitolak;
use App\Mail\PaketReturDiterima;
use App\Mail\HasilCekValid;
use App\Mail\HasilCekTidakValid;
use App\Models\ActivityLog;
use App\Mail\PaymentRejectedMail;

class TransaksiController extends Controller
{
  public function index(Request $request )
    {
        $kategoris = [
            'Processor', 'Motherboard', 'VGA / GPU', 'RAM', 'Storage', 
            'Casing', 'PSU', 'Cooler', 'Monitor', 'Accessories', 'Computer'
        ];
        
        $query = \App\Models\Barang::with('components'); 
        
        $query->where(function($mainQuery) {
            $mainQuery->where(function($q) {
                $q->where('jenis', '!=', 'paket') 
                  ->orWhereNull('jenis');
            })
            ->orWhere(function($q) {
                $q->where('jenis', 'paket')
                  ->where('stok', '>', 0);
            });
        });
        // 👆 ======================================================= 👆
        
        if ($request->filled('kategori')) {
          $query->where('kategori', 'like', '%' . $request->kategori . '%');
      }
        
        if ($request->has('search') && $request->search != '') {
            $query->where(function($q) use ($request) {
                $q->where('nama_barang', 'like', '%' . $request->search . '%')
                  ->orWhere('kode_barang', 'like', '%' . $request->search . '%');
            });
        }
        
        $customers = \App\Models\Customer::orderBy('nama', 'asc')->get();
        
        $barangs = $query->latest()->get(); 
        return view('admin.transaksi.index', compact('barangs', 'kategoris', 'customers'));
    }

public function store(Request $request)
{
    $request->validate([
        'cart_data'        => 'required|json',
        'total_amount'     => 'required|numeric',
        'nomor_telepon'    => 'required|string',
        'nama_customer'    => 'required|string',
        'perusahaan'       => 'nullable|string|max:255',
        'biaya_ongkir'     => 'nullable|numeric|min:0',
        'is_rakit'         => 'nullable', 
        'estimasi_ready'   => 'nullable|date',
        'email'            => 'nullable|email', 
    ]);

    $cartItems = json_decode($request->cart_data, true);
    if (empty($cartItems)) return back()->with('error', 'Keranjang kosong!');
    
    $ongkir = $request->biaya_ongkir ?? 0;
    $grandTotal = $request->total_amount + $ongkir;
    $cleanEmail = $request->email ? trim(strtolower($request->email)) : null;

    DB::beginTransaction();
    try {
        $userBaru = null;
        $passwordAsli = strtolower(str_replace(' ', '', $request->nama_customer)); 
        
        if (!empty($cleanEmail) && $request->status_pembayaran != 'belum_bayar') {
            $userBaru = User::firstOrCreate(
                ['email' => $cleanEmail],
                [
                    'name'     => $request->nama_customer,
                    'password' => Hash::make($passwordAsli),
                    'role'     => 'pelanggan'
                ]
            );
        }

        $existingCustomer = Customer::where('nomor_telepon', $request->nomor_telepon)->first();
        $userId = null;
        
        if ($userBaru) {
            $userId = $userBaru->id;
        } elseif ($existingCustomer) {
            $userId = $existingCustomer->user_id; 
        }

        $customer = Customer::updateOrCreate(
            ['nomor_telepon' => $request->nomor_telepon], 
            [
                'nama'       => $request->nama_customer,
                'perusahaan' => $request->perusahaan,
                'email'      => $cleanEmail,
                'alamat'     => $request->alamat,
                'catatan'    => $request->catatan_customer,
                'user_id'    => $userId 
            ]
        );

        if ($request->bayar_nominal == 0 || $request->status_pembayaran == 'belum_bayar') {
            $globalStatus = 'belum_bayar'; 
        } else {
            $globalStatus = $request->has_po ? 'menunggu_po' : 'menunggu_proses';
        }
        
        $kembalian = $request->bayar_nominal - $grandTotal;
        $noTransaksi = 'TRX-' . date('Ymd') . '-' . strtoupper(Str::random(4));
        
        // 1. SCAN ESTIMASI PO & DETEKSI OTOMATIS PC READY (QC PER-UNIT)
        $globalEstimasiPo = null;
        $hasPCBundle = false;

        foreach ($cartItems as $item) {
            if (isset($item['is_po']) && $item['is_po'] == true && !empty($item['estimasi_po'])) {
                $globalEstimasiPo = $item['estimasi_po'] . ' Hari Kerja';
            }

            $kat   = strtolower($item['kategori'] ?? '');
            $jenis = strtolower($item['jenis'] ?? '');
            if (str_contains($kat, 'pc') || str_contains($kat, 'rakitan') || str_contains($kat, 'build') || $jenis == 'paket_ready') {
                $hasPCBundle = true;
            }
        }
        
        $isRakitAktif = filter_var($request->is_rakit, FILTER_VALIDATE_BOOLEAN) || $hasPCBundle ? 1 : 0;

        $transaksi = Transaksi::create([
            'no_transaksi'      => $noTransaksi,
            'user_id'           => $userId, 
            'customer_id'       => $customer->id,
            'nama_customer'     => $customer->nama,
            'nomor_telepon'     => $customer->nomor_telepon,
            'alamat'            => $customer->alamat,
            'metode_pengiriman' => $request->metode_pengiriman,
            'biaya_pengiriman'  => $ongkir,
            'status_pembayaran' => $request->status_pembayaran,
            
            'status_pesanan'    => $globalStatus,
            'status_order'      => $globalStatus, 
            'is_rakit'          => $isRakitAktif,
            
            'total_harga'       => $grandTotal,
            'bayar'             => $request->bayar_nominal,
            'kembalian'         => $kembalian,
            'estimasi_po'       => $globalEstimasiPo,
        ]);

        // =========================================================================
        // 👇 3. HITUNG TOTAL PC LANGSUNG DARI DATABASE BARANG (CONTEKAN TADI PAGI) 👇
        // =========================================================================
        $totalUnitPc = 1;
        $cpuCount = 0;
        foreach ($cartItems as $c) {
            $isCustomCart = isset($c['is_custom']) && $c['is_custom'] == true;
            if (!$isCustomCart && isset($c['id'])) {
                $cekBarang = Barang::find($c['id']);
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

        $urutanSatuan = 0;
        $poItems = [];

        // =========================================================================
        // 👇 4. SIMPAN & PECAH BARIS OTOMATIS PERSIS SEPERTI CHECKOUT ONLINE 👇
        // =========================================================================
        foreach ($cartItems as $item) {
            $isPO = isset($item['is_po']) && $item['is_po'] == true;
            $isCustom = isset($item['is_custom']) && $item['is_custom'] == true;
            $hargaItem = $item['price'] ?? $item['harga'] ?? 0;
            $namaItem = $item['name'] ?? $item['nama_barang'] ?? 'Komponen Pre-Order';

            if ($isPO) {
                $poItems[] = $item;
            } else {
                $barangDB = Barang::lockForUpdate()->find($item['id']);
                if ($barangDB) {
                    if ($barangDB->stok < $item['qty']) {
                        throw new \Exception("Gagal: Stok '{$barangDB->nama_barang}' tidak cukup.");
                    }
                    $barangDB->decrement('stok', $item['qty']);

                    ActivityLog::record(
                        'stock_out', 
                        "Stok '{$barangDB->nama_barang}' dikurangi {$item['qty']} untuk Transaksi POS {$transaksi->no_transaksi}", 
                        $barangDB
                    );
                }
            }

            // --- LOGIKA ALOKASI NOMOR UNIT KE DATABASE (OTOMATIS PECAH BARIS) ---
            if (isset($item['nomor_unit'])) {
                TransaksiDetail::create([
                    'transaksi_id'       => $transaksi->id,
                    'nomor_unit'         => (int) $item['nomor_unit'],
                    'barang_id'          => $isCustom ? null : $item['id'],
                    'nama_barang_custom' => $isCustom ? $namaItem : null,
                    'qty'                => $item['qty'],
                    'harga_satuan'       => $hargaItem,
                    'subtotal'           => $hargaItem * $item['qty'],
                    'link'               => $item['link'] ?? null, 
                ]);
            } elseif ($totalUnitPc > 1 && $item['qty'] >= $totalUnitPc) {
                // Barang QTY banyak (misal RAM 2x, SSD 2x) -> Dipecah jadi baris Unit 1 & Unit 2
                $qtyPerUnit = floor($item['qty'] / $totalUnitPc);
                for ($u = 1; $u <= $totalUnitPc; $u++) {
                    TransaksiDetail::create([
                        'transaksi_id'       => $transaksi->id,
                        'nomor_unit'         => $u, // 👈 Baris 1 masuk Unit 1, Baris 2 masuk Unit 2
                        'barang_id'          => $isCustom ? null : $item['id'],
                        'nama_barang_custom' => $isCustom ? $namaItem : null,
                        'qty'                => $qtyPerUnit,
                        'harga_satuan'       => $hargaItem,
                        'subtotal'           => $hargaItem * $qtyPerUnit,
                        'link'               => $item['link'] ?? null, 
                    ]);
                }
            } elseif ($totalUnitPc > 1 && $item['qty'] == 1) {
                // Barang satuan (CPU, Motherboard) -> Masuk bergantian: Unit 1, lalu Unit 2
                $unitTujuan = ($urutanSatuan % $totalUnitPc) + 1;
                $urutanSatuan++;

                TransaksiDetail::create([
                    'transaksi_id'       => $transaksi->id,
                    'nomor_unit'         => $unitTujuan, // 👈 CPU 1 -> Unit 1, CPU 2 -> Unit 2
                    'barang_id'          => $isCustom ? null : $item['id'],
                    'nama_barang_custom' => $isCustom ? $namaItem : null,
                    'qty'                => 1,
                    'harga_satuan'       => $hargaItem,
                    'subtotal'           => $hargaItem,
                    'link'               => $item['link'] ?? null, 
                ]);
            } else {
                // Default 1 PC / tidak bisa dibagi rata
                TransaksiDetail::create([
                    'transaksi_id'       => $transaksi->id,
                    'nomor_unit'         => 1,
                    'barang_id'          => $isCustom ? null : $item['id'],
                    'nama_barang_custom' => $isCustom ? $namaItem : null,
                    'qty'                => $item['qty'],
                    'harga_satuan'       => $hargaItem,
                    'subtotal'           => $hargaItem * $item['qty'],
                    'link'               => $item['link'] ?? null, 
                ]);
            }
        }

        // --- 5. LOGIKA EMAIL AKUN BARU ---
        if (!empty($cleanEmail) && $userBaru && $userBaru->wasRecentlyCreated) {
            $welcomeMail = new \App\Mail\WelcomeCustomerEmail($customer, $passwordAsli);
            $htmlBodyWelcome = $welcomeMail->render(); 

            EmailLog::create([
                'customer_id'     => $customer->id,
                'recipient_email' => $customer->email,
                'subject'         => $welcomeMail->envelope()->subject,
                'body'            => $htmlBodyWelcome,
                'status'          => 'sent', 
                'trigger_type'    => 'welcome_customer'
            ]);

            Mail::to($customer->email)->send($welcomeMail);
        }

        // --- 6. JIKA ADA BARANG PO ---
        if (count($poItems) > 0) {
            $preOrder = PreOrder::create([
                'no_po'          => 'PO-' . date('Ymd') . '-' . strtoupper(Str::random(3)),
                'transaksi_id'   => $transaksi->id,
                'customer_id'    => $customer->id,
                'estimasi_ready' => $request->estimasi_ready,
                'status'         => 'pending',
                'catatan'        => 'Auto-generated from POS Offline',
            ]);

            if ($customer->email) {
                $mailable = new \App\Mail\PreOrderInvoice($preOrder, $customer);
                $htmlBody = $mailable->render(); 

                EmailLog::create([
                    'customer_id'     => $customer->id,
                    'pre_order_id'    => $preOrder->id,
                    'recipient_email' => $customer->email,
                    'subject'         => $mailable->envelope()->subject,
                    'body'            => $htmlBody,
                    'status'          => 'pending',
                    'trigger_type'    => 'invoice_po'
                ]);
            }
        }

        // --- 7. LOG AKTIVITAS TRANSAKSI ---
        $infoRakit = $isRakitAktif ? " [JASA RAKIT & QC AKTIF]" : "";
        ActivityLog::record(
            'create', 
            "Membuat transaksi POS baru: {$transaksi->no_transaksi}{$infoRakit} dengan total Rp " . number_format($transaksi->total_harga, 0, ',', '.'), 
            $transaksi
        );

        DB::commit();
        
        $msg = count($poItems) > 0 ? "Transaksi & Pre-Order Berhasil Disimpan!" : "Transaksi POS Berhasil!";
        return redirect()->route('transaksi.index')->with('success', $msg);

    } catch (\Exception $e) {
        DB::rollBack();
        return back()->with('error', 'Gagal: ' . $e->getMessage());
    }
}

public function riwayat(Request $request)
{
    $jenis = $request->query('jenis', 'reguler');
    $query = \App\Models\Transaksi::query();

    if ($jenis == 'reguler') {
        // TAB DIPROSES: Exclude semua status yang sudah selesai/batal/ready/dikirim
        $query->whereNotIn('status_order', [
            'belum_bayar', 
            'menunggu_proses', 
            'menunggu_kalkulasi', 
            'siap_diambil', 
            'sedang_dikirim',    // <-- FIX: Dikeluarkan dari tab Diproses
            'ready',             // <-- FIX: Dikeluarkan dari tab Diproses
            'diterima_kustomer', // <-- FIX: Dikeluarkan dari tab Diproses
            'selesai', 
            'batal'
        ])
        ->where(function($q) {
            $q->whereNull('estimasi_po') 
              ->orWhere(function($subQ) {
                  $subQ->whereNotNull('estimasi_po')
                       ->whereNotNull('teknisi_id'); 
              });
        });
    } 
    elseif ($jenis == 'po') {
        // TAB PO: Pesanan Pre-Order yang butuh kalkulasi, nego harga, atau belum ada teknisi
        $query->where(function($q) {
            $q->whereIn('status_order', ['menunggu_kalkulasi', 'menunggu_acc_customer', 'menunggu_po', 'po_disetujui'])
              ->orWhere(function($subQ) {
                  $subQ->whereNotNull('estimasi_po')
                       ->whereNull('teknisi_id');
              });
        });
    } 
    elseif ($jenis == 'belum_bayar') {
        // TAB BELUM BAYAR: Murni yang belum transfer ATAU lagi nunggu admin nge-ACC bukti transfer
        $query->whereIn('status_order', ['belum_bayar', 'menunggu_proses']);
    }
    elseif ($jenis == 'ready') {
        // 👇 FIX: TAB READY sekarang menampung yang Siap Diambil & Sedang Dikirim 👇
        $query->whereIn('status_order', [
            'siap_diambil', 
            'sedang_dikirim', 
            'ready', 
            'diterima_kustomer'
        ]); 
    }
    elseif ($jenis == 'riwayat') {
        // TAB RIWAYAT: Selesai atau Dibatalkan
        $query->whereIn('status_order', ['selesai', 'batal']);
    }
    elseif ($jenis == 'retur') {
        // TAB RETUR
        $query->whereHas('pengajuanRetur', function($q) {
            $q->whereNotIn('status', ['klaim_ditolak', 'selesai_refund']);
        });
    }

    // 👇 FIX KRUSIAL: PENCARIAN ANTI-GAGAL (Cari di No Transaksi, Nama Transaksi, & Relasi) 👇
    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function($q) use ($search) {
            $q->where('no_transaksi', 'like', "%{$search}%")
              ->orWhere('nama_customer', 'like', "%{$search}%") // 1. Cari di tabel transaksi langsung
              ->orWhereHas('customer', function($qc) use ($search) {
                  // 2. Cari di relasi customer (pakai kolom bahasa Indonesia 'nama_customer' / 'nama')
                  $qc->where('nama_customer', 'like', "%{$search}%")
                     ->orWhere('nama', 'like', "%{$search}%");
              });
        });
    }

    $transaksis = $query->latest()->paginate(10);
    return view('admin.transaksi.order', compact('transaksis', 'jenis'));
}

public function show($id)
    {
        $transaksi = Transaksi::with(['details.barang', 'customer', 'teknisi'])->findOrFail($id);
        $teknisis = [];
        if ($transaksi->is_rakit && is_null($transaksi->teknisi_id)) {
            $teknisis = \App\Models\User::where('role', 'teknisi')->get(); 
        }
        return view('admin.transaksi.show', compact('transaksi', 'teknisis'));
    }
public function updateStatus(Request $request, $id)
    {
        // Eager load customer untuk efisiensi kueri
        $transaksi = \App\Models\Transaksi::with('customer')->findOrFail($id);
        $statusLama = $transaksi->status_order;
        $statusBaru = $request->status;

        // 👇 1. MASTER OVERRIDE (CUKUP SATU KALI DI SINI) 👇
        $isRakit = (int) $transaksi->is_rakit; 
        
        // Jika tombol Admin ngirim 'process' atau 'sedang_dirakit'
        if (in_array($statusBaru, ['sedang_dirakit', 'process'])) {
            if ($isRakit === 0) {
                // HARGA MATI: Kalau bukan rakitan, paksa jadi disiapkan!
                $statusBaru = 'sedang_disiapkan';
            } else {
                // HARGA MATI: Kalau rakitan, masuk Teknisi
                $statusBaru = 'sedang_dirakit';
            }
        }
        // 👆 ======================================================== 👆

        // --- 2. LOGIKA JIKA BARU BAYAR / DIPROSES ---
        if ($statusLama == 'belum_bayar' && $statusBaru == 'menunggu_proses') {
            $transaksi->status_pembayaran = 'lunas';
            $transaksi->bayar = $transaksi->total_harga;

            if ($transaksi->has_po) {
                $statusBaru = 'menunggu_po';
            }

            $customer = $transaksi->customer;
            
            if ($customer && !empty($customer->email)) {
                // A. JIKA CUSTOMER BELUM PUNYA AKUN (BUATKAN AKUN & KIRIM WELCOME MAIL)
                if (is_null($customer->user_id)) {
                    $passwordAsli = strtolower(str_replace(' ', '', $customer->nama)); 
                    
                    $userBaru = \App\Models\User::firstOrCreate(
                        ['email' => $customer->email],
                        [
                            'name' => $customer->nama,
                            'password' => \Illuminate\Support\Facades\Hash::make($passwordAsli),
                            'role' => 'pelanggan'
                        ]
                    );

                    $customer->update(['user_id' => $userBaru->id]);

                    // Kirim Welcome Mail
                    try {
                        $welcomeMail = new \App\Mail\WelcomeCustomerEmail($customer, $passwordAsli);
                        
                        \App\Models\EmailLog::create([
                            'customer_id' => $customer->id,
                            'recipient_email' => $customer->email,
                            'subject' => $welcomeMail->envelope()->subject,
                            'body' => $welcomeMail->render(),
                            'status' => 'sent', 
                            'trigger_type' => 'welcome_customer'
                        ]);

                        \Illuminate\Support\Facades\Mail::to($customer->email)->send($welcomeMail);
                    } catch (\Exception $e) {
                        \Illuminate\Support\Facades\Log::error('Gagal kirim WelcomeCustomerEmail: ' . $e->getMessage());
                    }
                }

                // B. KIRIM NOTIFIKASI PEMBAYARAN DITERIMA
                try {
                    $paymentMail = new \App\Mail\PaymentApprovedMail($transaksi);

                    \App\Models\EmailLog::create([
                        'customer_id' => $customer->id,
                        'recipient_email' => $customer->email,
                        'subject' => $paymentMail->envelope()->subject ?? 'Pembayaran Diterima - DiabloTech',
                        'body' => $paymentMail->render(),
                        'status' => 'sent', 
                        'trigger_type' => 'payment_approved'
                    ]);

                    \Illuminate\Support\Facades\Mail::to($customer->email)->send($paymentMail);
                } catch (\Exception $e) {
                    \Illuminate\Support\Facades\Log::error('Gagal kirim PaymentApprovedMail: ' . $e->getMessage());
                }
            }
        }

        // --- 3. LOGIKA JIKA BARANG SIAP DIAMBIL / KIRIM ---
        elseif (in_array($statusLama, ['menunggu_proses', 'menunggu_po', 'process', 'preorder', 'sedang_dirakit', 'sedang_disiapkan']) && $statusBaru == 'siap_diambil') {
            if ($transaksi->metode_pengiriman == 'delivery') {
                $statusBaru = 'sedang_dikirim'; 
            }

            $customer = $transaksi->customer;
            
            if ($customer && !empty($customer->email)) {
                try {
                    $readyMail = new \App\Mail\OrderReadyEmail($transaksi);

                    \App\Models\EmailLog::create([
                        'customer_id' => $customer->id,
                        'recipient_email' => $customer->email,
                        'subject' => $readyMail->envelope()->subject,
                        'body' => $readyMail->render(),
                        'status' => 'sent', 
                        'trigger_type' => 'order_ready'
                    ]);

                    \Illuminate\Support\Facades\Mail::to($customer->email)->send($readyMail);
                } catch (\Exception $e) {
                    \Illuminate\Support\Facades\Log::error('Gagal kirim OrderReadyEmail: ' . $e->getMessage());
                }
            }
        }
        elseif (in_array($statusLama, ['siap_diambil', 'sedang_dikirim']) && $statusBaru == 'selesai') {
            $customer = $transaksi->customer;

            if ($customer && !empty($customer->email)) {
                try {
                    $completedMail = new \App\Mail\OrderCompletedEmail($transaksi);

                    \App\Models\EmailLog::create([
                        'customer_id' => $customer->id,
                        'recipient_email' => $customer->email,
                        'subject' => $completedMail->envelope()->subject,
                        'body' => $completedMail->render(),
                        'status' => 'sent', 
                        'trigger_type' => 'order_completed'
                    ]);

                    \Illuminate\Support\Facades\Mail::to($customer->email)->send($completedMail);
                } catch (\Exception $e) {
                    \Illuminate\Support\Facades\Log::error('Gagal kirim OrderCompletedEmail: ' . $e->getMessage());
                }
            }
        }
        
        // 👇 4. SIMPAN STATUS KE DATABASE 👇
        $transaksi->status_order = $statusBaru;
        $transaksi->status_pesanan = $statusBaru; // Biar kedua kolom sinkron dan nggak belang
        $transaksi->save();

        ActivityLog::record(
            'update', 
            "Mengubah status pesanan {$transaksi->no_transaksi} menjadi: {$statusBaru}", 
            $transaksi
        );

        return redirect()->back()->with('success', 'Status pesanan berhasil diperbarui!');
    }

    public function printOffering($id)
    {
        $transaksi = Transaksi::with(['details.barang', 'customer'])->findOrFail($id);
        return view('pdf.print_offering', compact('transaksi'));

    }
    public function printInvoice($id)
    {
        $transaksi = Transaksi::with(['details.barang', 'customer'])->findOrFail($id);
        return view('pdf.print_invoice', compact('transaksi'));
    }

public function updateOngkir(Request $request, $id)
    {
        $request->validate([
            'biaya_pengiriman' => 'required|numeric|min:1'
        ]);

        $transaksi = \App\Models\Transaksi::findOrFail($id);

        if ($transaksi->biaya_pengiriman > 0) {
            return back()->with('error', 'Gagal! Ongkos kirim sudah ditetapkan sebelumnya.');
        }

        $ongkir = $request->biaya_pengiriman;
        $totalHargaBaru = $transaksi->total_harga + $ongkir;

        $transaksi->update([
            'biaya_pengiriman' => $ongkir,
            'total_harga' => $totalHargaBaru,
        ]);
        try {
            $customer = $transaksi->customer;
            if ($customer && $customer->email) {                
                $mailable = new \App\Mail\TagihanOngkirEmail($transaksi);
                $htmlBody = $mailable->render();

                // 2. Simpan jejaknya di log email
                \App\Models\EmailLog::create([
                    'customer_id' => $customer->id,
                    'recipient_email' => $customer->email,
                    'subject' => $mailable->envelope()->subject,
                    'body' => $htmlBody,
                    'status' => 'sent',
                    'trigger_type' => 'update_ongkir'
                ]);
                \Illuminate\Support\Facades\Mail::to($customer->email)->send($mailable);
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Gagal kirim email tagihan ongkir: ' . $e->getMessage());
        }
        // 👆 SELESAI 👆
            ActivityLog::record(
            'update', 
            "Menambahkan ongkos kirim Rp" . number_format($ongkir, 0, ',', '.') . " pada Transaksi {$transaksi->no_transaksi}", 
            $transaksi
        );
        return back()->with('success', 'Ongkos kirim berhasil ditambahkan! Notifikasi tagihan telah dikirim ke Kustomer.');
    }

    public function assignTeknisi(Request $request, $id)
    {
        $request->validate([
            'teknisi_id' => 'required|exists:users,id'
        ]);

        $transaksi = Transaksi::findOrFail($id);
        $teknisi = \App\Models\User::findOrFail($request->teknisi_id);
        
        // Update data teknisi dan ubah status otomatis jadi sedang dirakit
        $transaksi->update([
            'teknisi_id' => $teknisi->id,
            'status_order' => 'sedang_dirakit' 
        ]);

        // --- KIRIM EMAIL KE TEKNISI ---
        try {
            \Illuminate\Support\Facades\Mail::to($teknisi->email)->send(new \App\Mail\TugasRakitBaruMail($transaksi, $teknisi));
        } catch (\Exception $e) {
            // Jika email gagal terkirim (misal karena koneksi/konfigurasi), proses tetap berjalan
            \Illuminate\Support\Facades\Log::error('Gagal kirim email ke teknisi: ' . $e->getMessage());
        }

        ActivityLog::record(
            'update', 
            "Menugaskan Teknisi '{$teknisi->name}' untuk merakit pesanan {$transaksi->no_transaksi}", 
            $transaksi
        );
        return back()->with('success', 'Teknisi berhasil ditugaskan & Notifikasi Email telah dikirim!');
    }
public function accPembayaran($id)
    {
        $transaksi = \App\Models\Transaksi::with('user')->findOrFail($id);
        
        // 👇 1. LOGIKA CERDAS PINTU BELAKANG 👇
        if ((int) $transaksi->is_rakit === 0) {
            // HARGA MATI: Kalau Part Bersegel, Bypass Teknisi!
            $transaksi->status_pesanan = 'sedang_disiapkan';
            $transaksi->status_order = 'sedang_disiapkan'; 
        } else {
            // HARGA MATI: Kalau Rakitan, masuk antrean Teknisi!
            $transaksi->status_pesanan = 'sedang_dirakit';
            
            // Aku biarkan 'process' sesuai kodingan aslimu biar nggak bentrok dengan fungsi lain
            $transaksi->status_order = 'process'; 
        }
        $transaksi->save();
        // 👆 ================================== 👆

        // 2. Kirim Email Notifikasi
        if ($transaksi->user && $transaksi->user->email) {
            \Illuminate\Support\Facades\Mail::to($transaksi->user->email)->send(new \App\Mail\PaymentApprovedMail($transaksi));
        }
        return redirect()->back()->with('success', 'Pembayaran diverifikasi! Pesanan diproses dan email notifikasi berhasil dikirim.');
    }
    public function konfirmasiPelunasan($id)
    {
        $transaksi = \App\Models\Transaksi::findOrFail($id);
        $transaksi->update([
            'status_pembayaran' => 'lunas',
            'bayar' => $transaksi->total_harga, 
        ]);

        // 👇 LOGIKA EMAIL MILIKMU 👇
        try {
            $customer = $transaksi->customer; 
            
            if ($customer && $customer->email) {
                \Illuminate\Support\Facades\Mail::to($customer->email)
                    ->send(new \App\Mail\PelunasanDikonfirmasiMail($transaksi));
                    
                \App\Models\EmailLog::create([
                    'customer_id'     => $customer->id,
                    'recipient_email' => $customer->email,
                    'subject'         => '✅ Pembayaran Lunas Dikonfirmasi - ' . $transaksi->no_transaksi,
                    'body'            => 'Email konfirmasi lunas dikirim otomatis', 
                    'status'          => 'sent',
                    'trigger_type'    => 'payment_confirmed_by_admin' 
                ]);
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Gagal kirim email pelunasan ke customer: ' . $e->getMessage());
        }

        ActivityLog::record(
            'update', 
            "Mengonfirmasi pelunasan 100% untuk pesanan {$transaksi->no_transaksi}", 
            $transaksi
        );
        return redirect()->back()->with('success', 'Pembayaran berhasil dikonfirmasi LUNAS 100%! Transaksi siap diproses ke pengiriman.');
    }
    public function tagihPelunasan($id)
    {
        $transaksi = Transaksi::with('user')->findOrFail($id);
        
        if ($transaksi->user && $transaksi->user->email) {
            // Kita akan buat Mailable ini di langkah 4
            Mail::to($transaksi->user->email)->send(new \App\Mail\TagihanPelunasanMail($transaksi));
            return redirect()->back()->with('success', 'Email tagihan pelunasan berhasil dikirim ke pelanggan!');
        }

        return redirect()->back()->with('error', 'Gagal mengirim email: Pelanggan tidak memiliki alamat email yang valid.');
    }
public function kirimEstimasi(Request $request, $id)
    {
        $request->validate([
            'harga_custom' => 'required|array',
            'estimasi_po' => 'required|string|max:255',
        ]);

        $transaksi = \App\Models\Transaksi::with(['customer', 'details'])->findOrFail($id);
        
        if ($request->has('harga_custom')) {
            foreach ($request->harga_custom as $detail_id => $harga) {
                $hargaBersih = str_replace('.', '', $harga); 
                $detail = \App\Models\TransaksiDetail::findOrFail($detail_id);
                $detail->harga_satuan = $hargaBersih;
                $detail->subtotal = $hargaBersih * $detail->qty;
                $detail->save();
            }
        }
        
        $total_barang = $transaksi->details()->sum('subtotal');
        $transaksi->total_harga = $total_barang + $transaksi->biaya_pengiriman;
        $transaksi->estimasi_po = $request->estimasi_po;
        $transaksi->status_order = 'menunggu_acc_customer';
        $transaksi->save();

        $customer = $transaksi->customer;
        if ($customer && !empty($customer->email)) {
            try {
                $poMail = new \App\Mail\PenawaranPOMail($transaksi);
                
                \App\Models\EmailLog::create([
                    'customer_id' => $customer->id,
                    'recipient_email' => $customer->email,
                    'subject' => $poMail->envelope()->subject ?? 'Estimasi Harga Pre-Order - DiabloTech',
                    'body' => $poMail->render(),
                    'status' => 'sent', 
                    'trigger_type' => 'order_ready' 
                ]);

                \Illuminate\Support\Facades\Mail::to($customer->email)->send($poMail);
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Gagal kirim Penawaran PO: ' . $e->getMessage());
            }
        }

        ActivityLog::record(
            'update', 
            "Mengirim estimasi PO ({$request->estimasi_po}) untuk pesanan {$transaksi->no_transaksi}", 
            $transaksi
        );
        return redirect()->back()->with('success', 'Estimasi harga berhasil diupdate & Penawaran dikirim ke Kustomer!');
    }
public function kirimPaket(Request $request, $id)
    {
        $request->validate([
            'resi_pengiriman' => 'required|string|max:255',
            'foto_resi_pengiriman' => 'required|image|mimes:jpeg,png,jpg|max:5120',
            'foto_paket_ekspedisi' => 'required|image|mimes:jpeg,png,jpg|max:5120',
        ]);

        $transaksi = Transaksi::findOrFail($id);

        // 2. Simpan Foto 1 (Resi)
        $fotoResiPath = null;
        if ($request->hasFile('foto_resi_pengiriman')) {
            $fotoResiPath = $request->file('foto_resi_pengiriman')->store('bukti_resi', 'public');
        }

        // 3. Simpan Foto 2 (Paket Ekspedisi)
        $fotoPaketPath = null;
        if ($request->hasFile('foto_paket_ekspedisi')) {
            // Foldernya dibedakan sedikit biar rapi di storage
            $fotoPaketPath = $request->file('foto_paket_ekspedisi')->store('bukti_paket', 'public');
        }

        // 4. Update Database
        $transaksi->update([
            'resi_pengiriman' => $request->resi_pengiriman,
            'foto_resi_pengiriman' => $fotoResiPath,
            'foto_paket_ekspedisi' => $fotoPaketPath, // Pastikan kolom ini udah ada di database!
            'status_order' => 'sedang_dikirim', 
        ]);

        // 👇 LOGIKA KIRIM EMAIL NOTIFIKASI 👇
        try {
            $customer = $transaksi->customer;
            if ($customer && $customer->email) {
                $mailable = new \App\Mail\PaketDikirimEmail($transaksi);
                $htmlBody = $mailable->render();

                \App\Models\EmailLog::create([
                    'customer_id' => $customer->id,
                    'recipient_email' => $customer->email,
                    'subject' => $mailable->envelope()->subject,
                    'body' => $htmlBody,
                    'status' => 'sent',
                    'trigger_type' => 'paket_dikirim'
                ]);

                \Illuminate\Support\Facades\Mail::to($customer->email)->send($mailable);
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Gagal kirim email resi: ' . $e->getMessage());

        }

        ActivityLog::record(
            'update', 
            "Memasukkan resi pengiriman ({$request->resi_pengiriman}) untuk pesanan {$transaksi->no_transaksi}", 
            $transaksi
        );
        return back()->with('success', 'Paket berhasil dikirim! 2 Foto bukti dan notifikasi email telah diteruskan ke pelanggan.');
    }
    public function konfirmasiSelesai($id)
    {
        $transaksi = Transaksi::findOrFail($id);
        
        $transaksi->update([
            'status_order' => 'selesai',
            'status_pesanan' => 'selesai'
        ]);

        ActivityLog::record(
            'update', 
            "Menutup transaksi {$transaksi->no_transaksi} (Selesai secara keseluruhan)", 
            $transaksi
        );
        return back()->with('success', 'Sip! Transaksi telah resmi ditutup dan selesai. Mantap Bosku!');
    }

    public function accPelunasan($id)
    {
        $transaksi = \App\Models\Transaksi::findOrFail($id);
        
        $transaksi->update([
            'status_pembayaran' => 'lunas',
            'bayar' => $transaksi->total_harga, 
        ]);

        // 👇 LOGIKA EMAIL MILIKMU (Sudah dirapikan relasinya) 👇
        try {
            // Pastikan pakai relasi ->customer sesuai dengan database kamu (bukan ->user)
            $customer = $transaksi->customer; 
            
            if ($customer && $customer->email) {
                \Illuminate\Support\Facades\Mail::to($customer->email)
                    ->send(new \App\Mail\PelunasanDikonfirmasiMail($transaksi));
                    
                \App\Models\EmailLog::create([
                    'customer_id'     => $customer->id,
                    'recipient_email' => $customer->email,
                    'subject'         => '✅ Pembayaran Lunas Dikonfirmasi - ' . $transaksi->no_transaksi,
                    'body'            => 'Email konfirmasi lunas dikirim otomatis', 
                    'status'          => 'sent',
                    'trigger_type'    => 'payment_confirmed_by_admin' 
                ]);
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Gagal kirim email pelunasan ke customer: ' . $e->getMessage());
        }

        ActivityLog::record(
            'update', 
            "Mengonfirmasi pelunasan 100% untuk pesanan {$transaksi->no_transaksi}", 
            $transaksi
        );
        return redirect()->back()->with('success', 'Pembayaran berhasil dikonfirmasi LUNAS 100%! Email struk telah dikirim ke kustomer.');
    }
   public function approveRetur($id)
    {
        $retur = \App\Models\PengajuanRetur::findOrFail($id);
        $retur->update([
            'status' => 'disetujui'
        ]);

        $emailKustomer = $retur->transaksi->user->email;
        Mail::to($emailKustomer)->send(new ReturDisetujui($retur));
        ActivityLog::record('update', "Menyetujui pengajuan retur untuk transaksi {$retur->transaksi->no_transaksi}", $retur);
        return redirect()->back()->with('success', 'Pengajuan retur berhasil disetujui. Sistem sekarang menunggu Kustomer mengunggah Resi Pengiriman Balik.');
    }

    public function rejectRetur(\Illuminate\Http\Request $request, $id)
    {
        $request->validate([
            'pesan_admin' => 'required|string'
        ]);
        $retur = \App\Models\PengajuanRetur::findOrFail($id);
        $retur->update([
            'status' => 'ditolak',
            'pesan_admin' => $request->pesan_admin
        ]);
        $retur->transaksi->update([
            'status_order' => 'sedang_dikirim'
        ]);
        $emailKustomer = $retur->transaksi->user->email;
    Mail::to($emailKustomer)->send(new ReturDitolak($retur));

    ActivityLog::record('update', "Menolak pengajuan retur untuk transaksi {$retur->transaksi->no_transaksi} dengan alasan: {$request->pesan_admin}", $retur);
        return redirect()->back()->with('success', 'Pengajuan retur berhasil ditolak. Notifikasi penolakan telah dikirimkan (ditampilkan) ke Kustomer.');
    }
    public function terimaPaket($id)
    {
        $retur = \App\Models\PengajuanRetur::findOrFail($id);
        $retur->update([
            'status' => 'sedang_dicek_teknisi'
        ]);
        $emailKustomer = $retur->transaksi->user->email;
    Mail::to($emailKustomer)->send(new PaketReturDiterima($retur));

    ActivityLog::record('update', "Mengonfirmasi penerimaan paket retur dari kustomer untuk transaksi {$retur->transaksi->no_transaksi}", $retur);
        return redirect()->back()->with('success', 'Paket retur telah terkonfirmasi diterima di toko. Silakan serahkan unit ke Teknisi untuk dilakukan pengecekan atau perbaikan.');
    }

   public function hasilCekTeknisi(\Illuminate\Http\Request $request, $id)
    {
        $retur = \App\Models\PengajuanRetur::findOrFail($id);
        $transaksi = $retur->transaksi;
        $emailKustomer = $transaksi->user->email;

        if ($request->keputusan == 'valid') {
            $request->validate(['bukti_refund' => 'required|image|max:5120']);
            $pathRefund = $request->file('bukti_refund')->store('bukti_refund_kustomer', 'public');            
            
            $retur->update([
                'status' => 'selesai_refund',
                'bukti_refund' => $pathRefund,
                'nominal_refund' => $transaksi->total_harga - $transaksi->ongkos_kirim
            ]);
            
            $transaksi->update(['status_order' => 'batal']);
            \Illuminate\Support\Facades\Mail::to($emailKustomer)->send(new HasilCekValid($retur));
            ActivityLog::record('update', "Klaim retur valid (Refund Dana). Transaksi {$transaksi->no_transaksi} dibatalkan otomatis.", $retur);
            
            return redirect()->back()->with('success', 'Refund berhasil diproses! Transaksi otomatis dibatalkan.');
        } 
        else if ($request->keputusan == 'invalid') {
            $request->validate([
                'kategori_tolak' => 'required|string',
                'hasil_cek_teknisi' => 'required|string'
            ]);
            $nominalRefund = null;
            $pathRefund = null;
            if ($request->kategori_tolak == 'Komponen Ditukar Kustomer (Akumulasi)') {                
                $request->merge([
                    'nominal_refund_partial' => str_replace('.', '', $request->nominal_refund_partial)
                ]);
                $request->validate([
                    'nominal_refund_partial' => 'required|numeric|min:1',
                    'bukti_refund_partial' => 'required|image|max:5120'
                ]);
                $nominalRefund = $request->nominal_refund_partial;
                $pathRefund = $request->file('bukti_refund_partial')->store('bukti_refund_kustomer', 'public');
            }
            $alasanLengkap = "Kategori: " . $request->kategori_tolak . "<br>Detail: " . $request->hasil_cek_teknisi;            
            $retur->update([
                'status' => 'klaim_ditolak',
                'hasil_cek_teknisi' => $alasanLengkap,
                'nominal_refund' => $nominalRefund, 
                'bukti_refund' => $pathRefund
            ]);
            
            $transaksi->update(['status_order' => 'selesai']);
            
            \Illuminate\Support\Facades\Mail::to($emailKustomer)->send(new HasilCekTidakValid($retur));
            ActivityLog::record('update', "Klaim retur tidak valid (Ditolak: {$request->kategori_tolak}). Transaksi {$transaksi->no_transaksi} diselesaikan paksa.", $retur);
            
            return redirect()->back()->with('success', 'Klaim dinyatakan gugur. Transaksi langsung DITUTUP.');
        }
    }
public function downloadDokumenGaransi(\Illuminate\Http\Request $request, $id)
    {
        // 1. Eager load semua relasi penting (termasuk dokumenQcs dan details)
        $transaksi = \App\Models\Transaksi::with(['dokumenQc', 'dokumenQcs', 'customer', 'details.barang'])->findOrFail($id);
        $user = \Illuminate\Support\Facades\Auth::user();

        // 2. Tangkap nomor unit dari URL (?unit=1 atau ?unit=2), default ke 1
        $nomorUnit = (int) $request->query('unit', 1);

        // 3. Cari dokumen QC untuk unit tersebut (Fallback ke relasi lama jika tidak ada)
        $dokumen = $transaksi->dokumenQcs()->where('nomor_unit', $nomorUnit)->first()
                   ?? $transaksi->dokumenQc;

        // --- A. JIKA ADMIN ATAU TEKNISI (Unduh File PDF Full dari Storage) ---
        if ($user->role == 'admin' || $user->role == 'teknisi') {
            if (!$dokumen || !$dokumen->file_pdf) {
                return back()->with('error', 'Dokumen QC untuk Unit #' . $nomorUnit . ' belum dibuat oleh teknisi.');
            }
            
            $path = $dokumen->file_pdf;
            if (!\Illuminate\Support\Facades\Storage::disk('public')->exists($path)) {
                return back()->with('error', 'File PDF tidak ditemukan di server.');
            }
            
            return response()->download(storage_path('app/public/' . $path));
        }

        // --- B. JIKA KUSTOMER (Generate Kartu Garansi Saja Secara On The Fly) ---
        if ($user->role == 'pelanggan') {
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.garansi_kustomer', [
                'transaksi' => $transaksi,
                'dokumen'   => $dokumen,    // 👈 Kirim dokumen unit yang dipilih
                'nomorUnit' => $nomorUnit   // 👈 Kirim nomor unit (1, 2, dst) ke Blade PDF
            ]);
            
            return $pdf->stream('Kartu_Garansi_' . $transaksi->no_transaksi . '_UNIT-' . $nomorUnit . '.pdf');
        }

        return back()->with('error', 'Anda tidak memiliki akses untuk dokumen ini.');
    }
public function tolakPembayaran($id)
    {
        $transaksi = Transaksi::findOrFail($id);
        
        if ($transaksi->bukti_pembayaran && file_exists(public_path('storage/' . $transaksi->bukti_pembayaran))) {
            unlink(public_path('storage/' . $transaksi->bukti_pembayaran));
        }

        $transaksi->status_order = 'belum_bayar';
        $transaksi->bukti_pembayaran = null; 
        // 👇 TAMBAHKAN PESAN PENOLAKAN AWAL 👇
        $transaksi->pesan_penolakan = 'Bukti pembayaran awal belum valid (Foto buram/dana belum masuk). Harap unggah ulang.';
        $transaksi->save();
        
        Mail::to($transaksi->customer->email)->send(new \App\Mail\PaymentRejectedMail($transaksi, 'awal'));
        
        return back()->with('success', 'Bukti pembayaran berhasil ditolak.');
    }

    public function tolakPelunasan($id)
    {
        $transaksi = Transaksi::findOrFail($id);
        
        if ($transaksi->bukti_pelunasan && file_exists(public_path('storage/' . $transaksi->bukti_pelunasan))) {
            unlink(public_path('storage/' . $transaksi->bukti_pelunasan));
        }

        $transaksi->bukti_pelunasan = null;
        // 👇 TAMBAHKAN PESAN PENOLAKAN PELUNASAN 👇
        $transaksi->pesan_penolakan = 'Bukti pelunasan belum valid. Pastikan nominal sesuai dengan sisa tagihan + ongkos kirim.';
        $transaksi->save();

        Mail::to($transaksi->customer->email)->send(new \App\Mail\PaymentRejectedMail($transaksi, 'pelunasan'));

        return back()->with('success', 'Bukti pelunasan berhasil ditolak.');
    }
}