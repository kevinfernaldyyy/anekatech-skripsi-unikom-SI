@extends('layouts.customer') 

@section('content')

@if(session('success'))
    <!-- Toast Notification -->
    <div id="toast-success" class="fixed top-[110px] right-8 z-[100] bg-white border-l-4 border-emerald-500 px-5 py-4 rounded-xl shadow-2xl flex items-center justify-between gap-5 animate-[slideDown_0.3s_ease-out] min-w-[280px]">
        <div class="flex items-center gap-3">
            <div class="w-8 h-8 rounded-full bg-emerald-100 flex items-center justify-center shrink-0">
                <i class="fas fa-check text-emerald-600 text-sm font-black"></i>
            </div>
            <p class="font-bold text-sm text-slate-800">{{ session('success') }}</p>
        </div>
        <button onclick="document.getElementById('toast-success').style.display='none'" class="text-slate-400 hover:text-slate-600 transition-colors p-1">
            <i class="fas fa-times text-lg"></i>
        </button>
    </div>

    <script>
        setTimeout(function() {
            let toast = document.getElementById('toast-success');
            if (toast) {
                toast.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
                toast.style.opacity = '0';
                toast.style.transform = 'translateY(-10px)';
                setTimeout(() => { toast.style.display = 'none'; }, 500);
            }
        }, 3000);
    </script>
@endif

<div class="mb-5 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
    <div>
        <h2 class="text-2xl font-black text-slate-800 tracking-tight">Katalog <span class="text-blue-600">Produk & Rakitan</span></h2>
        <p class="text-slate-500 text-sm mt-0.5">Pilih komponen eceran atau racikan PC impianmu sekarang.</p>
    </div>
    
    <div class="flex items-center w-full sm:w-auto">
        <button onclick="document.getElementById('modal-po').classList.remove('hidden')" class="w-full sm:w-auto bg-amber-100 hover:bg-amber-200 border border-amber-200 text-amber-700 font-bold py-2.5 px-6 rounded-xl transition-all shadow-sm flex items-center justify-center gap-2 group">
            <i class="fas fa-box-open group-hover:scale-110 transition-transform"></i>
            <span class="text-sm">Request PO Custom</span>
        </button>
    </div>
</div>

<!-- NAVIGASI KATALOG 2 TINGKAT (PROFESSIONAL E-COMMERCE STYLE) -->
<div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 mb-8 overflow-hidden">
    
    <!-- LEVEL 1: TOP BAR (SEARCH + PRIMARY CATEGORY TABS) -->
    <div class="p-3 sm:p-4 border-b border-slate-100 flex flex-col lg:flex-row gap-3 items-center justify-between">
        
        <!-- Search Bar -->
        <form action="{{ route('customer.katalog') }}" method="GET" class="w-full lg:w-72 relative flex-shrink-0">
            @if(request('kategori'))
                <input type="hidden" name="kategori" value="{{ request('kategori') }}">
            @endif
            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                <i class="fas fa-search text-slate-400 text-sm"></i>
            </div>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari barang / spesifikasi..." 
                   class="w-full bg-slate-50 border border-slate-200 text-sm rounded-xl pl-10 pr-4 py-2.5 focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-100 focus:bg-white transition-all">
        </form>

        <!-- Primary Group Tabs -->
        @php
            $currentKat = request('kategori');
            
            // Cek user lagi ada di grup mana
            $inKomponen  = in_array($currentKat, $komponenList);
            $inComputer  = in_array($currentKat, $computerList);
            $inAksesoris = in_array($currentKat, $aksesorisList);
            
            // Tab Aktif untuk Primary
            $activeGroup = 'semua';
            if ($inKomponen)  $activeGroup = 'komponen';
            if ($inComputer)  $activeGroup = 'computer';
            if ($inAksesoris) $activeGroup = 'aksesoris';
        @endphp

        <div class="flex gap-1.5 overflow-x-auto w-full pb-1 lg:pb-0 scrollbar-hide items-center justify-start lg:justify-end">
            <!-- Tombol Semua -->
            <a href="{{ route('customer.katalog') }}" 
               class="px-4 py-2 rounded-xl text-xs font-black uppercase tracking-wider transition-all whitespace-nowrap {{ $activeGroup == 'semua' && !$currentKat ? 'bg-slate-900 text-white shadow-md shadow-slate-900/20' : 'bg-slate-50 text-slate-600 hover:bg-slate-100' }}">
                <i class="fas fa-layer-group mr-1.5"></i> Semua
            </a>
            
            <!-- Tombol PC Rakitan / Sistem -->
            <a href="{{ route('customer.katalog', ['kategori' => 'PC Rakitan / Build']) }}" 
               class="px-4 py-2 rounded-xl text-xs font-black uppercase tracking-wider transition-all whitespace-nowrap {{ $activeGroup == 'computer' ? 'bg-blue-600 text-white shadow-md shadow-blue-600/20' : 'bg-slate-50 text-slate-600 hover:bg-slate-100' }}">
                <i class="fas fa-desktop mr-1.5"></i> PC & Laptop
            </a>

            <!-- Tombol Komponen PC (Default pas klik ke Processor dulu) -->
            <a href="{{ route('customer.katalog', ['kategori' => 'Processor']) }}" 
               class="px-4 py-2 rounded-xl text-xs font-black uppercase tracking-wider transition-all whitespace-nowrap {{ $activeGroup == 'komponen' ? 'bg-blue-600 text-white shadow-md shadow-blue-600/20' : 'bg-slate-50 text-slate-600 hover:bg-slate-100' }}">
                <i class="fas fa-microchip mr-1.5"></i> Komponen PC
            </a>

            <!-- Tombol Aksesoris -->
            <a href="{{ route('customer.katalog', ['kategori' => 'Monitor']) }}" 
               class="px-4 py-2 rounded-xl text-xs font-black uppercase tracking-wider transition-all whitespace-nowrap {{ $activeGroup == 'aksesoris' ? 'bg-blue-600 text-white shadow-md shadow-blue-600/20' : 'bg-slate-50 text-slate-600 hover:bg-slate-100' }}">
                <i class="fas fa-headphones mr-1.5"></i> Aksesoris
            </a>
        </div>
    </div>

    <!-- LEVEL 2: DYNAMIC SUB-PILLS (Hanya Muncul Sesuai Kelompok yang Dipilih) -->
    @if($currentKat && ($inKomponen || $inComputer || $inAksesoris))
        @php
            $subCategories = [];
            if ($inKomponen)  $subCategories = $komponenList;
            if ($inComputer)  $subCategories = $computerList;
            if ($inAksesoris) $subCategories = $aksesorisList;
        @endphp

        <div class="bg-slate-50/80 px-4 py-3 flex items-center gap-2 overflow-x-auto scrollbar-hide border-t border-slate-100">
            <span class="text-[11px] font-black uppercase tracking-widest text-slate-400 mr-2 flex items-center gap-1 shrink-0">
                <i class="fas fa-filter text-[10px]"></i> Filter :
            </span>
            
            @foreach($subCategories as $subKat)
                <a href="{{ route('customer.katalog', ['kategori' => $subKat, 'search' => request('search')]) }}" 
                   class="px-3.5 py-1.5 rounded-lg text-xs font-bold transition-all whitespace-nowrap {{ $currentKat == $subKat ? 'bg-white text-blue-600 shadow-sm border border-slate-200/80' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-200/50' }}">
                    {{ $subKat }}
                </a>
            @endforeach
        </div>
    @endif

</div>

@if(isset($rekomendasiBuild) && count($rekomendasiBuild) > 0)
<div class="mb-8 bg-gradient-to-br from-slate-900 to-slate-800 rounded-2xl shadow-xl border border-slate-700 overflow-hidden relative">
    <div class="absolute top-0 right-0 p-8 opacity-10 pointer-events-none">
        <i class="fas fa-microchip text-[150px] text-white"></i>
    </div>
    
    <div class="p-6 md:p-8 relative z-10">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-6 mb-6">
            <div>
                <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-blue-500/20 border border-blue-500/30 text-blue-300 text-xs font-black uppercase tracking-wider mb-3">
                    <i class="fas fa-magic"></i> Auto-Match System
                </div>
                <h3 class="text-2xl md:text-3xl font-black text-white tracking-tight">Rekomendasi Rakitan <span class="text-blue-400">Hari Ini</span></h3>
                <p class="text-slate-400 text-sm mt-1 max-w-lg">Sistem kami meracik PC khusus untukmu! Dijamin 100% kompatibel dari soket, ukuran, hingga daya listrik.</p>
            </div>
            
            <div class="text-left md:text-right">
                <p class="text-slate-400 text-xs font-bold uppercase tracking-wider">Estimasi Harga 1 Set</p>
                <p class="text-3xl font-black text-emerald-400 mt-1">Rp {{ number_format($totalHargaBuild, 0, ',', '.') }}</p>
            </div>
        </div>

        <div class="flex gap-4 overflow-x-auto pb-4 scrollbar-hide snap-x">
            @foreach($rekomendasiBuild as $item)
                <a href="{{ route('customer.katalog.detail', $item->id) }}" target="_blank" class="snap-start flex-shrink-0 w-44 bg-white/10 hover:bg-white/20 backdrop-blur-md border border-white/10 rounded-xl p-3 transition-colors group">
                    <div class="aspect-square bg-white rounded-lg p-2 mb-3 relative flex items-center justify-center">
                        <span class="absolute top-1 left-1 bg-slate-900 text-white text-[8px] font-bold px-1.5 py-0.5 rounded shadow">
                            {{ $item->kategori }}
                        </span>
                        @if($item->gambar)
                            <img src="{{ asset('storage/' . $item->gambar) }}" class="h-full object-contain mix-blend-multiply group-hover:scale-110 transition-transform">
                        @else
                            <i class="fas fa-box text-3xl text-slate-300"></i>
                        @endif
                    </div>
                    <h4 class="text-white text-xs font-bold line-clamp-2 leading-snug">{{ $item->nama_barang }}</h4>
                    <p class="text-blue-300 font-black text-sm mt-1">Rp {{ number_format($item->harga_jual, 0, ',', '.') }}</p>
                </a>
            @endforeach
        </div>

        <!-- 👇 TAMBAHAN FORM BELI 1 SET RAKITAN 👇 -->
        <form action="{{ route('customer.cart.bundle') }}" method="POST" class="mt-6 flex justify-end">
            @csrf
            
            <!-- Looping otomatis ID Barang untuk dikirim ke Controller -->
            @foreach($rekomendasiBuild as $item)
                <input type="hidden" name="barang_ids[]" value="{{ $item->id }}">
            @endforeach

            <button type="submit" class="bg-emerald-500 hover:bg-emerald-600 text-white font-bold py-3 px-6 rounded-full shadow-lg transition transform hover:scale-105 inline-flex items-center gap-2">
                <i class="fas fa-shopping-cart"></i> Beli 1 Set Rakitan
            </button>
        </form>
        <!-- 👆 AKHIR TAMBAHAN FORM 👆 -->

    </div>
</div>
@endif

<!-- REKOMENDASI BARANG TERLARIS -->
@if(isset($rekomendasiTerlaris) && count($rekomendasiTerlaris) > 0)
<div class="mb-8 bg-white rounded-2xl shadow-sm border border-slate-200 p-6 md:p-8">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h3 class="text-xl md:text-2xl font-black text-slate-800 flex items-center gap-2">
                <i class="fas fa-fire text-orange-500"></i> Pilihan Favorit Kustomer
            </h3>
            <p class="text-slate-500 text-sm mt-1">Komponen nomor 1 yang paling banyak dirakit dan dibeli berdasarkan kategorinya.</p>
        </div>
    </div>

    <!-- Grid 5 Kolom untuk Top 5 Barang -->
 <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-4 md:gap-6">
        @foreach($rekomendasiTerlaris as $item)
            <!-- Pembungkus Card pakai DIV (bukan A) biar form bisa masuk -->
            <div class="bg-white border border-slate-200 hover:border-orange-500 rounded-xl p-4 transition-all hover:shadow-lg relative flex flex-col h-full">
                
                <!-- Badge Kategori Terlaris -->
                <div class="absolute -top-3 -right-3 z-10">
                    <span class="bg-gradient-to-r from-orange-400 to-orange-500 text-white text-[10px] font-black px-3 py-1 rounded-full shadow-md border-2 border-white flex items-center gap-1">
                        <i class="fas fa-crown"></i> TOP {{ strtoupper(substr($item->kategori, 0, 3)) }}
                    </span>
                </div>

                <!-- Link cuma bungkus gambar dan judul -->
                <a href="{{ route('customer.katalog.detail', $item->id) }}" class="block group mb-2">
                    <div class="aspect-square bg-slate-50 rounded-lg p-3 mb-4 relative flex items-center justify-center overflow-hidden">
                        @if(isset($item->gambar) && $item->gambar)
                            <img src="{{ asset('storage/' . $item->gambar) }}" class="h-full object-contain group-hover:scale-110 transition-transform">
                        @else
                            <i class="fas fa-box text-4xl text-slate-300"></i>
                        @endif
                    </div>
                    <h4 class="text-slate-800 text-sm font-bold line-clamp-2 hover:text-orange-500 transition-colors" title="{{ $item->nama_barang }}">
                        {{ $item->nama_barang }}
                    </h4>
                </a>
                
                <!-- Harga -->
                <p class="text-emerald-600 font-black text-sm mb-3">Rp {{ number_format($item->harga_jual ?? 0, 0, ',', '.') }}</p>
                
                <!-- Info Stok & Terjual -->
                <div class="flex items-center justify-between mb-4">
                    <span class="text-[10px] text-slate-400 font-medium">Sisa: {{ $item->stok }}</span>
                    <span class="text-[10px] bg-orange-50 text-orange-600 font-bold px-2 py-1 rounded">
                        {{ $item->total_terjual }} Terjual
                    </span>
                </div>

                <!-- 👇 TOMBOL CHECKOUT / ADD TO CART SATUAN 👇 -->
                <!-- Pastikan nama route-nya sesuai dengan route nambah ke keranjangmu, asumsinya 'customer.cart.add' -->
                <form action="{{ route('customer.cart.add', $item->id) }}" method="POST" class="mt-auto">
                    @csrf
                    <button type="submit" class="w-full bg-slate-900 hover:bg-orange-500 text-white text-xs font-bold py-2.5 px-4 rounded-lg transition-colors flex items-center justify-center gap-2">
                        <i class="fas fa-cart-plus"></i> Beli
                    </button>
                </form>

            </div>
        @endforeach
    </div>
</div>
@endif

<div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-3 sm:gap-4">
    @forelse($katalog as $item)
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden flex flex-col hover:border-blue-500 hover:shadow-lg transition-all duration-300 relative group">
            
            <!-- STOK BADGE -->
            @if($item->stok == 0)
                <span class="absolute top-0 left-0 bg-slate-500 text-white text-[9px] font-black px-2 py-1 rounded-br-lg z-10 uppercase tracking-wider">
                    Habis
                </span>
            @elseif($item->stok < 5 && $item->stok > 0)
                <span class="absolute top-0 left-0 bg-red-500 text-white text-[9px] font-black px-2 py-1 rounded-br-lg z-10 uppercase tracking-wider animate-pulse">
                    Sisa {{ $item->stok }}
                </span>
            @else
                <span class="absolute top-0 left-0 bg-blue-600 text-white text-[9px] font-black px-2 py-1 rounded-br-lg z-10 uppercase tracking-wider">
                    Ready ({{ $item->stok }})
                </span>
            @endif
            
            <div class="aspect-square bg-white flex items-center justify-center p-3 relative overflow-hidden group-hover:bg-slate-50 transition-colors">
                @if($item->gambar)
                    <img src="{{ asset('storage/' . $item->gambar) }}" alt="{{ $item->nama_barang }}" class="h-full object-contain mix-blend-multiply group-hover:scale-105 transition-transform duration-500">
                @else
                    <div class="text-slate-200 flex flex-col items-center gap-1">
                        <i class="fas fa-box text-3xl"></i>
                    </div>
                @endif
                <form action="{{ route('customer.cart.add', $item->id ?? 1) }}" method="POST" class="ajax-cart-form absolute inset-0 hidden md:block z-20">
                    @csrf
                    <button type="submit" class="w-full h-full bg-black/40 flex flex-col items-center justify-center opacity-0 hover:opacity-100 transition-all duration-300 cursor-pointer text-white">
                        <i class="fas fa-cart-plus text-4xl mb-2 transform translate-y-4 group-hover:translate-y-0 transition-transform duration-300"></i>
                        <span class="font-bold text-sm transform translate-y-4 group-hover:translate-y-0 transition-transform duration-300 delay-75 tracking-wider uppercase">+ Keranjang</span>
                    </button>
                </form>
            </div>

            <div class="p-2.5 sm:p-3 flex-1 flex flex-col justify-between border-t border-slate-100">
                <div>
                    <h3 class="text-xs sm:text-[13px] font-medium text-slate-800 leading-snug line-clamp-2 min-h-[36px] group-hover:text-blue-600 transition-colors">
                        <a href="{{ route('customer.katalog.detail', $item->id) }}">
                            {{ $item->nama_barang }}
                        </a>
                    </h3>

                    <!-- LENCANA SPESIFIKASI -->
                    @if($item->socket || $item->tipe_memory || $item->form_factor || $item->power_watt > 0)
                        <div class="flex flex-wrap gap-1 mt-1.5 mb-1">
                            @if($item->socket)
                                <span class="bg-slate-100 text-slate-600 border border-slate-200 text-[8px] sm:text-[9px] font-bold px-1.5 py-0.5 rounded uppercase"><i class="fas fa-microchip mr-0.5"></i>{{ $item->socket }}</span>
                            @endif
                            @if($item->tipe_memory)
                                <span class="bg-slate-100 text-slate-600 border border-slate-200 text-[8px] sm:text-[9px] font-bold px-1.5 py-0.5 rounded uppercase"><i class="fas fa-memory mr-0.5"></i>{{ $item->tipe_memory }}</span>
                            @endif
                            @if($item->form_factor)
                                <span class="bg-slate-100 text-slate-600 border border-slate-200 text-[8px] sm:text-[9px] font-bold px-1.5 py-0.5 rounded uppercase"><i class="fas fa-border-all mr-0.5"></i>{{ $item->form_factor }}</span>
                            @endif
                            @if($item->power_watt > 0)
                                <span class="bg-slate-100 text-slate-600 border border-slate-200 text-[8px] sm:text-[9px] font-bold px-1.5 py-0.5 rounded uppercase"><i class="fas fa-bolt mr-0.5"></i>{{ $item->power_watt }}W</span>
                            @endif
                        </div>
                    @endif
                </div>
                
                <div class="mt-2">
                    <!-- 👇 UI HARGA PROMO & CORET DISKON 10% 👇 -->
                    @if($item->is_diskon_bundle)
                        @php
                            $hargaEceranNormal = round($item->harga_jual / 0.9);
                        @endphp
                        <div class="flex items-center gap-1.5">
                            <span class="bg-red-500 text-white font-black text-[8px] px-1.5 py-0.5 rounded">
                                HEMAT 10%
                            </span>
                            <span class="text-[10px] text-slate-400 line-through font-bold">
                                Rp {{ number_format($hargaEceranNormal, 0, ',', '.') }}
                            </span>
                        </div>
                        <p class="text-sm sm:text-base font-black text-amber-600 tracking-tight">
                            Rp {{ number_format($item->harga_jual, 0, ',', '.') }}
                        </p>
                    @else
                        <p class="text-sm sm:text-base font-black text-slate-900 tracking-tight">
                            Rp {{ number_format($item->harga_jual, 0, ',', '.') }}
                        </p>
                    @endif
                    <!-- 👆 AKHIR HARGA PROMO 👆 -->

                    <div class="flex items-center gap-1 mt-1 mb-1.5">
                        <span class="bg-emerald-100 text-emerald-700 text-[8px] sm:text-[9px] font-bold px-1.5 py-0.5 rounded uppercase flex items-center gap-1">
                            <i class="fas fa-truck text-[8px]"></i> Bebas Rakit
                        </span>
                    </div>

                    @php
                        try {
                            $totalTerjual = $item->details()->sum('qty');
                        } catch (\Exception $e) {
                            $totalTerjual = 0; 
                        }
                    @endphp

                    <div class="flex items-center text-[10px] sm:text-[11px] text-slate-500 mt-1">
                        <i class="fas fa-shopping-bag text-slate-400 mr-1.5"></i>
                        <span>Terjual {{ $totalTerjual > 0 ? $totalTerjual : '0' }}</span>
                    </div>
                    
                    <div class="flex items-center gap-1 text-[10px] sm:text-[11px] text-slate-500 mt-1.5">
                        <i class="fas fa-check-circle text-blue-500"></i>
                        <span>DiabloTech Official</span>
                    </div>

                    <div class="mt-3 block md:hidden">
                        <form action="{{ route('customer.cart.add', $item->id ?? 1) }}" method="POST" class="ajax-cart-form">
                            @csrf
                            <button type="submit" class="w-full flex items-center justify-center border border-blue-600 text-blue-600 hover:bg-blue-50 font-bold py-1.5 rounded-lg text-xs transition-colors">
                                <span class="btn-text">+ Keranjang</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-span-full py-20 flex flex-col items-center justify-center text-center bg-white rounded-xl shadow-sm border border-slate-200 border-dashed">
            <div class="w-20 h-20 bg-slate-100 rounded-full flex items-center justify-center mb-4">
                <i class="fas fa-box-open text-4xl text-slate-300"></i>
            </div>
            <h3 class="text-lg font-black text-slate-800">Komponen Tidak Ditemukan</h3>
            <p class="text-sm text-slate-500 mt-2 max-w-md">Barang yang kamu cari mungkin belum masuk katalog kami. Jangan khawatir, kamu bisa request <strong>Pre-Order (PO)</strong> sekarang!</p>
            
            <div class="mt-6 flex flex-col sm:flex-row gap-3">
                <a href="{{ route('customer.katalog') }}" class="bg-white border border-slate-200 text-slate-600 font-bold px-6 py-2.5 rounded-xl hover:bg-slate-50 transition-colors text-sm">
                    Kembali ke Awal
                </a>
                <button onclick="document.getElementById('modal-po').classList.remove('hidden')" class="bg-amber-500 text-white font-black px-6 py-2.5 rounded-xl hover:bg-amber-600 transition-colors text-sm shadow-lg shadow-amber-500/30 flex items-center gap-2">
                    <i class="fas fa-plus-circle"></i> Ajukan Request PO
                </button>
            </div>
        </div>
    @endforelse
</div>

<!-- Modal PO -->
<div id="modal-po" class="fixed inset-0 z-50 hidden bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4 transition-opacity">
    <div class="bg-white rounded-3xl shadow-2xl w-full max-w-md overflow-hidden animate-[slideUp_0.3s_ease-out]">
        <div class="bg-amber-500 p-5 text-white flex justify-between items-center relative overflow-hidden">
            <div class="absolute -right-4 -top-4 opacity-20">
                <i class="fas fa-box-open text-8xl"></i>
            </div>
            <div class="relative z-10">
                <h3 class="font-black text-lg flex items-center gap-2"><i class="fas fa-magic"></i> Request Custom Part</h3>
                <p class="text-[11px] text-amber-100 mt-1 font-medium tracking-wide">Ajukan komponen idaman yang belum ada di katalog kami.</p>
            </div>
            <button onclick="document.getElementById('modal-po').classList.add('hidden')" class="relative z-10 text-amber-100 hover:text-white bg-amber-600 hover:bg-amber-700 w-8 h-8 rounded-full flex items-center justify-center transition-colors">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <form action="{{ route('customer.cart.add_custom') }}" method="POST" class="p-6">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-widest mb-1.5">Nama Komponen (Merek & Tipe) <span class="text-red-500">*</span></label>
                    <input type="text" name="nama_barang" required placeholder="Cth: VGA RTX 4090 ROG Strix" class="w-full border border-slate-300 rounded-xl px-4 py-2.5 text-sm focus:border-amber-500 focus:ring-1 focus:ring-amber-500 outline-none transition-all bg-slate-50 focus:bg-white">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-widest mb-1.5">Jumlah <span class="text-red-500">*</span></label>
                    <input type="number" name="qty" value="1" min="1" required class="w-full border border-slate-300 rounded-xl px-4 py-2.5 text-sm focus:border-amber-500 focus:ring-1 focus:ring-amber-500 outline-none transition-all bg-slate-50 focus:bg-white">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-widest mb-1.5">Link Referensi (Tokped/Web) <span class="text-slate-400 font-normal capitalize">(Opsional)</span></label>
                    <input type="url" name="link" placeholder="Cth: https://tokopedia.com/..." class="w-full border border-slate-300 rounded-xl px-4 py-2.5 text-sm focus:border-amber-500 focus:ring-1 focus:ring-amber-500 outline-none transition-all bg-slate-50 focus:bg-white">
                </div>
            </div>

            <div class="mt-8">
                <button type="submit" class="w-full bg-slate-900 hover:bg-black text-white font-black py-3 rounded-xl transition-colors shadow-lg flex items-center justify-center gap-2">
                    <i class="fas fa-plus"></i> Tambahkan ke Keranjang
                </button>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 1500,
            timerProgressBar: true,
            customClass: {
                container: 'mt-[100px] mr-4' 
            }
        });

        const cartBadge = document.getElementById('nav-cart-badge'); 
        
        document.addEventListener('submit', function (e) {
            const form = e.target.closest('.ajax-cart-form');
            if (!form) return; 

            e.preventDefault(); 

            const btn = form.querySelector('button');
            const originalContent = btn.innerHTML;

            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
            btn.disabled = true;

            fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                body: new FormData(form)
            })
            .then(response => {
                if (response.redirected) {
                    window.location.href = response.url;
                    throw new Error('Redirecting'); 
                }
                return response.json();
            })
            .then(data => {
                if (data && data.success) {
                    if (cartBadge) {
                        cartBadge.textContent = data.cart_count;
                        cartBadge.classList.remove('hidden');
                    }
                    
                    Toast.fire({
                        icon: 'success',
                        title: 'Masuk Keranjang!'
                    });
                }
            })
            .catch(error => {
                if (error.message !== 'Redirecting') {
                    console.error('Error:', error);
                    form.submit(); 
                }
            })
            .finally(() => {
                btn.innerHTML = originalContent;
                btn.disabled = false;
            });
        });
    });
</script>
@endsection