@extends('layouts.customer')
@section('title', 'Pesanan Aktif | DiabloTech')

@section('content')
<div class="max-w-4xl mx-auto pb-10">
    
    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-4 rounded-2xl mb-8 flex items-center gap-4 shadow-sm animate-fade-in-down">
            <i class="fas fa-check-circle text-emerald-500 text-2xl"></i>
            <div><p class="font-bold text-sm">{{ session('success') }}</p></div>
        </div>
    @endif

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
        <div class="w-10 h-10 rounded-xl bg-slate-900 text-white flex items-center justify-center shadow-md"><i class="fas fa-list-ul"></i></div>
        <div>
            <h2 class="text-lg font-black text-slate-800 leading-tight">Daftar Pesanan Aktif</h2>
            <p class="text-xs text-slate-500 font-medium">Semua transaksi yang sedang berjalan saat ini.</p>
        </div>
    </div>

    <div class="space-y-6">
        @forelse($transaksis as $trx)
            @php
                $status = strtolower($trx->status_order); 
                $progress = 0; $progressColor = 'bg-slate-300'; $icon = 'fa-box';
                
                $isRetur = str_contains($status, 'retur') || $status == 'komplain';
                $isBatal = in_array($status, ['batal', 'cancel']);
                $isRakit = ($trx->is_rakit == 1);

                if ($isBatal) {
                    $progress = 100; $progressColor = 'bg-red-600'; $icon = 'fa-times-circle';
                } elseif ($isRetur) {
                    if (in_array($status, ['retur_diajukan', 'komplain'])) {
                        $progress = 30; $progressColor = 'bg-rose-500'; $icon = 'fa-exclamation-triangle';
                    } elseif (in_array($status, ['retur_diproses', 'retur_dicek'])) {
                        $progress = 65; $progressColor = 'bg-rose-500'; $icon = 'fa-microscope';
                    } else {
                        $progress = 100; $progressColor = 'bg-emerald-500'; $icon = 'fa-handshake';
                    }
                } else {
                    if ($status == 'belum_bayar') {
                        $progress = 10; $progressColor = 'bg-red-400'; $icon = 'fa-wallet';
                    } elseif (in_array($status, ['menunggu_proses', 'menunggu_po', 'menunggu_kalkulasi', 'menunggu_acc_customer'])) {
                        $progress = 30; $progressColor = 'bg-amber-400'; $icon = 'fa-user-check';
                    } elseif (in_array($status, ['sedang_dirakit', 'process', 'sedang_disiapkan'])) {
                        $progress = 65; $progressColor = 'bg-blue-500'; $icon = $isRakit ? 'fa-tools' : 'fa-box-open';
                    } elseif (in_array($status, ['siap_diambil', 'sedang_dikirim', 'ready'])) {
                        $progress = 90; $progressColor = 'bg-indigo-500'; $icon = 'fa-shipping-fast';
                    } else {
                        $progress = 100; $progressColor = 'bg-emerald-500'; $icon = 'fa-check-double';
                    }
                }

                $badgeClass = match($status) {
                    'belum_bayar' => 'bg-red-50 text-red-600 border-red-200',
                    'menunggu_proses', 'menunggu_po' => 'bg-amber-50 text-amber-600 border-amber-200',
                    'sedang_dirakit', 'process', 'sedang_disiapkan' => 'bg-blue-50 text-blue-600 border-blue-200',
                    'siap_diambil', 'sedang_dikirim', 'ready' => 'bg-indigo-50 text-indigo-600 border-indigo-200',
                    'selesai', 'completed', 'diterima_kustomer' => 'bg-emerald-50 text-emerald-600 border-emerald-200',
                    'batal', 'cancel', 'retur_ditolak' => 'bg-red-50 text-red-600 border-red-200',
                    'retur_diajukan', 'komplain', 'retur_diproses', 'retur_dicek' => 'bg-rose-50 text-rose-600 border-rose-200',
                    'retur_selesai', 'retur_diterima' => 'bg-emerald-50 text-emerald-600 border-emerald-200',
                    default => 'bg-slate-50 text-slate-600 border-slate-200'
                };

                $isPelunasan = ($trx->status_pembayaran == 'dp' && in_array($status, ['siap_diambil', 'sedang_dikirim', 'ready']));
                $isNegoPO = in_array($status, ['menunggu_kalkulasi', 'menunggu_acc_customer']);
            @endphp

            <div class="bg-white rounded-2xl shadow-sm border {{ $isPelunasan || $isNegoPO ? 'border-amber-300 shadow-amber-100/50' : 'border-slate-200/60' }} p-6 transition-all duration-300 hover:shadow-lg relative overflow-hidden group">
                <div class="absolute top-0 left-0 w-2 h-full {{ str_replace('bg-', 'bg-gradient-to-b from-', $progressColor) }} to-white opacity-80"></div>
                
                <div class="flex justify-between items-start mb-6 pl-4">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl {{ str_replace('bg-', 'bg-', $progressColor) }}/10 flex items-center justify-center text-xl {{ str_replace('bg-', 'text-', $progressColor) }}">
                            <i class="fas {{ $icon }}"></i>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-0.5">Inv. {{ $trx->created_at->format('d M Y') }}</p>
                            <h3 class="text-lg font-black text-slate-800 leading-none group-hover:text-indigo-600 transition-colors">{{ $trx->no_transaksi }}</h3>
                        </div>
                    </div>
                    @if($isNegoPO)
                        <span class="px-3 py-1.5 rounded-lg text-[10px] font-black uppercase tracking-wider border bg-amber-50 text-amber-600 border-amber-200 shadow-sm animate-pulse flex items-center gap-1"><i class="fas fa-handshake"></i> Nego PO</span>
                    @else
                        <span class="px-3 py-1.5 rounded-lg text-[10px] font-black uppercase tracking-wider border {{ $badgeClass }} shadow-sm">{{ str_replace('_', ' ', $status) }}</span>
                    @endif
                </div>

                <div class="mb-6 pl-4 pr-2">
                    @if($isBatal)
                        <div class="flex justify-between text-[9px] font-bold text-red-400 uppercase tracking-widest mb-2 px-1">
                            <span class="text-slate-400">Checkout</span>
                            <span class="text-red-600 text-right w-full">Pesanan Dibatalkan</span>
                        </div>
                    @elseif($isRetur)
                        <div class="flex justify-between text-[9px] font-bold text-rose-400 uppercase tracking-widest mb-2 px-1">
                            <span class="{{ $progress >= 30 ? 'text-rose-800' : '' }}">Retur Diajukan</span>
                            <span class="{{ $progress >= 65 ? 'text-rose-800' : '' }} text-center">Dicek Teknisi</span>
                            <span class="{{ $progress >= 100 ? 'text-rose-800' : '' }} text-right">Retur Selesai</span>
                        </div>
                    @else
                        <div class="flex justify-between text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-2 px-1">
                            <span class="{{ $progress >= 10 ? 'text-slate-800' : '' }}">Checkout</span>
                            <span class="{{ $progress >= 30 ? 'text-slate-800' : '' }} text-center">Diproses</span>
                            @if($isRakit)
                                <span class="{{ $progress >= 65 ? 'text-slate-800' : '' }} text-center">Dirakit</span>
                            @else
                                <span class="{{ $progress >= 65 ? 'text-slate-800' : '' }} text-center">Pengiriman</span>
                            @endif
                            <span class="{{ $progress >= 100 ? 'text-slate-800' : '' }} text-right">Selesai</span>
                        </div>
                    @endif
                    <div class="w-full bg-slate-100 rounded-full h-1.5 overflow-hidden mt-1">
                        <div class="{{ $progressColor }} h-1.5 rounded-full transition-all duration-1000 ease-out" style="width: {{ $progress }}%"></div>
                    </div>
                </div>

                @if($isPelunasan)
                    <div class="mb-5 ml-4 bg-gradient-to-r from-amber-50 to-orange-50 border border-amber-200 rounded-xl p-4 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                        <div class="flex items-center gap-3">
                            <span class="relative flex h-3 w-3 flex-shrink-0"><span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span><span class="relative inline-flex rounded-full h-3 w-3 bg-amber-500"></span></span>
                            <div>
                                <p class="text-[11px] font-bold text-amber-800 uppercase tracking-wider">Pelunasan Diperlukan</p>
                                <p class="text-xs font-medium text-amber-700 mt-0.5">Sisa tagihan <strong class="font-black text-amber-900 bg-amber-200/50 px-1 rounded">Rp {{ number_format($trx->total_harga - $trx->bayar, 0, ',', '.') }}</strong> menunggu pembayaran untuk serah terima unit.</p>
                            </div>
                        </div>
                    </div>
                @elseif($status == 'menunggu_acc_customer')
                    <div class="mb-5 ml-4 bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-xl p-4 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                        <div class="flex items-center gap-3">
                            <i class="fas fa-file-invoice-dollar text-blue-500 text-xl animate-bounce"></i>
                            <div>
                                <p class="text-[11px] font-bold text-blue-800 uppercase tracking-wider">Tawaran Harga PO Masuk</p>
                                <p class="text-xs font-medium text-blue-700 mt-0.5">Admin telah mengirimkan penawaran harga komponen. Silakan cek detail pesanan.</p>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="ml-4 flex flex-col md:flex-row justify-between items-start md:items-center gap-4 bg-slate-50/80 rounded-xl p-4 border border-slate-100">
                    <div class="flex-1 w-full">
                        <div class="flex items-center gap-2 mb-1.5">
                            @php $firstItem = $trx->details->first(); @endphp
                            <p class="text-sm font-bold text-slate-800 truncate max-w-[200px] sm:max-w-xs">
                                {{ $firstItem->barang->nama_barang ?? $firstItem->nama_barang_custom ?? 'Item Pesanan' }}
                            </p>
                            @if($trx->details->count() > 1)
                                <span class="text-[10px] font-black bg-white border border-slate-200 text-slate-600 px-2 py-0.5 rounded-md shadow-sm">+{{ $trx->details->count() - 1 }} Item</span>
                            @endif
                        </div>
                        <p class="text-[11px] text-slate-500 font-bold uppercase tracking-widest">Total: <span class="text-sm font-black text-indigo-600">Rp {{ number_format($trx->total_harga, 0, ',', '.') }}</span></p>
                    </div>
                    
                    <div class="w-full md:w-auto flex flex-col sm:flex-row items-center gap-3">
                        @if(empty($trx->bukti_pembayaran) && $status == 'belum_bayar')
                            <form action="{{ route('customer.upload_bukti', $trx->id) }}" method="POST" enctype="multipart/form-data" class="w-full sm:w-auto flex items-center gap-2">
                                @csrf
                                <input type="file" name="bukti_pembayaran" id="file-{{ $trx->id }}" required accept="image/*" class="hidden" onchange="document.getElementById('btn-submit-{{ $trx->id }}').classList.remove('hidden'); document.getElementById('lbl-file-{{ $trx->id }}').classList.add('bg-emerald-600', 'text-white', 'border-emerald-600')">
                                <label id="lbl-file-{{ $trx->id }}" for="file-{{ $trx->id }}" class="w-full sm:w-auto cursor-pointer bg-white hover:bg-slate-100 text-slate-700 text-xs font-bold py-2.5 px-4 rounded-lg transition-all flex items-center justify-center gap-2 border border-slate-300 shadow-sm">
                                    <i class="fas fa-camera text-slate-400"></i> Upload Struk
                                </label>
                                <button type="submit" id="btn-submit-{{ $trx->id }}" class="hidden w-full sm:w-auto bg-emerald-500 hover:bg-emerald-600 text-white text-xs font-bold py-2.5 px-6 rounded-lg transition-all shadow-md shadow-emerald-500/30">Kirim</button>
                            </form>
                        @elseif(!empty($trx->bukti_pembayaran))
                            <a href="{{ asset('storage/' . $trx->bukti_pembayaran) }}" target="_blank" class="w-full sm:w-auto flex items-center justify-center sm:justify-start gap-3 bg-white hover:bg-slate-50 border border-slate-200 p-1.5 pr-4 rounded-lg transition-all shadow-sm group">
                                <img src="{{ asset('storage/' . $trx->bukti_pembayaran) }}" alt="Resi" class="w-8 h-8 object-cover rounded border border-slate-100 bg-slate-100">
                                <div class="flex flex-col text-left">
                                    <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest leading-none mb-0.5">Struk Anda</span>
                                    <span class="text-[11px] font-black text-slate-700 leading-none group-hover:text-indigo-600 transition-colors">Terkirim <i class="fas fa-check-circle text-emerald-500 ml-1"></i></span>
                                </div>
                            </a>
                        @elseif(in_array($status, ['menunggu_proses', 'process']) && empty($trx->bukti_pembayaran))
                            <span class="w-full sm:w-auto bg-white text-slate-500 text-[11px] font-bold py-2.5 px-4 rounded-lg flex items-center justify-center gap-2 border border-slate-200 shadow-sm uppercase tracking-wider">
                                <i class="fas fa-store text-slate-400"></i> Dikelola Admin
                            </span>
                        @endif
                        <a href="{{ route('customer.orders.show', $trx->id) }}" class="w-full sm:w-auto bg-slate-900 hover:bg-indigo-600 text-white text-xs font-bold py-2.5 px-6 rounded-lg transition-colors flex items-center justify-center gap-2 shadow-sm uppercase tracking-wider">
                            Detail <i class="fas fa-arrow-right text-[10px]"></i>
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-24 bg-white rounded-3xl border border-slate-200/60 shadow-sm">
                <div class="relative w-24 h-24 mx-auto mb-6">
                    <div class="absolute inset-0 bg-indigo-100 rounded-full animate-ping opacity-50"></div>
                    <div class="relative w-full h-full bg-indigo-50 rounded-full flex items-center justify-center text-4xl text-indigo-500 shadow-inner border border-indigo-100"><i class="fas fa-ghost"></i></div>
                </div>
                <h3 class="text-xl font-black text-slate-800 mb-2">Belum Ada Transaksi Aktif</h3>
                <p class="text-slate-500 text-sm max-w-sm mx-auto mb-8">Riwayat pesananmu masih kosong. Ayo mulai rakit PC impianmu sekarang di DiabloTech!</p>
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