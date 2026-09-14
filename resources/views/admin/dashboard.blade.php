@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
    <!-- HEADER & REAL-TIME CLOCK -->
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between" x-data="{ time: new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' }) }" x-init="setInterval(() => time = new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' }), 1000)">
        <div>
            <h2 class="text-2xl font-black text-slate-800 tracking-tight">Dashboard Ringkasan</h2>
            <p class="text-sm text-slate-500 mt-1">Pantau performa penjualan, stok, dan operasional DiabloTech.</p>
        </div>
        
        <div class="flex items-center gap-4">
            <!-- Live Clock -->
            <div class="bg-white border border-slate-200 shadow-sm px-4 py-2 rounded-xl flex items-center gap-3">
                <i class="fas fa-clock text-blue-500 animate-pulse"></i>
                <span class="font-black text-slate-700 tracking-widest" x-text="time"></span>
                <span class="text-xs font-bold text-slate-400 border-l border-slate-200 pl-3 uppercase">{{ \Carbon\Carbon::now()->translatedFormat('d M Y') }}</span>
            </div>
        </div>
    </div>

    <!-- 4 METRIC CARDS UTAMA -->
    <div class="grid grid-cols-1 gap-5 md:grid-cols-2 xl:grid-cols-4 mb-6">
        <!-- Card 1: Pesanan -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200 hover:-translate-y-1 transition-transform duration-300 group">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 flex items-center justify-center rounded-xl bg-blue-50 text-blue-600 group-hover:bg-blue-600 group-hover:text-white transition-colors shadow-inner">
                    <i class="fas fa-shopping-cart text-xl"></i>
                </div>
                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 bg-slate-100 px-2 py-1 rounded-md">Bulan Ini</span>
            </div>
            <div>
                <h4 class="text-3xl font-black text-slate-800 mb-1">{{ $pesananBaru }}</h4>
                <p class="text-sm font-bold text-slate-500">Total Pesanan</p>
            </div>
        </div>

        <!-- Card 2: Pendapatan Bersih -->
      <!-- Card 2: Pendapatan Bersih -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200 hover:-translate-y-1 transition-transform duration-300 group relative overflow-hidden">
            <div class="absolute -right-4 -top-4 opacity-5"><i class="fas fa-money-bill-wave text-8xl"></i></div>
            <div class="flex items-center justify-between mb-4 relative z-10">
                <div class="w-12 h-12 flex items-center justify-center rounded-xl bg-emerald-50 text-emerald-600 group-hover:bg-emerald-600 group-hover:text-white transition-colors shadow-inner">
                    <i class="fas fa-wallet text-xl"></i>
                </div>
                <span class="text-[10px] font-bold uppercase tracking-wider text-emerald-600 bg-emerald-50 border border-emerald-100 px-2 py-1 rounded-md">Selesai Saja</span>
            </div>
            <div class="relative z-10">
                <h4 class="text-2xl font-black text-slate-800 mb-1">Rp {{ number_format($pendapatanBulanIni, 0, ',', '.') }}</h4>
                <p class="text-sm font-bold text-slate-500">Pendapatan Bersih</p>
                
                <!-- 👇 TAMBAHKAN KODE INI BOS 👇 -->
                @if(isset($totalRefund) && $totalRefund > 0)
                    <p class="text-[10px] font-bold text-rose-500 mt-1.5 flex items-center gap-1">
                        <i class="fas fa-minus-circle"></i> Potongan Retur: Rp {{ number_format($totalRefund, 0, ',', '.') }}
                    </p>
                @endif
                <!-- 👆 BATAS TAMBAHAN 👆 -->
                
            </div>
        </div>

        <!-- Card 3: Proses Rakit -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200 hover:-translate-y-1 transition-transform duration-300 group">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 flex items-center justify-center rounded-xl bg-amber-50 text-amber-600 group-hover:bg-amber-600 group-hover:text-white transition-colors shadow-inner">
                    <i class="fas fa-tools text-xl"></i>
                </div>
                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 bg-slate-100 px-2 py-1 rounded-md">Aktif</span>
            </div>
            <div>
                <h4 class="text-3xl font-black text-slate-800 mb-1">{{ $prosesRakit }}</h4>
                <p class="text-sm font-bold text-slate-500">Antrean Rakit & QC</p>
            </div>
        </div>

        <!-- Card 4: Total Pelanggan -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200 hover:-translate-y-1 transition-transform duration-300 group">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 flex items-center justify-center rounded-xl bg-purple-50 text-purple-600 group-hover:bg-purple-600 group-hover:text-white transition-colors shadow-inner">
                    <i class="fas fa-users text-xl"></i>
                </div>
                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 bg-slate-100 px-2 py-1 rounded-md">Total</span>
            </div>
            <div>
                <h4 class="text-3xl font-black text-slate-800 mb-1">{{ $totalPelanggan }}</h4>
                <p class="text-sm font-bold text-slate-500">Pelanggan Terdaftar</p>
            </div>
        </div>
    </div>

    <!-- GRID LAYOUT TENGAH: Transaksi Terbaru & Stok Menipis -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        
        <!-- TRANSAKSI TERBARU (Kiri, ambil porsi lebih besar) -->
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden flex flex-col">
            <div class="border-b border-slate-100 px-6 py-5 flex justify-between items-center bg-slate-50/50">
                <h3 class="font-black text-slate-800 text-base flex items-center gap-2">
                    <i class="fas fa-bolt text-blue-500"></i> Aktivitas Transaksi Terbaru
                </h3>
                <!-- 👇 URL sudah diperbaiki ke parameter jenis=riwayat 👇 -->
                <a href="{{ route('admin.orders.index', ['jenis' => 'riwayat']) }}" class="text-xs font-bold text-blue-600 hover:text-blue-800 bg-blue-50 hover:bg-blue-100 px-4 py-2 rounded-lg transition-colors">
                    Lihat Riwayat <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
            
            <div class="overflow-x-auto flex-1">
                <table class="w-full text-left border-collapse min-w-[500px]">
                    <thead>
                        <tr class="bg-white text-slate-400 text-[10px] uppercase tracking-widest border-b border-slate-100">
                            <th class="px-6 py-4 font-bold">No. Transaksi</th>
                            <th class="px-6 py-4 font-bold">Pelanggan</th>
                            <th class="px-6 py-4 font-bold text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50 text-sm">
                        @forelse($aktivitasTerbaru as $trx)
                            @php
                                $status = strtolower($trx->status_order ?? 'pending');
                            @endphp
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-4">
                                    <p class="font-black text-slate-800">{{ $trx->no_transaksi }}</p>
                                    <p class="text-[11px] font-bold text-blue-600 mt-1">Rp {{ number_format($trx->total_harga ?? 0, 0, ',', '.') }}</p>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-slate-100 text-slate-600 flex items-center justify-center font-black text-xs shrink-0">
                                            {{ strtoupper(substr($trx->nama_customer, 0, 1)) }}
                                        </div>
                                        <div>
                                            <p class="font-bold text-slate-800 text-sm">{{ $trx->nama_customer }}</p>
                                            <p class="text-[10px] font-medium text-slate-400 mt-0.5">{{ $trx->created_at->diffForHumans() }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if(in_array($status, ['selesai', 'completed', 'diterima_kustomer']))
                                        <span class="inline-flex items-center gap-1.5 bg-emerald-100 text-emerald-700 text-[9px] font-black px-2 py-1 rounded uppercase tracking-wider">Selesai</span>
                                    @elseif(in_array($status, ['batal', 'cancel', 'dikembalikan']))
                                        <span class="inline-flex items-center gap-1.5 bg-rose-100 text-rose-700 text-[9px] font-black px-2 py-1 rounded uppercase tracking-wider">Batal / Retur</span>
                                    @elseif(in_array($status, ['sedang_dirakit', 'process', 'diproses']))
                                        <span class="inline-flex items-center gap-1.5 bg-amber-100 text-amber-700 text-[9px] font-black px-2 py-1 rounded uppercase tracking-wider">Dirakit</span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 bg-blue-100 text-blue-700 text-[9px] font-black px-2 py-1 rounded uppercase tracking-wider">{{ str_replace('_', ' ', $status) }}</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-6 py-12 text-center text-slate-400 text-sm font-bold">
                                    <i class="fas fa-inbox text-3xl mb-3 block text-slate-200"></i> Belum ada transaksi.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- STOK MENIPIS (Kanan) -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden flex flex-col">
            <div class="border-b border-slate-100 px-6 py-5 flex justify-between items-center bg-rose-50/30">
                <h3 class="font-black text-rose-600 text-base flex items-center gap-2">
                    <i class="fas fa-exclamation-triangle"></i> Peringatan Stok
                </h3>
                <span class="bg-rose-500 text-white text-[10px] font-black px-2 py-0.5 rounded-full">{{ $countStokMenipis }} Item</span>
            </div>
            
            <div class="p-4 flex-1 overflow-y-auto max-h-[320px]">
                @if($countStokMenipis > 0)
                    <div class="space-y-3">
                        @foreach($stokMenipis as $barang)
                            <div class="flex items-center justify-between p-3 border border-rose-100 bg-rose-50/50 rounded-xl hover:bg-rose-50 transition-colors">
                                <div class="pr-3">
                                    <p class="text-xs font-bold text-slate-800 line-clamp-2 leading-tight">{{ $barang->nama_barang }}</p>
                                    <p class="text-[10px] font-bold text-slate-400 mt-1 uppercase tracking-wider">{{ $barang->kategori }}</p>
                                </div>
                                <div class="text-center shrink-0">
                                    <span class="block text-lg font-black text-rose-600 leading-none">{{ $barang->stok }}</span>
                                    <span class="text-[9px] font-bold text-rose-400 uppercase">Sisa</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="h-full flex flex-col items-center justify-center text-slate-400 py-10">
                        <i class="fas fa-check-circle text-4xl mb-3 text-emerald-200"></i>
                        <p class="text-sm font-bold text-slate-500">Stok Aman Terkendali</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- GRID LAYOUT BAWAH: Barang Paling Laku & Kurang Laku -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        
        <!-- BARANG PALING LAKU -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="border-b border-slate-100 px-6 py-4 bg-emerald-50/30">
                <h3 class="font-black text-emerald-700 text-sm flex items-center gap-2">
                    <i class="fas fa-fire"></i> Top 5 Barang Terlaris
                </h3>
            </div>
            <div class="p-2">
                <ul class="divide-y divide-slate-50">
                    @forelse($barangLaris as $index => $item)
                        <li class="flex items-center justify-between p-3 hover:bg-slate-50 rounded-xl transition-colors">
                            <div class="flex items-center gap-3">
                                <span class="w-6 h-6 flex items-center justify-center rounded-full {{ $index == 0 ? 'bg-amber-100 text-amber-600' : 'bg-slate-100 text-slate-500' }} font-black text-xs">
                                    {{ $index + 1 }}
                                </span>
                                <div>
                                    <p class="text-xs font-bold text-slate-800 line-clamp-1">{{ $item->nama_barang }}</p>
                                    <p class="text-[10px] text-slate-400 font-medium mt-0.5">Sisa Gudang: <b class="text-slate-600">{{ $item->stok }}</b></p>
                                </div>
                            </div>
                            <span class="bg-emerald-100 text-emerald-700 font-black text-[10px] px-2.5 py-1 rounded-md whitespace-nowrap">
                                {{ $item->total_terjual }} Terjual
                            </span>
                        </li>
                    @empty
                        <li class="p-6 text-center text-xs font-bold text-slate-400">Belum ada data penjualan.</li>
                    @endforelse
                </ul>
            </div>
        </div>

        <!-- BARANG KURANG LAKU -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="border-b border-slate-100 px-6 py-4 bg-slate-50/50">
                <h3 class="font-black text-slate-600 text-sm flex items-center gap-2">
                    <i class="fas fa-snowflake"></i> Top 5 Barang Kurang Laku
                </h3>
            </div>
            <div class="p-2">
                <ul class="divide-y divide-slate-50">
                    @forelse($barangKurangLaku as $index => $item)
                        <li class="flex items-center justify-between p-3 hover:bg-slate-50 rounded-xl transition-colors">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-slate-100 text-slate-400 flex items-center justify-center text-lg shrink-0">
                                    <i class="fas fa-box"></i>
                                </div>
                                <div>
                                    <p class="text-xs font-bold text-slate-800 line-clamp-1">{{ $item->nama_barang }}</p>
                                    <p class="text-[10px] text-slate-400 font-medium mt-0.5">Numpuk di Gudang: <b class="text-rose-500">{{ $item->stok }}</b></p>
                                </div>
                            </div>
                            <span class="bg-slate-100 text-slate-500 font-black text-[10px] px-2.5 py-1 rounded-md whitespace-nowrap">
                                Laku {{ $item->total_terjual }}
                            </span>
                        </li>
                    @empty
                        <li class="p-6 text-center text-xs font-bold text-slate-400">Belum ada data barang.</li>
                    @endforelse
                </ul>
            </div>
        </div>

    </div>
@endsection