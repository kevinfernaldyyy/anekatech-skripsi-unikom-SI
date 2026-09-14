@extends('layouts.teknisi')

@section('content')
<div class="p-6 bg-slate-50 min-h-screen">
    <div class="flex justify-between items-center mb-6">
    @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl shadow-sm flex items-center gap-3">
            <span class="text-xl">✅</span>
            <span class="font-bold text-sm">{{ session('success') }}</span>
        </div>
    @endif
        <div>
            <h2 class="text-2xl font-bold text-slate-800">Tugas Rakitan Saya</h2>
            <p class="text-sm text-slate-500">Daftar pesanan Custom Build yang menunggu untuk dirakit.</p>
        </div>
    </div>
    <div class="mb-6 bg-white p-4 rounded-xl shadow-sm border border-slate-200">
        <form action="{{ route('teknisi.history') }}" method="GET" class="flex flex-col sm:flex-row gap-3">
            
            <div class="flex-1">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="🔍 Cari No. Pesanan atau Nama Customer..." 
                       class="w-full text-sm border-slate-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 py-2.5">
            </div>
            
            <div class="w-full sm:w-48">
                <select name="bulan" class="w-full text-sm border-slate-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 py-2.5">
                    <option value="all" {{ $bulan == 'all' ? 'selected' : '' }}>Semua Waktu</option>
                    <option value="01" {{ $bulan == '01' ? 'selected' : '' }}>Januari</option>
                    <option value="02" {{ $bulan == '02' ? 'selected' : '' }}>Februari</option>
                    <option value="03" {{ $bulan == '03' ? 'selected' : '' }}>Maret</option>
                    <option value="04" {{ $bulan == '04' ? 'selected' : '' }}>April</option>
                    <option value="05" {{ $bulan == '05' ? 'selected' : '' }}>Mei</option>
                    <option value="06" {{ $bulan == '06' ? 'selected' : '' }}>Juni</option>
                    <option value="07" {{ $bulan == '07' ? 'selected' : '' }}>Juli</option>
                    <option value="08" {{ $bulan == '08' ? 'selected' : '' }}>Agustus</option>
                    <option value="09" {{ $bulan == '09' ? 'selected' : '' }}>September</option>
                    <option value="10" {{ $bulan == '10' ? 'selected' : '' }}>Oktober</option>
                    <option value="11" {{ $bulan == '11' ? 'selected' : '' }}>November</option>
                    <option value="12" {{ $bulan == '12' ? 'selected' : '' }}>Desember</option>
                </select>
            </div>
            
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-lg font-bold text-sm transition-colors shadow-sm">
                Filter Data
            </button>
            
            @if(request()->has('search') || request()->has('bulan'))
                <a href="{{ route('teknisi.history') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-600 px-5 py-2.5 rounded-lg font-bold text-sm transition-colors text-center shadow-sm">
                    Reset
                </a>
            @endif
            
        </form>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-100 text-slate-600 text-[10px] uppercase tracking-wider">
                        <th class="p-4 font-bold">No. Pesanan</th>
                        <th class="p-4 font-bold">Customer</th>
                        <th class="p-4 font-bold">Status</th>
                        <th class="p-4 font-bold">Waktu Tugas</th>
                        <th class="p-4 text-center font-bold">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    @forelse($transaksis as $trx)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="p-4 font-bold text-blue-600">{{ $trx->no_transaksi }}</td>
                            <td class="p-4">
                                <p class="font-bold text-slate-800">{{ $trx->nama_customer }}</p>
                                <p class="text-xs text-slate-500">{{ $trx->customer->nomor_telepon ?? '-' }}</p>
                            </td>
                            <td class="p-4">
                                @if($trx->status_order == 'siap_diambil')
                                    <span class="bg-blue-100 text-blue-700 px-2.5 py-1 rounded text-[10px] font-bold uppercase tracking-wide flex items-center gap-1.5 w-max">
                                        📦 Siap Diambil / Kirim
                                    </span>
                                @elseif($trx->status_order == 'sedang_dikirim')
                                    <span class="bg-purple-100 text-purple-700 px-2.5 py-1 rounded text-[10px] font-bold uppercase tracking-wide flex items-center gap-1.5 w-max">
                                        🚚 Sedang Dikirim
                                    </span>
                                @elseif($trx->status_order == 'selesai')
                                    <span class="bg-green-100 text-green-700 px-2.5 py-1 rounded text-[10px] font-bold uppercase tracking-wide flex items-center gap-1.5 w-max">
                                        ✅ Selesai
                                    </span>
                                @else
                                    <span class="bg-gray-100 text-gray-700 px-2.5 py-1 rounded text-[10px] font-bold uppercase tracking-wide flex items-center gap-1.5 w-max">
                                        {{ str_replace('_', ' ', strtoupper($trx->status_order)) }}
                                    </span>
                                @endif
                            </td>
                            <td class="p-4 text-slate-500 text-xs">
                                {{ $trx->updated_at->diffForHumans() }}
                            </td>
                            <td class="p-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('teknisi.orders.show', $trx->id) }}" class="bg-slate-100 hover:bg-slate-200 text-slate-700 px-3 py-1.5 rounded-md text-xs font-bold transition-colors border border-slate-200 shadow-sm">
                                        Lihat Detail
                                    </a>
                                    
                                    @if($trx->dokumenQc && $trx->dokumenQc->file_pdf)
                                        <a href="{{ asset('storage/' . $trx->dokumenQc->file_pdf) }}" target="_blank" class="bg-red-50 text-red-600 hover:bg-red-100 px-3 py-1.5 rounded-md text-xs font-bold transition-colors border border-red-200 shadow-sm flex items-center gap-1">
                                            <span>📄</span> Sertifikat QC
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                    <p class="font-bold">Belum ada tugas rakitan.</p>
                    <p class="text-xs">Silakan bersantai sambil menunggu tugas dari Admin.</p>
                        <tr>
                            <td colspan="5" class="p-8 text-center text-slate-400">
                                <div class="text-4xl mb-3">☕</div>
                                <p class="font-bold">Belum ada tugas rakitan.</p>
                                <p class="text-xs">Silakan bersantai sambil menunggu tugas dari Admin.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($transaksis->hasPages())
            <div class="p-4 border-t border-slate-100 bg-slate-50">
                {{ $transaksis->links() }}
            </div>
        @endif
    </div>
</div>
@endsection