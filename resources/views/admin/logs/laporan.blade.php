@extends('layouts.admin')

@section('title', 'Laporan Penjualan')

@section('content')
<div class="mx-auto max-w-6xl">
    
    <!-- HEADER & FILTER -->
    <div class="mb-6 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-extrabold text-slate-800">Laporan Penjualan</h2>
            <p class="text-sm text-slate-500">Ringkasan pendapatan harian dan bulanan AnekaTech.</p>
        </div>

        <!-- Filter Form -->
        <form action="{{ route('admin.laporan.penjualan') }}" method="GET" class="flex items-center gap-2 bg-white p-2 rounded-xl border border-slate-200 shadow-sm">
            <select name="bulan" class="rounded-lg border-transparent bg-slate-50 text-sm font-medium focus:border-blue-500 focus:ring-blue-500 cursor-pointer">
                @foreach(range(1, 12) as $m)
                    <option value="{{ sprintf('%02d', $m) }}" {{ $bulan == sprintf('%02d', $m) ? 'selected' : '' }}>
                        {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                    </option>
                @endforeach
            </select>
            
            <select name="tahun" class="rounded-lg border-transparent bg-slate-50 text-sm font-medium focus:border-blue-500 focus:ring-blue-500 cursor-pointer">
                @foreach(range(date('Y')-2, date('Y')) as $y)
                    <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>{{ $y }}</option>
                @endforeach
            </select>

            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white p-2 rounded-lg transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </button>
        </form>
    </div>

    <!-- SUMMARY CARDS -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <!-- Total Pendapatan -->
        <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm flex items-center gap-5 relative overflow-hidden">
            <div class="absolute -right-4 -bottom-4 opacity-10">
                <svg class="w-32 h-32 text-emerald-600" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/></svg>
            </div>
            <div class="p-4 bg-emerald-50 text-emerald-600 rounded-xl">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div>
                <p class="text-sm font-bold text-slate-400 uppercase tracking-wider">Pendapatan Bulan Ini</p>
                <h3 class="text-3xl font-extrabold text-slate-800 mt-1">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</h3>
            </div>
        </div>

        <!-- Total Transaksi -->
        <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm flex items-center gap-5 relative overflow-hidden">
            <div class="absolute -right-4 -bottom-4 opacity-10">
                <svg class="w-32 h-32 text-blue-600" fill="currentColor" viewBox="0 0 24 24"><path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/></svg>
            </div>
            <div class="p-4 bg-blue-50 text-blue-600 rounded-xl">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
            </div>
            <div>
                <p class="text-sm font-bold text-slate-400 uppercase tracking-wider">Total Transaksi</p>
                <h3 class="text-3xl font-extrabold text-slate-800 mt-1">{{ $totalTransaksi }} <span class="text-lg font-medium text-slate-500">Pesanan</span></h3>
            </div>
        </div>
    </div>

    <!-- TABEL RINCIAN HARIAN -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex justify-between items-center bg-slate-50">
            <h3 class="font-bold text-slate-700">Rincian Pendapatan Harian</h3>
            
            <!-- 👇 TOMBOL CETAK BULANAN 👇 -->
            <a href="{{ route('admin.laporan.cetak_pdf', ['bulan' => $bulan, 'tahun' => $tahun]) }}" target="_blank" class="text-xs font-bold text-blue-600 bg-blue-100 px-4 py-2 rounded-lg hover:bg-blue-200 transition-colors shadow-sm flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                Cetak Laporan Bulanan
            </a>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-white text-xs uppercase text-slate-400 border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-4 font-bold">Tanggal</th>
                        <th class="px-6 py-4 font-bold text-center">Jml Transaksi</th>
                        <th class="px-6 py-4 font-bold text-right">Pendapatan</th>
                        <th class="px-6 py-4 font-bold text-center">Aksi</th>
                    </tr>
                </thead>
                
                <!-- 👇 LOOPING HARIAN DENGAN ACCORDION ALPINE.JS 👇 -->
                @forelse($laporanHarian as $tanggal => $transaksis)
                <tbody x-data="{ openDetail: false }" class="divide-y divide-slate-100 border-b border-slate-200">
                    <!-- BARIS UTAMA HARIAN -->
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4 font-bold text-slate-700">
                            {{ \Carbon\Carbon::parse($tanggal)->translatedFormat('l, d F Y') }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="bg-slate-100 text-slate-600 font-bold px-3 py-1 rounded-full text-xs">
                                {{ $transaksis->count() }} Pesanan
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right font-extrabold text-emerald-600 text-base">
                            Rp {{ number_format($transaksis->sum('pendapatan_bersih'), 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 flex justify-center gap-2">
                            <!-- TOMBOL BUKA DETAIL -->
                            <button @click="openDetail = !openDetail" class="text-xs font-bold text-slate-500 bg-slate-100 border border-slate-200 px-3 py-1.5 rounded-lg hover:bg-slate-200 transition-colors flex items-center gap-1">
                                <span x-text="openDetail ? 'Tutup' : 'Lihat Detail'"></span>
                                <svg class="w-3 h-3 transition-transform duration-200" :class="openDetail ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </button>
                            <!-- TOMBOL CETAK HARIAN -->
                            <a href="{{ route('admin.laporan.cetak_pdf', ['tanggal' => $tanggal]) }}" target="_blank" class="text-xs font-bold text-emerald-600 bg-emerald-50 border border-emerald-200 px-3 py-1.5 rounded-lg hover:bg-emerald-100 transition-colors flex items-center gap-1" title="Cetak Hari Ini">
                                <i class="fas fa-print"></i> Cetak Harian
                            </a>
                        </td>
                    </tr>

                    <!-- BARIS RINCIAN (MUNCUL KALAU TOMBOL DETAIL DIKLIK) -->
                    <tr x-show="openDetail" x-transition class="bg-slate-50/50">
                        <td colspan="4" class="px-6 py-4">
                            <div class="bg-white border border-slate-200 rounded-xl p-4 shadow-inner">
                                <h4 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-3 border-b border-slate-100 pb-2">Rincian Barang Terjual</h4>
                                
                                <div class="space-y-4">
                                    @foreach($transaksis as $trx)
                                        <div class="border-b border-slate-100 last:border-0 pb-4 last:pb-0">
                                            
                                            <!-- Header TRX -->
                                            <div class="flex justify-between items-center mb-3">
                                                <span class="text-[10px] font-extrabold text-blue-500 bg-blue-50 px-2 py-1 rounded-md uppercase tracking-wider">
                                                    {{ $trx->kode_transaksi ?? 'TRX-'.$trx->id }}
                                                </span>
                                                <span class="text-[10px] font-bold text-emerald-600 bg-emerald-50 px-2 py-1 rounded-md">
                                                    Pendapatan Bersih: Rp {{ number_format($trx->pendapatan_bersih, 0, ',', '.') }}
                                                </span>
                                            </div>
                                            
                                            <!-- List Item -->
                                            <ul class="space-y-2 mb-2">
                                                @foreach($trx->details as $item)
                                                <li class="flex justify-between items-center text-sm">
                                                    <div class="flex items-center gap-3">
                                                        <div class="bg-slate-100 text-slate-600 font-bold px-2 py-1 rounded text-xs">{{ $item->qty }}x</div>
                                                        <span class="font-medium text-slate-700">{{ $item->barang->nama_barang ?? 'Barang Dihapus' }}</span>
                                                    </div>
                                                    <div class="text-right">
                                                        <div class="text-xs text-slate-400">@ Rp {{ number_format($item->harga_satuan, 0, ',', '.') }}</div>
                                                        <div class="font-bold text-slate-800">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</div>
                                                    </div>
                                                </li>
                                                @endforeach
                                            </ul>

                                            <!-- 👇 KETERANGAN POTONGAN (MUNCUL OTOMATIS) 👇 -->
                                            @if($trx->ongkos_kirim > 0 || ($trx->pengajuanRetur && $trx->pengajuanRetur->nominal_refund))
                                                <div class="bg-slate-50 border border-slate-100 rounded-lg p-2.5 mt-3 space-y-1.5 shadow-sm">
                                                    @if($trx->ongkos_kirim > 0)
                                                        <p class="text-[10px] text-slate-500 font-medium italic flex items-center gap-1.5">
                                                            <i class="fas fa-truck text-slate-400"></i>
                                                            * Telah dipotong ongkos kirim senilai Rp {{ number_format($trx->ongkos_kirim, 0, ',', '.') }}
                                                        </p>
                                                    @endif
                                                    
                                                    @if($trx->pengajuanRetur && $trx->pengajuanRetur->nominal_refund)
                                                        <p class="text-[10px] text-rose-500 font-bold italic flex items-center gap-1.5">
                                                            <i class="fas fa-exclamation-circle"></i>
                                                            * Klaim Retur: Pengembalian dana senilai Rp {{ number_format($trx->pengajuanRetur->nominal_refund, 0, ',', '.') }}
                                                        </p>
                                                    @endif
                                                </div>
                                            @endif
                                            
                                        </div>
                                    @endforeach
                                </div>
                        </td>
                    </tr>
                </tbody>
                @empty
                <!-- Tampilan Empty State tetap sama -->
                @endforelse
            </table>
        </div>
    </div>

</div>
@endsection