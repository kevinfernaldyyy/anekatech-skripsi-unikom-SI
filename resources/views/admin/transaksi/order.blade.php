@extends('layouts.admin')

@section('title', 'Data Transaksi')

@section('content')
<div class="mb-6">
    <h2 class="text-2xl font-bold text-gray-800">Antrean Pesanan</h2>
    <p class="text-sm text-gray-500">Kelola pesanan pelanggan berdasarkan tahapan prosesnya.</p>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    


    <div class="bg-white p-4 border-b border-gray-100 flex flex-wrap gap-4 items-center justify-between">
        <form action="{{ route('admin.orders.index') }}" method="GET" class="flex flex-wrap gap-3 items-center w-full">
            <input type="hidden" name="jenis" value="{{ request('jenis', 'reguler') }}">

            <div class="flex-1 min-w-[200px]">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="🔍 Cari No Transaksi / Nama Customer..." 
                       class="w-full text-sm py-2 px-3 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
            </div>

            @if(request('jenis') == 'riwayat')
                <select name="bulan" class="text-sm py-2 px-3 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 bg-gray-50">
                    @php $bulanIni = request('bulan') ?? now()->month; @endphp
                    @for($i = 1; $i <= 12; $i++)
                        <option value="{{ $i }}" {{ $bulanIni == $i ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::create()->month($i)->translatedFormat('F') }}
                        </option>
                    @endfor
                </select>

                <select name="tahun" class="text-sm py-2 px-3 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 bg-gray-50">
                    @php $tahunIni = request('tahun') ?? now()->year; @endphp
                    @for($i = now()->year; $i >= now()->year - 5; $i--)
                        <option value="{{ $i }}" {{ $tahunIni == $i ? 'selected' : '' }}>
                            {{ $i }}
                        </option>
                    @endfor
                </select>
            @endif

            <button type="submit" class="bg-gray-800 text-white px-5 py-2 rounded-lg text-sm font-bold hover:bg-gray-900 transition shadow-sm">
                Cari / Filter
            </button>

            @if(request('search') || request('bulan') || request('tahun'))
                <a href="{{ route('admin.orders.index', ['jenis' => request('jenis', 'reguler')]) }}" class="text-gray-500 hover:text-red-500 text-sm font-bold px-2 underline">
                    Reset
                </a>
            @endif
        </form>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm text-gray-600">
            <thead class="bg-gray-50 text-xs uppercase text-gray-500 border-b border-gray-100">
                <tr>
                    <th class="px-6 py-4">No Transaksi</th>
                    <th class="px-6 py-4">Customer</th>
                    <th class="px-6 py-4">Total & Bayar</th>
                    <th class="px-6 py-4">Tipe Order</th>
                    <th class="px-6 py-4 text-center">Status Tracking</th>
                    <th class="px-6 py-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($transaksis as $trx)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4">
                        <div class="font-bold text-blue-600">{{ $trx->no_transaksi }}</div>
                        <div class="text-[10px] text-gray-400 mt-1">{{ $trx->created_at->format('d M Y, H:i') }}</div>
                    </td>
                    
                    <td class="px-6 py-4">
                        <div class="font-bold text-gray-800">{{ $trx->nama_customer }}</div>
                        <div class="text-[11px] text-gray-500 flex items-center gap-1 mt-0.5">
                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                            {{ $trx->customer->perusahaan ?? 'Perorangan' }}
                        </div>
                    </td>
                    
<td class="p-4">
    <p class="font-bold text-gray-800">Rp {{ number_format($trx->total_harga ?? 0, 0, ',', '.') }}</p>
    
    @php
        // Logika Cerdas Status Validasi Admin (Support Pesanan Online & Offline)
        if ($trx->status_pesanan == 'belum_bayar') {
            $payText = 'BELUM BAYAR';
            $payClass = 'text-red-500';
        } elseif ($trx->status_pesanan == 'menunggu_proses') {

            $payText = empty($trx->bukti_pembayaran) ? 'PERLU VALIDASI ADMIN' : 'CEK BUKTI TRANSFER';
            $payClass = 'text-amber-600 text-[10px] bg-amber-50 px-2 py-0.5 rounded-md inline-block border border-amber-200'; 
        } else {
            // Jika sudah di-ACC (sedang_dirakit, selesai, dll)
            $payText = $trx->status_pembayaran == 'lunas' ? 'LUNAS (TERVERIFIKASI)' : 'DP (TERVERIFIKASI)';
            $payClass = $trx->status_pembayaran == 'lunas' ? 'text-emerald-600' : 'text-blue-600';
        }
    @endphp

    <p class="text-[10px] font-black uppercase tracking-wider {{ $payClass }} mt-1">
        {{ $payText }}
    </p>
</td>
</td>
                    
                    <td class="px-4 py-4 whitespace-nowrap">
    <div class="flex flex-col gap-1.5 items-start">
        
    
        @if($trx->is_rakit)
            <span class="bg-indigo-100 text-indigo-800 border border-indigo-200 px-2.5 py-1 rounded text-[10px] font-extrabold uppercase tracking-wide flex items-center gap-1.5 shadow-sm">
                🛠️ CUSTOM BUILD
            </span>
        @else
            <span class="bg-slate-100 text-slate-600 border border-slate-200 px-2.5 py-1 rounded text-[10px] font-bold uppercase tracking-wide flex items-center gap-1.5">
                📦 REGULER
            </span>
        @endif

        {{-- Logika Penanda Pre-Order (Jika ada) --}}
        @if(in_array($trx->status_order, ['menunggu_po', 'preorder']) || $trx->preOrder)
            <span class="bg-orange-100 text-orange-700 border border-orange-200 px-2.5 py-1 rounded text-[10px] font-bold uppercase tracking-wide flex items-center gap-1.5">
                ⏳ PRE-ORDER
            </span>
        @endif

    </div>
</td>
                    
                    <td class="px-6 py-4 text-center">
                        <!-- 👇 Tambahkan div wrapper ini biar numpuk rapi ke bawah 👇 -->
                        <div class="flex flex-col items-center justify-center gap-2"> 
                            
                            {{-- Logika Warna Badge Status (Bawaanmu) --}}
                            @php
                                $badgeClass = match($trx->status_order) {
                                    'belum_bayar' => 'bg-purple-100 text-purple-700 border-purple-200',
                                    'menunggu_proses', 'sedang_dirakit', 'process' => 'bg-blue-100 text-blue-700 border-blue-200',
                                    'menunggu_po', 'preorder' => 'bg-orange-100 text-orange-700 border-orange-200',
                                    'siap_diambil', 'sedang_dikirim' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                                    'selesai' => 'bg-green-100 text-green-700 border-green-200',
                                    'batal', 'cancel' => 'bg-red-100 text-red-700 border-red-200',
                                    default => 'bg-gray-100 text-gray-600 border-gray-200'
                                };
                            @endphp
                            
                            <span class="px-3 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest border {{ $badgeClass }}">
                                {{ str_replace('_', ' ', $trx->status_order) }}
                            </span>

                            <!-- 👇 TAMBAHAN LENCANA JEJAK RETUR 👇 -->
                            @if($trx->pengajuanRetur)
                                @if($trx->pengajuanRetur->status == 'klaim_ditolak')
                                    <span class="inline-flex items-center gap-1 bg-rose-100 text-rose-700 text-[9px] px-2 py-0.5 rounded border border-rose-300 font-black uppercase tracking-wider shadow-sm" title="Klaim retur ditolak / indikasi kecurangan">
                                        <i class="fas fa-ban text-rose-600"></i> Klaim Gugur
                                    </span>
                                @elseif($trx->pengajuanRetur->status == 'selesai_refund')
                                    <span class="inline-flex items-center gap-1 bg-emerald-100 text-emerald-700 text-[9px] px-2 py-0.5 rounded border border-emerald-300 font-black uppercase tracking-wider shadow-sm" title="Retur berhasil direfund">
                                        <i class="fas fa-hand-holding-usd text-emerald-600"></i> Refund Selesai
                                    </span>
                                @endif
                            @endif
                            <!-- 👆 SELESAI TAMBAHAN JEJAK RETUR 👆 -->

                        </div>
                    </td>
                    
                    <td class="px-6 py-4 text-center">
                        <a href="{{ route('admin.orders.show', $trx->id) }}" class="inline-flex items-center justify-center px-4 py-2 text-xs font-bold text-blue-600 bg-white border border-blue-200 rounded-lg hover:bg-blue-50 hover:text-blue-700 transition shadow-sm">
                            Detail & Aksi
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-gray-400">
                        <div class="text-4xl mb-3">📭</div>
                        <div class="font-bold text-gray-500 mb-1">Tidak Ada Pesanan</div>
                        <div>Belum ada data transaksi di kategori ini.</div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($transaksis->hasPages())
    <div class="p-4 border-t border-gray-100 bg-gray-50">
        {{ $transaksis->withQueryString()->links() }}
    </div>
    @endif
</div>
@endsection