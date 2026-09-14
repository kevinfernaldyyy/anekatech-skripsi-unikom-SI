@extends('layouts.admin')

@section('title', 'Detail & QC PC Ready Stock')

@section('content')
<div class="max-w-6xl mx-auto min-h-screen bg-gray-50/50 pb-12">
    
    <!-- HEADER -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <div class="flex items-center gap-2">
                <span class="px-3 py-1 rounded-full text-xs font-extrabold bg-green-100 text-green-800 border border-green-200">
                    🟢 PC Ready Stock
                </span>
                <span class="text-xs text-gray-400 font-mono">{{ $barang->kode_barang }}</span>
            </div>
            <h2 class="text-2xl font-bold text-gray-900 mt-1">{{ $barang->nama_barang }}</h2>
        </div>
        
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.barang.index', ['jenis' => 'paket_ready']) }}" 
               class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 font-bold text-sm transition shadow-2xs">
                &larr; Kembali ke Daftar Ready
            </a>
            @if(request()->user() && request()->user()->role == 'admin')
            <a href="{{ route('admin.barang.edit', $barang->id) }}" 
               class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-blue-600 text-white hover:bg-blue-700 font-bold text-sm transition shadow-sm">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                Edit Barang
            </a>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- KOLOM KIRI: INFO PC & SPESIFIKASI KOMPONEN (2 KOLOM) -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- CARD 1: OVERVIEW HARGA & STOK -->
            <div class="bg-white rounded-2xl p-6 border border-gray-200 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-6">
                <div class="flex items-center gap-4">
                    <div class="h-20 w-20 flex-shrink-0 overflow-hidden rounded-xl border border-gray-200 bg-white p-2">
                        @if($barang->gambar)
                            <img src="{{ asset('storage/' . $barang->gambar) }}" class="h-full w-full object-contain rounded-lg">
                        @else
                            <div class="h-full w-full flex items-center justify-center bg-gray-50 text-gray-300">
                                <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                            </div>
                        @endif
                    </div>
                    <div>
                        <p class="text-xs uppercase font-bold text-gray-400">Harga Jual Paket PC</p>
                        <p class="text-2xl font-black text-gray-900 mt-0.5">Rp {{ number_format($barang->harga_jual, 0, ',', '.') }}</p>
                        <p class="text-xs text-green-600 font-bold mt-1">✓ Siap diproses di transaksi kasir</p>
                    </div>
                </div>

                <div class="sm:text-right border-t sm:border-t-0 pt-4 sm:pt-0 border-gray-100">
                    <p class="text-xs uppercase font-bold text-gray-400">Stok Fisik Tersedia</p>
                    <div class="inline-flex items-center gap-1.5 mt-1 px-4 py-1.5 rounded-full bg-green-50 border border-green-200 text-green-700 font-black text-lg">
                        <span>📦</span>
                        <span>{{ $barang->stok }} Unit Ready</span>
                    </div>
                </div>
            </div>

            <!-- CARD 2: DAFTAR SPESIFIKASI KOMPONEN PC -->
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="p-6 bg-gray-50 border-b border-gray-100 flex justify-between items-center">
                    <div>
                        <h3 class="font-bold text-gray-800 text-base">Spesifikasi & Part Terpasang</h3>
                        <p class="text-xs text-gray-500 mt-0.5">Daftar komponen yang digunakan dalam rakitan PC ini.</p>
                    </div>
                    <span class="px-3 py-1 rounded-full bg-blue-100 text-blue-800 font-bold text-xs">
                        {{ $barang->components->count() }} Komponen
                    </span>
                </div>

                <div class="divide-y divide-gray-100">
                    @forelse($barang->components as $c)
                    <div class="p-4 sm:px-6 flex items-center justify-between hover:bg-gray-50/80 transition">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-xs shrink-0">
                                ✓
                            </div>
                            <div>
                                <p class="font-bold text-gray-800 text-sm">{{ $c->nama_barang }}</p>
                                <div class="flex items-center gap-2 mt-0.5">
                                    <span class="text-[11px] text-gray-400 font-mono">{{ $c->kode_barang }}</span>
                                    <span class="text-[11px] px-2 py-0.5 rounded bg-gray-100 text-gray-600 font-semibold">{{ $c->kategori }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="text-right">
                            <span class="inline-flex items-center px-3 py-1 rounded-lg bg-slate-100 text-slate-800 font-black text-xs border border-slate-200">
                                x{{ $c->pivot->qty }} {{ $c->satuan ?? 'Pcs' }}
                            </span>
                        </div>
                    </div>
                    @empty
                    <div class="p-12 text-center text-gray-400">
                        <p class="text-sm italic">Belum ada data komponen/resep yang terdaftar pada PC ini.</p>
                    </div>
                    @endforelse
                </div>
            </div>

        </div>

        <!-- KOLOM KANAN: QUALITY CONTROL (QC) & RIWAYAT RAKIT (1 KOLOM) -->
        <div class="lg:col-span-1 space-y-6">
            
            <!-- CARD QC & GARANSI PER UNIT -->
            <div class="bg-white rounded-2xl p-6 border border-gray-200 shadow-sm">
                <div class="flex items-center gap-2 mb-4">
                    <div class="w-8 h-8 rounded-lg bg-purple-100 text-purple-600 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-900 text-base">Quality Control (QC)</h3>
                        <p class="text-[11px] text-gray-500">Cetak lembar pengecekan & garansi per unit.</p>
                    </div>
                </div>

                <div class="space-y-4">
                    @if($histories->count() > 0)
                        @foreach($histories as $log)
                        <div class="p-4 rounded-xl bg-slate-50 border border-slate-200">
                            <div class="flex justify-between items-start mb-2">
                                <div>
                                    <span class="text-[10px] font-bold uppercase tracking-wider text-purple-700 bg-purple-100 px-2 py-0.5 rounded">
                                        Batch #{{ $log->id }}
                                    </span>
                                    <p class="text-xs font-bold text-gray-800 mt-1">Rakit +{{ $log->qty_rakit }} Unit</p>
                                </div>
                                <time class="text-[10px] text-gray-400 font-medium">
                                    {{ $log->created_at->format('d M Y, H:i') }}
                                </time>
                            </div>

                            <!-- TOMBOL CETAK QC PER-UNIT -->
                            <div class="mt-3 pt-3 border-t border-slate-200/80">
                                <p class="text-[11px] font-bold text-gray-600 mb-2">Pilih Unit untuk Cetak QC:</p>
                                <div class="flex flex-wrap gap-1.5">
                                    @for($u = 1; $u <= $log->qty_rakit; $u++)
                                    <a href="{{ route('teknisi.admin.produksi.cetak_garansi', ['log_id' => $log->id, 'nomor_unit' => $u]) }}" 
                                       target="_blank"
                                       class="inline-flex items-center gap-1 bg-white hover:bg-slate-900 text-slate-700 hover:text-white border border-slate-300 hover:border-slate-900 text-xs font-bold py-1.5 px-3 rounded-lg transition-all shadow-2xs">
                                        <span>🖨️</span>
                                        <span>QC Unit #{{ $u }}</span>
                                    </a>
                                    @endfor
                                </div>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <div class="p-6 text-center bg-gray-50 rounded-xl border border-dashed border-gray-300">
                            <p class="text-xs text-gray-400 italic">Belum ada riwayat batch produksi/rakitan untuk unit ini.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- CARD TIPS INSPEKSI TEKNISI -->
            <div class="bg-blue-50 rounded-2xl p-5 border border-blue-200">
                <h4 class="font-bold text-blue-900 text-xs uppercase tracking-wider mb-2 flex items-center gap-1.5">
                    <span>💡</span> Standar Inspeksi QC
                </h4>
                <ul class="text-xs text-blue-800 space-y-1.5 list-disc list-inside">
                    <li>Pastikan semua kabel power & front panel terpasang presisi.</li>
                    <li>Cek temperatur CPU & GPU saat booting awal (BIOS/UEFI).</li>
                    <li>Pastikan XMP/EXPO RAM sudah diaktifkan sesuai frekuensi.</li>
                    <li>Tempelkan stiker segel garansi setelah unit lulus uji.</li>
                </ul>
            </div>

        </div>

    </div>
</div>
@endsection