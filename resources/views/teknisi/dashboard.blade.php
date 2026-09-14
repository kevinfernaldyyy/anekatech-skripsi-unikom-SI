@extends('layouts.teknisi')

@section('content')
<div class="p-6 bg-slate-50 min-h-screen">
    <div class="flex justify-between items-end mb-6">
        <div>
            <h2 class="text-2xl font-black text-slate-800 tracking-tight">Halo, {{ Auth::user()->name }}! 👋</h2>
            <p class="text-sm text-slate-500 mt-1">Berikut adalah ringkasan performa perakitanmu di bulan <strong class="text-blue-600">{{ $namaBulan }}</strong>.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white p-6 rounded-xl shadow-sm border border-l-4 border-slate-200 border-l-amber-500 flex items-center justify-between transition-transform hover:-translate-y-1">
            <div>
                <p class="text-xs text-slate-500 font-bold uppercase tracking-widest mb-1">Perlu Dirakit</p>
                <h3 class="text-3xl font-black text-slate-800">{{ $tugasAktif }} <span class="text-sm font-medium text-slate-400">Unit</span></h3>
            </div>
            <div class="w-12 h-12 rounded-full bg-amber-50 flex items-center justify-center text-amber-500 text-xl shadow-inner">⏳</div>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-sm border border-l-4 border-slate-200 border-l-blue-500 flex items-center justify-between transition-transform hover:-translate-y-1">
            <div>
                <p class="text-xs text-slate-500 font-bold uppercase tracking-widest mb-1">Selesai Bulan Ini</p>
                <h3 class="text-3xl font-black text-slate-800">{{ $selesaiBulanIni }} <span class="text-sm font-medium text-slate-400">Unit</span></h3>
            </div>
            <div class="w-12 h-12 rounded-full bg-blue-50 flex items-center justify-center text-blue-500 text-xl shadow-inner">📈</div>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-sm border border-l-4 border-slate-200 border-l-green-500 flex items-center justify-between transition-transform hover:-translate-y-1">
            <div>
                <p class="text-xs text-slate-500 font-bold uppercase tracking-widest mb-1">Total Diselesaikan</p>
                <h3 class="text-3xl font-black text-slate-800">{{ $selesaiTotal }} <span class="text-sm font-medium text-slate-400">Unit</span></h3>
            </div>
            <div class="w-12 h-12 rounded-full bg-green-50 flex items-center justify-center text-green-500 text-xl shadow-inner">🏆</div>
        </div>
    </div>
    
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="px-5 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-800">
                <h3 class="font-bold text-white text-sm flex items-center gap-2">🔥 Prioritas Rakitan Saat Ini</h3>
                <a href="{{ route('teknisi.orders.index') }}" class="text-[10px] bg-slate-700 text-slate-300 px-2 py-1 rounded hover:bg-slate-600">Lihat Semua</a>
            </div>
            <div class="p-0">
                <ul class="divide-y divide-slate-100">
                    @forelse($antreanTerbaru as $trx)
                        <li class="p-4 hover:bg-slate-50 flex justify-between items-center">
                            <div>
                                <p class="text-sm font-bold text-blue-600">{{ $trx->no_transaksi }}</p>
                                <p class="text-[10px] text-slate-500 mt-0.5">Pelanggan: {{ $trx->nama_customer }}</p>
                            </div>
                            <a href="{{ route('teknisi.orders.show', $trx->id) }}" class="bg-blue-100 text-blue-700 hover:bg-blue-600 hover:text-white px-3 py-1.5 rounded-md text-xs font-bold transition-colors">
                                Rakit &rarr;
                            </a>
                        </li>
                    @empty
                        <li class="p-6 text-center text-slate-400 text-sm">
                            🎉 Yeay! Tidak ada antrean rakitan saat ini.
                        </li>
                    @endforelse
                </ul>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="px-5 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50">
                <h3 class="font-bold text-slate-700 text-sm flex items-center gap-2">✅ Baru Saja Diselesaikan</h3>
                <a href="{{ route('teknisi.history') }}" class="text-[10px] bg-white border border-slate-200 text-slate-500 px-2 py-1 rounded hover:bg-slate-100">Riwayat Penuh</a>
            </div>
            <div class="p-0">
                <ul class="divide-y divide-slate-100">
                    @forelse($riwayatTerbaru as $trx)
                        <li class="p-4 hover:bg-slate-50 flex justify-between items-center">
                            <div>
                                <p class="text-sm font-bold text-slate-700">{{ $trx->no_transaksi }}</p>
                                <p class="text-[10px] text-slate-500 mt-0.5">Selesai: {{ $trx->updated_at->diffForHumans() }}</p>
                            </div>
                            <span class="text-green-600 font-bold text-[10px] uppercase tracking-wider bg-green-50 px-2 py-1 rounded border border-green-100">
                                Selesai
                            </span>
                        </li>
                    @empty
                        <li class="p-6 text-center text-slate-400 text-sm">
                            Belum ada PC yang diselesaikan bulan ini.
                        </li>
                    @endforelse
                </ul>
            </div>
        </div>

    </div>
</div>
@endsection