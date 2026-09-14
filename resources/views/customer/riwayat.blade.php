@extends('layouts.customer')
@section('title', 'Riwayat Pesanan | DiabloTech')

@section('content')
<div class="max-w-4xl mx-auto pb-10">

    <!-- HEADER DASHBOARD PERSONAL & JAM REALTIME -->
    <div class="bg-gradient-to-r from-slate-900 to-indigo-900 rounded-3xl p-6 sm:p-8 mb-6 text-white shadow-xl relative overflow-hidden">
        <div class="absolute -top-24 -right-24 text-indigo-500/20"><i class="fas fa-microchip text-[200px]"></i></div>
        <div class="relative z-10 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-6">
            <div>
                <p class="text-indigo-200 text-sm font-bold tracking-widest uppercase mb-1 flex items-center gap-2">
                    <span id="greeting">Selamat Datang</span>, {{ Auth::user()->name ?? 'Gamers' }}
                </p>
                <h1 class="text-3xl sm:text-4xl font-black tracking-tight">Dashboard Pesanan</h1>
                <p class="text-slate-300 mt-2 text-sm max-w-md">Pantau perakitan PC rakitan impianmu dan riwayat transaksi dengan mudah.</p>
            </div>
            <div class="bg-white/10 backdrop-blur-sm border border-white/20 rounded-2xl p-4 min-w-[180px] text-center sm:text-right shadow-inner">
                <p class="text-[10px] text-indigo-200 font-bold uppercase tracking-widest mb-1" id="current-date">-- --- ----</p>
                <div class="text-2xl font-black font-mono tracking-wider flex items-center justify-center sm:justify-end gap-1" id="live-clock">
                    <span>00</span><span class="animate-pulse">:</span><span>00</span><span class="animate-pulse">:</span><span>00</span>
                </div>
                <p class="text-[10px] text-indigo-300 font-medium mt-1">Waktu Server (WIB)</p>
            </div>
        </div>
    </div>

    <!-- BANNER CALL-TO-ACTION (CTA) -->
    <div class="bg-gradient-to-r from-blue-600 to-indigo-700 rounded-2xl p-6 sm:p-8 mb-6 text-white shadow-lg shadow-indigo-200 flex flex-col sm:flex-row items-center justify-between gap-6 relative overflow-hidden group cursor-pointer" onclick="window.location.href='{{ route('customer.katalog') }}'">
        <div class="absolute inset-0 bg-white opacity-0 group-hover:opacity-10 transition-opacity duration-300"></div>
        <div class="absolute -right-10 -top-10 w-40 h-40 bg-white/10 blur-3xl rounded-full group-hover:bg-white/20 transition-all duration-500"></div>
        <div class="relative z-10 text-center sm:text-left">
            <h2 class="text-xl sm:text-2xl font-black mb-2">Rakit PC Impianmu Sekarang! 🚀</h2>
            <p class="text-indigo-100 text-sm max-w-lg">Eksplorasi katalog komponen PC terlengkap. Gunakan layanan teknisi kami untuk perakitan langsung di rumah Anda.</p>
        </div>
        <div class="relative z-10 shrink-0">
            <span class="inline-flex items-center gap-2 bg-white text-indigo-700 hover:bg-slate-50 font-bold px-6 py-3 rounded-xl transition-transform transform group-hover:scale-105 shadow-md">
                Eksplor Katalog <i class="fas fa-arrow-right"></i>
            </span>
        </div>
    </div>

    <!-- STATISTIK RINGKAS -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
        <div class="bg-white rounded-2xl p-5 border border-slate-200/60 shadow-sm flex items-center gap-4 hover:shadow-md transition-shadow">
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-500 flex items-center justify-center text-xl shrink-0"><i class="fas fa-wallet"></i></div>
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-0.5">Total Belanja</p>
                <h3 class="text-xl font-black text-slate-800">Rp {{ number_format($totalBelanja ?? 0, 0, ',', '.') }}</h3>
            </div>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-slate-200/60 shadow-sm flex items-center gap-4 hover:shadow-md transition-shadow">
            <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-500 flex items-center justify-center text-xl shrink-0"><i class="fas fa-box-open"></i></div>
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-0.5">Pesanan Aktif</p>
                <h3 class="text-xl font-black text-slate-800">{{ $pesananAktif ?? 0 }} Transaksi</h3>
            </div>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-slate-200/60 shadow-sm flex items-center gap-4 hover:shadow-md transition-shadow">
            <div class="w-12 h-12 rounded-xl bg-purple-50 text-purple-500 flex items-center justify-center text-xl shrink-0"><i class="fas fa-cubes"></i></div>
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-0.5">Total Item Dibeli</p>
                <h3 class="text-xl font-black text-slate-800">{{ $totalItem ?? 0 }} Unit</h3>
            </div>
        </div>
    </div>

    <div class="flex items-center gap-3 mb-6">
        <div class="w-10 h-10 rounded-xl bg-slate-900 text-white flex items-center justify-center shadow-md"><i class="fas fa-history"></i></div>
        <div>
            <h2 class="text-lg font-black text-slate-800 leading-tight">Riwayat Pesanan Selesai</h2>
            <p class="text-xs text-slate-500 font-medium">Daftar transaksi yang sudah selesai atau dibatalkan.</p>
        </div>
    </div>

    <div class="space-y-4">
        @forelse($transaksis as $trx)
            @php
                $status = strtolower($trx->status_order);
                $badgeClass = match($status) {
                    'selesai', 'completed', 'diterima_kustomer', 'retur_selesai', 'retur_diterima' => 'bg-emerald-100 text-emerald-700',
                    'batal', 'cancel', 'retur_ditolak' => 'bg-red-100 text-red-700',
                    default => 'bg-slate-100 text-slate-700'
                };
            @endphp

            <!-- TAMPILAN RIWAYAT (Marketplace Style) -->
            <div class="bg-white border border-slate-200 rounded-xl mb-4 shadow-sm hover:shadow-md transition-shadow duration-200">
                
                <!-- Header Card -->
                <div class="px-4 py-3 border-b border-slate-100 flex flex-wrap items-center justify-between bg-slate-50/50 rounded-t-xl gap-2">
                    <div class="flex items-center gap-2 sm:gap-3">
                        <i class="fas fa-shopping-bag text-indigo-500 text-sm"></i>
                        <span class="text-xs font-bold text-slate-700">{{ \Carbon\Carbon::parse($trx->tgl_transaksi ?? $trx->created_at)->translatedFormat('d M Y') }}</span>
                        <span class="bg-slate-200 text-slate-600 text-[10px] font-bold px-2 py-0.5 rounded-md tracking-wider">{{ $trx->no_transaksi }}</span>
                    </div>
                    <div>
                        <span class="px-2.5 py-1 rounded-md text-[10px] font-black uppercase tracking-wider shadow-sm {{ $badgeClass }}">
                            {{ str_replace('_', ' ', $status) }}
                        </span>
                    </div>
                </div>

                <!-- Body Card -->
                <div class="p-4 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-5">
                    @php
                        $firstDetail = $trx->details->first();
                        $otherItemsCount = $trx->details->count() - 1;
                    @endphp

                    <div class="flex items-start gap-4 flex-1">
                        <div class="w-16 h-16 bg-slate-100 rounded-xl overflow-hidden border border-slate-200 flex-shrink-0 flex items-center justify-center shadow-inner">
                            @if($firstDetail && $firstDetail->barang && $firstDetail->barang->gambar)
                                <img src="{{ asset('storage/' . $firstDetail->barang->gambar) }}" alt="Produk" class="w-full h-full object-contain mix-blend-multiply p-1">
                            @else
                                <i class="fas fa-box-open text-slate-300 text-2xl"></i>
                            @endif
                        </div>
                        
                        <div>
                            <h4 class="text-sm font-bold text-slate-800 line-clamp-1 hover:text-indigo-600 transition-colors cursor-pointer">
                                {{ $firstDetail->barang->nama_barang ?? $firstDetail->nama_barang_custom ?? 'Item Pesanan' }}
                            </h4>
                            <p class="text-xs text-slate-500 mt-1 font-medium">
                                {{ $firstDetail->qty ?? 1 }} barang x Rp {{ number_format($firstDetail->harga ?? $firstDetail->harga_jual ?? 0, 0, ',', '.') }}
                            </p>
                            @if($otherItemsCount > 0)
                                <p class="text-[10px] text-slate-400 mt-1.5 font-bold bg-slate-100 px-2 py-0.5 rounded-md inline-block">
                                    + {{ $otherItemsCount }} produk lainnya
                                </p>
                            @endif
                        </div>
                    </div>

                    <div class="hidden sm:block h-12 w-px bg-slate-200 mx-2"></div>

                    <div class="flex flex-row sm:flex-col items-center sm:items-end justify-between w-full sm:w-auto border-t sm:border-0 border-slate-100 pt-3 sm:pt-0 mt-2 sm:mt-0">
                        <div class="text-left sm:text-right">
                            <p class="text-[10px] text-slate-500 font-bold uppercase tracking-wider mb-0.5">Total Belanja</p>
                            <p class="text-sm font-black text-indigo-600">Rp {{ number_format($trx->total_harga, 0, ',', '.') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Footer Card -->
                <div class="px-4 py-3 border-t border-slate-100 flex flex-wrap items-center justify-end gap-3 bg-slate-50/30 rounded-b-xl">
                    <a href="{{ route('customer.orders.show', $trx->id) }}" class="bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 text-[11px] font-bold px-5 py-2 rounded-lg transition-all shadow-sm flex items-center gap-2">
                        Detail Transaksi
                    </a>
                    <a href="{{ route('customer.katalog') }}" class="bg-indigo-50 text-indigo-700 border border-indigo-200 hover:bg-indigo-600 hover:text-white text-[11px] font-bold px-4 py-2 rounded-lg transition-all shadow-sm">
                        Beli Lagi
                    </a>
                </div>
            </div>
        @empty
            <div class="text-center py-24 bg-white rounded-3xl border border-slate-200/60 shadow-sm">
                <div class="relative w-24 h-24 mx-auto mb-6">
                    <div class="absolute inset-0 bg-indigo-100 rounded-full animate-ping opacity-50"></div>
                    <div class="relative w-full h-full bg-indigo-50 rounded-full flex items-center justify-center text-4xl text-indigo-500 shadow-inner border border-indigo-100"><i class="fas fa-ghost"></i></div>
                </div>
                <h3 class="text-xl font-black text-slate-800 mb-2">Belum Ada Riwayat Pesanan</h3>
                <p class="text-slate-500 text-sm max-w-sm mx-auto mb-8">Riwayat pesanan selesai akan muncul di sini.</p>
                <a href="{{ route('customer.katalog') }}" class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold px-8 py-3.5 rounded-xl transition-all shadow-lg shadow-indigo-500/30 active:scale-95">
                    <i class="fas fa-rocket"></i> Eksplor Katalog
                </a>
            </div>
        @endforelse
        
        @if(method_exists($transaksis, 'hasPages') && $transaksis->hasPages())
            <div class="mt-8">{{ $transaksis->withQueryString()->links() }}</div>
        @endif
    </div>
</div>

<script>
    function updateClock() {
        const now = new Date();
        let hours = String(now.getHours()).padStart(2, '0');
        let minutes = String(now.getMinutes()).padStart(2, '0');
        let seconds = String(now.getSeconds()).padStart(2, '0');
        
        document.getElementById('live-clock').innerHTML = `<span>${hours}</span><span class="animate-pulse text-indigo-400">:</span><span>${minutes}</span><span class="animate-pulse text-indigo-400">:</span><span class="text-indigo-300">${seconds}</span>`;
        
        let greeting = 'Selamat Malam';
        if (hours >= 5 && hours < 11) greeting = 'Selamat Pagi';
        else if (hours >= 11 && hours < 15) greeting = 'Selamat Siang';
        else if (hours >= 15 && hours < 18) greeting = 'Selamat Sore';
        
        document.getElementById('greeting').innerText = greeting;
        const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
        document.getElementById('current-date').innerText = now.toLocaleDateString('id-ID', options);
    }
    setInterval(updateClock, 1000);
    updateClock();
</script>
@endsection