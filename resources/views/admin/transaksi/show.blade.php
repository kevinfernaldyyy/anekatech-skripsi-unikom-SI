@extends('layouts.admin')

@section('title', 'Detail Pesanan #' . $transaksi->no_transaksi)

@section('content')
<div class="max-w-6xl mx-auto px-4 py-8">
@error('foto_resi_pengiriman')
    <span class="text-xs text-red-500 font-bold mt-1">{{ $message }}</span>
@enderror
    
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
        <div>
            <a href="{{ route('admin.transaksi.index') }}" class="text-sm text-gray-500 hover:text-gray-700 flex items-center gap-1 transition-colors">
                <i class="fas fa-arrow-left"></i> Kembali ke Daftar Pesanan
            </a>
            <h1 class="text-2xl font-black text-gray-900 mt-2">Pesanan #{{ $transaksi->no_transaksi }}</h1>
        </div>
        
        <div class="flex flex-wrap gap-3">
            @php
                $status = $transaksi->status_order; 
                $jenisPembayaran = $transaksi->status_pembayaran; 
            @endphp

            @if($status == 'belum_bayar')
                <a href="{{ route('admin.orders.print_offering', $transaksi->id) }}" target="_blank" class="inline-flex items-center gap-2 bg-white border-2 border-indigo-600 text-indigo-600 px-5 py-2.5 rounded-xl font-bold hover:bg-indigo-50 transition-all shadow-sm">
                    <i class="fas fa-print"></i> Cetak Offering Letter
                </a>
            
            @elseif(in_array($status, ['menunggu_proses', 'menunggu_kalkulasi', 'menunggu_acc_customer']))
                <button disabled title="Tunggu proses ACC atau Nego selesai" class="inline-flex items-center gap-2 bg-slate-50 border-2 border-slate-200 text-slate-400 cursor-not-allowed px-5 py-2.5 rounded-xl font-bold shadow-sm">
                    <i class="fas fa-lock"></i> Menunggu ACC / Nego
                </button>
            
            @else
                <a href="{{ route('admin.orders.print_invoice', $transaksi->id) }}" target="_blank" class="inline-flex items-center gap-2 bg-white border-2 {{ $jenisPembayaran == 'dp' ? 'border-blue-500 text-blue-600 hover:bg-blue-50' : 'border-emerald-600 text-emerald-600 hover:bg-emerald-50' }} px-5 py-2.5 rounded-xl font-bold transition-all shadow-sm">
                    <i class="fas fa-receipt"></i> 
                    {{ $jenisPembayaran == 'dp' ? 'Cetak Bukti DP' : 'Cetak Invoice Lunas' }}
                </a>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
        
        <!-- ========================================== -->
        <!-- KOLOM KIRI (KONTEN UTAMA) -->
        <!-- ========================================== -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- SKENARIO 1: KUSTOMER SUDAH TERIMA BARANG -->
            @if($transaksi->status_order == 'diterima_kustomer')
            <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-6 shadow-sm mb-6">
                <h3 class="text-lg font-bold text-emerald-900 mb-2 flex items-center gap-2">
                    <i class="fas fa-check-circle"></i> Kustomer Telah Menerima PC
                </h3>
                <p class="text-sm text-emerald-700">
                    Kustomer telah mengonfirmasi bahwa pesanan sampai dengan selamat dan berfungsi normal. Silakan klik tombol <strong>"Selesaikan Pesanan"</strong> di panel sebelah kanan untuk menutup transaksi ini secara permanen.
                </p>
            </div>
            @endif

            <!-- SKENARIO 2: KUSTOMER MENGAJUKAN RETUR -->
            @if($transaksi->status_order == 'retur_dikirim')
            <div class="bg-rose-50 border border-rose-200 rounded-xl p-6 shadow-sm">
                <h3 class="text-lg font-bold text-rose-900 mb-2 flex items-center gap-2">
                    <i class="fas fa-exclamation-triangle"></i> Pengajuan Retur dari Kustomer!
                </h3>
                <div class="bg-white p-4 rounded-lg border border-rose-100 mb-4 text-sm text-slate-700">
                    <p><strong>Keluhan/Kerusakan:</strong> {{ $transaksi->catatan_retur }}</p>
                    <p class="mt-2"><strong>Resi Balik Kustomer:</strong> <span class="font-mono bg-slate-100 px-2">{{ $transaksi->resi_retur }}</span></p>
                    <a href="{{ asset('storage/' . $transaksi->foto_resi_retur) }}" target="_blank" class="inline-block mt-3 text-rose-600 font-bold hover:underline">
                        <i class="fas fa-image"></i> Lihat Foto Bukti Retur dari Kustomer
                    </a>
                </div>
                <p class="text-sm text-rose-700 mb-4 border-t border-rose-200 pt-4"><strong>Langkah Admin:</strong> Jika barang retur sudah diperbaiki/diganti, silakan ACC dengan cara mengunggah Resi POS dan Foto Packing yang <strong>BARU</strong> untuk dikirim balik.</p>
                <form action="{{ route('admin.orders.kirim_paket', $transaksi->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-semibold text-rose-900 mb-1">Nomor Resi PENGGANTI (POS) <span class="text-rose-500">*</span></label>
                        <input type="text" name="resi_pengiriman" required placeholder="Resi pengiriman balik ke kustomer..." class="w-full sm:w-1/2 rounded-lg border-rose-300 focus:ring-rose-500 focus:border-rose-500 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-rose-900 mb-1">Foto Packing BARU <span class="text-rose-500">*</span></label>
                        <input type="file" name="foto_resi_pengiriman" required accept="image/*" class="w-full sm:w-1/2 block text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-rose-600 file:text-white hover:file:bg-rose-700 cursor-pointer">
                    </div>
                    <button type="submit" class="bg-rose-600 hover:bg-rose-700 text-white font-bold py-2.5 px-6 rounded-lg shadow transition-all flex items-center gap-2 text-sm mt-4">
                        <i class="fas fa-paper-plane"></i> ACC Retur & Kirim Ulang Paket
                    </button>
                </form>
            </div>
            @endif

            <!-- RINCIAN KOMPONEN PESANAN -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center">
                    <h3 class="font-black text-gray-800 uppercase tracking-wider text-sm"><i class="fas fa-box mr-2"></i> Rincian Perangkat</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-gray-50 text-gray-400 uppercase text-[10px] font-black">
                            <tr>
                                <th class="px-6 py-3">Item / Deskripsi</th>
                                <th class="px-6 py-3 text-right">Harga Satuan</th>
                                <th class="px-6 py-3 text-center">Qty</th>
                                <th class="px-6 py-3 text-right">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($transaksi->details as $item)
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="px-6 py-4">
                                    @if($item->barang)
                                        <div class="font-bold text-gray-900">{{ $item->barang->nama_barang }}</div>
                                        <div class="text-[10px] text-gray-400 font-mono uppercase tracking-tighter">{{ $item->barang->kode_barang }}</div>
                                    @else
                                        <div class="font-bold text-blue-600">{{ $item->nama_barang_custom }}</div>
                                        <span class="text-[10px] bg-blue-50 text-blue-500 px-2 py-0.5 rounded-full border border-blue-100 uppercase font-black">Custom Item / PO</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right font-medium {{ $item->harga_satuan > 0 ? 'text-gray-600' : 'text-amber-500' }}">
                                    {{ $item->harga_satuan > 0 ? 'Rp ' . number_format($item->harga_satuan, 0, ',', '.') : 'Menunggu Harga' }}
                                </td>
                                <td class="px-6 py-4 text-center font-bold text-gray-900">{{ $item->qty }}</td>
                                <td class="px-6 py-4 text-right font-black {{ $item->subtotal > 0 ? 'text-gray-900' : 'text-amber-500' }}">
                                    {{ $item->subtotal > 0 ? 'Rp ' . number_format($item->subtotal, 0, ',', '.') : 'Menunggu Harga' }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-gray-50/80 divide-y divide-gray-100">
                            <tr>
                                <td colspan="3" class="px-6 py-3 text-right text-gray-500 font-bold uppercase text-[10px]">Ongkos Kirim</td>
                                <td class="px-6 py-3 text-right font-bold text-gray-900">Rp {{ number_format($transaksi->biaya_pengiriman, 0, ',', '.') }}</td>
                            </tr>
                            <tr class="bg-blue-50/50">
                                <td colspan="3" class="px-6 py-4 text-right font-black text-gray-800 uppercase tracking-widest">Grand Total</td>
                                <td class="px-6 py-4 text-right font-black text-xl text-blue-600 italic">Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <!-- NEGOSIASI PO (Jika Ada Custom Part) -->
            @if($transaksi->status_order == 'menunggu_kalkulasi')
            <div class="bg-white rounded-xl shadow-sm border border-blue-200 overflow-hidden">
                <div class="bg-blue-50 border-b border-blue-100 px-5 py-4">
                    <h4 class="font-black text-blue-800 text-sm mb-1"><i class="fas fa-calculator mr-1"></i> Input Estimasi Harga PO</h4>
                    <p class="text-[11px] text-blue-600">Customer mengajukan Custom Part. Masukkan harga modal/jual sebelum mengirim tagihan.</p>
                </div>
                <div class="p-6">
                  @if(!empty($transaksi->catatan_revisi))
                    <div class="mb-5 text-sm text-amber-700 bg-amber-50 p-4 rounded-lg border border-amber-200">
                        <p class="font-bold mb-1">Pesan / Revisi dari Customer:</p>
                        <p>📝 {{ $transaksi->catatan_revisi }}</p>
                    </div>
                    @endif
                    <form action="{{ route('admin.orders.kirim_estimasi', $transaksi->id) }}" method="POST">
                        @if ($errors->any())
                            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                                <ul class="list-disc pl-5 mt-1 text-sm">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        @csrf
                        @foreach($transaksi->details->whereNull('barang_id') as $item)
                            <div class="mb-5 p-4 border border-blue-100 rounded-xl bg-slate-50/50">
                                <label class="block text-xs font-bold text-slate-700 mb-2">Nama Komponen (Qty: {{ $item->qty }})</label>
                                <div class="bg-white border border-slate-200 rounded-lg px-4 py-2.5 text-sm text-slate-600 mb-4 font-semibold cursor-not-allowed shadow-sm">
                                    {{ $item->nama_barang_custom }}
                                </div>
                                @if(!empty($item->link)) 
                                <div class="mt-3 mb-4 p-3 bg-blue-50/50 border border-blue-200 rounded-lg">
                                    <label class="block text-[11px] font-black text-blue-800 uppercase tracking-widest mb-1.5 flex items-center gap-1.5">
                                        <i class="fas fa-external-link-alt"></i> Link Referensi Customer
                                    </label>
                                    <a href="{{ $item->link }}" target="_blank" class="inline-flex items-center gap-2 text-sm text-blue-600 hover:text-blue-800 font-medium hover:underline break-all transition-colors">
                                        {{ $item->link }}
                                    </a>
                                </div>
                                @endif
                                <label class="block text-xs font-bold text-slate-700 mb-2">Harga Satuan <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <span class="absolute left-4 top-2.5 text-slate-500 font-bold text-sm">Rp</span>
                                    <input type="hidden" name="harga_custom[{{ $item->id }}]" id="raw_harga_{{ $item->id }}">
                                    <input type="text" class="w-full border border-slate-300 rounded-lg pl-10 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 outline-none shadow-sm" placeholder="Masukkan Harga" oninput="formatRupiah(this, 'raw_harga_{{ $item->id }}')" required>
                                </div>
                            </div>
                        @endforeach
                        <div class="mb-6 border-t border-slate-200 pt-5">
                            <label class="block text-xs font-bold text-slate-700 mb-2">Estimasi Kedatangan Barang (PO)</label>
                            <div class="relative">
                                <span class="absolute left-4 top-2.5 text-slate-500 text-sm"><i class="fas fa-calendar-alt"></i></span>
                                <input type="text" name="estimasi_po" class="w-full border border-slate-300 rounded-lg pl-10 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 outline-none shadow-sm" placeholder="Cth: 3 - 7 Hari Kerja" required>
                            </div>
                        </div>
                        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-black py-3.5 rounded-xl text-sm transition-all shadow-lg shadow-blue-600/30">
                            KIRIM ESTIMASI KE CUSTOMER
                        </button>
                    </form>
                </div>
            </div>
            @elseif($transaksi->status_order == 'menunggu_acc_customer')
            <div class="bg-white rounded-xl shadow-sm border border-amber-200 overflow-hidden text-center">
                <div class="bg-amber-50 border-b border-amber-100 p-6">
                    <div class="w-16 h-16 bg-white text-amber-500 rounded-full flex items-center justify-center mx-auto mb-4 shadow-sm border border-amber-100">
                        <i class="fas fa-hourglass-half text-2xl animate-pulse"></i>
                    </div>
                    <h4 class="font-black text-amber-800 text-lg mb-2">Menunggu Keputusan Customer</h4>
                    <p class="text-sm text-amber-700 px-4">Estimasi harga PO dan waktu kedatangan telah dikirim. Saat ini menunggu Customer untuk Setuju atau Revisi.</p>
                </div>
                <div class="p-6">
                    <div class="inline-flex flex-col text-left bg-slate-50 border border-slate-200 rounded-xl p-4 w-full sm:w-2/3 items-center justify-center mx-auto shadow-inner">
                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-wider mb-2">Estimasi PO yang dikirim:</span>
                        <span class="text-base font-bold text-slate-700 flex items-center justify-center gap-2">
                            <i class="fas fa-calendar-check text-blue-500"></i> {{ $transaksi->estimasi_po ?? '-' }}
                        </span>
                    </div>
                </div>
            </div>
            @endif

            <!-- WORKSHOP & QC TEKNISI -->
            @if($transaksi->is_rakit)
            <div class="bg-white p-6 rounded-2xl shadow-sm border-2 border-orange-100 bg-orange-50/20">
                <h3 class="font-black text-orange-800 mb-4 flex items-center gap-2 uppercase tracking-widest text-sm">
                    <i class="fas fa-tools"></i> Workshop & Perakitan
                </h3>
                
                @if($transaksi->dokumenQc)
                <div class="mt-6 border-t border-slate-200 pt-6">
                    <h4 class="font-bold text-slate-800 mb-4 flex items-center gap-2">
                        <i class="fas fa-camera-retro text-slate-500"></i> Laporan Quality Control (QC)
                    </h4>
                    <div class="bg-slate-50 border border-slate-200 rounded-xl p-5">
                        @if($transaksi->dokumenQc->catatan_teknisi)
                            <div class="mb-5 bg-white p-3 rounded-lg border border-slate-200 shadow-sm">
                                <p class="text-[10px] text-slate-500 font-bold uppercase tracking-wider mb-1">Catatan Teknisi:</p>
                                <p class="text-sm text-slate-800">{{ $transaksi->dokumenQc->catatan_teknisi }}</p>
                            </div>
                        @endif
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="bg-white p-3 rounded-lg border border-slate-200 shadow-sm hover:shadow-md transition-shadow">
                                <p class="text-[10px] font-bold text-center text-slate-500 uppercase mb-2">Tampak Depan</p>
                                <a href="{{ asset('storage/' . $transaksi->dokumenQc->foto_depan) }}" target="_blank">
                                    <img src="{{ asset('storage/' . $transaksi->dokumenQc->foto_depan) }}" class="w-full h-48 object-cover rounded border border-slate-100 cursor-pointer hover:opacity-90">
                                </a>
                            </div>
                            <div class="bg-white p-3 rounded-lg border border-slate-200 shadow-sm hover:shadow-md transition-shadow">
                                <p class="text-[10px] font-bold text-center text-slate-500 uppercase mb-2">Cable Management</p>
                                <a href="{{ asset('storage/' . $transaksi->dokumenQc->foto_belakang) }}" target="_blank">
                                    <img src="{{ asset('storage/' . $transaksi->dokumenQc->foto_belakang) }}" class="w-full h-48 object-cover rounded border border-slate-100 cursor-pointer hover:opacity-90">
                                </a>
                            </div>
                            <div class="bg-white p-3 rounded-lg border border-slate-200 shadow-sm hover:shadow-md transition-shadow">
                                <p class="text-[10px] font-bold text-center text-slate-500 uppercase mb-2">Test Menyala</p>
                                <a href="{{ asset('storage/' . $transaksi->dokumenQc->foto_menyala) }}" target="_blank">
                                    <img src="{{ asset('storage/' . $transaksi->dokumenQc->foto_menyala) }}" class="w-full h-48 object-cover rounded border border-slate-100 cursor-pointer hover:opacity-90">
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                @if(is_null($transaksi->teknisi_id))
                    <div class="bg-white border border-orange-200 rounded-2xl p-6 shadow-sm relative overflow-hidden mt-6">
                        <p class="text-sm text-orange-600 font-bold mb-5 flex items-center gap-2">
                            <i class="fas fa-exclamation-triangle animate-pulse"></i> Belum ada teknisi yang ditugaskan.
                        </p>
                        <form action="{{ route('admin.transaksi.assignTeknisi', $transaksi->id) }}" method="POST">
                            @csrf
                            <div class="flex flex-col sm:flex-row gap-4">
                                <div class="relative flex-1">
                                    <select name="teknisi_id" class="appearance-none w-full pl-4 pr-10 py-3 rounded-xl border-2 border-gray-100 bg-gray-50 text-sm font-bold text-gray-700 focus:outline-none focus:border-orange-400 focus:bg-white transition-all cursor-pointer shadow-inner" required>
                                        <option value="" disabled selected>Pilih Teknisi Berpengalaman...</option>
                                        @foreach($teknisis as $tek)
                                            <option value="{{ $tek->id }}">{{ $tek->name }} (Teknisi)</option>
                                        @endforeach
                                    </select>
                                </div>
                                <button type="submit" class="bg-orange-600 text-white px-8 py-3 rounded-xl font-black text-sm hover:bg-orange-700 shadow-lg shadow-orange-200 transition-all active:scale-95 flex items-center justify-center gap-2">
                                    MULAI RAKIT <i class="fas fa-rocket ml-1"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                @else
                    <div class="flex flex-col sm:flex-row justify-between items-center p-5 bg-white border border-orange-100 rounded-2xl shadow-sm gap-4 mt-6">
                        <div class="flex items-center gap-4">
                            <div class="h-14 w-14 rounded-2xl bg-gradient-to-br from-orange-500 to-orange-700 flex items-center justify-center text-white shadow-lg shadow-orange-200">
                                <i class="fas fa-user-cog text-xl"></i>
                            </div>
                            <div>
                                <p class="text-[10px] text-gray-400 uppercase font-black tracking-widest">Master Technician</p>
                                <h4 class="font-black text-gray-900 text-xl leading-none mt-1">{{ $transaksi->teknisi->name ?? 'Admin' }}</h4>
                            </div>
                        </div>
                        <span class="inline-flex items-center gap-1 px-4 py-1.5 rounded-full text-[10px] font-black bg-orange-100 text-orange-700 border border-orange-200 uppercase tracking-widest">
                            <i class="fas fa-cogs"></i> {{ str_replace('_', ' ', $transaksi->status_order) }}
                        </span>
                    </div>
                @endif
            </div>
            @endif
            @if($transaksi->metode_pengiriman === 'technician' && $transaksi->status_order === 'selesai' && $transaksi->pengirimanTeknisi)
<div class="mt-6 bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
    <div class="bg-slate-800 px-5 py-3 border-b border-slate-900">
        <h3 class="font-bold text-white flex items-center gap-2 text-sm">
            <i class="fas fa-folder-open text-indigo-400"></i> Arsip Bukti Pengiriman Home Service
        </h3>
    </div>
    <div class="p-5 grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <p class="text-xs font-bold text-slate-700 mb-2">Foto Bukti Instalasi</p>
            <a href="{{ asset('storage/' . $transaksi->pengirimanTeknisi->foto_bukti_instalasi) }}" target="_blank">
                <img src="{{ asset('storage/' . $transaksi->pengirimanTeknisi->foto_bukti_instalasi) }}" class="w-full h-40 object-cover rounded-lg border border-slate-200 hover:opacity-90">
            </a>
        </div>
        <div>
            <p class="text-xs font-bold text-slate-700 mb-2">Dokumen & Garansi</p>
            <a href="{{ asset('storage/' . $transaksi->pengirimanTeknisi->dokumen) }}" target="_blank" class="flex items-center gap-3 p-4 border border-slate-200 rounded-lg hover:bg-slate-50 transition">
                <i class="fas fa-file-pdf text-red-500 text-4xl"></i>
                <div>
                    <p class="text-sm font-bold text-slate-800">Unduh PDF</p>
                    <p class="text-xs text-slate-500">Klik untuk melihat atau mencetak</p>
                </div>
            </a>
            <p class="text-xs text-slate-500 mt-3">
                <i class="fas fa-check-circle text-emerald-500"></i> Diselesaikan oleh teknisi pada: {{ $transaksi->pengirimanTeknisi->waktu_selesai->format('d M Y, H:i') }}
            </p>
        </div>
    </div>
</div>
@endif
        </div>


        <!-- ========================================== -->
        <!-- KOLOM KANAN (SIDEBAR AKSI, ONGKIR, BUKTI TF) -->
        <!-- ========================================== -->
        <div class="lg:col-span-1 space-y-6 sticky top-6 max-h-[calc(100vh-2rem)] overflow-y-auto no-scrollbar pb-10">

           <!-- 1. STATUS & AKSI UMUM -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 mb-6">
                <h3 class="font-black text-gray-800 mb-4 uppercase tracking-wider text-sm border-b border-gray-100 pb-3">
                    <i class="fas fa-bolt text-yellow-500 mr-1"></i> Status & Aksi
                </h3>
                
               @php
                $badgeStyles = match($transaksi->status_order) {
                    'belum_bayar' => 'bg-gray-100 text-gray-600 border-gray-200',
                    'menunggu_proses', 'menunggu_po', 'menunggu_kalkulasi', 'menunggu_acc_customer' => 'bg-blue-100 text-blue-700 border-blue-200',
                    
                    // 👇 1. Tambahkan 'sedang_disiapkan' di sini biar warnanya oranye 👇
                    'sedang_dirakit', 'process', 'sedang_disiapkan' => 'bg-orange-100 text-orange-700 border-orange-200',
                    
                    'siap_diambil', 'sedang_dikirim', 'ready' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                    'selesai', 'completed', 'diterima_kustomer' => 'bg-green-600 text-white border-green-700 shadow-md',
                    'batal', 'cancel' => 'bg-red-600 text-white border-red-700',
                    'retur_diajukan' => 'bg-rose-600 text-white border-rose-700 shadow-md', 
                    default => 'bg-gray-50 text-gray-400'
                };
                
                // Deklarasi Variabel Mutlak 
                $bayar = (float) $transaksi->bayar;
                $total = (float) $transaksi->total_harga;
                $adaSisaTagihan = $bayar < $total && $total > 0;
                $isLunas = $bayar >= $total && $total > 0;
                $belumBayarSamaSekali = $bayar <= 0;
                
                $isReady = in_array($transaksi->status_order, ['siap_diambil', 'sedang_dikirim', 'ready', 'diterima_kustomer']);
                $isOffline = empty($transaksi->bukti_pembayaran) && !in_array($transaksi->status_order, ['belum_bayar', 'batal', 'cancel']);

                // Variabel Input Resi 
                $metodeDb = strtolower($transaksi->metode_pengiriman);
                $isEkspedisi = in_array($metodeDb, ['courier', 'delivery']);
                $statusBolehKirim = in_array($transaksi->status_order, ['siap_diambil', 'lunas', 'siap_dikirim', 'sedang_dikirim', 'ready', 'diterima_kustomer']);
                $butuhResi = $isEkspedisi && $statusBolehKirim;

                // GEMBOK BATAL
                // 👇 2. Tambahkan 'sedang_disiapkan' di array ini biar gemboknya aktif 👇
                $tidakBisaBatal = !$belumBayarSamaSekali || !empty($transaksi->bukti_pembayaran) || in_array($transaksi->status_order, ['menunggu_proses', 'sedang_dirakit', 'sedang_disiapkan', 'process', 'siap_diambil', 'sedang_dikirim', 'ready', 'diterima_kustomer', 'retur_diajukan']);
            @endphp
                <!-- BADGE STATUS UTAMA -->
                <div class="{{ $badgeStyles }} px-4 py-3 rounded-xl text-sm font-black uppercase text-center border-2 tracking-widest mb-6">
                    {{ str_replace('_', ' ', $transaksi->status_order) }}
                </div>

                <!-- 👇 PANEL PENGAJUAN RETUR (MUNCUL JIKA ADA KOMPLAIN) 👇 -->
                @if($transaksi->pengajuanRetur && $transaksi->status_order == 'retur_diajukan')
                <div class="bg-rose-50 border-2 border-rose-300 rounded-2xl p-6 shadow-md relative overflow-hidden mb-6">
                    <div class="absolute top-0 right-0 bg-rose-600 text-white text-[10px] font-black px-4 py-1.5 rounded-bl-lg uppercase tracking-widest shadow-sm">
                        <i class="fas fa-bell animate-pulse mr-1"></i> Terdapat Komplain
                    </div>

                    <h3 class="font-black text-rose-800 mb-4 uppercase tracking-wider text-sm border-b border-rose-200 pb-3 flex items-center gap-2">
                        <i class="fas fa-exclamation-triangle text-rose-600 text-lg"></i> Pengajuan Retur Kustomer
                    </h3>

                    <div class="grid grid-cols-1 gap-6">
                        <div>
                            <p class="text-[10px] font-bold text-rose-500 uppercase tracking-wider mb-1">Kontak Kustomer</p>
<div class="flex items-center gap-2 mb-4">
    
    @php
        $nomorHp = ($transaksi->customer && $transaksi->customer->nomor_telepon != '0000' && !empty($transaksi->customer->nomor_telepon)) 
                   ? $transaksi->customer->nomor_telepon 
                   : $transaksi->nomor_telepon;
    @endphp

    <span class="text-sm font-bold text-slate-800">{{ $nomorHp }}</span>
    
    <a href="https://wa.me/62{{ ltrim($nomorHp, '0') }}" target="_blank" class="bg-green-500 hover:bg-green-600 text-white text-[10px] px-2 py-1 rounded font-bold transition-colors">
        <i class="fab fa-whatsapp"></i> Chat Kustomer
    </a>
                            </div>

                            <p class="text-[10px] font-bold text-rose-500 uppercase tracking-wider mb-1">Detail Kerusakan / Alasan</p>
                            <div class="text-sm text-slate-700 bg-white p-4 rounded-xl border border-rose-100 shadow-inner leading-relaxed mb-4">
                                {{ $transaksi->pengajuanRetur->catatan_retur }}
                            </div>

                            <p class="text-[10px] font-bold text-rose-500 uppercase tracking-wider mb-2">Foto Bukti Kerusakan</p>
                            <div class="flex flex-wrap gap-3">
                                @if(is_array($transaksi->pengajuanRetur->foto_kerusakan))
                                    @foreach($transaksi->pengajuanRetur->foto_kerusakan as $foto)
                                        <a href="{{ asset('storage/' . $foto) }}" target="_blank" class="block w-16 h-16 rounded-lg overflow-hidden border-2 border-rose-100 hover:border-rose-400 transition-all shadow-sm relative group">
                                            <img src="{{ asset('storage/' . $foto) }}" class="w-full h-full object-cover">
                                            <div class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                                <i class="fas fa-search-plus text-white text-xs"></i>
                                            </div>
                                        </a>
                                    @endforeach
                                @else
                                    <span class="text-xs text-rose-400 italic">Tidak ada foto.</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- 👇 AKSI VERIFIKASI ADMIN & INFO RESI BALIK 👇 -->
                    @if($transaksi->pengajuanRetur->status == 'menunggu_verifikasi')
                        <div class="mt-6 pt-5 border-t border-rose-200 flex flex-col gap-3">
                            <form action="{{ route('admin.retur.approve', $transaksi->pengajuanRetur->id) }}" method="POST" class="w-full">
                                @csrf
                             <button type="button" onclick="confirmAccRetur(this.form)" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3 px-4 rounded-xl text-xs transition-all shadow-md shadow-emerald-500/30 flex items-center justify-center gap-2">
                                    <i class="fas fa-check-circle"></i> Setujui Retur & Minta Resi
                                </button>
                            </form>

                            <button type="button" onclick="toggleTolakReturModal(true)" class="w-full bg-white border-2 border-rose-500 hover:bg-rose-50 text-rose-600 font-bold py-2.5 px-4 rounded-xl text-xs transition-all flex items-center justify-center gap-2 shadow-sm">
                                <i class="fas fa-times-circle"></i> Tolak Pengajuan
                            </button>
                        </div>
                    @else
                        <div class="mt-6 pt-5 border-t border-rose-200 space-y-4">
                            <div>
                                <p class="text-[10px] font-bold text-rose-500 uppercase tracking-wider mb-1">Status Pengajuan</p>
                                <span class="inline-block bg-slate-800 text-white text-xs font-bold px-3 py-1.5 rounded-lg uppercase tracking-wider shadow-sm">
                                    {{ str_replace('_', ' ', $transaksi->pengajuanRetur->status) }}
                                </span>
                            </div>

                            @if($transaksi->pengajuanRetur->status == 'ditolak')
                                <div>
                                    <p class="text-[10px] font-bold text-rose-500 uppercase tracking-wider mb-1">Alasan Penolakan</p>
                                    <p class="text-xs text-slate-700 font-medium bg-white p-3 rounded-lg border border-rose-100">{{ $transaksi->pengajuanRetur->pesan_admin }}</p>
                                </div>
                            
                            <!-- Muncul Saat Admin Menunggu Kustomer ke Ekspedisi -->
                            @elseif($transaksi->pengajuanRetur->status == 'disetujui')
                                <div class="bg-blue-50 border border-blue-200 p-3 rounded-xl text-xs text-blue-700 font-medium shadow-sm">
                                    <i class="fas fa-clock text-blue-500 mr-1"></i> Menunggu Kustomer mengirimkan paket retur, menginput nomor resi, dan foto packing.
                                </div>
                                
                            <!-- Muncul Saat Kustomer Selesai Upload Resi -->
                            @elseif(in_array($transaksi->pengajuanRetur->status, ['barang_dikirim', 'sedang_dicek_teknisi']))
                                <div class="bg-white p-4 rounded-xl border border-rose-100 shadow-sm">
                                    <p class="text-[10px] font-bold text-rose-500 uppercase tracking-wider mb-1">Info Pengiriman Balik (Dari Kustomer)</p>
                                    <p class="text-base font-bold text-slate-800 flex items-center gap-2">
                                            No. Resi: 
                                            <a href="https://parcelsapp.com/id/tracking/{{ $transaksi->resi_pengiriman }}" 
                                            target="_blank" 
                                            title="Lacak paket ini"
                                            class="text-rose-600 font-mono bg-rose-50 hover:bg-rose-100 hover:underline border border-rose-200 px-2 py-0.5 rounded cursor-pointer transition-all inline-flex items-center gap-1.5">
                                                {{ $transaksi->resi_pengiriman }} <i class="fas fa-external-link-alt text-[10px]"></i>
                                            </a>
                                        </p>
                                    <div class="flex flex-wrap gap-2">
                                        @if(!empty($transaksi->pengajuanRetur->foto_resi_balik))
                                            <a href="{{ asset('storage/' . $transaksi->pengajuanRetur->foto_resi_balik) }}" target="_blank" class="bg-white border border-slate-300 hover:bg-slate-100 text-slate-700 font-bold py-2 px-3 rounded-lg shadow-sm transition-all text-[11px] flex items-center gap-1.5">
                                                <i class="fas fa-receipt text-blue-500"></i> Lihat Kertas Resi
                                            </a>
                                        @endif
                                        @if(!empty($transaksi->pengajuanRetur->foto_packing_balik))
                                            <a href="{{ asset('storage/' . $transaksi->pengajuanRetur->foto_packing_balik) }}" target="_blank" class="bg-white border border-slate-300 hover:bg-slate-100 text-slate-700 font-bold py-2 px-3 rounded-lg shadow-sm transition-all text-[11px] flex items-center gap-1.5">
                                                <i class="fas fa-box text-amber-500"></i> Lihat Foto Packing
                                            </a>
                                        @endif
                                    </div>
                                    <div class="mt-4 pt-4 border-t border-rose-100">
                                        <p class="text-[10px] font-bold text-rose-500 uppercase tracking-wider mb-2">Informasi Refund (Pengembalian Dana)</p>
                                        <div class="grid grid-cols-2 gap-4">
                                            <div>
                                                <p class="text-[10px] text-slate-500 font-bold uppercase">Bank / E-Wallet</p>
                                                <p class="text-sm font-bold text-slate-800">{{ $transaksi->pengajuanRetur->nama_bank }}</p>
                                            </div>
                                            <div>
                                                <p class="text-[10px] text-slate-500 font-bold uppercase">No. Rekening / Tujuan</p>
                                                <p class="text-sm font-bold text-slate-800">{{ $transaksi->pengajuanRetur->no_rekening }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Tombol Untuk Mengkonfirmasi Paket Telah Sampai di Toko -->
                                @if($transaksi->pengajuanRetur->status == 'barang_dikirim')
                                    <form action="{{ route('admin.retur.terima_paket', $transaksi->pengajuanRetur->id) }}" method="POST">
                                        @csrf
                                        <button type="button" onclick="confirmPaketSampai(this.form)" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-xl text-xs transition-all shadow-md shadow-blue-500/30 flex items-center justify-center gap-2 mt-2">
                                            <i class="fas fa-people-carry"></i> Paket Diterima (Mulai Cek Teknisi)
                                        </button>
                                    </form>

                                @elseif($transaksi->pengajuanRetur->status == 'sedang_dicek_teknisi')
                                    <div class="mt-4 p-4 bg-indigo-50 border border-indigo-200 rounded-xl">
                                        <h4 class="text-[11px] font-black text-indigo-800 uppercase tracking-wider mb-3 text-center">Keputusan Teknisi</h4>
                                        <div class="flex flex-col sm:flex-row gap-3">
                                            <button type="button" onclick="toggleModalValidasiRetur('valid')" class="flex-1 bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2.5 px-3 rounded-lg text-[11px] flex items-center justify-center gap-1.5 shadow-sm transition-all">
                                                <i class="fas fa-check-circle"></i> Klaim Valid (Refund)
                                            </button>
                                            <button type="button" onclick="toggleModalValidasiRetur('invalid')" class="flex-1 bg-rose-600 hover:bg-rose-700 text-white font-bold py-2.5 px-3 rounded-lg text-[11px] flex items-center justify-center gap-1.5 shadow-sm transition-all">
                                                <i class="fas fa-times-circle"></i> Klaim Gugur (Tolak)
                                            </button>
                                        </div>
                                    </div>

                                @elseif($transaksi->pengajuanRetur->status == 'selesai_refund')
                                    <div class="mt-4 p-4 bg-emerald-50 border border-emerald-200 rounded-xl text-center shadow-sm">
                                        <div class="inline-block bg-emerald-600 text-white text-[10px] font-black px-3 py-1 rounded-full uppercase tracking-widest mb-2"><i class="fas fa-check mr-1"></i> REFUND SELESAI</div>
                                        <p class="text-xs text-emerald-800 font-medium mb-3">Dana telah dikembalikan ke Kustomer dan pesanan resmi dibatalkan.</p>
                                        <a href="{{ asset('storage/' . $transaksi->pengajuanRetur->bukti_refund) }}" target="_blank" class="inline-flex items-center gap-2 bg-white border border-emerald-300 hover:bg-emerald-100 text-emerald-700 font-bold py-2 px-4 rounded-lg shadow-sm transition-all text-xs">
                                            <i class="fas fa-receipt"></i> Lihat Struk Refund
                                        </a>
                                    </div>

                                @elseif($transaksi->pengajuanRetur->status == 'klaim_ditolak')
                                    <div class="mt-4 p-4 bg-rose-50 border border-rose-200 rounded-xl text-center shadow-sm">
                                        <div class="inline-block bg-rose-600 text-white text-[10px] font-black px-3 py-1 rounded-full uppercase tracking-widest mb-2"><i class="fas fa-times mr-1"></i> KLAIM GUGUR</div>
                                        <p class="text-[10px] font-bold text-rose-500 uppercase tracking-wider mb-1">Alasan Penolakan Teknisi:</p>
                                        <p class="text-xs text-rose-800 font-medium bg-white p-3 rounded-lg border border-rose-100">"{{ $transaksi->pengajuanRetur->hasil_cek_teknisi }}"</p>
                                    </div>
                                @endif

                            @endif
                        </div>
                    @endif
                </div>
                @endif
                <!-- 👆 SELESAI PANEL RETUR 👆 -->

                <!-- ========================================================================= -->
                <!-- 👇 SEMUA TOMBOL DI BAWAH INI AKAN LENYAP SAAT STATUS: RETUR_DIAJUKAN 👇 -->
                <!-- ========================================================================= -->
                @if($transaksi->status_order != 'retur_diajukan')
                    
                    <!-- KOTAK PERINGATAN MENUNGGU PELUNASAN -->
                    @if($adaSisaTagihan && $isReady && !in_array($transaksi->status_order, ['selesai', 'batal', 'completed', 'cancel']))
                        <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 shadow-sm mb-6 mt-6">
                            <h4 class="font-bold text-amber-800 text-sm mb-2"><i class="fas fa-bell animate-bounce mr-1"></i> Menunggu Pelunasan</h4>
                            
                            @if($transaksi->bukti_pelunasan)
                                <div class="bg-white p-3 rounded-lg border border-amber-200 text-[11px] text-amber-800 leading-relaxed shadow-sm">
                                    <i class="fas fa-info-circle text-amber-500"></i> Kustomer telah mengunggah struk pelunasan. Silakan cek & klik <strong>Konfirmasi Lunas</strong> di kotak Bukti Transfer di bawah.
                                </div>
                            @else
                                <p class="text-[11px] text-amber-700 mb-3">
                                    Terdapat sisa tagihan/ongkir. Kirim email pengingat, atau scroll ke bawah dan klik <strong class="text-blue-600">KONFIRMASI LUNAS 100%</strong> jika dana tunai diterima.
                                </p>
                                
                                <form action="{{ route('admin.orders.tagih_pelunasan', $transaksi->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="w-full bg-white border border-amber-400 text-amber-700 hover:bg-amber-100 font-bold py-2.5 px-4 rounded-xl text-xs transition-colors shadow-sm flex items-center justify-center gap-2">
                                        <i class="fas fa-envelope"></i> Kirim Email Tagihan
                                    </button>
                                </form>
                            @endif
                        </div>
                    @endif

                    <!-- FORM AKSI UTAMA & BATAL -->
@if(!in_array($transaksi->status_order, ['selesai', 'batal', 'completed', 'cancel']))
    <div class="space-y-4 mt-6">
        
        <form action="{{ route('admin.orders.update_status', $transaksi->id) }}" method="POST" class="form-status">
            @csrf
            
            <!-- ================================================================= -->
            <!-- TAHAP 0: BELUM BAYAR (ONLINE VS OFFLINE)                          -->
            <!-- ================================================================= -->
            @if(in_array($transaksi->status_order, ['belum_bayar']))
                @php
                    // Sesuaikan nama kolom 'metode_pembayaran' dengan yang ada di database kamu!
                    $isOffline = (strtolower($transaksi->metode_pembayaran) == 'offline'); 
                @endphp

                @if(!$isOffline)
                    <!-- JIKA ONLINE & BELUM BAYAR -> TERKUNCI -->
                    <button type="button" disabled class="w-full bg-amber-50 text-amber-600 py-3.5 rounded-xl font-bold text-sm cursor-not-allowed border border-amber-300 uppercase tracking-tighter flex items-center justify-center gap-2">
                        <i class="fas fa-hourglass-half"></i> TUNGGU KUSTOMER BAYAR
                    </button>
                @else
                    <!-- JIKA OFFLINE -> BISA LANGSUNG PROSES TANPA BAYAR AWAL -->
                    <input type="hidden" name="status" value="menunggu_proses">
                    <input type="hidden" name="status_order" value="menunggu_proses">
                    <input type="hidden" name="status_pesanan" value="menunggu_proses">
                    <button type="button" data-message="Konfirmasi pesanan offline untuk diproses?" class="btn-confirm w-full bg-blue-600 text-white py-3.5 rounded-xl font-black text-sm hover:bg-blue-700 shadow-lg shadow-blue-100 transition-all active:scale-95 uppercase tracking-tighter flex items-center justify-center gap-2">
                        <i class="fas fa-check-circle"></i> Konfirmasi & Proses
                    </button>
                @endif
                
            <!-- ================================================================= -->
            <!-- TAHAP 1: DALAM PROSES RAKIT / WORKSHOP / PERSIAPAN                -->
            <!-- ================================================================= -->
            @elseif(in_array($transaksi->status_order, ['menunggu_proses', 'sedang_dirakit', 'process', 'sedang_disiapkan', 'diproses', 'menunggu_qc']))
                
                @php
                    // 1. Ambil nilai metode logistik
                    $metodeLogistik = strtolower($transaksi->metode_pengiriman ?? $transaksi->metode_logistik ?? $transaksi->delivery ?? '');

                    // 2. Cek apakah pesanan ini Ambil di Toko / Gudang
                    $isPickup = str_contains($metodeLogistik, 'pickup') || 
                                str_contains($metodeLogistik, 'ambil') || 
                                str_contains($metodeLogistik, 'gudang') || 
                                str_contains($metodeLogistik, 'toko');

                    // 3. Harus dikirim JIKA BUKAN pesanan pickup
                    $harusDikirim = !$isPickup;

                    // 4. KUNCI PENGAMAN YANG SUDAH DIBENAHI LOGIKANYA
                    $kunciPembayaran = ($transaksi->status_order == 'menunggu_proses');               
                    $kunciTeknisi    = ($transaksi->is_rakit == 1 && empty($transaksi->teknisi_id));
                    
                    // 🔥 PERBAIKAN LOGIKA ONGKIR: Hanya terkunci jika HARUS DIKIRIM tapi ongkir masih 0/kosong! 🔥
                    $kunciOngkir     = ($harusDikirim && (empty($transaksi->biaya_pengiriman) || $transaksi->biaya_pengiriman <= 0));
                @endphp

                @if($kunciPembayaran)
                    <button type="button" disabled class="w-full bg-red-50 text-red-500 font-bold py-3.5 rounded-xl text-sm cursor-not-allowed border border-red-200 uppercase tracking-tighter flex items-center justify-center gap-2">
                        <i class="fas fa-wallet"></i> ACC PEMBAYARAN DULU
                    </button>
                @elseif($kunciTeknisi)
                    <button type="button" disabled class="w-full bg-slate-100 text-slate-400 font-bold py-3.5 rounded-xl text-sm cursor-not-allowed border border-slate-200 uppercase tracking-tighter flex items-center justify-center gap-2">
                        <i class="fas fa-user-lock"></i> TUGASKAN TEKNISI DULU
                    </button>
                @elseif($kunciOngkir)
                    <button type="button" disabled class="w-full bg-amber-50 text-amber-600 font-bold py-3.5 rounded-xl text-sm cursor-not-allowed border border-amber-200 uppercase tracking-tighter flex items-center justify-center gap-2" title="Update biaya logistik di kotak bawah terlebih dahulu!">
                        <i class="fas fa-truck-loading"></i> UPDATE ONGKIR DULU
                    </button>                    
                @else
                    <input type="hidden" name="status" value="siap_diambil">
                    <input type="hidden" name="status_order" value="siap_diambil">
                    <input type="hidden" name="status_pesanan" value="siap_diambil">
                    <button type="button" data-message="Tandai pesanan siap diserahkan/dikirim?" class="btn-confirm w-full bg-indigo-600 text-white py-3.5 rounded-xl font-black text-sm hover:bg-indigo-700 shadow-lg shadow-indigo-100 transition-all uppercase tracking-tighter flex items-center justify-center gap-2">
                        <i class="fas fa-box-open"></i> SIAP DIAMBIL/KIRIM
                    </button>
                @endif

            <!-- TAHAP 2: SIAP DIAMBIL / SEDANG DIKIRIM / SELESAI                  -->
     @elseif(in_array($transaksi->status_order, ['siap_diambil', 'ready', 'sedang_dikirim', 'diterima_kustomer']))
                
                @php
                    // Ambil value enum dari database (bersihkan spasi & huruf kecil)
                    $metodeLogistik = strtolower(trim($transaksi->metode_pengiriman ?? $transaksi->metode_logistik ?? $transaksi->delivery ?? ''));
                    
                    // 1. Deteksi Tipe Pengiriman Berdasarkan ENUM
                    $isPickup    = ($metodeLogistik === 'pickup');
                    $isTeknisi   = ($metodeLogistik === 'technician');
                    $isEkspedisi = in_array($metodeLogistik, ['delivery', 'courier']);
                    
                    // Semua yang bukan 'pickup' berarti harus dikirim
                    $harusDikirim = !$isPickup;

                    // 2. Ambil nominal ongkir saat ini di database
                    $ongkirNow  = $transaksi->biaya_pengiriman ?? $transaksi->ongkir ?? 0;

                    // 3. Cek apakah bukti pelunasan akhir (ongkir/sisa) masih kosong
                    $buktiPelunasanKosong = empty($transaksi->bukti_pelunasan);

                    // 4. Hitung sisa tagihan secara nyata
                    $totalTagihan   = $transaksi->total_harga ?? 0;
                    $sisaTagihanNow = max(0, $totalTagihan - ($transaksi->bayar ?? 0));
                    $isLunasDb      = strtolower($transaksi->status_pembayaran ?? '') == 'lunas';

                    // 5. Kunci Mutlak Belum Lunas
                    $belumLunas = ($sisaTagihanNow > 0)
                               || ($harusDikirim && $ongkirNow > 0 && $buktiPelunasanKosong)
                               || (!$isLunasDb && $buktiPelunasanKosong);
                @endphp

                <!-- 1. PRIORITAS TERTINGGI: JIKA KUSTOMER SUDAH TERIMA PC -> SELESAIKAN PESANAN -->
                @if($transaksi->status_order == 'diterima_kustomer')
                    <input type="hidden" name="status" value="selesai">
                    <input type="hidden" name="status_order" value="selesai">
                    <input type="hidden" name="status_pesanan" value="selesai">
                    <button type="button" data-message="Tutup dan selesaikan transaksi ini secara permanen?" class="btn-confirm w-full bg-emerald-600 hover:bg-emerald-700 text-white font-black py-3.5 rounded-xl text-sm shadow-lg shadow-emerald-100 transition-all uppercase tracking-wide flex items-center justify-center gap-2 mt-2">
                        <i class="fas fa-check-double text-lg"></i> SELESAIKAN PESANAN
                    </button>
                @elseif($transaksi->status_order == 'sedang_dikirim')
                    <button type="button" disabled class="w-full bg-amber-50 text-amber-500 font-bold py-3.5 rounded-xl text-[11px] cursor-not-allowed border border-amber-200 uppercase tracking-widest flex items-center justify-center gap-2 mt-2">
                        <i class="fas fa-truck-loading text-lg"></i> Menunggu Kustomer Terima PC
                    </button>
                @elseif($harusDikirim && $ongkirNow <= 0)
                    <button type="button" disabled class="w-full bg-amber-50 text-amber-600 font-bold py-3.5 rounded-xl text-sm border border-amber-300 uppercase tracking-wide cursor-not-allowed flex items-center justify-center gap-2 mt-2" title="Harap isi biaya pengiriman di kotak bawah terlebih dahulu!">
                        <i class="fas fa-exclamation-circle text-lg"></i> UPDATE ONGKIR DULU
                    </button>
                @elseif($belumLunas)
                    <button type="button" disabled class="w-full bg-amber-50 text-amber-600 font-bold py-3.5 rounded-xl text-sm border border-amber-200 uppercase tracking-wide cursor-not-allowed flex items-center justify-center gap-2 mt-2" title="Menunggu Kustomer melakukan pelunasan sisa tagihan / ongkir">
                        <i class="fas fa-clock text-lg"></i> MENUNGGU PELUNASAN
                    </button>                    
               @elseif($harusDikirim && !$belumLunas)
                    @if($isTeknisi)
                        <button type="button" disabled class="w-full bg-indigo-50 text-indigo-600 font-bold py-3.5 rounded-xl text-sm border border-indigo-200 uppercase tracking-wide cursor-not-allowed flex items-center justify-center gap-2 mt-2" title="Pengiriman Home Service dioperasikan langsung oleh Teknisi melalui Dasbor Teknisi">
                            <i class="fas fa-user-cog text-lg"></i> MENUNGGU TEKNISI BERANGKAT
                        </button>
                    @else
                        <button type="button" data-modal-target="modal-resi" onclick="document.querySelector('input[name=nomor_resi], input[name=no_resi]')?.focus()" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-black py-3.5 rounded-xl text-sm shadow-lg shadow-blue-500/20 uppercase tracking-wide transition-all flex items-center justify-center gap-2 mt-2">
                            <i class="fas fa-shipping-fast text-lg"></i> SILAKAN INPUT RESI
                        </button>
                    @endif
                @elseif($isPickup && !$belumLunas)
                    <input type="hidden" name="status" value="selesai">
                    <input type="hidden" name="status_order" value="selesai">
                    <input type="hidden" name="status_pesanan" value="selesai">
                    <button type="button" data-message="Konfirmasi barang sudah diambil oleh Kustomer?" class="btn-confirm w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3.5 rounded-xl text-sm shadow-md uppercase tracking-wide transition-all flex items-center justify-center gap-2 mt-2">
                        <i class="fas fa-check-circle text-lg"></i> KONFIRMASI BARANG DIAMBIL
                    </button>
                @else
                    <input type="hidden" name="status" value="selesai">
                    <button type="button" data-message="Selesaikan transaksi ini?" class="btn-confirm w-full bg-emerald-600 text-white py-3.5 rounded-xl font-black text-sm hover:bg-emerald-700 shadow-lg shadow-emerald-100 transition-all uppercase tracking-tighter flex items-center justify-center gap-2 mt-2">
                        <i class="fas fa-handshake text-lg"></i> Selesaikan Pesanan
                    </button>
                @endif
                            
                            @endif
                        </form>

                        {{-- TOMBOL BATAL--}}
                    <form action="{{ route('admin.orders.update_status', $transaksi->id) }}" method="POST" class="form-status mt-2">
                    @csrf
                    <input type="hidden" name="status" value="batal">
                    @php
                        $tidakBisaBatal = !in_array($transaksi->status_order, ['belum_bayar']);
                    @endphp
                    @if($tidakBisaBatal)
                        <button type="button" disabled class="w-full bg-slate-50 text-slate-400 py-3 rounded-xl font-bold text-xs border border-slate-200 cursor-not-allowed uppercase tracking-wider flex items-center justify-center gap-2">
                            <i class="fas fa-lock"></i> Batalkan Pesanan (Terkunci)
                        </button>
                    @else
                        <button type="button" data-message="Batalkan pesanan ini? Aksi ini permanen." class="btn-confirm w-full bg-white text-red-500 py-3 rounded-xl font-bold text-xs hover:bg-red-50 border border-red-100 transition-colors uppercase tracking-wider flex items-center justify-center gap-2">
                            <i class="fas fa-times-circle"></i> Batalkan Pesanan
                        </button>
                    @endif
                </form>

                    </div>
                @elseif(in_array($transaksi->status_order, ['selesai', 'batal', 'completed', 'cancel']))
                    <div class="mt-4 p-4 bg-gray-50 rounded-xl border border-dashed border-gray-200 text-center">
                        <p class="text-xs font-bold text-gray-400 italic">Transaksi Telah Diarsipkan (Final)</p>
                    </div>
                @endif

                @endif 
                <!-- 👆 BATAS AREA YANG DIHILANGKAN SAAT RETUR 👆 -->

            </div>
            <!-- SELESAI BOX 1. STATUS & AKSI UMUM -->

            <!-- MODAL TOLAK RETUR DI LUAR PANEL -->
            @if($transaksi->pengajuanRetur && $transaksi->pengajuanRetur->status == 'menunggu_verifikasi')
            <div id="tolakReturModal" class="hidden fixed inset-0 z-[99] overflow-y-auto">
                <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
                    <div class="fixed inset-0 transition-opacity bg-slate-900/60 backdrop-blur-sm" onclick="toggleTolakReturModal(false)"></div>
                    <div class="relative inline-block overflow-hidden text-left align-bottom transition-all transform bg-white rounded-2xl shadow-2xl sm:my-8 sm:w-full sm:max-w-lg sm:align-middle">
                        <div class="bg-rose-600 px-6 py-4 text-white flex justify-between items-center">
                            <h3 class="text-base font-bold flex items-center gap-2"><i class="fas fa-times-circle"></i> Tolak Pengajuan Retur</h3>
                            <button type="button" onclick="toggleTolakReturModal(false)" class="text-white/80 hover:text-white text-xl font-bold">&times;</button>
                        </div>
                        <form action="{{ route('admin.retur.reject', $transaksi->pengajuanRetur->id) }}" method="POST" class="p-6">
                            @csrf
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Alasan Penolakan <span class="text-rose-500">*</span></label>
                                <textarea name="pesan_admin" required rows="3" placeholder="Contoh: Video unboxing tidak ada, atau kerusakan karena kesalahan Kustomer..." class="w-full rounded-xl border-slate-300 focus:ring-rose-500 focus:border-rose-500 text-sm"></textarea>
                            </div>
                            <div class="mt-5 flex justify-end gap-3">
                                <button type="button" onclick="toggleTolakReturModal(false)" class="bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold py-2 px-4 rounded-xl text-xs transition-all">Batal</button>
                                <button type="submit" class="bg-rose-600 hover:bg-rose-700 text-white font-bold py-2 px-5 rounded-xl text-xs shadow-md transition-all">Kirim Penolakan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <script>
    // 👇 JURUS TELEPORTASI: Pindahkan modal ke paling luar (body) saat halaman dimuat
    document.addEventListener("DOMContentLoaded", function() {
        const modal = document.getElementById('tolakReturModal');
        if(modal) {
            document.body.appendChild(modal);
        }
    });

    function toggleTolakReturModal(show) {
        document.getElementById('tolakReturModal').classList.toggle('hidden', !show);
    }
</script>
            @endif
            @if($transaksi->pengajuanRetur)
           <!-- 👇 MODAL KEPUTUSAN TEKNISI 👇 -->
            <div id="modalValidasiRetur" class="hidden fixed inset-0 z-[99] overflow-y-auto">
                <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
                    <div class="fixed inset-0 transition-opacity bg-slate-900/60 backdrop-blur-sm" onclick="toggleModalValidasiRetur(false)"></div>
                    <div class="relative inline-block overflow-hidden text-left align-bottom transition-all transform bg-white rounded-2xl shadow-2xl sm:my-8 sm:w-full sm:max-w-lg sm:align-middle">
                        
                        <!-- Header Modal -->
                        <div id="modalHeader" class="px-6 py-4 text-white flex justify-between items-center">
                            <h3 id="modalTitle" class="text-base font-bold flex items-center gap-2"></h3>
                            <button type="button" onclick="toggleModalValidasiRetur(false)" class="text-white/80 hover:text-white text-xl font-bold">&times;</button>
                        </div>

                        <form action="{{ route('admin.retur.hasil_cek', $transaksi->pengajuanRetur->id) }}" method="POST" enctype="multipart/form-data" class="p-6">
                            @csrf
                            <input type="hidden" name="keputusan" id="inputKeputusan">

                            <!-- Form Jika Valid (Refund) -->
                            <div id="formValid" class="hidden space-y-4">
                                <div class="bg-emerald-50 text-emerald-800 text-xs p-3 rounded-lg border border-emerald-200">
                                    Silakan transfer dana ke rekening Kustomer, lalu unggah bukti transfer di bawah ini. Status transaksi akan otomatis menjadi <strong>BATAL</strong>.
                                </div>
                               <div id="divNominalRefund" class="hidden mb-4">
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                                        Nominal Refund (Sesuai Harga Barang)
                                    </label>
                                    
                                    <!-- Kotak Display Nominal (Bukan Input) -->
                                    <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-3 flex items-center justify-between shadow-inner">
                                        <span class="text-emerald-600 font-bold">Rp</span>
                                        <span class="text-emerald-700 font-black text-lg tracking-wide">
                                    {{ number_format($transaksi->total_harga - $transaksi->biaya_pengiriman, 0, ',', '.') }}
                                </span>
                                    </div>
                                    
                                    <p class="text-[10px] text-slate-400 mt-1 italic">
                                        *Nominal dihitung otomatis dari sistem (Total Transaksi tanpa ongkos kirim).
                                    </p>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Unggah Struk Bukti Refund <span class="text-rose-500">*</span></label>
                                    <input type="file" name="bukti_refund" accept="image/*" class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 cursor-pointer border border-slate-200 p-1">
                                </div>
                            </div>

                            <!-- Form Jika Tidak Valid (Gugur / Penipuan) -->
                           <div id="formInvalid" class="hidden space-y-4">
                            
                            <!-- Info Merah -->
                            <div class="bg-rose-50 text-rose-800 text-xs p-3 rounded-lg border border-rose-200">
                                <strong>KLAIM GUGUR / PENIPUAN:</strong> Jika komponen ditukar atau paket kosong, catat akumulasinya di bawah ini. Transaksi akan langsung <strong>DITUTUP</strong>.
                            </div>

                            <!-- Pilihan Kategori -->
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Kategori Pelanggaran <span class="text-rose-500">*</span></label>
                                <select name="kategori_tolak" id="kategoriTolak" onchange="togglePartialRefund(this.value)" class="w-full rounded-xl border-slate-300 focus:ring-rose-500 focus:border-rose-500 text-sm mb-4">
                                    <option value="" disabled selected>Pilih Kategori Pelanggaran...</option>
                                    <option value="Paket Kosong">Paket Kosong (Tanpa Refund)</option>
                                    <option value="Komponen Ditukar Kustomer (Akumulasi)">Komponen Ditukar Kustomer (Akumulasi)</option>
                                </select>
                            </div>

                            <!-- 👇 Form Partial Refund (Sembunyi by default) 👇 -->
                            <div id="divPartialRefund" class="hidden mb-4 p-4 bg-rose-50 border border-rose-200 rounded-xl shadow-inner space-y-3">
                                <p class="text-xs text-rose-700 font-bold mb-1"><i class="fas fa-exclamation-triangle"></i> Form Refund Akumulasi (Manual)</p>
                                
                                <div>
                                    <label class="block text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-1">Nominal Refund (Rp) *</label>
                                    <div class="relative">
                                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-500 font-bold">Rp</span>
                                        <input type="text" name="nominal_refund_partial" id="inputNominalPartial" onkeyup="formatRupiah(this)" placeholder="Contoh: 1.500.000" class="w-full rounded-xl border-slate-300 focus:ring-rose-500 focus:border-rose-500 text-sm pl-9">
                                    </div>
                                </div>
                                
                                <div>
                                    <label class="block text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-1">Bukti Transfer Akumulasi *</label>
                                    <input type="file" name="bukti_refund_partial" id="inputBuktiPartial" accept="image/*" class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-rose-600 file:text-white hover:file:bg-rose-700 cursor-pointer">
                                </div>
                            </div>

                            <!-- 👇 INI TEXTAREA YANG TADI NGGAK SENGAJA KEHAPUS BOS 👇 -->
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Detail Akumulasi / Alasan Tutup <span class="text-rose-500">*</span></label>
                                <textarea name="hasil_cek_teknisi" required rows="3" placeholder="Contoh: Kustomer menukar RTX 3050 menjadi GTX 1040. Akumulasi kerugian..." class="w-full rounded-xl border-slate-300 focus:ring-rose-500 focus:border-rose-500 text-sm"></textarea>
                            </div>

                            <!-- Tombol Submit & Batal -->
                            <div class="mt-6 flex justify-end gap-3 border-t border-slate-100 pt-4">
                                <button type="button" onclick="toggleModalValidasiRetur(false)" class="bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold py-2.5 px-4 rounded-xl text-xs transition-all">Batal</button>
                                <button type="submit" id="btnSubmitModal" class="text-white font-bold py-2.5 px-5 rounded-xl text-xs shadow-md transition-all">Proses Keputusan</button>
                            </div>

                        </div>
                        </form>
                    </div>
                </div>
            </div>

<script>
    // 1. Jurus Teleportasi (Biar modal nggak ketutup Sidebar)
    document.addEventListener("DOMContentLoaded", function() {
        const modal = document.getElementById('modalValidasiRetur');
        if(modal) {
            document.body.appendChild(modal);
        }
    });

    function toggleModalValidasiRetur(type) {
        const modal = document.getElementById('modalValidasiRetur');
        
        if (type === false) {
            modal.classList.add('hidden');
            resetFormTolak(); 
            return;
        }

        document.getElementById('inputKeputusan').value = type;
        document.getElementById('formValid').classList.toggle('hidden', type !== 'valid');
        document.getElementById('formInvalid').classList.toggle('hidden', type !== 'invalid');
        
        const header = document.getElementById('modalHeader');
        const title = document.getElementById('modalTitle');
        const btnSubmit = document.getElementById('btnSubmitModal'); 
        
        // Cari elemennya dulu (biar gak error kalau HTML-nya beda nama)
        const inputBukti = document.querySelector('input[name="bukti_refund"]');
        const inputCatatan = document.querySelector('textarea[name="hasil_cek_teknisi"]'); // Sesuaikan nama ini sama HTML kamu ya!

        if (type === 'valid') {
            header.className = 'px-6 py-4 text-white flex justify-between items-center bg-emerald-600';
            title.innerHTML = '<i class="fas fa-check-circle"></i> Proses Refund (Klaim Valid)';
            btnSubmit.className = 'text-white font-bold py-2.5 px-5 rounded-xl text-xs shadow-md transition-all bg-emerald-600 hover:bg-emerald-700';
            
            document.getElementById('divNominalRefund').classList.remove('hidden');
            
            // Set required HANYA JIKA elemennya ditemukan
            if(inputBukti) inputBukti.required = true;
            if(inputCatatan) inputCatatan.required = false;
            
            resetFormTolak();

        } else {
            header.className = 'px-6 py-4 text-white flex justify-between items-center bg-rose-600';
            title.innerHTML = '<i class="fas fa-times-circle"></i> Gugurkan Klaim (Tolak)';
            btnSubmit.className = 'text-white font-bold py-2.5 px-5 rounded-xl text-xs shadow-md transition-all bg-rose-600 hover:bg-rose-700';
            
            document.getElementById('divNominalRefund').classList.add('hidden');
            
            // Set required HANYA JIKA elemennya ditemukan
            if(inputBukti) inputBukti.required = false;
            if(inputCatatan) inputCatatan.required = true;
        }

        modal.classList.remove('hidden');
    }
    function togglePartialRefund(value) {
        const divPartial = document.getElementById('divPartialRefund');
        const inputNominal = document.getElementById('inputNominalPartial');
        const inputBukti = document.getElementById('inputBuktiPartial');

        if (value === 'Komponen Ditukar Kustomer (Akumulasi)') {
            divPartial.classList.remove('hidden');
            inputNominal.required = true; 
            inputBukti.required = true;   
        } else {
            divPartial.classList.add('hidden');
            inputNominal.required = false;
            inputBukti.required = false;
            inputNominal.value = ''; // Kosongkan nilai otomatis
        }
    }

    // 4. Fungsi Bersih-bersih Form Tolak
    function resetFormTolak() {
        const kategoriSelect = document.getElementById('kategoriTolak'); // Pastikan ID select kamu 'kategoriTolak'
        if (kategoriSelect) {
            kategoriSelect.value = ''; 
        }
        togglePartialRefund(''); // Sembunyikan form partial
    }
</script>
            @endif

            <!-- 2. PROFIL KUSTOMER -->
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-200">
                <h3 class="font-black text-gray-800 mb-6 uppercase tracking-wider text-sm border-b border-gray-100 pb-3"><i class="fas fa-user-circle text-indigo-500 mr-1"></i> Client Profile</h3>
                <div class="space-y-4">
                    <div class="flex justify-between items-end border-b border-gray-50 pb-3">
                        <span class="text-[10px] text-gray-400 font-black uppercase">Nama Client</span>
                        <span class="font-black text-gray-900 text-sm italic">{{ $transaksi->nama_customer }}</span>
                    </div>
                    <div class="flex justify-between items-end border-b border-gray-50 pb-3">
                        <span class="text-[10px] text-gray-400 font-black uppercase">Perusahaan</span>
                        <span class="font-bold text-gray-600 text-xs">{{ $transaksi->customer->perusahaan ?? 'Personal' }}</span>
                    </div>
                    <div class="flex justify-between items-center border-b border-gray-50 pb-3">
                        <span class="text-[10px] text-gray-400 font-black uppercase">Metode Logistik</span>
                        @php
                            $shipLabel = match($transaksi->metode_pengiriman) {
                                'pickup' => ['Ambil Gudang', 'bg-blue-50 text-blue-600 border-blue-100'],
                                'courier' => ['Ekspedisi', 'bg-green-50 text-green-600 border-green-100'],
                                'delivery' => ['Pengiriman', 'bg-yellow-50 text-yellow-600 border-yellow-100'],
                                'technician' => ['Kurir Teknisi', 'bg-purple-50 text-purple-600 border-purple-100'],
                                default => ['Standar', 'bg-gray-50 text-gray-600']
                            };
                        @endphp
                        <span class="px-2 py-0.5 rounded text-[10px] font-black border {{ $shipLabel[1] }}">{{ $shipLabel[0] }}</span>
                    </div>
                    <div>
                        <span class="text-[10px] text-gray-400 font-black uppercase block mb-2">Destinasi Pengiriman</span>
                        <div class="bg-gray-50 p-3 rounded-xl border border-gray-100 text-xs font-medium text-gray-600 italic">
                            {{ $transaksi->alamat ?: 'Ambil di Workshop DiabloTech' }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- 3. INPUT ONGKIR -->
            @if(!in_array($transaksi->status_order, ['selesai', 'batal', 'completed', 'cancel']) && in_array(strtolower($transaksi->metode_pengiriman), ['courier', 'delivery', 'technician']))
            <div class="bg-gradient-to-br from-indigo-800 to-indigo-900 p-6 rounded-2xl shadow-xl text-white">
                <h3 class="font-black mb-1 flex items-center gap-2 uppercase text-sm">
                    <i class="fas fa-truck"></i> Logistik & Ongkir
                </h3>
                
                @if($transaksi->biaya_pengiriman > 0)
                    <p class="text-xs opacity-80 mb-4 font-medium italic">Biaya pengiriman telah ditetapkan.</p>
                    <div class="flex gap-2">
                        <div class="relative flex-1">
                            <span class="absolute left-3 top-2 text-white/50 text-sm font-bold">Rp</span>
                            <input type="text" disabled value="{{ number_format($transaksi->biaya_pengiriman, 0, '', '.') }}" class="w-full pl-9 pr-3 py-2.5 rounded-xl border-none bg-white/10 text-white/70 text-sm font-black cursor-not-allowed">
                        </div>
                        <button type="button" disabled class="bg-indigo-950/50 text-white/50 px-4 py-2.5 rounded-xl text-xs font-black cursor-not-allowed flex items-center gap-2 shadow-inner">
                            <i class="fas fa-lock"></i> TERKUNCI
                        </button>
                    </div>
                @else
                    <p class="text-xs opacity-80 mb-4 font-medium italic">Update biaya pengiriman untuk ditagihkan ke Kustomer.</p>
                    <form action="{{ route('admin.orders.update_ongkir', $transaksi->id) }}" method="POST" class="flex gap-2">
                        @csrf
                        <div class="relative flex-1">
                            <span class="absolute left-3 top-2 text-white/50 text-sm font-bold">Rp</span>
                            <input type="text" class="w-full pl-9 pr-3 py-2.5 rounded-xl border-none bg-white/20 text-white placeholder-white/40 text-sm font-black focus:ring-2 focus:ring-white/50 outline-none" placeholder="0" oninput="formatRupiah(this, 'biaya_actual')">
                            <input type="hidden" name="biaya_pengiriman" id="biaya_actual" value="0">
                        </div>
                        <button type="submit" class="bg-white text-indigo-800 px-4 py-2.5 rounded-xl text-xs font-black hover:bg-indigo-50 transition-all shadow-lg active:scale-95 uppercase">
                            UPDATE
                        </button>
                    </form>
                @endif
            </div>
            @endif
<!-- 4. VALIDASI BUKTI TRANSFER -->
<!-- 4. VALIDASI BUKTI TRANSFER -->
            @if(in_array($transaksi->status_order, ['belum_bayar', 'menunggu_proses', 'menunggu_kalkulasi', 'menunggu_acc_customer', 'siap_diambil', 'ready', 'lunas', 'sedang_dikirim', 'selesai']) || $transaksi->bukti_pembayaran)
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden mb-6">
                @php
                    $isNegoPO = in_array($transaksi->status_order, ['menunggu_kalkulasi', 'menunggu_acc_customer']);
                    
                    // 👇 DETEKSI KEBUTUHAN ONGKIR 👇
                    $metodeDb = strtolower($transaksi->metode_pengiriman);
                    $isEkspedisi = in_array($metodeDb, ['courier', 'delivery']);
                    $isKurirToko = in_array($metodeDb, ['technician']);
                    $ongkirBelumDihitung = ($isEkspedisi || $isKurirToko) && $transaksi->biaya_pengiriman == 0;
                @endphp
                <div class="bg-amber-50 border-b border-amber-100 px-5 py-3 flex items-center justify-between">
                    <h3 class="font-bold text-amber-800 text-sm"><i class="fas fa-file-invoice-dollar mr-2"></i> Bukti Transfer</h3>
                    @if($isLunas)
                        <span class="bg-emerald-500 text-white text-[10px] font-bold px-2 py-1 rounded uppercase"><i class="fas fa-check"></i> Lunas</span>
                    @elseif($isOffline && in_array($transaksi->status_order, ['menunggu_proses', 'sedang_dirakit', 'process']))
                        <span class="bg-slate-600 text-white text-[10px] font-bold px-2 py-1 rounded uppercase">Kasir Offline</span>
                    @elseif(in_array($transaksi->status_order, ['belum_bayar', 'menunggu_proses']))
                        <span class="bg-amber-500 text-white text-[10px] font-bold px-2 py-1 rounded uppercase animate-pulse">Cek Dana</span>
                    @elseif(!empty($transaksi->bukti_pelunasan) && $adaSisaTagihan)
                        <span class="bg-blue-500 text-white text-[10px] font-bold px-2 py-1 rounded uppercase animate-pulse">Cek Pelunasan</span>
                    @else
                        <span class="bg-slate-500 text-white text-[10px] font-bold px-2 py-1 rounded uppercase">{{ $transaksi->status_pembayaran }}</span>
                    @endif
                </div>
                
                <div class="p-5">
    {{-- ========================================== --}}
    {{-- BAGIAN 1: BANNER INFO & GAMBAR STRUK       --}}
    {{-- ========================================== --}}
    @if($transaksi->bukti_pembayaran || $transaksi->bukti_pelunasan)
        <div class="flex flex-col gap-4 mb-4">
            @if($transaksi->bukti_pembayaran)
            <div class="bg-slate-50 p-3 rounded-xl border border-slate-100">
                <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-2 text-center">Struk Pembayaran Awal</p>
                <a href="{{ asset('storage/' . $transaksi->bukti_pembayaran) }}" target="_blank" class="block border border-gray-200 rounded-xl overflow-hidden hover:border-blue-500 transition-colors shadow-sm relative group">
                    <img src="{{ asset('storage/' . $transaksi->bukti_pembayaran) }}" class="w-full h-32 object-cover transition-transform duration-300 group-hover:scale-105">
                    <div class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                        <span class="text-white text-[10px] font-bold uppercase tracking-widest"><i class="fas fa-search-plus"></i> Perbesar</span>
                    </div>
                </a>
            </div>
            @endif

            @if($transaksi->bukti_pelunasan)
            <div class="bg-emerald-50/50 p-3 rounded-xl border border-emerald-100">
                <p class="text-[10px] font-bold text-emerald-600 uppercase tracking-widest mb-2 text-center">
                    Struk Pelunasan {{ $transaksi->biaya_pengiriman > 0 ? '(Sisa + Ongkir)' : '(Sisa DP)' }}
                </p>
                <a href="{{ asset('storage/' . $transaksi->bukti_pelunasan) }}" target="_blank" class="block border border-emerald-200 rounded-xl overflow-hidden hover:border-emerald-500 transition-colors shadow-sm relative group">
                    <img src="{{ asset('storage/' . $transaksi->bukti_pelunasan) }}" class="w-full h-32 object-cover transition-transform duration-300 group-hover:scale-105">
                    <div class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                        <span class="text-white text-[10px] font-bold uppercase tracking-widest"><i class="fas fa-search-plus"></i> Perbesar</span>
                    </div>
                </a>
            </div>
            @endif
        </div>
    @elseif($isNegoPO)
        <div class="bg-blue-50 border border-dashed border-blue-200 rounded-lg p-4 text-center mb-4">
            <i class="fas fa-handshake text-2xl mb-2 text-blue-400"></i>
            <p class="text-[11px] text-blue-500 mt-1">Pembayaran ditangguhkan menunggu kesepakatan harga PO.</p>
        </div>
    @else
        {{-- JIKA STRUK KOSONG, PASTIKAN BANNER INI SELALU MUNCUL --}}
        @if(!$isOffline)
            <!-- KHUSUS PESANAN ONLINE -->
            <div class="bg-indigo-50 border border-dashed border-indigo-200 rounded-lg p-4 text-center mb-4">
                <p class="text-[11px] font-bold text-indigo-700 mt-1 uppercase tracking-wider">Menunggu Bukti Transfer</p>
                <p class="text-[10px] text-indigo-500 mt-1.5 leading-relaxed">
                    Customer belum mengunggah struk digital.
                </p>
            </div>
        @else
            <!-- KHUSUS PESANAN OFFLINE -->
            <div class="bg-slate-50 border border-dashed border-slate-300 rounded-lg p-4 text-center mb-4">
                <p class="text-[11px] font-bold text-slate-700 mt-1 uppercase tracking-wider">Pesanan Offline / Kasir</p>
                <p class="text-[10px] text-slate-500 mt-1.5 leading-relaxed">
                    Ini adalah pesanan offline. Pastikan uang tunai telah diterima.
                </p>
            </div>
        @endif
    @endif

    {{-- ========================================== --}}
    {{-- BAGIAN 2: TOMBOL AKSI                      --}}
    {{-- ========================================== --}}
    @if(!$isNegoPO)
        
        <!-- 1. Tombol ACC Pembayaran Awal -->
        @if(in_array($transaksi->status_order, ['belum_bayar', 'menunggu_proses']))
            
            @if($isOffline)
                <!-- OFFLINE: CUMA 1 TOMBOL (Gak ada tombol tolak karena bayar cash) -->
                <form action="{{ route('admin.orders.acc_pembayaran', $transaksi->id) }}" method="POST" class="mt-2">
                    @csrf
                    <button type="submit" class="w-full bg-emerald-500 hover:bg-emerald-600 text-white font-bold py-3.5 px-4 rounded-xl text-xs transition-all shadow-sm uppercase tracking-widest flex items-center justify-center gap-2">
                        Konfirmasi dana awal
                    </button>
                </form>
            @else
                <!-- ONLINE -->
                @if(!empty($transaksi->bukti_pembayaran))
                    <!-- Struk Ada -> Muncul 2 Tombol (Hijau & Merah) -->
                    <div class="mt-2 space-y-3">
                        <form action="{{ route('admin.orders.acc_pembayaran', $transaksi->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full bg-emerald-500 hover:bg-emerald-600 text-white font-bold py-3.5 px-4 rounded-xl text-xs transition-all shadow-sm uppercase tracking-widest flex items-center justify-center gap-2">
                                Konfirmasi dana awal
                            </button>
                        </form>
                        <form action="{{ route('admin.orders.tolak_pembayaran', $transaksi->id) }}" method="POST">
                            @csrf
                            <button type="submit" onclick="return confirm('Yakin ingin menolak bukti pembayaran ini? Kustomer akan diminta upload ulang.')" class="w-full bg-rose-500 hover:bg-rose-600 text-white font-bold py-3.5 px-4 rounded-xl text-xs transition-all shadow-sm uppercase tracking-widest flex items-center justify-center gap-2">
                                Tolak Bukti Pembayaran
                            </button>
                        </form>
                    </div>
                @else
                    <!-- Struk Kosong -> Tombol Terkunci (Sesuai gambarmu) -->
                    <button type="button" disabled class="mt-2 w-full bg-slate-50 text-slate-400 font-bold py-3.5 px-4 rounded-xl text-xs cursor-not-allowed uppercase tracking-widest flex items-center justify-center gap-2 border border-slate-200">
                        MENUNGGU STRUK
                    </button>
                @endif
            @endif

        @endif

        <!-- 2. Tombol Konfirmasi Pelunasan Sisa/Ongkir -->
        @if(!$isLunas && !in_array($transaksi->status_order, ['belum_bayar', 'menunggu_proses']))
            
            @if($ongkirBelumDihitung)
                <div class="mt-4 bg-amber-100 border border-amber-300 text-amber-800 p-3 rounded-xl text-xs font-bold text-center flex items-center justify-center gap-2 shadow-sm">
                    <i class="fas fa-lock text-amber-600"></i> Input Ongkos Kirim di panel atas sebelum ACC Lunas!
                </div>
            @else
                <!-- JIKA ONGKIR SUDAH AMAN, CEK OFFLINE ATAU ONLINE -->
                @if($isOffline)
                    <!-- OFFLINE: Langsung ACC Lunas karena uang cash diterima di toko -->
                    <form action="{{ route('admin.orders.konfirmasi_pelunasan', $transaksi->id) }}" method="POST" class="mt-4">
                        @csrf
                        <button type="button" onclick="konfirmasiLunas(this.form)" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-xl text-xs transition-colors shadow-lg flex items-center justify-center gap-2">
                        <i class="fas fa-money-bill-wave"></i> <!-- Kalau belum ada icon, ini cocok buat Lunas -->
                        LUNAS 100%
                    </button>
                    </form>
                @else
                    <!-- ONLINE: Cek apakah kustomer sudah upload struk pelunasan -->
                    @if(!empty($transaksi->bukti_pelunasan))
                        <!-- ADA STRUK: Muncul 2 Tombol (ACC & Tolak) -->
                        <div class="mt-4 space-y-3">
                            <form action="{{ route('admin.orders.konfirmasi_pelunasan', $transaksi->id) }}" method="POST">
                                @csrf
                                <button type="button" onclick="confirmLunas(this.form)" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-xl text-xs transition-colors shadow-lg flex items-center justify-center gap-2 uppercase tracking-widest">
                                    <i class="fas fa-check-circle"></i> Konfirmasi Lunas
                            </button>
                            </form>
                            
                            <form action="{{ route('admin.orders.tolak_pelunasan', $transaksi->id) }}" method="POST">
                                @csrf
                                <button type="button" onclick="confirmTolak(this.form)" class="w-full bg-rose-500 hover:bg-rose-600 text-white font-bold py-3.5 px-4 rounded-xl text-xs transition-all shadow-sm uppercase tracking-widest flex items-center justify-center gap-2">
                                    <i class="fas fa-times-circle"></i> Tolak Pelunasan
                                </button>
                            </form>
                        </div>
                    @else
                        <!-- BELUM ADA STRUK: Tombol Terkunci -->
                        <button type="button" disabled class="mt-4 w-full bg-slate-50 text-slate-400 font-bold py-3.5 px-4 rounded-xl text-xs cursor-not-allowed uppercase tracking-widest flex items-center justify-center gap-2 border border-slate-200">
                            <i class="fas fa-hourglass-half"></i> MENUNGGU PELUNASAN
                        </button>
                    @endif
                @endif

            @endif
        @endif
        
    @endif
</div>
            </div>
            @endif
            <!-- 5. INPUT PENGIRIMAN & RESI (DISABLED JIKA BELUM LUNAS) -->
            @if($butuhResi && empty($transaksi->resi_pengiriman))
                <!-- Jika belum lunas, berikan efek abu-abu/transparan -->
                <div class="bg-blue-50 border border-blue-200 rounded-2xl p-6 shadow-sm transition-all {{ !$isLunas ? 'opacity-70 grayscale-[20%]' : '' }}">
                    <h3 class="text-lg font-black text-blue-900 mb-2 flex items-center gap-2">
                        <i class="fas fa-truck-fast"></i> Input Pengiriman (POS)
                    </h3>
                    
                    @if(!$isLunas)
                        <!-- Peringatan jika belum Lunas -->
                        <div class="bg-amber-100 border border-amber-300 text-amber-800 p-3 rounded-lg text-xs font-bold mb-4 flex items-start gap-2 shadow-sm">
                            <i class="fas fa-lock text-amber-600 mt-0.5"></i> 
                            Form ini terkunci. Harap klik "Konfirmasi Lunas" pada Bukti Transfer terlebih dahulu untuk menginput resi.
                        </div>
                    @else
                        <p class="text-xs text-blue-700 mb-5 leading-relaxed">
                            Masukkan nomor resi, foto bukti resi, dan foto paket saat sudah berada di pihak ekspedisi.
                        </p>
                    @endif

                    <form action="{{ route('admin.orders.kirim_paket', $transaksi->id) }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                        @csrf
                        
                        <div>
                            <label class="block text-xs font-bold text-blue-900 uppercase tracking-wider mb-1.5">Nomor Resi <span class="text-red-500">*</span></label>
                            <input type="text" name="resi_pengiriman" {{ !$isLunas ? 'disabled' : 'required' }} placeholder="Contoh: POS-987654321" 
                                   class="w-full rounded-xl border border-blue-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm px-4 py-3 outline-none transition-all shadow-sm {{ !$isLunas ? 'bg-gray-100 cursor-not-allowed' : 'bg-white' }}">
                        </div>
                        
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-xs font-bold text-blue-900 uppercase tracking-wider mb-1.5">Foto Kertas Resi <span class="text-red-500">*</span></label>
                                <div id="preview-container-resi" class="hidden mb-3">
                                    <img id="img-preview-resi" class="w-full h-40 object-cover rounded-xl border-2 border-blue-300 shadow-sm">
                                </div>
                                <label id="lbl-file-resi" for="file-resi" class="flex flex-col items-center justify-center gap-2 w-full bg-white border-2 border-dashed border-blue-300 hover:bg-blue-100 hover:border-blue-500 text-blue-600 font-bold py-6 px-4 rounded-xl transition-all text-xs shadow-sm text-center {{ !$isLunas ? 'cursor-not-allowed bg-gray-50 opacity-60' : 'cursor-pointer' }}">
                                    <i class="fas fa-receipt text-2xl mb-1"></i> 
                                    <span id="text-file-resi">Pilih Foto Resi</span>
                                </label>
                                <input type="file" name="foto_resi_pengiriman" id="file-resi" accept="image/*" class="hidden" {{ !$isLunas ? 'disabled' : 'required' }} onchange="previewResiAdmin(event)">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-blue-900 uppercase tracking-wider mb-1.5">Foto Paket di POS <span class="text-red-500">*</span></label>
                                <div id="preview-container-paket" class="hidden mb-3">
                                    <img id="img-preview-paket" class="w-full h-40 object-cover rounded-xl border-2 border-blue-300 shadow-sm">
                                </div>
                                <label id="lbl-file-paket" for="file-paket" class="flex flex-col items-center justify-center gap-2 w-full bg-white border-2 border-dashed border-blue-300 hover:bg-blue-100 hover:border-blue-500 text-blue-600 font-bold py-6 px-4 rounded-xl transition-all text-xs shadow-sm text-center {{ !$isLunas ? 'cursor-not-allowed bg-gray-50 opacity-60' : 'cursor-pointer' }}">
                                    <i class="fas fa-box text-2xl mb-1"></i> 
                                    <span id="text-file-paket">Pilih Foto Paket</span>
                                </label>
                                <input type="file" name="foto_paket_ekspedisi" id="file-paket" accept="image/*" class="hidden" {{ !$isLunas ? 'disabled' : 'required' }} onchange="previewPaketAdmin(event)">
                            </div>
                        </div>

                        <!-- Tombol Submit Dinamis -->
                        @if($isLunas)
                            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-black py-3.5 px-6 rounded-xl transition-all shadow-lg hover:shadow-blue-500/30 flex justify-center items-center gap-2 uppercase tracking-wide text-sm mt-2">
                                <i class="fas fa-paper-plane"></i> Kirim Paket Sekarang
                            </button>
                        @else
                            <button type="button" disabled class="w-full bg-gray-400 text-white font-black py-3.5 px-6 rounded-xl transition-all shadow flex justify-center items-center gap-2 uppercase tracking-wide text-sm mt-2 cursor-not-allowed">
                                <i class="fas fa-lock"></i> Kunci Terbuka Jika Lunas
                            </button>
                        @endif
                    </form>
                </div>
            @endif

            <!-- 6. PREVIEW STATUS PENGIRIMAN (Jika Resi Sudah Ada) -->
            @if(!empty($transaksi->resi_pengiriman))
            <div class="bg-blue-50 border border-blue-200 rounded-xl p-5 shadow-sm flex flex-col gap-3">
                <p class="text-[10px] font-bold text-blue-500 uppercase tracking-wider mb-1">Paket Telah Diserahkan ke Ekspedisi</p>
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                    <p class="text-base font-bold text-slate-800 flex items-center gap-2">
                        No Resi: <span class="text-blue-700 font-mono bg-white border border-blue-300 px-3 py-1 rounded-lg shadow-sm">{{ $transaksi->resi_pengiriman }}</span>
                    </p>
                    <div class="flex gap-2 w-full sm:w-auto">
                        <a href="{{ asset('storage/' . $transaksi->foto_resi_pengiriman) }}" target="_blank" title="Lihat Bukti Kertas Resi" class="flex-1 bg-white border border-blue-300 hover:bg-blue-100 text-blue-700 font-bold py-2 px-3 rounded-lg shadow-sm transition-all text-xs flex justify-center items-center gap-2">
                            <i class="fas fa-receipt"></i> Resi
                        </a>
                        @if(!empty($transaksi->foto_paket_ekspedisi))
                        <a href="{{ asset('storage/' . $transaksi->foto_paket_ekspedisi) }}" target="_blank" title="Lihat Foto Paket POS" class="flex-1 bg-white border border-amber-300 hover:bg-amber-100 text-amber-700 font-bold py-2 px-3 rounded-lg shadow-sm transition-all text-xs flex justify-center items-center gap-2">
                            <i class="fas fa-box"></i> Paket
                        </a>
                        @endif
                    </div>
                </div>
            </div>
            @endif

        </div>
    </div>
</div>

<style>
    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
</style>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const confirmButtons = document.querySelectorAll('.btn-confirm');
        confirmButtons.forEach(button => {
            button.addEventListener('click', function () {
                const message = this.getAttribute('data-message');
                const form = this.closest('form');
                Swal.fire({
                    title: 'Konfirmasi Aksi',
                    text: message,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#1e293b',
                    cancelButtonColor: '#ef4444',
                    confirmButtonText: 'Ya, Lanjutkan!',
                    cancelButtonText: 'Batal',
                    reverseButtons: true,
                    customClass: {
                        popup: 'rounded-2xl',
                        confirmButton: 'rounded-xl px-6 py-2.5 font-black text-sm uppercase',
                        cancelButton: 'rounded-xl px-6 py-2.5 font-bold text-sm uppercase'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    });

    function formatRupiah(element, hiddenId) {
        let angkaAsli = element.value.replace(/[^0-9]/g, '');
        if (hiddenId && document.getElementById(hiddenId)) {
            document.getElementById(hiddenId).value = angkaAsli;
        }
        if (angkaAsli !== '') {
            element.value = angkaAsli.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        } else {
            element.value = '';
        }
    }

    function previewResiAdmin(event) {
        var input = event.target;
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e){
                document.getElementById('img-preview-resi').src = e.target.result;
                document.getElementById('preview-container-resi').classList.remove('hidden');
                var lbl = document.getElementById('lbl-file-resi');
                lbl.classList.remove('py-6', 'flex-col');
                lbl.classList.add('py-3', 'flex-row');
                document.getElementById('text-file-resi').innerHTML = 'Ganti Foto Resi';
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    function previewPaketAdmin(event) {
        var input = event.target;
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e){
                document.getElementById('img-preview-paket').src = e.target.result;
                document.getElementById('preview-container-paket').classList.remove('hidden');
                var lbl = document.getElementById('lbl-file-paket');
                lbl.classList.remove('py-6', 'flex-col');
                lbl.classList.add('py-3', 'flex-row');
                document.getElementById('text-file-paket').innerHTML = 'Ganti Foto Paket';
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
    function confirmLunas(form) {
        Swal.fire({
            title: 'Konfirmasi Pelunasan',
            text: "Yakin mengubah status menjadi LUNAS 100%? Pastikan dana sudah masuk ke rekening.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#2563eb', // Biru Tailwind
            cancelButtonColor: '#94a3b8',  // Abu-abu Tailwind
            confirmButtonText: 'Ya, ACC Lunas!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit(); // Submit form jika tombol "Ya" diklik
            }
        });
    }

    // Fungsi untuk Pop-up Tolak Pembayaran
    function confirmTolak(form) {
        Swal.fire({
            title: 'Tolak Pembayaran?',
            text: "Yakin ingin menolak bukti pembayaran ini? Kustomer akan diminta upload ulang.",
            icon: 'error',
            showCancelButton: true,
            confirmButtonColor: '#e11d48', // Merah Rose Tailwind
            cancelButtonColor: '#94a3b8',
            confirmButtonText: 'Ya, Tolak!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit(); // Submit form jika tombol "Ya" diklik
            }
        });
    }
    function confirmAccRetur(form) {
        Swal.fire({
            title: 'Setujui Pengajuan Retur?',
            text: "Pastikan Anda sudah menerima & mengecek video unboxing di WhatsApp.",
            icon: 'question', // Pakai icon tanda tanya biar admin mikir dulu sebelum klik
            showCancelButton: true,
            confirmButtonColor: '#059669', // Warna Hijau Emerald Tailwind
            cancelButtonColor: '#94a3b8',  // Warna Abu-abu
            confirmButtonText: 'Ya, Setujui!',
            cancelButtonText: 'Cek Dulu'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit(); // Lanjutkan submit kalau diklik "Ya"
            }
        });
    }
    function confirmPaketSampai(form) {
        Swal.fire({
            title: 'Paket Sudah Sampai?',
            text: "Konfirmasi bahwa paket retur sudah sampai di toko dan siap dicek teknisi.",
            icon: 'info', // Pakai icon info warna biru
            showCancelButton: true,
            confirmButtonColor: '#2563eb', // Warna Biru Tailwind (Blue-600)
            cancelButtonColor: '#94a3b8',  // Warna Abu-abu
            confirmButtonText: 'Ya, Sudah Diterima!',
            cancelButtonText: 'Belum'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit(); // Eksekusi submit kalau diklik "Ya"
            }
        });
    }
    function konfirmasiLunas(form) {
    Swal.fire({
        title: 'Konfirmasi Pelunasan',
        text: "Yakin mengubah status menjadi LUNAS 100%? Pastikan dana cash sudah diterima dari Kustomer.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#2563eb', // Warna biru sama kayak tombol (bg-blue-600)
        cancelButtonColor: '#dc2626', // Warna merah untuk batal
        confirmButtonText: 'Ya, Sudah Lunas!',
        cancelButtonText: 'Batal',
        reverseButtons: true // Biar tombol Batal ada di kiri, Ya di kanan (Standar UX)
    }).then((result) => {
        if (result.isConfirmed) {
            // Kalau diklik Ya, form baru dilanjutkan submit ke backend
            form.submit();
        }
    });
}
</script>
@endsection