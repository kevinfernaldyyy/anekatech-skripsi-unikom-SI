@extends('layouts.customer')

@section('title', 'Detail Pesanan | DiabloTech')

@section('content')
<div class="max-w-5xl mx-auto pb-10">
    
    @if(session('success'))
        <div class="bg-blue-50 border border-blue-200 text-blue-700 px-4 py-3 rounded-xl mb-6 flex items-center gap-3 shadow-sm">
            <i class="fas fa-check-circle text-blue-500 text-xl"></i>
            <div><p class="font-bold text-sm">{{ session('success') }}</p></div>
        </div>
    @endif

   <div class="mb-6 flex flex-col sm:flex-row sm:items-start justify-between gap-4">
    <div>
       @php
    // Cek apakah status transaksinya masuk kategori "Selesai" atau "Batal"
    $status = strtolower($transaksi->status_order ?? '');
    $isSelesai = in_array($status, [
        'selesai', 'completed', 'diterima_kustomer', 
        'batal', 'cancel', 'retur_ditolak', 
        'retur_selesai', 'retur_diterima'
    ]);

    // Tentukan rute kembalinya berdasarkan status di atas
    $backRoute = $isSelesai 
        ? route('customer.dashboard', ['tab' => 'riwayat']) 
        : route('customer.dashboard', ['tab' => 'aktif']);
@endphp

<a href="{{ $backRoute }}" class="inline-flex items-center gap-2 text-sm font-bold text-slate-500 hover:text-indigo-600 transition-colors mb-3">
    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
    </svg>
    Kembali ke {{ $isSelesai ? 'Riwayat Selesai' : 'Dashboard' }}
</a>
        <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Detail Pesanan</h1>
        <p class="text-slate-500 mt-1 text-sm">No. Invoice: <span class="font-bold text-indigo-600">{{ $transaksi->no_transaksi }}</span></p>
    </div>
    
    <div class="flex flex-col items-start sm:items-end gap-3 w-full sm:w-auto">
        
        
        <div class="text-left sm:text-right w-full sm:w-auto flex flex-col sm:items-end">
           <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Status Pembayaran</p>
            @php
                $isDP = strtolower($transaksi->status_pembayaran) == 'dp';
                $isReady = in_array($transaksi->status_order, ['siap_diambil', 'sedang_dikirim', 'ready']);

                if (in_array(strtolower($transaksi->status_order), ['batal', 'cancel'])) {
                    $payText = 'DIBATALKAN';
                    $payClass = 'bg-red-100 text-red-700 border-red-300';
                } elseif (in_array($transaksi->status_order, ['menunggu_kalkulasi', 'menunggu_acc_customer'])) {
                    $payText = 'PROSES NEGO PO';
                    $payClass = 'bg-amber-50 text-amber-600 border-amber-200 animate-pulse';
                } elseif ($transaksi->status_order == 'belum_bayar' && empty($transaksi->bukti_pembayaran)) {
                    $payText = strtoupper($transaksi->status_pembayaran) . ' (BELUM DIBAYAR)';
                    $payClass = 'bg-rose-50 text-rose-600 border-rose-200';
                } elseif (in_array($transaksi->status_order, ['belum_bayar', 'menunggu_proses']) && !empty($transaksi->bukti_pembayaran)) {
                    $payText = 'SEDANG DIVERIFIKASI';
                    $payClass = 'bg-blue-50 text-blue-600 border-blue-200';
                } elseif ($isDP && $isReady && empty($transaksi->bukti_pelunasan)) {
                    $payText = 'MENUNGGU PELUNASAN';
                    $payClass = 'bg-amber-50 text-amber-600 border-amber-200 animate-pulse';
                } elseif ($isDP && $isReady && !empty($transaksi->bukti_pelunasan) && !in_array($transaksi->status_order, ['selesai', 'completed'])) {
                    $payText = 'PELUNASAN DIVERIFIKASI';
                    $payClass = 'bg-blue-50 text-blue-600 border-blue-200';
                } else {
                    $payText = strtoupper($transaksi->status_pembayaran) . ' (TERKONFIRMASI)';
                    $payClass = 'bg-blue-50 text-blue-600 border-blue-200';
                }
            @endphp
            <span class="px-4 py-2 rounded-lg text-xs font-black uppercase tracking-wider border {{ $payClass }}">
                {{ $payText }}
            </span>
        </div>

        <div class="flex flex-wrap gap-2 w-full sm:w-auto justify-start sm:justify-end mt-1">
            @php
                $status = $transaksi->status_order; 
                $jenisPembayaran = $transaksi->status_pembayaran; 
            @endphp

            @if($status == 'belum_bayar')
                <a href="{{ route('customer.orders.print_offering', $transaksi->id) }}" target="_blank" class="w-full sm:w-auto inline-flex justify-center items-center gap-2 bg-white border border-indigo-600 text-indigo-600 px-4 py-2 rounded-xl text-xs font-bold hover:bg-indigo-50 transition-all shadow-sm">
                    <i class="fas fa-print"></i> Cetak Offering Letter
                </a>
            @elseif(in_array($status, ['menunggu_proses', 'menunggu_kalkulasi', 'menunggu_acc_customer']))
                <button disabled title="Tunggu proses ACC atau Nego selesai" class="w-full sm:w-auto inline-flex justify-center items-center gap-2 bg-slate-50 border border-slate-200 text-slate-400 cursor-not-allowed px-4 py-2 rounded-xl text-xs font-bold shadow-sm">
                    <i class="fas fa-lock"></i> Menunggu ACC Admin
                </button>
            @else
                <a href="{{ route('customer.orders.print_invoice', $transaksi->id) }}" target="_blank" class="w-full sm:w-auto inline-flex justify-center items-center gap-2 bg-white border {{ $jenisPembayaran == 'dp' ? 'border-blue-500 text-blue-600 hover:bg-blue-50' : 'border-blue-600 text-blue-600 hover:bg-blue-50' }} px-4 py-2 rounded-xl text-xs font-bold transition-all shadow-sm">
                    <i class="fas fa-receipt"></i> 
                    {{ $jenisPembayaran == 'dp' ? 'Cetak Bukti DP' : 'Cetak Invoice' }}
                </a>
            @endif
        </div>
    </div>
</div>

@if($transaksi->status_order == 'menunggu_acc_customer')
    <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-2xl shadow-sm border border-indigo-200 p-6 relative overflow-hidden mb-8">
        <div class="relative z-10">
            <h3 class="text-lg font-black text-indigo-800 mb-3 flex items-center gap-2">
                <i class="fas fa-handshake"></i> Review Penawaran Harga PO
            </h3>
            <p class="text-sm text-indigo-600 mb-4">Admin kami telah memberikan harga untuk komponen Custom/PO yang Anda ajukan. Berikut rinciannya:</p>
            
            <div class="bg-white rounded-xl border border-indigo-100 p-4 mb-4 shadow-sm">
                <div class="space-y-3">
                    @foreach($transaksi->details->whereNull('barang_id') as $item)
                        <div class="flex justify-between items-start sm:items-center text-sm flex-col sm:flex-row gap-1">
                            <div class="flex-1">
                                <span class="font-bold text-slate-700">{{ $item->nama_barang_custom }}</span>
                                <span class="text-xs font-black text-slate-400 ml-1">x{{ $item->qty }}</span>
                                <span class="ml-2 bg-amber-100 text-amber-600 text-[9px] px-2 py-0.5 rounded-full uppercase font-black tracking-wider">Custom / PO</span>
                            </div>
                            <div class="font-bold text-slate-800">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</div>
                        </div>
                    @endforeach
                </div>
                
                <div class="mt-4 pt-4 border-t-2 border-indigo-100 flex justify-between items-center bg-indigo-50/50 -mx-4 -mb-4 p-4 rounded-b-xl">
                    <div>
                        <span class="text-sm font-black text-indigo-900 uppercase block">Total Tagihan Akhir</span>
                    </div>
                    <span class="text-xl font-black text-indigo-700">Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</span>
                </div>
            </div>

            <div class="bg-white border-l-4 border-blue-500 rounded-r-xl p-3 mb-5 shadow-sm flex items-center gap-3">
                <i class="fas fa-shipping-fast text-blue-500 text-xl"></i>
                <div>
                    <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider">Estimasi Komponen Tiba</span>
                    <span class="text-sm font-black text-blue-700">{{ $transaksi->estimasi_po ?? 'Menunggu Konfirmasi Admin' }}</span>
                </div>
            </div>

            <div class="w-full mt-6">
                <p class="mb-4 text-sm font-bold text-slate-700">Pilih tindakan untuk pesanan Anda:</p>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <form action="{{ route('customer.cart.acc_po', $transaksi->id) }}" method="POST" class="m-0 p-0">
                        @csrf
                        <button type="submit" class="w-full h-[52px] bg-indigo-600 hover:bg-indigo-700 text-white font-bold px-4 rounded-xl shadow-md transition-all flex justify-center items-center gap-2 text-sm border border-transparent">
                            <i class="fas fa-check-circle"></i> Setuju & Lanjut
                        </button>
                    </form>
                    <button type="button" onclick="toggleRevisiModal(true)" class="w-full h-[52px] m-0 bg-amber-500 hover:bg-amber-600 text-white font-bold px-4 rounded-xl shadow-md transition-all flex justify-center items-center gap-2 text-sm border border-transparent">
                        <i class="fas fa-edit"></i> Ajukan Revisi
                    </button>
                    <form action="{{ route('customer.cart.tolak_po', $transaksi->id) }}" method="POST" class="m-0 p-0">
                        @csrf
                        <button type="submit" class="w-full h-[52px] bg-white border border-red-200 text-red-500 hover:bg-red-50 font-bold px-4 rounded-xl transition-all flex justify-center items-center gap-2 text-sm">
                            <i class="fas fa-times-circle"></i> Batal Beli
                        </button>
                    </form>
                </div>
            </div>
        </div> 
    </div> 
@elseif($transaksi->status_order == 'menunggu_kalkulasi')
    <div class="bg-amber-50 border border-amber-200 rounded-2xl p-6 text-center shadow-sm mb-8">
        <div class="w-16 h-16 bg-white text-amber-500 rounded-full flex items-center justify-center mx-auto mb-4 shadow-sm border border-amber-100">
            <i class="fas fa-calculator text-2xl animate-pulse"></i>
        </div>
        <h4 class="font-black text-amber-800 text-lg mb-2">Admin Sedang Mengkalkulasi Harga</h4>
        <p class="text-sm text-amber-700">Revisi komponen PO Anda sedang diproses. Mohon tunggu admin memberikan estimasi harga terbaru.</p>
    </div>
@endif

<div id="revisiModal" class="fixed inset-0 z-[100] hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    
    <!-- LAPISAN 1: BACKGROUND OVERLAY (Hanya Hitam Blur) -->
    <!-- Posisi absolute agar tidak membungkus konten putih -->
    <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" onclick="toggleRevisiModal(false)"></div>

    <!-- LAPISAN 2: WRAPPER KONTEN (Anti Blur) -->
    <!-- z-10 memastikan form ini melayang di atas background blur -->
    <div class="fixed inset-0 z-10 overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
            
            <!-- FORM PUTIH UTAMA -->
            <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg border border-slate-100 animate-fade-in-up">
                
                <div class="bg-slate-50 px-6 py-4 border-b border-slate-200 flex justify-between items-center">
                    <h3 class="text-xs font-black text-slate-800 uppercase tracking-wider" id="modal-title">Form Ajukan Revisi PO</h3>
                    <button type="button" onclick="toggleRevisiModal(false)" class="text-slate-400 hover:text-slate-600 transition-colors">
                        <i class="fas fa-times text-base"></i>
                    </button>
                </div>
                
                <form action="{{ route('customer.cart.revisi_po', $transaksi->id) }}" method="POST">
                    @csrf
                    <div class="px-6 py-5 max-h-[60vh] overflow-y-auto space-y-4">
                        <div class="text-xs text-amber-700 bg-amber-50 p-3.5 rounded-xl border border-amber-100 flex gap-2.5 items-start">
                            <i class="fas fa-info-circle mt-0.5"></i>
                            <p>Perbarui nama komponen atau link referensi di bawah. Admin akan menghitung ulang estimasi biayanya setelah form dikirim.</p>
                        </div>
                        
                        @foreach($transaksi->details->whereNull('barang_id') as $item)
                            <div class="p-4 bg-slate-50 border border-slate-200 rounded-xl space-y-3">
                                <div>
                                    <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1">Nama Komponen Pengganti</label>
                                    <input type="text" name="revisi_nama[{{ $item->id }}]" value="{{ $item->nama_barang_custom }}" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none transition-all bg-white" required>
                                </div>
                                <div>
                                    <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1">Link Referensi Baru</label>
                                    <input type="url" name="revisi_link[{{ $item->id }}]" value="{{ $item->link }}" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none transition-all bg-white" placeholder="https://tokopedia.com/...">
                                </div>
                            </div>
                        @endforeach
                        
                        <div>
                            <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1">Alasan / Pesan Tambahan (Opsional)</label>
                            <textarea name="catatan_revisi" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none bg-white" rows="3" placeholder="Tulis detail alasan revisi..."></textarea>
                        </div>
                    </div>
                    
                    <div class="bg-slate-50 px-6 py-4 border-t border-slate-200 flex flex-row-reverse gap-2">
                        <button type="submit" class="bg-amber-500 hover:bg-amber-600 text-white font-bold py-2 px-4 rounded-xl text-sm transition-all shadow-sm">Kirim Revisi</button>
                        <button type="button" onclick="toggleRevisiModal(false)" class="bg-white border border-slate-300 text-slate-700 hover:bg-slate-100 font-bold py-2 px-4 rounded-xl text-sm transition-all">Kembali</button>
                    </div>
                </form>
            </div>
            <!-- AKHIR FORM PUTIH UTAMA -->

        </div>
    </div>
</div>

   <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mt-6">
        <div class="lg:col-span-2 space-y-6">
            
@php
                // 1. Cek Metode Pengiriman (Ekspedisi / Kurir Teknisi vs Pickup Toko)
                $metodeDb = strtolower($transaksi->metode_pengiriman ?? $transaksi->delivery ?? '');
                $isPickup = str_contains($metodeDb, 'pickup') || str_contains($metodeDb, 'ambil') || str_contains($metodeDb, 'toko') || str_contains($metodeDb, 'gudang');
                $harusDikirim = !$isPickup;

                // 2. Ambil ongkir & hitung sisa bayar secara nyata
                $ongkirCust   = $transaksi->biaya_pengiriman ?? $transaksi->ongkir ?? 0;
                $totalTagihan = ($transaksi->total_harga ?? 0) + $ongkirCust;
                $sisaBayar    = max(0, $totalTagihan - ($transaksi->bayar ?? 0));
                
                // 🔥 LOGIKA JENIUSMU: Ongkir belum dihitung jika HARUS DIKIRIM tapi ongkir masih 0
                $ongkirBelumDihitung = ($harusDikirim && $ongkirCust <= 0);

                // 🔥 KUNCI MUTLAK: Wajib masuk panel ini JIKA bukti_pelunasan masih kosong DAN (ada ongkir belum dihitung ATAU ada sisa tagihan > 0)
                $wajibPelunasan = empty($transaksi->bukti_pelunasan) && ($ongkirBelumDihitung || $sisaBayar > 0);

                $isReady = in_array(strtolower($transaksi->status_order), ['siap_diambil', 'sedang_dikirim', 'ready']);
                
                // 3. Deteksi PC Rakitan vs Eceran
                $isPaketPC = ($transaksi->is_rakit == 1);
                if (!$isPaketPC && $transaksi->details) {
                    foreach ($transaksi->details as $d) {
                        $kat   = strtolower(optional($d->barang)->kategori ?? '');
                        $jenis = strtolower(optional($d->barang)->jenis ?? '');
                        if (str_contains($kat, 'pc') || str_contains($kat, 'rakitan') || str_contains($kat, 'build') || $jenis == 'paket_ready') {
                            $isPaketPC = true;
                            break;
                        }
                    }
                }
            @endphp

            <!-- 👇 1. FORM PELUNASAN (MUNCUL JIKA PESANAN SUDAH SIAP & ADA SISA TAGIHAN) 👇 -->
@if(($wajibPelunasan || !empty($transaksi->bukti_pelunasan) || $transaksi->status_order == 'sedang_dikirim') && !empty($transaksi->bukti_pembayaran) && $isReady)
                <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl shadow-lg border border-blue-400 p-6 text-white relative overflow-hidden">
                    <div class="absolute top-0 right-0 p-6 opacity-10">
                        @if($transaksi->status_order == 'sedang_dikirim')
                            <i class="fas fa-motorcycle text-9xl"></i>
                        @else
                            <i class="fas fa-hand-holding-usd text-9xl"></i>
                        @endif
                    </div>
                    
                    <div class="relative z-10">
                        <!-- JUDUL BANNER DINAMIS -->
                        <h3 class="text-xl font-black text-white mb-4 flex items-center gap-2">
                            @if($transaksi->status_order == 'sedang_dikirim')
                                <i class="fas fa-shipping-fast"></i> 
                                {{ ($isKurirToko ?? false) ? 'Teknisi Sedang Dalam Perjalanan!' : 'Pesanan Sedang Dikirim!' }}
                            @elseif($isPaketPC)
                                <i class="fas fa-check-double"></i> PC Anda Sudah Siap!
                            @else
                                <i class="fas fa-check-double"></i> Pesanan Anda Siap Dikirim!
                            @endif
                        </h3>
                        
                        @if($transaksi->pesan_penolakan)
                        <div class="bg-rose-50 border-l-4 border-rose-500 p-4 mb-5 rounded-r-xl shadow-md text-slate-800 flex items-start gap-3">
                            <div class="text-rose-500 mt-0.5">
                                <i class="fas fa-exclamation-triangle text-xl"></i>
                            </div>
                            <div>
                                <h4 class="text-rose-800 font-black text-xs uppercase tracking-wide mb-1">
                                    Bukti Pelunasan Ditolak Admin!
                                </h4>
                                <p class="text-rose-600 text-xs font-medium leading-relaxed">
                                    {{ $transaksi->pesan_penolakan }}
                                </p>
                                <p class="text-rose-500 text-[11px] mt-1.5 italic font-semibold">
                                    * Silakan upload ulang foto struk pelunasan yang valid & terbaca jelas.
                                </p>
                            </div>
                        </div>
                        @endif

                        <!-- 🔥 1. PRIORITAS MUTLAK TERATAS: JIKA TEKNISI/PAKET SEDANG DIKIRIM 🔥 -->
                        @if($transaksi->status_order == 'sedang_dikirim')
                            <div class="bg-black/20 border-l-4 border-emerald-400 rounded-r-xl p-5 mt-2 shadow-sm backdrop-blur-sm">
                                <h4 class="text-white font-black text-base mb-1 flex items-center gap-2">
                                    <i class="fas fa-motorcycle text-emerald-300 animate-bounce"></i> 
                                    {{ ($isKurirToko ?? false) ? 'Teknisi Meluncur ke Alamat Anda' : 'Paket Dalam Perjalanan Ekspedisi' }}
                                </h4>
                                <p class="text-xs text-blue-50 leading-relaxed">
                                    @if(($isKurirToko ?? false))
                                        Teknisi kami sedang dalam perjalanan menuju lokasi Anda untuk melakukan penyerahan & instalasi PC. Harap pastikan nomor telepon aktif dan ada penerima di lokasi.
                                    @else
                                        Pesanan Anda telah diserahkan ke pihak ekspedisi dan sedang dalam pengiriman ke alamat tujuan.
                                    @endif
                                </p>
                                @if(!empty($transaksi->bukti_pelunasan))
                                    <a href="{{ asset('storage/' . $transaksi->bukti_pelunasan) }}" target="_blank" class="inline-block mt-3 text-[10px] font-bold bg-white/10 hover:bg-white/20 px-3 py-1.5 rounded text-white transition-colors border border-white/30">
                                        Lihat Struk Pelunasan
                                    </a>
                                @endif
                            </div>

                        <!-- 2. PRIORITAS KEDUA: JIKA BUKTI PELUNASAN MASIH KOSONG -->
                        @elseif(empty($transaksi->bukti_pelunasan))
                            <!-- A. JIKA ONGKIR BELUM DIHITUNG (EKSPEDISI & KURIR TEKNISI) -->
                            @if($ongkirBelumDihitung)
                                <div class="bg-black/20 border-l-4 border-white-300 rounded-r-xl p-5 mt-4 shadow-sm backdrop-blur-sm">
                                    <h4 class="text-white font-bold mb-2 flex items-center gap-2">
                                        <i class="fas fa-box-open"></i> Menunggu Perhitungan Ongkir Akhir
                                    </h4>
                                    <p class="text-sm text-blue-50 leading-relaxed">
                                        Admin sedang menimbang paket / menghitung biaya pengiriman teknisi ke alamat Anda. <br>
                                        <strong>Form pelunasan dikunci sementara</strong> dan akan terbuka otomatis setelah biaya pengiriman ditambahkan.
                                    </p>
                                </div>

                            <!-- B. JIKA ONGKIR SUDAH ADA / ADA SISA TAGIHAN -> BUKA FORM UPLOAD -->
                            @else
                                <p class="text-sm text-blue-100 mb-6">
                                    @if($isPaketPC)
                                        Proses perakitan dan QC telah selesai. Silakan lunasi sisa tagihan (ongkir/sisa DP) sebesar 
                                    @else
                                        Komponen pesanan Anda siap diproses ke pengiriman. Silakan lunasi sisa tagihan (ongkir) sebesar 
                                    @endif
                                    <strong class="text-white text-lg tracking-wider bg-black/20 px-2 py-1 rounded whitespace-nowrap">
                                    Rp {{ number_format(max(0, $transaksi->total_harga - $transaksi->bayar), 0, ',', '.') }}
                                    </strong>
                                    untuk tahap selanjutnya.
                                </p>
                                
                                <form action="{{ route('customer.upload_bukti', $transaksi->id) }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <input type="file" name="bukti_pembayaran" id="file-pelunasan" required accept="image/*" class="hidden" onchange="previewPelunasan(event)">
                                    
                                    <div id="preview-container-pelunasan" class="hidden mb-5 relative">
                                        <p class="text-[10px] font-bold text-blue-200 uppercase tracking-widest mb-2">Preview Struk Pelunasan:</p>
                                        <img id="img-preview-pelunasan" class="w-32 h-32 object-cover rounded-xl border-2 border-white shadow-xl">
                                    </div>

                                    <div class="flex flex-col sm:flex-row items-center gap-3">
                                        <label id="lbl-file-pelunasan" for="file-pelunasan" class="w-full sm:w-auto cursor-pointer bg-white hover:bg-slate-100 text-blue-600 text-sm font-bold py-3 px-6 rounded-xl transition-all flex items-center justify-center gap-2 shadow-sm">
                                            <i class="fas fa-camera"></i> Upload Bukti Pelunasan
                                        </label>
                                        <button type="submit" id="btn-kirim-pelunasan" class="hidden w-full sm:w-auto bg-slate-900 hover:bg-black text-white text-sm font-bold py-3 px-8 rounded-xl transition-all duration-200 shadow-lg hover:shadow-2xl hover:scale-105 hover:ring-2 hover:ring-white hover:ring-offset-2 hover:ring-offset-blue-600 active:scale-95 flex items-center justify-center gap-2">
                                            <i class="fas fa-paper-plane"></i> Kirim Struk Pelunasan
                                        </button>
                                    </div>
                                </form>
                            @endif

                        <!-- 3. PRIORITAS KETIGA: JIKA BUKTI PELUNASAN SUDAH DIUPLOAD TAPI TEKNISI BELUM OTW -->
                        @else
                            <div class="bg-black/20 border border-white/20 rounded-xl p-4 mt-4 backdrop-blur-sm">
                                <p class="text-sm font-bold text-blue-300"><i class="fas fa-shield-check mr-1"></i> Bukti Pelunasan Terkirim</p>
                                <p class="text-[11px] text-blue-100 mt-1">Admin sedang memvalidasi pembayaran sisa tagihan / ongkir Anda. Harap tunggu konfirmasi.</p>
                                <a href="{{ asset('storage/' . $transaksi->bukti_pelunasan) }}" target="_blank" class="inline-block mt-3 text-[10px] font-bold bg-white/10 hover:bg-white/20 px-3 py-1.5 rounded text-white transition-colors border border-white/30">
                                    Lihat Struk Pelunasan
                                </a>
                            </div>
                        @endif
                    </div>
                </div>

            <!-- 👇 2. JIKA BELUM BAYAR SAMA SEKALI 👇 -->
            @elseif($transaksi->status_order == 'belum_bayar' && empty($transaksi->bukti_pembayaran))
                <div class="bg-gradient-to-br from-slate-900 to-indigo-900 rounded-2xl shadow-lg border border-indigo-800 p-6 text-white relative overflow-hidden">
                    <div class="absolute top-0 right-0 p-6 opacity-10"><i class="fas fa-wallet text-9xl"></i></div>
                    <div class="relative z-10">
                        <h3 class="text-lg font-bold text-amber-400 mb-3 flex items-center gap-2">
                            <i class="fas fa-exclamation-circle"></i> Menunggu Pembayaran       
                        </h3>       

                        @if($transaksi->pesan_penolakan)
                        <div class="bg-rose-50 border-l-4 border-rose-500 p-4 mb-5 rounded-r-xl shadow-sm flex items-start gap-3">
                            <div class="text-rose-500 mt-0.5"><i class="fas fa-exclamation-triangle text-xl"></i></div>
                            <div>
                                <h4 class="text-rose-800 font-black text-xs uppercase tracking-wide mb-1">Pembayaran Sebelumnya Ditolak Admin!</h4>
                                <p class="text-rose-600 text-xs font-medium leading-relaxed">{{ $transaksi->pesan_penolakan }}</p>
                                <p class="text-rose-500 text-[11px] mt-1.5 italic font-semibold">* Silakan unggah ulang foto struk transfer yang jelas dan valid di bawah ini.</p>
                            </div>
                        </div>
                        @endif

                        <p class="text-sm text-slate-300 mb-6">
                            Silakan lakukan transfer sebesar 
                            <strong class="text-white text-lg bg-white/10 px-2 py-1 rounded">
                                @if(strtolower($transaksi->status_pembayaran) == 'dp')
                                    Rp {{ number_format($transaksi->total_harga * 0.50, 0, ',', '.') }}
                                @else
                                    Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}
                                @endif
                            </strong> 
                            ke rekening di bawah ini untuk memproses pesanan Anda.
                        </p>
                        <div class="bg-white/10 border border-white/20 rounded-xl p-4 mb-6 inline-block">
                            <span class="block text-xs text-indigo-200 font-bold uppercase tracking-wider mb-1">Bank BCA</span>
                            <span class="block text-2xl font-black tracking-widest">8765-4321-00</span>
                            <span class="block text-xs text-slate-300 mt-1 font-medium">a.n PT Aneka Teknologi Solusindo</span>
                        </div>

                        <form action="{{ route('customer.upload_bukti', $transaksi->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="file" name="bukti_pembayaran" id="file-bukti" accept="image/*" class="hidden" onchange="previewImage(event)">
                            <div id="preview-container" class="hidden mb-5 relative">
                                <p class="text-[10px] font-bold text-indigo-300 uppercase tracking-widest mb-2">Preview Struk Anda:</p>
                                <img id="img-preview" class="w-32 h-32 object-cover rounded-xl border-2 border-blue-400 shadow-xl">
                                <div class="absolute top-7 right-2 bg-blue-500 text-white rounded-full p-1 shadow-lg"><i class="fas fa-check-circle"></i></div>
                            </div>
                            <div class="flex flex-col sm:flex-row items-center gap-3">
                                <label id="lbl-file" for="file-bukti" class="w-full sm:w-auto cursor-pointer bg-white hover:bg-slate-100 text-slate-800 text-sm font-bold py-3 px-6 rounded-xl transition-all flex items-center justify-center gap-2 shadow-sm">
                                    <i class="fas fa-camera"></i> Pilih Foto Struk
                                </label>
                                <button type="submit" id="btn-kirim" class="hidden w-full sm:w-auto bg-blue-500 hover:bg-blue-400 text-white text-sm font-bold py-3 px-8 rounded-xl transition-all shadow-lg shadow-blue-500/40">Kirim Bukti Transfer</button>
                                <button type="submit" form="form-pembatalan" class="w-full sm:w-auto text-sm font-medium text-rose-300 hover:text-rose-100 underline underline-offset-4 transition-colors py-3 px-4 text-center">Batalkan Pesanan</button>
                            </div>
                        </form>
                        <form id="form-pembatalan" action="{{ route('customer.cart.batal', $transaksi->id) }}" method="POST" class="hidden" onsubmit="confirmBatal(event, this)">
                            @csrf
                        </form>
                    </div>
                </div>

            <!-- 👇 3. JIKA SUDAH UPLOAD DP TAPI BELUM SELESAI DIRAKIT 👇 -->
            @elseif(!empty($transaksi->bukti_pembayaran) && empty($transaksi->bukti_pelunasan) && !$isReady)
                <div class="bg-gradient-to-br from-slate-900 to-indigo-900 rounded-2xl shadow-lg border border-indigo-800 p-6 text-white relative overflow-hidden flex flex-col sm:flex-row items-center justify-between gap-6">
                    <div class="absolute top-0 right-0 p-6 opacity-10"><i class="fas fa-shield-alt text-9xl"></i></div>
                    <div class="relative z-10 flex-1 text-center sm:text-left">
                        <h3 class="text-lg font-bold text-blue-400 mb-2"><i class="fas fa-check-double mr-2"></i> Pembayaran Terekam</h3>
                        <p class="text-sm text-slate-300">Bukti transfer Anda telah masuk. Pesanan saat ini sedang diproses/dirakit oleh Admin. Jika ada sisa tagihan, form pelunasan akan muncul setelah pesanan siap.</p>
                    </div>
                    <div class="relative z-10 shrink-0">
                        <p class="text-[10px] text-center font-bold text-indigo-300 uppercase tracking-widest mb-2">Arsip Struk Anda</p>
                        <a href="{{ asset('storage/' . $transaksi->bukti_pembayaran) }}" target="_blank" class="group relative block w-28 h-28 sm:w-32 sm:h-32 rounded-xl overflow-hidden border-2 border-white/20 hover:border-blue-400 transition-all shadow-xl bg-slate-800">
                            <img src="{{ asset('storage/' . $transaksi->bukti_pembayaran) }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                            <div class="absolute inset-0 bg-black/50 flex flex-col items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                <i class="fas fa-search-plus text-white text-2xl mb-1"></i>
                                <span class="text-[9px] text-white font-bold uppercase tracking-widest">Perbesar</span>
                            </div>
                        </a>
                    </div>
                </div>

            <!-- 👇 4. PESANAN OFFLINE 👇 -->
            @elseif(in_array($transaksi->status_order, ['menunggu_proses', 'process', 'sedang_dirakit']) && empty($transaksi->bukti_pembayaran))
                <div class="bg-gradient-to-br from-slate-800 to-slate-900 rounded-2xl shadow-lg border border-slate-700 p-6 text-white relative overflow-hidden">
                    <div class="absolute top-0 right-0 p-6 opacity-10"><i class="fas fa-store text-9xl"></i></div>
                    <div class="relative z-10">
                        <h3 class="text-lg font-bold text-amber-400 mb-2"><i class="fas fa-cash-register mr-2"></i> Pesanan Offline / Admin</h3>
                        <p class="text-sm text-slate-300">Pesanan ini dikelola langsung oleh Admin kami. Pembayaran dilakukan secara offline di toko atau telah dikonfirmasi.</p>
                    </div>
                </div>
            @endif
            <!-- 👆 AKHIR DARI BLOK CONDITION PEMBAYARAN 👆 -->
            <script>
                // 1. Fungsi Preview untuk Upload Pembayaran Awal / DP
                function previewImage(event) {
                    var reader = new FileReader();
                    reader.onload = function(){
                        var output = document.getElementById('img-preview');
                        output.src = reader.result;
                        
                        var container = document.getElementById('preview-container');
                        if (container) {
                            container.classList.remove('hidden');
                            container.classList.add('inline-block'); 
                        }
                        
                        var btnKirim = document.getElementById('btn-kirim');
                        if (btnKirim) btnKirim.classList.remove('hidden'); 
                        
                        var lblFile = document.getElementById('lbl-file');
                        if (lblFile) {
                            lblFile.classList.replace('bg-white', 'bg-slate-800'); 
                            lblFile.classList.replace('text-slate-800', 'text-white');
                            lblFile.innerHTML = '<i class="fas fa-sync-alt"></i> Ganti Foto';
                        }
                    };
                    reader.readAsDataURL(event.target.files[0]);
                }

                // 2. 👇 FUNGSI BARU YANG KETINGGALAN: Preview untuk Upload Pelunasan 👇
                function previewPelunasan(event) {
                    var reader = new FileReader();
                    reader.onload = function(){
                        var output = document.getElementById('img-preview-pelunasan');
                        if (output) output.src = reader.result;
                        
                        var container = document.getElementById('preview-container-pelunasan');
                        if (container) {
                            container.classList.remove('hidden');
                            container.classList.add('inline-block'); 
                        }
                        
                        var btnKirim = document.getElementById('btn-kirim-pelunasan');
                        if (btnKirim) btnKirim.classList.remove('hidden'); 
                        
                        var lblFile = document.getElementById('lbl-file-pelunasan');
                        if (lblFile) {
                            lblFile.classList.replace('bg-white', 'bg-slate-800'); 
                            lblFile.classList.replace('text-blue-600', 'text-white');
                            lblFile.innerHTML = '<i class="fas fa-sync-alt"></i> Ganti Foto Struk';
                        }
                    };
                    reader.readAsDataURL(event.target.files[0]);
                }
            </script>

            <!-- 👆 AKHIR DARI BLOK CONDITION PEMBAYARAN  PENUTUP UTAMA) 👆 -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex items-center gap-2">
                    <i class="fas fa-box-open text-slate-400"></i>
                    <h3 class="font-bold text-slate-800">Daftar Item Pesanan</h3>
                </div>
            
                <div class="p-6">
                    @php
                        // Tambahkan 'retur_diajukan' dan status akhir lainnya agar banner PO menghilang
                        $statusSelesai = ['siap_diambil', 'sedang_dikirim', 'selesai', 'ready', 'diterima_kustomer', 'completed', 'retur_diajukan'];
                        $isDone = in_array(strtolower($transaksi->status_order), $statusSelesai);
                        $showPoInfo = !empty($transaksi->estimasi_po) && !$isDone;
                    @endphp

                    @if($showPoInfo)
                    <div class="bg-amber-50 border border-amber-200 text-amber-800 px-4 py-3 rounded-xl mb-5 shadow-sm flex items-start gap-3">
                        <i class="fas fa-info-circle text-amber-500 mt-0.5"></i>
                        <p class="text-xs leading-relaxed font-medium">
                            <strong>Perhatian:</strong> Pesanan Anda mengandung komponen Pre-Order (PO). Waktu pengiriman/perakitan adalah <strong>{{ $transaksi->estimasi_po }}</strong>.
                        </p>
                    </div>
                    @endif
                    
                    <ul class="divide-y divide-slate-100">
                        @foreach($transaksi->details as $item)
                        <li class="py-4 first:pt-0 last:pb-0 flex justify-between items-start gap-4">
                            <div>
                                <p class="font-bold text-slate-800 text-sm mb-1">
                                    {{ $item->barang->nama_barang ?? $item->nama_barang_custom }}
                                </p>
                                
                                @if($showPoInfo && !empty($item->nama_barang_custom))
                                <div class="mb-1.5">
                                    <span class="inline-flex items-center gap-1 bg-amber-50 text-amber-700 text-[10px] font-bold px-2 py-0.5 rounded border border-amber-200 uppercase tracking-wider">
                                        <i class="fas fa-clock"></i> Estimasi PO: {{ $transaksi->estimasi_po }}
                                    </span>
                                </div>
                                @endif

                                <p class="text-xs font-semibold text-slate-500">
                                    {{ $item->qty }} x Rp {{ number_format($item->harga_satuan, 0, ',', '.') }}
                                </p>
                            </div>
                            <div class="font-black text-slate-900 text-sm">
                                Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                            </div>
                        </li>
                        @endforeach
                    </ul>
                    
                    <div class="mt-6 border-t border-slate-100 pt-4 space-y-3">
                        <div class="flex justify-between items-center text-sm">
                            <span class="font-semibold text-slate-500">Subtotal Perangkat</span>
                            <span class="font-bold text-slate-700">Rp {{ number_format($transaksi->total_harga - $transaksi->biaya_pengiriman, 0, ',', '.') }}</span>
                        </div>

                        @if($transaksi->biaya_pengiriman > 0)
                        <div class="flex justify-between items-center text-sm">
                            <span class="font-semibold text-slate-500">Biaya Pengiriman (Ekspedisi/Kurir)</span>
                            <span class="font-bold text-blue-600">+ Rp {{ number_format($transaksi->biaya_pengiriman, 0, ',', '.') }}</span>
                        </div>
                        @endif

                        <div class="flex justify-between items-center pt-3 border-t border-slate-100">
                            <span class="font-bold text-slate-800 text-base">Total Keseluruhan</span>
                            <span class="font-black text-indigo-700 text-xl">Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>

           @if(!empty($transaksi->resi_pengiriman))
            <div class="bg-slate-50 border border-slate-200 rounded-xl p-5 mb-6 shadow-sm">
                
                <!-- Info Resi & Foto (Selalu Muncul) -->
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                    <div>
                        <p class="text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Status Pengiriman (Dari Admin)</p>
                        <p class="text-base font-bold text-slate-800">
                            No. Resi: <span class="text-blue-600 font-mono bg-blue-50 border border-blue-200 px-2 py-0.5 rounded">{{ $transaksi->resi_pengiriman }}</span>
                        </p>
                    </div>
                    
                    <div class="flex flex-wrap items-center gap-2 w-full sm:w-auto mt-3 sm:mt-0">
                        <a href="{{ asset('storage/' . $transaksi->foto_resi_pengiriman) }}" target="_blank" class="bg-white border border-slate-300 hover:bg-slate-100 text-slate-700 font-bold py-2 px-4 rounded-lg shadow-sm transition-all text-xs flex items-center gap-2 flex-1 sm:flex-none justify-center">
                            <i class="fas fa-receipt text-blue-500"></i> Kertas Resi
                        </a>
                        
                        @if(!empty($transaksi->foto_paket_ekspedisi))
                        <a href="{{ asset('storage/' . $transaksi->foto_paket_ekspedisi) }}" target="_blank" class="bg-white border border-slate-300 hover:bg-slate-100 text-slate-700 font-bold py-2 px-4 rounded-lg shadow-sm transition-all text-xs flex items-center gap-2 flex-1 sm:flex-none justify-center">
                            <i class="fas fa-box text-amber-500"></i> Paket di POS
                        </a>
                        @endif
                    </div>
                </div>

                @if($transaksi->status_order == 'sedang_dikirim')
                <div class="mt-5 border-t border-slate-200 pt-5">
                    
                    <div class="bg-rose-50 border-l-4 border-rose-500 rounded-r-xl p-4 mb-5 shadow-sm">
                        <h4 class="font-black text-rose-800 text-sm mb-3 flex items-center gap-2 tracking-wide uppercase">
                            <i class="fas fa-shield-alt text-rose-600"></i> Perhatian Penting!
                        </h4>
                        <ul class="text-xs text-rose-700 space-y-3 font-medium">
                            <li class="flex items-start gap-2.5">
                                <i class="fas fa-hand-paper text-rose-500 mt-0.5 text-sm"></i>
                                <span class="leading-relaxed"><strong>JANGAN KLIK</strong> tombol selesai sebelum paket fisik benar-benar sampai di tangan Anda.</span>
                            </li>
                            <li class="flex items-start gap-2.5">
                                <i class="fas fa-video text-rose-500 mt-0.5 text-sm"></i>
                                <span class="leading-relaxed"><strong>WAJIB VIDEO UNBOXING:</strong> Harap rekam video tanpa jeda saat membuka paket. Video ini adalah syarat mutlak komplain.</span>
                            </li>
                        </ul>
                    </div>
                <!-- 👆 SELESAI BANNER 👆 -->
               <div class="flex flex-col sm:flex-row gap-3">
    
                    <form action="{{ route('customer.orders.selesai', $transaksi->id) }}" method="POST" class="w-full sm:flex-1" onsubmit="confirmSelesai(event, this)">
                        @csrf
                        <button type="submit" class="w-full h-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 px-6 rounded-lg shadow text-sm flex items-center justify-center gap-2 transition-all">
                            <i class="fas fa-check-circle"></i> Pesanan Diterima & Aman
                        </button>
                    </form>

                    <button type="button" onclick="toggleReturModal(true)" class="w-full sm:flex-1 h-full bg-rose-600 hover:bg-rose-700 text-white font-bold py-2.5 px-6 rounded-lg shadow text-sm flex items-center justify-center gap-2 transition-all">
                        <i class="fas fa-exclamation-triangle"></i> Ajukan Komplain Retur
                    </button>
                    
                </div>
                </div>
                @endif
            </div>
            @endif

            <div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 p-6">
    <h3 class="font-bold text-slate-800 mb-4 flex items-center gap-2">
        <i class="fas fa-shipping-fast text-slate-400"></i> Informasi Pengiriman
    </h3>
    
    @php
        // Cek apakah di dalam transaksi ada item PC Paket / Ready Stock
        $isPaketReady = $transaksi->details->contains(function ($detail) {
            return optional($detail->barang)->jenis == 'paket';
        });
    @endphp

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
        <div>
            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Metode Pengiriman</p>
            <p class="font-semibold text-slate-700">
                @if($transaksi->metode_pengiriman == 'pickup') Ambil di Toko
                @elseif($transaksi->metode_pengiriman == 'courier') Kirim via POS Indonesia
                @else Teknisi ke Rumah @endif
            </p>
        </div>
        
        <div>
            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Opsi Rakit</p>
            {{-- 👇 FIX: DETEKSI 3 KONDISI (TEKNISI / READY STOCK / PART BERSEGEL) 👇 --}}
            @if($transaksi->is_rakit == 1)
                <p class="font-semibold text-blue-600"><i class="fas fa-tools mr-1"></i> Dirakit oleh Teknisi</p>
            @elseif($isPaketReady)
                <p class="font-semibold text-emerald-600"><i class="fas fa-desktop mr-1"></i> PC Ready Stock (Siap Pakai)</p>
            @else
                <p class="font-semibold text-amber-600"><i class="fas fa-box mr-1"></i> Kirim Part Bersegel</p>
            @endif
        </div>
        
        <div class="sm:col-span-2 mt-2">
            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Alamat / Catatan Tambahan</p>
            <p class="font-semibold text-slate-700 bg-slate-50 p-4 rounded-xl border border-slate-100">
                {!! nl2br(e($transaksi->alamat ?? 'Tidak ada catatan alamat.')) !!}
            </p>
        </div>
        
        @if(in_array(strtolower($transaksi->metode_pengiriman), ['courier', 'delivery', 'technician']) && $transaksi->biaya_pengiriman == 0 && !in_array($transaksi->status_order, ['siap_diambil', 'sedang_dikirim', 'selesai', 'ready', 'diterima_kustomer']))
            <div class="sm:col-span-2 mt-2 bg-blue-50 border border-blue-200 rounded-xl p-4 flex items-start gap-3 shadow-sm">
                <i class="fas fa-info-circle text-blue-500 mt-0.5 text-lg"></i>
                <p class="text-xs text-blue-800 leading-relaxed font-medium">
                    <strong>Pemberitahuan Ongkos Kirim:</strong> Karena Anda memilih pengiriman via Ekspedisi/Kurir, ongkos kirim sementara adalah <strong>Rp 0</strong>. Admin kami akan menghitung biaya aktual (berdasarkan dimensi/berat PC) <strong>setelah PC selesai dirakit</strong>. Tagihan ongkir akan muncul otomatis pada tahap pelunasan.
                </p>
            </div>
        @endif

        {{-- ========================================================================= --}}
        {{-- BAGIAN GARANSI & QC MASTER RAKITAN / TEKNISI --}}
        {{-- ========================================================================= --}}
     @if(in_array($transaksi->status_order, ['siap_diambil', 'sedang_dikirim', 'selesai', 'completed', 'ready', 'diterima_kustomer']))
            @php
                // 1. Deteksi PC Rakitan
                $isPaketPC = ($transaksi->is_rakit == 1);
                if (!$isPaketPC && $transaksi->details) {
                    foreach ($transaksi->details as $d) {
                        $kat   = strtolower(optional($d->barang)->kategori ?? '');
                        $jenis = strtolower(optional($d->barang)->jenis ?? '');
                        if (str_contains($kat, 'pc') || str_contains($kat, 'rakitan') || str_contains($kat, 'build') || $jenis == 'paket_ready') {
                            $isPaketPC = true;
                            break;
                        }
                    }
                }
                if (!$isPaketPC && (($transaksi->dokumenQcs && $transaksi->dokumenQcs->count() > 0) || $transaksi->dokumenQc)) {
                    $isPaketPC = true;
                }

                // 🔥 2. PERBAIKAN MUTLAK GEMBOK GARANSI & QC:
                // - Jika status SUDAH 'sedang_dikirim' (OTW), 'selesai', 'diterima_kustomer', GARANSI WAJIB 100% TERBUKA!
                // - Jangan tambah ongkir 2 kali (karena total_harga di DB sudah termasuk ongkir)
                $statusOtwAtauSelesai = in_array(strtolower($transaksi->status_order), ['sedang_dikirim', 'selesai', 'completed', 'diterima_kustomer']);
                
                $metodeCek    = strtolower($transaksi->metode_pengiriman ?? $transaksi->delivery ?? '');
                $isPickupCek  = str_contains($metodeCek, 'pickup') || str_contains($metodeCek, 'ambil') || str_contains($metodeCek, 'toko') || str_contains($metodeCek, 'gudang');
                $ongkirNowCek = $transaksi->biaya_pengiriman ?? $transaksi->ongkir ?? 0;
                
                // total_harga sudah termasuk ongkir dari database
                $totalTagihanCek = $transaksi->total_harga; 
                $sisaBayarCek    = max(0, $totalTagihanCek - ($transaksi->bayar ?? 0));

                // Gembok HANYA JIKA belum OTW/Selesai DAN (ongkir masih 0 untuk non-pickup ATAU masih ada sisa bayar > 0)
                $garansiTerkunci = !$statusOtwAtauSelesai && ((!$isPickupCek && $ongkirNowCek <= 0) || $sisaBayarCek > 0);
            @endphp

            <div class="sm:col-span-2 mt-4 pt-4 border-t border-slate-200">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">
                    Garansi & Quality Control (QC)
                </p>
                
                {{-- 🔥 1. JIKA BELI PART BERSEGEL / ECERAN (!isPaketPC) -> SELALU MUNCUL INFO DISTRIBUTOR 🔥 --}}
                @if(!$isPaketPC)
                    <div class="bg-amber-50/90 border border-amber-200 rounded-xl p-4 flex items-start gap-3.5 shadow-2xs">
                        <div class="w-9 h-9 bg-amber-100 text-amber-600 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5">
                            <i class="fas fa-shield-alt text-base"></i>
                        </div>
                        <div>
                            <h4 class="text-xs font-black text-amber-900 uppercase tracking-wider mb-1 flex items-center gap-2">
                                <span>Garansi Resmi Distributor (Part Bersegel)</span>
                                <span class="bg-amber-200/80 text-amber-800 text-[9px] px-2 py-0.5 rounded-full font-bold">100% Original</span>
                            </h4>
                            <p class="text-[11px] text-amber-800/90 leading-relaxed">
                                Pembelian komponen bersegel utuh tidak melalui tahap perakitan/QC Workshop kami. Klaim garansi produk dapat dilakukan langsung ke <b>Service Center Resmi Distributor</b> brand terkait dengan menunjukkan bukti <b>Invoice Pembelian</b> ini atau kemasan produk.
                            </p>
                        </div>
                    </div>

                {{-- 🔥 2. JIKA RAKIT PC / READY STOCK BUNDLING ($isPaketPC == true) 🔥 --}}
                @else
                    @if(!$garansiTerkunci)
                        {{-- A. JIKA SUDAH LUNAS / TEKNISI OTW -> MUNCUL TOMBOL DOWNLOAD GARANSI --}}
                        @if($transaksi->dokumenQcs && $transaksi->dokumenQcs->count() > 0)
                            <div class="flex flex-wrap gap-2">
                                @foreach($transaksi->dokumenQcs as $doc)
                                    <a href="{{ route('customer.transaksi.download_garansi', [$transaksi->id, 'unit' => $doc->nomor_unit]) }}" target="_blank" 
                                       class="inline-flex items-center justify-center gap-2 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white text-sm font-black py-3 px-5 rounded-xl transition-all shadow-lg shadow-indigo-500/20 active:scale-95">
                                        <i class="fas fa-file-pdf text-lg"></i>
                                        Garansi Unit #{{ $doc->nomor_unit }}
                                    </a>
                                @endforeach
                            </div>
                        @else
                            <div class="flex flex-col sm:flex-row gap-3">
                                <a href="{{ route('customer.transaksi.cetak_garansi_qc', ['id' => $transaksi->id, 'unit' => 1]) }}" target="_blank" 
                                   class="inline-flex items-center justify-center gap-2 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white text-sm font-black py-3 px-6 rounded-xl transition-all shadow-lg shadow-indigo-500/30">
                                    <i class="fas fa-file-pdf text-lg"></i>
                                    Download Kartu Garansi & QC PC
                                </a>

                                @php
                                    $fotoQc = $transaksi->dokumenQc->foto_menyala 
                                              ?? optional($transaksi->details->first()->barang)->foto_qc 
                                              ?? optional($transaksi->details->first()->barang)->foto_menyala 
                                              ?? null;
                                @endphp

                                @if($fotoQc)
                                <a href="{{ asset('storage/' . $fotoQc) }}" target="_blank" 
                                   class="inline-flex items-center justify-center gap-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-bold py-3 px-5 rounded-xl transition-all border border-slate-200">
                                    <i class="fas fa-camera text-blue-500"></i>
                                    Lihat Foto QC PC
                                </a>
                                @endif
                            </div>
                        @endif
                    @else
                        {{-- B. JIKA ONGKIR BELUM DIHITUNG ATAU TAGIHAN ONGKIR BELUM LUNAS -> KUNCI KARTU GARANSI --}}
                        <div class="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-slate-100 text-slate-400 text-sm font-bold py-3 px-6 rounded-xl border border-slate-200 cursor-not-allowed mb-2" title="Lunasi sisa tagihan/ongkir terlebih dahulu">
                            <i class="fas fa-lock text-lg"></i>
                            Kartu Garansi (Terkunci)
                        </div>
                        <p class="text-[10px] text-amber-500 font-bold uppercase tracking-widest">* Lunasi sisa tagihan (ongkir) untuk membuka kartu garansi & QC.</p>
                    @endif
                @endif
            </div>
        @endif

    </div>
</div>

<!-- 👇 PANEL RETUR DISETUJUI (KUSTOMER DIMINTA INPUT RESI) 👇 -->
            @if($transaksi->pengajuanRetur && $transaksi->pengajuanRetur->status == 'disetujui')
            <div class="bg-blue-50 border-2 border-blue-300 rounded-2xl p-6 mb-6 shadow-sm relative overflow-hidden">
                <div class="absolute top-0 right-0 bg-blue-600 text-white text-[10px] font-black px-4 py-1.5 rounded-bl-lg uppercase tracking-widest shadow-sm">
                    <i class="fas fa-check-circle mr-1"></i> Retur Disetujui
                </div>

                <h3 class="font-black text-blue-800 mb-3 uppercase tracking-wider text-sm flex items-center gap-2">
                    <i class="fas fa-truck-loading text-blue-600 text-lg"></i> Kirim Balik PC Anda
                </h3>
                
                <p class="text-xs text-blue-700 mb-5 leading-relaxed font-medium">
                    Pengajuan retur Anda telah diverifikasi dan <strong>DISETUJUI</strong> oleh Admin. Silakan packing kembali PC dengan aman (wajib menggunakan kayu/bubble wrap tebal), lalu kirimkan melalui ekspedisi ke alamat toko kami. <br><br>
                    Jika paket sudah diserahkan ke kurir, wajib unggah bukti resi dan foto packing di bawah ini:
                </p>

                <form action="{{ route('customer.retur.kirim_resi', $transaksi->pengajuanRetur->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4 bg-white p-5 rounded-xl border border-blue-100 shadow-sm">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Nomor Resi Pengiriman Balik <span class="text-rose-500">*</span></label>
                        <input type="text" name="resi_pengiriman_balik" value="{{ old('resi_pengiriman_balik') }}" required placeholder="Contoh: POS-123456789" class="w-full rounded-xl border-slate-300 focus:ring-blue-500 focus:border-blue-500 text-sm">
                        @error('resi_pengiriman_balik') <span class="text-xs text-rose-600 font-bold mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Foto Kertas Resi <span class="text-rose-500">*</span></label>
                            <input type="file" name="foto_resi_balik" required accept="image/jpeg,image/png,image/jpg" class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer">
                            @error('foto_resi_balik') <span class="text-xs text-rose-600 font-bold block mt-1">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Foto Kondisi Packing <span class="text-rose-500">*</span></label>
                            <input type="file" name="foto_packing_balik" required accept="image/jpeg,image/png,image/jpg" class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer">
                            <p class="text-[10px] text-slate-400 mt-1 italic">Pastikan terlihat kayu / pelindung paket.</p>
                            @error('foto_packing_balik') <span class="text-xs text-rose-600 font-bold block mt-1">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-2 pt-4 border-t border-blue-100">
                        <div class="md:col-span-2">
                            <p class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">Informasi Pengembalian Dana (Refund / Biaya Ongkir)</p>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Nama Bank / E-Wallet <span class="text-rose-500">*</span></label>
                            <input type="text" name="nama_bank" required placeholder="Contoh: BCA / GoPay" class="w-full rounded-xl border-slate-300 focus:ring-blue-500 focus:border-blue-500 text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Nomor Rekening / No. HP <span class="text-rose-500">*</span></label>
                            <input type="text" name="no_rekening" required placeholder="Contoh: 1234567890 a/n Kevin" class="w-full rounded-xl border-slate-300 focus:ring-blue-500 focus:border-blue-500 text-sm">
                        </div>
                    </div>
                    <div class="pt-2 border-t border-blue-100 mt-4">
                        <button type="submit" onclick="this.innerHTML='Mengunggah...'; this.disabled=true; this.form.submit();" class="mt-3 bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-xl text-xs shadow-md transition-all w-full sm:w-auto flex items-center justify-center gap-2">
                            <i class="fas fa-paper-plane"></i> Kirim Resi & Foto Packing
                        </button>
                    </div>
                </form>
            </div>

            <!-- 👇 PANEL TAMBAHAN: MUNCUL SETELAH RESI BERHASIL DIKIRIM KUSTOMER 👇 -->
            @elseif($transaksi->pengajuanRetur && in_array($transaksi->pengajuanRetur->status, ['barang_dikirim', 'sedang_dicek_teknisi']))
            <div class="bg-indigo-50 border-2 border-indigo-300 rounded-2xl p-6 mb-6 shadow-sm relative overflow-hidden">
                <div class="absolute top-0 right-0 bg-indigo-600 text-white text-[10px] font-black px-4 py-1.5 rounded-bl-lg uppercase tracking-widest shadow-sm">
                    <i class="fas fa-truck mr-1"></i> Paket Retur Menuju Toko
                </div>

                <h3 class="font-black text-indigo-800 mb-3 uppercase tracking-wider text-sm flex items-center gap-2">
                    <i class="fas fa-box-open text-indigo-600 text-lg"></i> Menunggu Pengecekan Admin
                </h3>
                
                <p class="text-xs text-indigo-700 mb-5 leading-relaxed font-medium">
                    Resi pengiriman balik telah berhasil diunggah. Saat ini kami sedang menunggu paket retur Anda tiba di workshop untuk dilakukan pengecekan dan perbaikan oleh tim teknisi.
                </p>

                <div class="bg-white p-4 rounded-xl border border-indigo-100 shadow-sm flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                    <div>
                        <p class="text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Resi Pengiriman Balik</p>
                        <p class="text-base font-bold text-slate-800">
                            <span class="text-indigo-600 font-mono bg-indigo-50 border border-indigo-200 px-2 py-0.5 rounded">{{ $transaksi->pengajuanRetur->resi_pengiriman_balik }}</span>
                        </p>
                    </div>
                    
                    <div class="flex flex-wrap items-center gap-2 w-full sm:w-auto mt-2 sm:mt-0">
                        <a href="{{ asset('storage/' . $transaksi->pengajuanRetur->foto_resi_balik) }}" target="_blank" class="bg-white border border-slate-300 hover:bg-slate-100 text-slate-700 font-bold py-2 px-4 rounded-lg shadow-sm transition-all text-xs flex items-center gap-2 flex-1 sm:flex-none justify-center">
                            <i class="fas fa-receipt text-indigo-500"></i> Kertas Resi
                        </a>
                        
                        @if(!empty($transaksi->pengajuanRetur->foto_packing_balik))
                        <a href="{{ asset('storage/' . $transaksi->pengajuanRetur->foto_packing_balik) }}" target="_blank" class="bg-white border border-slate-300 hover:bg-slate-100 text-slate-700 font-bold py-2 px-4 rounded-lg shadow-sm transition-all text-xs flex items-center gap-2 flex-1 sm:flex-none justify-center">
                            <i class="fas fa-box text-amber-500"></i> Foto Packing
                        </a>
                        @endif
                    </div>
                </div>
            </div>
            <!-- 👇 PANEL FINAL: REFUND SELESAI (KLAIM VALID) 👇 -->
            @elseif($transaksi->pengajuanRetur && $transaksi->pengajuanRetur->status == 'selesai_refund')
            <div class="bg-blue-50 border-2 border-blue-300 rounded-2xl p-6 mb-6 shadow-sm relative overflow-hidden">
                <div class="absolute top-0 right-0 bg-blue-600 text-white text-[10px] font-black px-4 py-1.5 rounded-bl-lg uppercase tracking-widest shadow-sm">
                    <i class="fas fa-check-circle mr-1"></i> Refund Berhasil
                </div>

                <h3 class="font-black text-blue-800 mb-3 uppercase tracking-wider text-sm flex items-center gap-2">
                    <i class="fas fa-hand-holding-usd text-blue-600 text-lg"></i> Dana Telah Dikembalikan
                </h3>
                
                <p class="text-xs text-blue-700 mb-4 leading-relaxed font-medium">
                    Pengajuan retur Anda dinyatakan valid. Dana telah kami transfer kembali ke rekening/e-wallet yang Anda berikan. Transaksi ini resmi dibatalkan.
                </p>

                <div class="bg-white p-4 rounded-xl border border-blue-100 shadow-sm flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                    <div>
                        <p class="text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Bukti Transfer (Dari Admin)</p>
                        <p class="text-xs font-bold text-slate-800">Silakan cek struk transfer untuk memastikan dana masuk.</p>
                    </div>
                    <a href="{{ asset('storage/' . $transaksi->pengajuanRetur->bukti_refund) }}" target="_blank" class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 px-4 rounded-lg shadow-sm transition-all text-xs flex items-center justify-center gap-2">
                        <i class="fas fa-receipt"></i> Lihat Bukti Refund
                    </a>
                </div>
            </div>

            <!-- 👇 PANEL FINAL: KLAIM DITOLAK / GUGUR 👇 -->
           @elseif($transaksi->pengajuanRetur && $transaksi->pengajuanRetur->status == 'klaim_ditolak')
<div class="bg-rose-50 border-2 border-rose-300 rounded-2xl p-6 mb-6 shadow-sm relative overflow-hidden">
    <div class="absolute top-0 right-0 bg-rose-600 text-white text-[10px] font-black px-4 py-1.5 rounded-bl-lg uppercase tracking-widest shadow-sm">
        <i class="fas fa-times-circle mr-1"></i> Klaim Gugur
    </div>

    <h3 class="font-black text-rose-800 mb-3 uppercase tracking-wider text-sm flex items-center gap-2">
        <i class="fas fa-ban text-rose-600 text-lg"></i> Pengajuan Retur Ditolak
    </h3>
    
    <p class="text-xs text-rose-700 mb-4 leading-relaxed font-medium">
        Berdasarkan hasil pengecekan tim Teknisi kami, klaim retur Anda dinyatakan <strong>TIDAK VALID / GUGUR</strong>. Transaksi ini otomatis ditutup dan dinyatakan selesai secara sepihak.
    </p>

    <div class="bg-white p-4 rounded-xl border border-rose-100 shadow-sm">
        <p class="text-[10px] font-bold text-rose-500 uppercase tracking-wider mb-3 border-b border-rose-100 pb-2">Hasil Pengecekan / Alasan Penolakan:</p>
        <div class="text-xs text-slate-700 font-medium leading-relaxed mb-4">
            <!-- Pakai biar tag <br> yang dibuat Admin kemarin jadi enter / baris baru -->
            {!! $transaksi->pengajuanRetur->hasil_cek_teknisi !!}
        </div>

        <!-- 👇 TAMBAHAN UI UNTUK PARTIAL REFUND (AKUMULASI) 👇 -->
        @if($transaksi->pengajuanRetur->nominal_refund && $transaksi->pengajuanRetur->bukti_refund)
        <div class="mt-2 p-4 bg-slate-50 border border-slate-200 rounded-lg">
            <p class="text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-3 border-b border-slate-200 pb-2">Informasi Pengembalian Dana (Sisa Akumulasi):</p>
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <div>
                    <p class="text-[10px] text-slate-400 font-bold uppercase mb-1">Total Dana Dikembalikan</p>
                    <p class="text-emerald-600 font-black text-lg">Rp {{ number_format($transaksi->pengajuanRetur->nominal_refund, 0, ',', '.') }}</p>
                </div>
                <div>
                    <!-- Link untuk melihat/download gambar bukti transfer -->
                    <a href="{{ asset('storage/' . $transaksi->pengajuanRetur->bukti_refund) }}" target="_blank" class="inline-flex items-center gap-2 bg-white border border-slate-300 hover:bg-slate-100 text-slate-700 font-bold py-2 px-4 rounded-lg text-xs transition-all shadow-sm">
                        <i class="fas fa-file-invoice-dollar text-emerald-500"></i> Lihat Bukti Transfer
                    </a>
                </div>
            </div>
        </div>
        @endif
        <!-- 👆 AKHIR TAMBAHAN 👆 -->

    </div>
</div>
@endif
            <!-- 👆 SELESAI PANEL RESI RETUR 👆 -->
            
           <!-- 👇 FITUR BARU: LOG RIWAYAT PEMBAYARAN (VERSI DINAMIS) 👇 -->
<div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 overflow-hidden mt-6">
    <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex items-center gap-2">
        <i class="fas fa-list-ul text-slate-400"></i>
        <h3 class="font-bold text-slate-800">Log Riwayat Pembayaran</h3>
    </div>
    
    <div class="p-6">
        <div class="relative border-l-2 border-slate-100 ml-3 space-y-6">
            
            <!-- TAHAP 1: PEMBAYARAN AWAL -->
            @if(!empty($transaksi->bukti_pembayaran))
            <div class="relative pl-6">
                <div class="absolute -left-[9px] top-0 w-4 h-4 rounded-full bg-blue-500 border-4 border-white shadow"></div>
                <div class="flex justify-between items-start sm:items-center flex-col sm:flex-row gap-2">
                    <div>
                        <p class="text-sm font-bold text-slate-800">
                            @if(strtolower($transaksi->status_pembayaran) == 'dp' || !empty($transaksi->bukti_pelunasan))
                                Pembayaran Awal (DP)
                            @else
                                Pembayaran Lunas 100%
                            @endif
                        </p>
                        <p class="text-[11px] font-medium text-slate-500 mt-0.5">Kustomer mengunggah struk pembayaran pertama.</p>
                    </div>
                    <a href="{{ asset('storage/' . $transaksi->bukti_pembayaran) }}" target="_blank" class="bg-slate-50 hover:bg-slate-100 text-indigo-600 text-[10px] font-bold px-3 py-1.5 rounded-lg border border-slate-200 transition-colors shadow-sm">
                        <i class="fas fa-image mr-1"></i> Lihat Struk
                    </a>
                </div>
            </div>
            @else
            <div class="relative pl-6">
                <div class="absolute -left-[9px] top-0 w-4 h-4 rounded-full bg-slate-200 border-4 border-white"></div>
                <p class="text-sm font-bold text-slate-400">Belum ada pembayaran terekam</p>
            </div>
            @endif

            <!-- TAHAP 2: TAGIHAN ONGKIR MUNCUL (JIKA ADA) -->
            @if($transaksi->biaya_pengiriman > 0)
            <div class="relative pl-6">
                <div class="absolute -left-[9px] top-0 w-4 h-4 rounded-full bg-blue-500 border-4 border-white shadow"></div>
                <div>
                    <p class="text-sm font-bold text-slate-800">Penambahan Ongkos Kirim</p>
                    <p class="text-[11px] font-medium text-slate-500 mt-0.5">
                        Admin menetapkan biaya logistik/ongkir sebesar <strong class="text-blue-600">Rp {{ number_format($transaksi->biaya_pengiriman, 0, ',', '.') }}</strong>.
                    </p>
                </div>
            </div>
            @endif

            <!-- TAHAP 3: BUKTI KEDUA (PELUNASAN SISA / ONGKIR) -->
            @if(!empty($transaksi->bukti_pelunasan))
            @php
                $isDPLog = strtolower($transaksi->status_pembayaran) == 'dp';
                $hasOngkirLog = $transaksi->biaya_pengiriman > 0;
                
                if ($isDPLog && $hasOngkirLog) {
                    $judulPelunasan = 'Pembayaran Sisa DP & Ongkos Kirim';
                    $descPelunasan = 'Kustomer melunasi sisa tagihan PC beserta biaya pengiriman.';
                } elseif ($isDPLog && !$hasOngkirLog) {
                    $judulPelunasan = 'Pembayaran Pelunasan (Sisa DP)';
                    $descPelunasan = 'Kustomer melunasi sisa tagihan PC.';
                } elseif (!$isDPLog && $hasOngkirLog) {
                    $judulPelunasan = 'Pembayaran Ongkos Kirim';
                    $descPelunasan = 'Kustomer membayar tagihan khusus ongkos kirim.';
                } else {
                    $judulPelunasan = 'Pembayaran Pelunasan';
                    $descPelunasan = 'Bukti pembayaran tahap akhir diunggah.';
                }
            @endphp
            <div class="relative pl-6">
                <div class="absolute -left-[9px] top-0 w-4 h-4 rounded-full {{ ($adaSisaTagihan ?? false) ? 'bg-amber-400 animate-pulse' : 'bg-blue-500' }} border-4 border-white shadow"></div>
                <div class="flex justify-between items-start sm:items-center flex-col sm:flex-row gap-2">
                    <div>
                        <p class="text-sm font-bold text-slate-800">{{ $judulPelunasan }}</p>
                        <p class="text-[10px] text-slate-400 mb-1 italic">{{ $descPelunasan }}</p>
                        
                        <p class="text-[11px] font-medium mt-1">
                            @if($adaSisaTagihan ?? false)
                                <span class="text-amber-600"><i class="fas fa-spinner fa-spin mr-1"></i> Menunggu verifikasi Admin...</span>
                            @else
                                <span class="text-blue-600 font-black"><i class="fas fa-check-double mr-1"></i> Divalidasi! Pesanan Lunas 100%.</span>
                            @endif
                        </p>
                    </div>
                    <a href="{{ asset('storage/' . $transaksi->bukti_pelunasan) }}" target="_blank" class="bg-slate-50 hover:bg-slate-100 text-indigo-600 text-[10px] font-bold px-3 py-1.5 rounded-lg border border-slate-200 transition-colors shadow-sm">
                        <i class="fas fa-image mr-1"></i> Lihat Struk
                    </a>
                </div>
            </div>
            @endif

        </div>
    </div>
</div>
<!-- 👆 AKHIR FITUR LOG PEMBAYARAN 👆 -->
</div>

        

        <div class="lg:col-span-1">
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 p-6 sticky top-24">
        <h3 class="font-bold text-slate-800 mb-6 flex items-center gap-2">
            <i class="fas fa-map-marker-alt text-indigo-500"></i> Lacak Pesanan
        </h3>
        
        @php
            $status = strtolower($transaksi->status_order);
            $isPO = $transaksi->details->whereNull('barang_id')->count() > 0;
            $metode = strtolower($transaksi->metode_pengiriman);
            
            $bayar = empty($transaksi->bayar) ? 0 : $transaksi->bayar;
            $total = empty($transaksi->total_harga) ? 0 : $transaksi->total_harga;
            $isOffline = empty($transaksi->bukti_pembayaran) && !in_array($status, ['belum_bayar', 'batal', 'cancel']);
            
            $level = match($status) {
                'batal', 'cancel' => -1,
                'menunggu_kalkulasi', 'menunggu_acc_customer' => 0, 
                'belum_bayar' => 1,
                'menunggu_proses', 'menunggu_po' => 2,
                'sedang_dirakit', 'process' => 3,
                // 👇 TAMBAHKAN retur_diajukan DI SINI AGAR TETAP DI TAHAP 4 👇
                'siap_diambil', 'sedang_dikirim', 'ready', 'diterima_kustomer', 'retur_dikirim', 'retur_diajukan' => 4,
                'selesai', 'completed' => 5,
                default => 1
            };

            // KUNCI MUTLAK: Tahan timeline di Tahap 1 selama uangnya belum di-ACC Admin
            if ($bayar == 0 || $status == 'belum_bayar') {
                $level = 1;
            }
        @endphp

        <div class="relative">
            @if($level >= 0)
                <div class="absolute left-5 top-4 bottom-8 w-0.5 bg-slate-100"></div>
            @endif

            <div class="space-y-8">
                
                <!-- TAHAP 0: PRE-ORDER (MUNCUL JIKA ADA PART CUSTOM) -->
                @if($isPO)
                <div class="relative flex items-start gap-4">
                    <div class="relative z-10 flex-shrink-0 w-10 h-10 rounded-full flex items-center justify-center text-sm transition-colors duration-500 {{ $level >= 0 ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/30' : 'bg-slate-100 text-slate-400' }}">
                        <i class="fas fa-handshake"></i>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold {{ $level == 0 ? 'text-indigo-600' : 'text-slate-900' }}">Kesepakatan Pre-Order</h4>
                        <p class="text-[11px] font-medium mt-1 {{ $level == 0 ? 'text-amber-500' : 'text-slate-500' }}">
                            @if($status == 'menunggu_kalkulasi')
                                Admin sedang menghitung estimasi biaya PO Anda.
                            @elseif($status == 'menunggu_acc_customer')
                                Menunggu persetujuan penawaran harga dari Anda.
                            @else
                                Harga komponen Pre-Order telah disepakati.
                            @endif
                        </p>
                    </div>
                </div>
                @endif

                <!-- TAHAP 1: PEMBAYARAN -->
               <div class="relative flex items-start gap-4">
                    @if($isPO && $level >= 1) <div class="absolute left-5 -top-8 bottom-10 w-0.5 bg-indigo-600 z-0"></div> @endif
                    
                    <div class="relative z-10 flex-shrink-0 w-10 h-10 rounded-full flex items-center justify-center text-sm transition-colors duration-500 {{ $level >= 1 ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/30' : 'bg-white border-2 border-slate-200 text-slate-300' }}">
                        <i class="fas fa-wallet"></i>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold {{ $level == 1 ? 'text-indigo-600' : ($level > 1 ? 'text-slate-900' : 'text-slate-400') }}">Pembayaran Pesanan</h4>
                        <p class="text-[11px] font-medium mt-1 {{ $level == 1 ? 'text-amber-500' : 'text-slate-500' }}">
                            
                            <!-- LOGIKA TAHAP 1 SEBAGAI BUKTI SEJARAH PEMBAYARAN AWAL -->
                            @if(in_array($status, ['belum_bayar']))
                                @if($isOffline)
                                    Pesanan dibuat. Menunggu pembayaran awal di kasir.
                                @else
                                    Silakan unggah bukti transfer untuk pembayaran <strong>{{ strtoupper($transaksi->status_pembayaran) }}</strong> Anda.
                                @endif
                            @elseif(in_array($status, ['menunggu_proses']))
                                Pembayaran awal sedang diverifikasi oleh Admin.
                            @elseif($bayar >= $total && $total > 0)
                                Dana pembayaran penuh telah divalidasi dan kami terima.
                            @elseif($bayar > 0 && $bayar < $total)
                                Pembayaran awal (DP) sebesar <strong>Rp {{ number_format($bayar, 0, ',', '.') }}</strong> telah diterima dan divalidasi.
                            @endif

                        </p>
                    </div>
                </div>

                <!-- TAHAP 2: VERIFIKASI ADMIN -->
                <div class="relative flex items-start gap-4">
                    @if($level >= 2) <div class="absolute left-5 -top-8 bottom-10 w-0.5 bg-indigo-600 z-0"></div> @endif
                    <div class="relative z-10 flex-shrink-0 w-10 h-10 rounded-full flex items-center justify-center text-sm transition-colors duration-500 {{ $level >= 2 ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/30' : 'bg-white border-2 border-slate-200 text-slate-300' }}">
                        <i class="fas fa-user-check"></i>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold {{ $level == 2 ? 'text-indigo-600' : ($level > 2 ? 'text-slate-900' : 'text-slate-400') }}">Verifikasi Admin</h4>
                        <p class="text-[11px] font-medium mt-1 text-slate-500">
                            Admin mengecek dana masuk & stok ketersediaan komponen.
                        </p>
                    </div>
                </div>

                <!-- TAHAP 3: PERAKITAN / PROSES -->
                <div class="relative flex items-start gap-4">
                    @if($level >= 3) <div class="absolute left-5 -top-8 bottom-10 w-0.5 bg-indigo-600 z-0"></div> @endif
                    
                    {{-- Ganti Icon Berdasarkan Opsi Rakit --}}
                    <div class="relative z-10 flex-shrink-0 w-10 h-10 rounded-full flex items-center justify-center text-sm transition-colors duration-500 {{ $level >= 3 ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/30' : 'bg-white border-2 border-slate-200 text-slate-300' }}">
                        <i class="{{ $transaksi->is_rakit ? 'fas fa-tools' : 'fas fa-box-open' }}"></i>
                    </div>
                    
                    <div>
                        {{-- Logika Teks Berdasarkan Opsi Rakit --}}
                        @if($transaksi->is_rakit)
                            <h4 class="text-sm font-bold {{ $level == 3 ? 'text-indigo-600' : ($level > 3 ? 'text-slate-900' : 'text-slate-400') }}">Diproses & Dirakit</h4>
                            <p class="text-[11px] font-medium mt-1 {{ $level >= 3 ? 'text-slate-500' : 'text-slate-400' }}">Teknisi sedang merakit dan melakukan Quality Control (QC).</p>
                        @else
                            <h4 class="text-sm font-bold {{ $level == 3 ? 'text-indigo-600' : ($level > 3 ? 'text-slate-900' : 'text-slate-400') }}">Pesanan Diproses</h4>
                            <p class="text-[11px] font-medium mt-1 {{ $level >= 3 ? 'text-slate-500' : 'text-slate-400' }}">Admin sedang menyiapkan dan mengecek kelengkapan komponen pesanan Anda.</p>
                        @endif
                    </div>
                </div>

                <!-- TAHAP 4: PENGIRIMAN / PENYERAHAN (SUPER DINAMIS!) -->
                <div class="relative flex items-start gap-4">
                    @if($level >= 4) <div class="absolute left-5 -top-8 bottom-10 w-0.5 bg-indigo-600 z-0"></div> @endif
                    
                    @php
                        $iconNode4 = match($metode) {
                            'pickup' => 'fas fa-store',
                            'technician' => 'fas fa-motorcycle',
                            default => 'fas fa-truck-fast'
                        };
                        
                        if ($status == 'retur_diajukan') {
                            $iconNode4 = 'fas fa-exchange-alt';
                        }
                    @endphp
                    
                    <div class="relative z-10 flex-shrink-0 w-10 h-10 rounded-full flex items-center justify-center text-sm transition-colors duration-500 {{ $level >= 4 ? ($status == 'retur_diajukan' ? 'bg-rose-600 text-white shadow-lg shadow-rose-500/30' : 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/30') : 'bg-white border-2 border-slate-200 text-slate-300' }}">
                        <i class="{{ $iconNode4 }}"></i>
                    </div>
                    
                    <div>
                        @if($level >= 4)
                            
                            <!-- 👇 CABANG BARU: PROSES RETUR 👇 -->
                            @if($status == 'retur_diajukan')
                                <h4 class="text-sm font-bold text-rose-600 flex items-center gap-1.5 flex-wrap">Proses Komplain & Retur <span class="bg-rose-100 text-rose-600 border border-rose-200 text-[9px] font-black px-2 py-0.5 rounded uppercase tracking-wider">Aktif</span></h4>
                                <p class="text-[11px] font-medium mt-1 text-slate-500">
                                    @if($transaksi->pengajuanRetur && $transaksi->pengajuanRetur->status == 'menunggu_verifikasi')
                                        Pengajuan komplain Anda sedang ditinjau oleh Admin.
                                    @elseif($transaksi->pengajuanRetur && $transaksi->pengajuanRetur->status == 'disetujui')
                                        Retur <strong class="text-blue-600">Disetujui!</strong> Silakan kirim unit ke toko dan unggah resi di panel samping.
                                    @elseif($transaksi->pengajuanRetur && $transaksi->pengajuanRetur->status == 'barang_dikirim')
                                        Paket retur sedang dalam perjalanan menuju toko. Menunggu pengecekan Admin.
                                    @else
                                        Proses penanganan garansi / retur.
                                    @endif
                                </p>
                            <!-- 👆 SELESAI CABANG RETUR 👆 -->

                            <!-- CABANG 1: PICKUP (AMBIL DI TOKO) -->
                            @elseif($metode == 'pickup')
                                @if($adaSisaTagihan ?? false)
                                    <h4 class="text-sm font-bold text-amber-600 flex items-center gap-1.5 flex-wrap">Menunggu Pelunasan <span class="bg-amber-100 text-amber-600 border border-amber-200 text-[9px] font-black px-2 py-0.5 rounded uppercase tracking-wider">Aksi</span></h4>
                                    <p class="text-[11px] font-medium mt-1 text-slate-500">PC sudah siap. Silakan lunasi sisa tagihan DP sebelum mengambil unit di workshop.</p>
                                @else
                                    <h4 class="text-sm font-bold text-indigo-600">Siap Diambil</h4>
                                    <p class="text-[11px] font-medium mt-1 text-slate-500">PC Anda sudah siap! Silakan ambil pesanan di workshop DiabloTech.</p>
                                @endif
                            
                            <!-- CABANG 2: COURIER (EKSPEDISI) -->
                            @elseif(in_array($metode, ['courier', 'delivery']))
                                @if($adaSisaTagihan ?? false)
                                    <h4 class="text-sm font-bold text-amber-600 flex items-center gap-1.5 flex-wrap">Menunggu Ongkos Kirim <span class="bg-amber-100 text-amber-600 border border-amber-200 text-[9px] font-black px-2 py-0.5 rounded uppercase tracking-wider">Aksi</span></h4>
                                    <p class="text-[11px] font-medium mt-1 text-slate-500">PC selesai dirakit. Silakan bayar tagihan ongkos kirim agar paket dapat diserahkan ke kurir ekspedisi.</p>
                                @elseif(empty($transaksi->resi_pengiriman))
                                    <h4 class="text-sm font-bold text-indigo-600">Disiapkan Untuk Pengiriman</h4>
                                    <p class="text-[11px] font-medium mt-1 text-slate-500">Pembayaran lunas. Admin sedang melakukan *packing* aman untuk diserahkan ke pihak ekspedisi.</p>
                                @else
                                    <h4 class="text-sm font-bold text-blue-600">Sedang Dalam Perjalanan</h4>
                                    @if(!empty($transaksi->resi_pengiriman))
                                        <p class="mt-1.5">
                                            <a href="https://parcelsapp.com/id/tracking/{{ $transaksi->resi_pengiriman }}" 
                                            target="_blank" 
                                            class="inline-flex items-center text-blue-600 hover:text-blue-800 hover:underline font-bold transition-all px-3 py-1.5 bg-blue-50 hover:bg-blue-100 rounded-md border border-blue-200 cursor-pointer text-[11px] sm:text-xs">
                                                <i class="fas fa-box-open mr-1.5"></i> {{ $transaksi->resi_pengiriman }} <i class="fas fa-external-link-alt text-[10px] ml-1.5"></i>
                                            </a>
                                        </p>
                                    @else
                                        <p class="text-[11px] text-slate-400 italic mt-1">Nomor resi belum diupdate oleh Admin.</p>
                                    @endif
                                @endif

                            <!-- CABANG 3: TECHNICIAN (TEKNISI KE RUMAH) -->
                            @elseif($metode == 'technician')
    {{-- 🔥 1. PRIORITAS UTAMA (PALING ATAS): JIKA TEKNISI UDAH OTW (SEDANG DIKIRIM) 🔥 --}}
    @if($status == 'sedang_dikirim')
        <h4 class="text-sm font-bold text-blue-600">Teknisi Menuju Lokasi</h4>
        <p class="text-[11px] font-medium mt-1 text-slate-500">
            Teknisi kami sedang dalam perjalanan ke alamat Anda. Mohon pastikan ada penerima di lokasi.
            
            {{-- Tampilkan jam keberangkatan dari database --}}
            @if($transaksi->pengirimanTeknisi && $transaksi->pengirimanTeknisi->waktu_berangkat)
                <span class="block mt-1.5">
                    <strong class="text-blue-700 bg-blue-50 border border-blue-100 px-2 py-1 rounded">
                        <i class="fas fa-clock mr-1"></i> Berangkat pukul: {{ $transaksi->pengirimanTeknisi->waktu_berangkat->format('H:i') }} WIB
                    </strong>
                </span>
            @endif
        </p>
                                    
                             @elseif(($adaSisaTagihan ?? false))
        <h4 class="text-sm font-bold text-amber-600 flex items-center gap-1.5 flex-wrap">
            Menunggu Pelunasan Sisa 
            <span class="bg-amber-100 text-amber-600 border border-amber-200 text-[9px] font-black px-2 py-0.5 rounded uppercase tracking-wider">Aksi</span>
        </h4>
        <p class="text-[11px] font-medium mt-1 text-slate-500">
            PC selesai dirakit. Lunasi sisa tagihan & ongkir sebelum teknisi meluncur ke lokasi Anda.
        </p>
    
    {{-- 3. JIKA SUDAH LUNAS TAPI TEKNISI BELUM OTW --}}
    @else
        <h4 class="text-sm font-bold text-indigo-600">Persiapan Teknisi</h4>
        <p class="text-[11px] font-medium mt-1 text-slate-500">
            Pembayaran lunas. Teknisi kami sedang bersiap membawa PC menuju alamat tujuan Anda.
        </p>
    @endif
                            @endif
                        @else
                            <!-- Teks Default Jika Belum Sampai Tahap 4 -->
                            <h4 class="text-sm font-bold text-slate-400">Pengiriman / Penyerahan</h4>
                            <p class="text-[11px] font-medium mt-1 text-slate-400">Proses serah terima pesanan ke pihak logistik atau Kustomer.</p>
                        @endif
                    </div>
                </div>

                <!-- TAHAP 5: SELESAI -->
                <div class="relative flex items-start gap-4">
                    @if($level >= 5) <div class="absolute left-5 -top-8 bottom-10 w-0.5 bg-blue-500 z-0"></div> @endif
                    <div class="relative z-10 flex-shrink-0 w-10 h-10 rounded-full flex items-center justify-center text-sm transition-colors duration-500 {{ $level == 5 ? 'bg-blue-500 text-white shadow-lg shadow-blue-500/30' : 'bg-white border-2 border-slate-200 text-slate-300' }}">
                        <i class="fas fa-check-double"></i>
                    </div>
                    <div class="w-full">
                        <h4 class="text-sm font-bold {{ $level == 5 ? 'text-blue-600' : 'text-slate-400' }}">Pesanan Selesai</h4>
                        <p class="text-[11px] font-medium mt-1 text-slate-500">Terima kasih telah mempercayakan perakitan PC di DiabloTech!</p>
                        
                        {{-- 👇 MUNCULKAN ARSIP PDF JIKA HOME SERVICE SELESAI 👇 --}}
                        @if($level == 5 && $metode === 'technician' && $transaksi->pengirimanTeknisi && $transaksi->pengirimanTeknisi->dokumen)
                            <div class="mt-3 bg-blue-50 border border-blue-100 rounded-lg p-3 inline-block">
                                <a href="{{ asset('storage/' . $transaksi->pengirimanTeknisi->dokumen) }}" target="_blank" class="flex items-center gap-2 text-blue-700 hover:text-blue-800 transition">
                                    <i class="fas fa-file-pdf text-red-500 text-lg"></i>
                                    <span class="text-xs font-bold">Download & Garansi (PDF)</span>
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
                
            </div>
        </div>
    </div>
</div>

<div id="returModal" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" onclick="toggleReturModal(false)"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="relative z-10 inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-slate-100">
            
            <div class="bg-rose-600 px-6 py-4 text-white flex justify-between items-center">
                <h3 class="text-base font-bold flex items-center gap-2">
                    <i class="fas fa-exclamation-triangle"></i> Pengajuan Retur / Komplain
                </h3>
                <button type="button" onclick="toggleReturModal(false)" class="text-white/80 hover:text-white text-lg font-bold">&times;</button>
            </div>

            <!-- INFO WAJIB VIDEO UNBOXING KE WA ADMIN -->
            <div class="bg-rose-50 border-b border-rose-100 p-5 flex items-start gap-3">
                <div class="bg-rose-200 text-rose-700 p-2 rounded-lg flex-shrink-0 mt-0.5">
                    <i class="fas fa-video text-lg"></i>
                </div>
                <div class="text-[11px] text-rose-800 leading-relaxed font-medium">
                    <strong>WAJIB VIDEO UNBOXING!</strong> <br>
                    Pengajuan retur hanya akan diverifikasi jika Anda mengirimkan <strong>Video Unboxing</strong> (dari segel utuh hingga PC dites menyala) ke WhatsApp Tim Admin kami di:<br>
                    <a href="https://wa.me/6281234567890" target="_blank" class="inline-block mt-1 font-black text-rose-600 hover:text-rose-800 underline">
                        <i class="fab fa-whatsapp text-green-500 text-sm"></i> 0812-3456-7890
                    </a>
                </div>
            </div>

            <!-- PASTIKAN ROUTE NYA BENAR DI SINI -->
           <form action="{{ route('customer.orders.ajukan_retur', $transaksi->id) }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-4">
                @csrf
                @if($errors->any())
                    <div class="bg-rose-100 border border-rose-400 text-rose-700 px-4 py-3 rounded-xl text-xs font-bold shadow-sm">
                        <p class="mb-1"><i class="fas fa-exclamation-circle"></i> Pengajuan gagal karena:</p>
                        <ul class="list-disc list-inside">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <div>
                    <div class="flex justify-between items-end mb-1">
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Foto Bukti Kerusakan <span class="text-rose-500">*</span></label>
                        <span class="text-[10px] font-bold text-slate-400">Maks. 5 Foto, @ 2MB</span>
                    </div>
                    <input type="file" name="foto_kerusakan[]" multiple required accept="image/jpeg,image/png,image/jpg" class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-rose-50 file:text-rose-700 hover:file:bg-rose-100 cursor-pointer">
                    <p class="text-[10px] text-slate-400 mt-1 italic">Tahan tombol CTRL untuk memilih lebih dari 1 foto.</p>                    
                    @error('foto_kerusakan')
                        <span class="text-xs text-rose-600 font-bold mt-1 block">{{ $message }}</span>
                    @enderror
                    @error('foto_kerusakan.*')
                        <span class="text-xs text-rose-600 font-bold mt-1 block">{{ $message }}</span>
                    @enderror
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Detail Kendala / Alasan Retur <span class="text-rose-500">*</span></label>
                    <textarea name="catatan_retur" required rows="3" placeholder="Jelaskan secara detail kerusakan yang dialami..." class="w-full rounded-xl border-slate-300 focus:ring-rose-500 focus:border-rose-500 text-sm">{{ old('catatan_retur') }}</textarea>
                    @error('catatan_retur')
                        <span class="text-xs text-rose-600 font-bold mt-1 block">{{ $message }}</span>
                    @enderror
                </div>
                <div class="pt-2 flex justify-end gap-3 border-t border-slate-100">
                    <button type="button" onclick="toggleReturModal(false)" class="bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold py-2 px-4 rounded-xl text-xs transition-all">Batal</button>                    
                    <button type="submit" onclick="this.innerHTML='Mengirim...'; this.disabled=true; this.form.submit();" class="bg-rose-600 hover:bg-rose-700 text-white font-bold py-2 px-5 rounded-xl text-xs shadow-md transition-all">Kirim Pengajuan Retur</button>
                </div>
            </form>
        </div>
    </div>
</div>
@if($errors->has('catatan_retur') || $errors->has('foto_kerusakan'))
<script>
    document.addEventListener("DOMContentLoaded", function() {
        toggleReturModal(true);
    });
</script>
@endif

@endsection
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function mintaGantiKomponen() {
        Swal.fire({
            title: 'Ganti Komponen PO',
            text: 'Komponen apa yang ingin Anda ganti atau sesuaikan harganya?',
            input: 'textarea',
            inputPlaceholder: '',
            showCancelButton: true,
            confirmButtonColor: '#f59e0b',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Kirim Permintaan',
            cancelButtonText: 'Batal',
            inputValidator: (value) => {
                if (!value) { return 'Harap isi pesan komponen yang ingin diganti!'; }
            }
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('inputPesanRevisi').value = result.value;
                document.getElementById('formRevisiPO').submit();
            }
        });
    }

    function previewImage(event) {
        var input = event.target;
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e){
                document.getElementById('img-preview').src = e.target.result;
                document.getElementById('preview-container').classList.remove('hidden');
                document.getElementById('btn-kirim').classList.remove('hidden'); 
                var lblFile = document.getElementById('lbl-file');
                lblFile.classList.replace('bg-white', 'bg-slate-800'); 
                lblFile.classList.replace('text-slate-800', 'text-white');
                lblFile.innerHTML = '<i class="fas fa-sync-alt"></i> Ganti Foto';
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    function toggleRevisiModal(show) {
        const modal = document.getElementById('revisiModal');
        if (show) {
            modal.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        } else {
            modal.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }
    }

    function toggleReturModal(show) {
        const modal = document.getElementById('returModal');
        if (show) {
            modal.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        } else {
            modal.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }
    }
    function confirmSelesai(event, form) {
        event.preventDefault(); // Tahan form biar gak langsung ke-submit
        
        Swal.fire({
            title: 'Pesanan Selesai?',
            text: "Apakah Anda yakin PC sudah diterima dengan aman dan menyala dengan normal?",
            icon: 'success', // Icon centang hijau
            showCancelButton: true,
            confirmButtonColor: '#10b981', // Warna Emerald/Hijau Tailwind
            cancelButtonColor: '#94a3b8',  // Warna Abu-abu
            confirmButtonText: 'Ya, PC Aman!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit(); // Lanjutkan submit kalau diklik "Ya"
            }
        });
    }

    // Fungsi Pop-up untuk Batalkan Pesanan
    function confirmBatal(event, form) {
        event.preventDefault(); // Tahan form biar gak langsung ke-submit
        
        Swal.fire({
            title: 'Batalkan Pesanan?',
            text: "Apakah Anda yakin ingin membatalkan pesanan ini? Stok akan dikembalikan dan pesanan tidak dapat dilanjutkan.",
            icon: 'warning', // Icon tanda seru kuning/merah
            showCancelButton: true,
            confirmButtonColor: '#e11d48', // Warna Merah Rose Tailwind
            cancelButtonColor: '#94a3b8',
            confirmButtonText: 'Ya, Batalkan',
            cancelButtonText: 'Kembali'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit(); // Lanjutkan submit kalau diklik "Ya"
            }
        });
    }
</script>