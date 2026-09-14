@extends('layouts.teknisi')

@section('content')
@php
    // 1. HITUNG JUMLAH UNIT PC DARI JUMLAH PROSESOR
    $totalUnitPc = 1;
    if ($transaksi && $transaksi->details) {
        $cpuCount = $transaksi->details->filter(function($detail) {
            $kat  = strtolower($detail->barang->kategori ?? '');
            $nama = strtolower($detail->barang->nama_barang ?? $detail->nama_barang_custom ?? '');
            return str_contains($kat, 'processor') || str_contains($nama, 'ryzen') || str_contains($nama, 'core i');
        })->sum('qty');
        
        if ($cpuCount > 0) {
            $totalUnitPc = $cpuCount;
        }
    }

    // 2. CEK APAKAH SEMUA UNIT PC SUDAH SELESAI DI-QC
    $unitSelesai = $transaksi->dokumenQcs ? $transaksi->dokumenQcs->count() : ($transaksi->dokumenQc ? 1 : 0);
    $semuaQcSelesai = ($unitSelesai >= $totalUnitPc);

    // 🔥 3. PINDAHKAN KUNCI OTW KE SINI AGAR TIDAK ERROR DI BARIS 244! 🔥
    $ongkirTeknisi = $transaksi->biaya_pengiriman ?? $transaksi->ongkir ?? 0;
    $ongkirKosong  = ($ongkirTeknisi <= 0);
    $kunciOtw      = ($ongkirKosong || empty($transaksi->bukti_pelunasan));
@endphp
<div class="p-6 bg-slate-50 min-h-screen">
    
    <!-- HEADER ATAS -->
    <div class="mb-6 flex items-center justify-between">
        <div>
            <a href="{{ route('teknisi.orders.index') }}" class="text-sm text-blue-600 hover:underline flex items-center gap-1 mb-2">
                &larr; Kembali ke Daftar Tugas
            </a>
            <h2 class="text-2xl font-black text-slate-800 tracking-tight">
                Lembar Kerja: <span class="text-blue-600">{{ $transaksi->no_transaksi }}</span>
            </h2>
            <p class="text-sm text-slate-500 mt-1">
                Pelanggan: <strong>{{ $transaksi->nama_customer }}</strong> 
                ({{ $transaksi->customer->nomor_telepon ?? '-' }})
            </p>
        </div>
        
        @if($transaksi->status_order == 'sedang_dirakit')
            <span class="bg-amber-100 text-amber-700 px-4 py-2 rounded-lg text-xs font-bold uppercase tracking-widest shadow-sm">
                Status: Sedang Dirakit
            </span>
        @else
            <span class="bg-green-100 text-green-700 px-4 py-2 rounded-lg text-xs font-bold uppercase tracking-widest shadow-sm flex items-center gap-1">
                <span>✅</span> Selesai Dirakit
            </span>
        @endif
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- ================= KOLOM KIRI: DAFTAR BARANG, SOP & PENGIRIMAN ================= -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- ALERT INSTRUKSI KHUSUS CUSTOMER -->
            @if(!empty($transaksi->catatan))
            <div class="bg-amber-50 border-2 border-amber-400 rounded-2xl p-5 shadow-sm">
                <div class="flex items-start gap-3">
                    <span class="text-2xl">⚠️</span>
                    <div>
                        <h4 class="text-xs font-black text-amber-800 uppercase tracking-wider">
                            INSTRUKSI KHUSUS RAKITAN DARI CUSTOMER:
                        </h4>
                        <p class="text-base font-extrabold text-slate-900 mt-1">
                            "{{ $transaksi->catatan }}"
                        </p>
                    </div>
                </div>
            </div>
            @endif

            <!-- TABEL KOMPONEN RAKITAN -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="bg-slate-900 px-5 py-4 border-b border-slate-800">
                    <h3 class="font-bold text-white flex items-center gap-2">
                        <span>📦</span> Daftar Komponen Rakitan
                    </h3>
                </div>
                <div class="p-0">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 text-slate-500 text-[10px] uppercase tracking-wider">
                                <th class="p-4 border-b">Item / Deskripsi</th>
                                <th class="p-4 border-b text-center w-24">Qty</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-sm">
                            @foreach($transaksi->details as $detail)
                                <tr class="hover:bg-slate-50">
                                    <td class="p-4">
                                        <p class="font-bold text-slate-800">
                                            {{ $detail->barang->nama_barang ?? $detail->nama_barang_custom }}
                                        </p>
                                        <p class="text-[10px] text-slate-400 font-mono mt-1">
                                            {{ $detail->barang->kode_barang ?? 'CUSTOM-PART' }}
                                        </p>
                                    </td>
                                    <td class="p-4 text-center font-black text-lg text-blue-600">
                                        {{ $detail->qty }}x
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- KOTAK SOP PERAKITAN -->
            <div class="bg-blue-50 border border-blue-200 rounded-xl p-5 text-sm text-blue-800">
                <strong><i class="fas fa-info-circle"></i> SOP Perakitan (Terdeteksi: {{ $totalUnitPc }} Unit PC):</strong>
                <ul class="list-disc pl-5 mt-2 space-y-1 text-xs">
                    <li>Pastikan semua komponen dipasang sesuai dengan daftar di atas.</li>
                    <li>Lakukan cable management dengan rapi.</li>
                    <li>Lakukan stress test / uji nyala sebelum mengambil foto QC.</li>
                </ul>
            </div>

            <!-- ================= PENGIRIMAN HOME SERVICE (100% ALUR ASLI KAMU) ================= -->
            @if($transaksi->metode_pengiriman === 'technician')
                <div class="bg-white border-2 border-indigo-100 rounded-2xl p-6 shadow-sm mt-6">
                    <h3 class="font-black text-indigo-800 mb-4 flex items-center gap-2 uppercase tracking-widest text-sm">
                        <i class="fas fa-map-marked-alt"></i> Pengiriman Home Service
                    </h3>

                    {{-- 1. JIKA STATUS SELESAI (TAMPILKAN ARSIP) --}}
                    @if($transaksi->status_order === 'selesai')
                        <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-5 mb-2">
                            <h4 class="font-bold text-emerald-800 text-sm mb-4 flex items-center gap-2">
                                <i class="fas fa-check-circle"></i> Selesai & Dokumen Terarsip
                            </h4>
                            @if($transaksi->pengirimanTeknisi)
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <p class="text-[10px] uppercase font-bold text-slate-500 mb-1.5">Foto Bukti Lokasi</p>
                                        <a href="{{ asset('storage/' . $transaksi->pengirimanTeknisi->foto_bukti_instalasi) }}" target="_blank" class="block">
                                            <img src="{{ asset('storage/' . $transaksi->pengirimanTeknisi->foto_bukti_instalasi) }}" class="w-full h-24 object-cover rounded-lg border border-slate-200 hover:opacity-80 transition">
                                        </a>
                                    </div>
                                    <div>
                                        <p class="text-[10px] uppercase font-bold text-slate-500 mb-1.5">Dokumen (PDF)</p>
                                        <a href="{{ asset('storage/' . $transaksi->pengirimanTeknisi->dokumen) }}" target="_blank" class="w-full h-24 flex flex-col items-center justify-center bg-white border border-slate-200 rounded-lg hover:bg-slate-50 transition shadow-sm text-slate-600 hover:text-red-600">
                                            <i class="fas fa-file-pdf text-3xl mb-1 text-red-500"></i>
                                            <span class="text-[11px] font-bold">Lihat/Garansi</span>
                                        </a>
                                    </div>
                                </div>
                                <p class="text-xs text-emerald-700 mt-4 font-medium flex items-center gap-1">
                                    <i class="fas fa-clock"></i> Selesai pada: {{ $transaksi->pengirimanTeknisi->waktu_selesai ? $transaksi->pengirimanTeknisi->waktu_selesai->format('d M Y - H:i') : '-' }} WIB
                                </p>
                            @endif
                        </div>

                    {{-- 2. JIKA TEKNISI SUDAH KLIK TOMBOL OTW (STATUS SEDANG DIKIRIM) --}}
                    {{-- 2. JIKA TEKNISI SUDAH KLIK TOMBOL OTW (STATUS SEDANG DIKIRIM) --}}
                    @elseif($transaksi->status_order === 'sedang_dikirim')
                        <div class="bg-amber-50 border border-amber-200 rounded-xl p-5 flex items-center justify-between mb-5">
                            <div>
                                <p class="text-[10px] text-amber-600 font-bold uppercase tracking-wider mb-1">Status Saat Ini:</p>
                                <p class="font-black text-amber-800 text-xl flex items-center gap-2">
                                    <i class="fas fa-route animate-pulse"></i> Sedang Dalam Perjalanan
                                </p>
                                @if($transaksi->pengirimanTeknisi)
                                    <p class="text-xs text-amber-700 mt-1.5 font-bold">
                                        Waktu Keberangkatan: {{ $transaksi->pengirimanTeknisi->waktu_berangkat->format('H:i') }} WIB
                                    </p>
                                @endif
                            </div>
                        </div>

                        <div class="border-t border-amber-200 pt-5">
                            <h4 class="font-bold text-slate-800 text-sm mb-3 flex items-center gap-2">
                                <i class="fas fa-camera-retro text-amber-600"></i> Laporan Selesai di Lokasi
                            </h4>
                            <form action="{{ route('teknisi.selesaikanTugas', $transaksi->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4" id="formLaporanLokasi">
                                @csrf
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Foto PC di Lokasi <span class="text-red-500">*</span></label>
                                        <input type="file" name="foto_bukti_instalasi" id="inputFotoLokasi" accept="image/*" required 
                                               class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-amber-100 file:text-amber-800 hover:file:bg-amber-200 border border-slate-200 rounded-lg p-1 bg-white">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Dokumen QC / Garansi (PDF) <span class="text-red-500">*</span></label>
                                        <input type="file" name="dokumen" id="inputDokumenPdf" accept=".pdf,application/pdf" required 
                                               class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-amber-100 file:text-amber-800 hover:file:bg-amber-200 border border-slate-200 rounded-lg p-1 bg-white">
                                    </div>
                                </div>
                                
                                {{-- TOMBOL SUBMIT DEFAULT-NYA DISABLED (ABU-ABU) --}}
                                <button type="submit" id="btnSelesaiTugas" disabled
                                        class="w-full mt-2 bg-slate-300 text-slate-500 cursor-not-allowed font-black py-3.5 px-6 rounded-xl text-sm transition-all shadow-none flex items-center justify-center gap-2">
                                    <i class="fas fa-lock text-lg" id="iconBtnSelesai"></i> 
                                    <span id="textBtnSelesai">UPLOAD FOTO & PDF UNTUK SELESAIKAN TUGAS</span>
                                </button>
                            </form>
                        </div>

                        {{-- SCRIPT OTOMATIS BUKA KUNCI TOMBOL JIKA 2 FILE SUDAH DIPILIH --}}
                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                const inputFoto = document.getElementById('inputFotoLokasi');
                                const inputPdf = document.getElementById('inputDokumenPdf');
                                const btnSubmit = document.getElementById('btnSelesaiTugas');
                                const iconBtn = document.getElementById('iconBtnSelesai');
                                const textBtn = document.getElementById('textBtnSelesai');

                                function cekFormLengkap() {
                                    // Cek apakah kedua input sudah terisi file
                                    if (inputFoto.files.length > 0 && inputPdf.files.length > 0) {
                                        // Buka kunci tombol (Aktifkan warna hijau)
                                        btnSubmit.disabled = false;
                                        btnSubmit.className = "w-full mt-2 bg-green-600 hover:bg-green-700 text-white font-black py-3.5 px-6 rounded-xl text-sm transition-all shadow-lg flex items-center justify-center gap-2 active:scale-95 cursor-pointer";
                                        iconBtn.className = "fas fa-check-double text-lg";
                                        textBtn.innerText = "SELESAIKAN TUGAS SEKARANG";
                                    } else {
                                        // Kunci tombol (Warna abu-abu)
                                        btnSubmit.disabled = true;
                                        btnSubmit.className = "w-full mt-2 bg-slate-300 text-slate-500 cursor-not-allowed font-black py-3.5 px-6 rounded-xl text-sm transition-all shadow-none flex items-center justify-center gap-2";
                                        iconBtn.className = "fas fa-lock text-lg";
                                        textBtn.innerText = "UPLOAD FOTO & PDF UNTUK SELESAIKAN TUGAS";
                                    }
                                }

                                // Dengarkan event perubahan saat teknisi memilih file
                                if(inputFoto && inputPdf) {
                                    inputFoto.addEventListener('change', cekFormLengkap);
                                    inputPdf.addEventListener('change', cekFormLengkap);
                                }
                            });
                        </script>

                    {{-- 3. JIKA SEMUA QC BERES TAPI CUSTOMER BELUM BAYAR ONGKIR --}}
                    @elseif($semuaQcSelesai && $kunciOtw)
                        <div class="bg-red-50 border border-red-200 rounded-xl p-4 text-center mb-4">
                            <p class="text-sm text-red-600 font-black tracking-wide">
                                <i class="fas fa-hand-paper mr-1"></i> TAHAN DULU!
                            </p>
                            <p class="text-xs text-red-500 mt-1">
                                @if($ongkirKosong)
                                    Biaya pengiriman / layanan teknisi belum dihitung oleh Admin. Tombol keberangkatan terkunci otomatis.
                                @else
                                    Customer belum mengunggah bukti pelunasan (sisa tagihan / ongkir). Tombol keberangkatan terkunci otomatis.
                                @endif
                            </p>
                        </div>
                        <button type="button" disabled class="w-full bg-slate-100 text-slate-400 font-black py-4 px-6 rounded-xl cursor-not-allowed transition-all shadow-inner flex items-center justify-center gap-2">
                            <i class="fas fa-lock text-lg"></i> MENUNGGU PEMBAYARAN CUSTOMER
                        </button>

                    {{-- 4. JIKA SEMUA QC BERES, ONGKIR SUDAH DIISI, DAN BUKTI PELUNASAN SUDAH ADA -> MUNCUL TOMBOL OTW --}}
                    @elseif($semuaQcSelesai && !$kunciOtw)
                        <div class="bg-indigo-50 border border-indigo-100 rounded-xl p-4 mb-4">
                            <p class="text-sm text-indigo-700">
                                <i class="fas fa-check-circle mr-1"></i> QC Selesai & Tagihan/Ongkir Lunas. PC siap diberangkatkan ke lokasi pelanggan.
                            </p>
                        </div>
                        <form action="{{ route('teknisi.tandaiOtw', $transaksi->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-black py-4 px-6 rounded-xl transition-all shadow-lg shadow-indigo-200 flex items-center justify-center gap-2 active:scale-95">
                                <i class="fas fa-motorcycle text-xl"></i> MULAI PERJALANAN KE LOKASI CUSTOMER
                            </button>
                        </form>

                    {{-- 5. JIKA QC BELUM ADA / MASIH DIRAKIT --}}
                    @else
                        <div class="bg-slate-50 border border-slate-200 rounded-xl p-4 text-center">
                            <p class="text-sm text-slate-500 font-bold">
                                <i class="fas fa-lock mr-1"></i> Pengiriman Terkunci
                            </p>
                            <p class="text-xs text-slate-400 mt-1">Selesaikan perakitan dan isi Form QC di sebelah kanan terlebih dahulu untuk membuka kunci pengiriman.</p>
                        </div>
                    @endif
                </div>
            @endif
            <!-- 👆 SELESAI BAGIAN PENGIRIMAN 👆 -->

        </div>

        <!-- ================= KOLOM KANAN: FORM QC (BISA 1 ATAU BANYAK PC) ================= -->
        <div class="lg:col-span-1 space-y-6">
            
            @if($transaksi->status_order == 'sedang_dirakit')
                <div class="bg-white rounded-xl shadow-md border border-slate-200 overflow-hidden sticky top-6">
                    <div class="bg-blue-600 px-5 py-4 border-b border-blue-700 flex items-center justify-between">
                        <h3 class="font-bold text-white flex items-center gap-2">
                            <span>📸</span> Laporan Quality Control
                        </h3>
                        <span class="bg-blue-800 text-white text-[10px] font-black px-2.5 py-1 rounded-full border border-blue-400">
                            {{ $totalUnitPc }} UNIT PC
                        </span>
                    </div>
                    
                    <div class="p-5 space-y-6">
                        <p class="text-xs text-slate-500 leading-relaxed border-b border-slate-100 pb-3">
                            Unggah foto bukti perakitan untuk masing-masing unit PC agar digenerate menjadi Kartu Garansi & Dokumen QC.
                        </p>

                        @for($unit = 1; $unit <= $totalUnitPc; $unit++)
                            @php
                                $qcUnit = $transaksi->dokumenQcs ? $transaksi->dokumenQcs->where('nomor_unit', $unit)->first() : null;
                            @endphp

                            @if(!$qcUnit)
                                <!-- FORM UPLOAD UNTUK UNIT YANG BELUM DI-QC -->
                                <form action="{{ route('teknisi.orders.storeQc', $transaksi->id) }}" method="POST" enctype="multipart/form-data" class="bg-slate-50 border border-slate-200 rounded-xl p-4 space-y-3">
                                    @csrf
                                    <input type="hidden" name="nomor_unit" value="{{ $unit }}">
                                    <input type="hidden" name="nama_pc" value="Custom Rakitan PC - Unit #{{ $unit }}">

                                    <div class="flex items-center justify-between border-b border-slate-200 pb-2">
                                        <span class="text-xs font-black text-blue-700 uppercase tracking-wider flex items-center gap-1.5">
                                            <i class="fas fa-desktop"></i> INPUT QC — UNIT #{{ $unit }}
                                        </span>
                                    </div>

                                    <div>
                                        <label class="block text-[11px] font-bold text-slate-700 uppercase mb-1">
                                            1. Tampak Depan (Unit #{{ $unit }}) <span class="text-red-500">*</span>
                                        </label>
                                        <input type="file" name="foto_depan" accept="image/*" required
                                               class="w-full text-xs text-slate-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-blue-100 file:text-blue-700 hover:file:bg-blue-200 border border-slate-200 rounded-lg p-1 bg-white">
                                    </div>

                                    <div>
                                        <label class="block text-[11px] font-bold text-slate-700 uppercase mb-1">
                                            2. Manajemen Kabel (Unit #{{ $unit }}) <span class="text-red-500">*</span>
                                        </label>
                                        <input type="file" name="foto_belakang" accept="image/*" required
                                               class="w-full text-xs text-slate-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-blue-100 file:text-blue-700 hover:file:bg-blue-200 border border-slate-200 rounded-lg p-1 bg-white">
                                    </div>

                                    <div>
                                        <label class="block text-[11px] font-bold text-slate-700 uppercase mb-1">
                                            3. PC Menyala / Test (Unit #{{ $unit }}) <span class="text-red-500">*</span>
                                        </label>
                                        <input type="file" name="foto_menyala" accept="image/*" required
                                               class="w-full text-xs text-slate-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-blue-100 file:text-blue-700 hover:file:bg-blue-200 border border-slate-200 rounded-lg p-1 bg-white">
                                    </div>

                                    <div>
                                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Catatan Teknisi (Opsional)</label>
                                        <textarea name="catatan_teknisi" rows="2" placeholder="Suhu normal, kabel aman..." 
                                                  class="w-full text-xs border-slate-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
                                    </div>

                                    <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-black py-3 px-4 rounded-xl text-xs transition-all shadow-md flex justify-center items-center gap-2">
                                        <i class="fas fa-check-double text-sm"></i>
                                        Selesaikan & Kirim QC (Unit #{{ $unit }})
                                    </button>
                                </form>
                            @else
                                <!-- KARTU HIJAU UNTUK UNIT YANG SUDAH SELESAI DI-QC -->
                                <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-4 space-y-2">
                                    <div class="flex items-center justify-between font-bold text-emerald-800 text-xs">
                                        <span>✅ UNIT #{{ $unit }} SELESAI DI-QC</span>
                                        @if($qcUnit->file_pdf)
                                            <a href="{{ asset('storage/' . $qcUnit->file_pdf) }}" target="_blank" class="text-red-600 hover:underline flex items-center gap-1">
                                                <i class="fas fa-file-pdf"></i> PDF Garansi
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        @endfor
                    </div>
                </div>

            @else
                <!-- ================= TAMPILAN ARSIP QC SEMUA UNIT (MULTI-UNIT) ================= -->
                <div class="bg-white rounded-xl shadow-md border border-slate-200 overflow-hidden sticky top-6">
                    <div class="bg-green-600 px-5 py-4 border-b border-green-700 flex items-center justify-between">
                        <h3 class="font-bold text-white flex items-center gap-2">
                            <span>✅</span> QC Telah Diselesaikan
                        </h3>
                        <span class="bg-green-800 text-white text-[10px] font-black px-2.5 py-1 rounded-full border border-green-400">
                            {{ $transaksi->dokumenQcs ? $transaksi->dokumenQcs->count() : 1 }} UNIT PC
                        </span>
                    </div>
                    
                    <div class="p-5 space-y-6">
                        <p class="text-xs text-slate-600 text-center">
                            Tugas perakitan ini telah selesai dan diserahkan ke Admin.
                        </p>
                        
                        {{-- 1. JIKA DATA MULTI-UNIT ADA (LOOPING SEMUA UNIT PC) --}}
                        @if($transaksi->dokumenQcs && $transaksi->dokumenQcs->count() > 0)
                            @foreach($transaksi->dokumenQcs as $doc)
                                <div class="bg-slate-50 border border-slate-200 rounded-xl p-4 space-y-3">
                                    <!-- HEADER UNIT -->
                                    <div class="flex items-center justify-between border-b border-slate-200 pb-2">
                                        <span class="text-xs font-black text-green-700 uppercase tracking-wider flex items-center gap-1.5">
                                            <i class="fas fa-desktop"></i> ARSIP QC — UNIT #{{ $doc->nomor_unit }}
                                        </span>
                                        <span class="text-[10px] bg-green-100 text-green-800 font-bold px-2 py-0.5 rounded">
                                            Lulus Test
                                        </span>
                                    </div>

                                    <!-- THUMBNAIL 3 FOTO -->
                                    <div class="grid grid-cols-3 gap-2">
                                        <a href="{{ asset('storage/' . $doc->foto_depan) }}" target="_blank">
                                            <img src="{{ asset('storage/' . $doc->foto_depan) }}" class="w-full h-16 object-cover rounded border border-slate-200 hover:opacity-80 transition">
                                        </a>
                                        <a href="{{ asset('storage/' . $doc->foto_belakang) }}" target="_blank">
                                            <img src="{{ asset('storage/' . $doc->foto_belakang) }}" class="w-full h-16 object-cover rounded border border-slate-200 hover:opacity-80 transition">
                                        </a>
                                        <a href="{{ asset('storage/' . $doc->foto_menyala) }}" target="_blank">
                                            <img src="{{ asset('storage/' . $doc->foto_menyala) }}" class="w-full h-16 object-cover rounded border border-slate-200 hover:opacity-80 transition">
                                        </a>
                                    </div>

                                    <!-- CATATAN TEKNISI (JIKA ADA) -->
                                    @if($doc->catatan_teknisi)
                                        <p class="text-[11px] bg-white p-2 rounded border border-slate-100 text-slate-600 font-mono">
                                            "{{ $doc->catatan_teknisi }}"
                                        </p>
                                    @endif

                                    <!-- TOMBOL PDF INTERNAL PER UNIT -->
                                    @if($doc->file_pdf)
                                        <a href="{{ asset('storage/' . $doc->file_pdf) }}" target="_blank" 
                                           class="w-full bg-red-50 text-red-600 border border-red-200 hover:bg-red-500 hover:text-white font-bold py-2.5 px-4 rounded-lg text-xs transition-colors text-center block shadow-sm flex items-center justify-center gap-2">
                                            <i class="fas fa-file-pdf"></i>
                                            Lihat Check List QC (Unit #{{ $doc->nomor_unit }})
                                        </a>
                                    @endif
                                </div>
                            @endforeach

                        {{-- 2. FALLBACK KALAU DATA LAMA (CUMA 1 PC) --}}
                        @elseif($transaksi->dokumenQc)
                            <div class="grid grid-cols-3 gap-2 mb-4">
                                <a href="{{ asset('storage/' . $transaksi->dokumenQc->foto_depan) }}" target="_blank">
                                    <img src="{{ asset('storage/' . $transaksi->dokumenQc->foto_depan) }}" class="w-full h-20 object-cover rounded border border-slate-200 hover:opacity-80">
                                </a>
                                <a href="{{ asset('storage/' . $transaksi->dokumenQc->foto_belakang) }}" target="_blank">
                                    <img src="{{ asset('storage/' . $transaksi->dokumenQc->foto_belakang) }}" class="w-full h-20 object-cover rounded border border-slate-200 hover:opacity-80">
                                </a>
                                <a href="{{ asset('storage/' . $transaksi->dokumenQc->foto_menyala) }}" target="_blank">
                                    <img src="{{ asset('storage/' . $transaksi->dokumenQc->foto_menyala) }}" class="w-full h-20 object-cover rounded border border-slate-200 hover:opacity-80">
                                </a>
                            </div>
                            
                            @if($transaksi->dokumenQc->file_pdf)
                                <a href="{{ asset('storage/' . $transaksi->dokumenQc->file_pdf) }}" target="_blank" class="w-full bg-red-50 text-red-600 border border-red-200 hover:bg-red-500 hover:text-white font-bold py-3 px-4 rounded-lg text-sm transition-colors text-center block shadow-sm flex items-center justify-center gap-2">
                                    <span>📄</span> Lihat Sertifikat PDF
                                </a>
                            @endif
                        @endif

                    </div>
                </div>
            @endif

        </div>

    </div>
</div>
@endsection