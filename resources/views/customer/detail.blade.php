@extends('layouts.customer')
@section('title', $barang->nama_barang . ' | DiabloTech')
@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8" x-data="{ 
    qty: 1, 
    stok: {{ $barang->stok }}, 
    harga: {{ $barang->harga_jual }},
    formatRupiah(angka) {
        return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(angka);
    }
}">

    <!-- BREADCRUMB NAVIGASI -->
    <nav class="flex text-sm text-slate-500 font-medium mb-6">
        <a href="{{ url('/') }}" class="hover:text-indigo-600">Home</a>
        <span class="mx-2">/</span>
        <a href="{{ route('customer.katalog') }}" class="hover:text-indigo-600">Katalog</a>
        <span class="mx-2">/</span>
        <span class="text-slate-800 truncate max-w-xs">{{ $barang->kategori }}</span>
    </nav>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
        <div class="grid grid-cols-1 md:grid-cols-12 gap-8">
            
            <!-- FOTO PRODUK -->
            <div class="md:col-span-4">
                <div class="aspect-square bg-slate-50 rounded-2xl border border-slate-100 flex items-center justify-center overflow-hidden p-4 sticky top-28">
                    @if($barang->gambar)
                        <img src="{{ asset('storage/' . $barang->gambar) }}" alt="{{ $barang->nama_barang }}" class="max-w-full max-h-full object-contain mix-blend-multiply m-auto">
                    @else
                        <i class="fas fa-box-open text-6xl text-slate-300"></i>
                    @endif
                </div>
            </div>

            <!-- INFO PRODUK & DESKRIPSI -->
            <div class="md:col-span-5 flex flex-col space-y-6">
                <div class="border-b border-slate-100 pb-6">
                    <span class="bg-indigo-50 text-indigo-600 text-[10px] font-black px-2.5 py-1 rounded-md uppercase tracking-wider">{{ $barang->kategori }}</span>
                    <h1 class="text-2xl font-black text-slate-800 mt-3 leading-tight">{{ $barang->nama_barang }}</h1>
                    
                    <!-- 👇 DISPLAY HARGA PROMO & CORET DI DETAIL 👇 -->
                    @if($barang->is_diskon_bundle)
                        @php
                            $hargaEceranNormal = round($barang->harga_jual / 0.9);
                            $hematRupiah = $hargaEceranNormal - $barang->harga_jual;
                        @endphp
                        <div class="mt-4 p-3 bg-amber-50 border border-amber-200 rounded-xl">
                            <span class="bg-red-500 text-white text-[10px] font-black px-2 py-0.5 rounded uppercase">
                                🔥 PROMO DISKON BUNDLE 10%
                            </span>
                            <div class="flex items-baseline gap-3 mt-2">
                                <p class="font-black text-3xl text-amber-600">Rp {{ number_format($barang->harga_jual, 0, ',', '.') }}</p>
                                <p class="text-sm text-slate-400 line-through font-bold">Rp {{ number_format($hargaEceranNormal, 0, ',', '.') }}</p>
                            </div>
                            <p class="text-xs text-emerald-700 font-bold mt-1">
                                <i class="fas fa-tags mr-1"></i> Hemat Rp {{ number_format($hematRupiah, 0, ',', '.') }} dari total eceran!
                            </p>
                        </div>
                    @else
                        <div class="flex items-center gap-4 mt-3 text-sm">
                            <p class="font-black text-3xl text-slate-900">Rp {{ number_format($barang->harga_jual, 0, ',', '.') }}</p>
                        </div>
                    @endif
                    <!-- 👆 AKHIR DISPLAY HARGA 👆 -->

                    <div class="flex items-center gap-4 mt-4 text-xs font-bold text-slate-500">
                        <span class="flex items-center gap-1"><i class="fas fa-check-circle text-emerald-500"></i> DiabloTech Official</span>
                        <span class="w-1 h-1 bg-slate-300 rounded-full"></span>
                        <span class="flex items-center gap-1">Kondisi: <span class="text-slate-800">Baru</span></span>
                    </div>
                </div>

                <div>
                   <!-- ========================================================= -->
                    <!-- 👇 1. TABEL SPESIFIKASI TEKNIS DINAMIS 👇 -->
                    <!-- ========================================================= -->
                    <h3 class="text-sm font-black text-slate-800 uppercase tracking-widest mb-3">Spesifikasi Teknis</h3>
                    
                    <div class="grid grid-cols-2 gap-y-3 text-sm mb-6 bg-slate-50 p-4 rounded-xl border border-slate-100 divide-y divide-slate-100/60">
                        <div class="text-slate-500 pt-1.5 first:pt-0">Kode Barang</div>
                        <div class="font-bold text-slate-800 pt-1.5 first:pt-0 font-mono">{{ $barang->kode_barang }}</div>
                        
                        <div class="text-slate-500 pt-1.5">Kategori</div>
                        <div class="font-bold text-slate-800 pt-1.5">{{ $barang->kategori }}</div>
                        
                        <!-- Soket CPU / Motherboard -->
                        @if($barang->socket)
                        <div class="text-slate-500 pt-1.5">Soket / Socket</div>
                        <div class="font-bold text-purple-600 pt-1.5 flex items-center gap-1">
                            <i class="fas fa-microchip text-xs"></i> {{ $barang->socket }}
                        </div>
                        @endif

                        <!-- Tipe Memory RAM -->
                        @if($barang->tipe_memory)
                        <div class="text-slate-500 pt-1.5">Tipe Memory</div>
                        <div class="font-bold text-blue-600 pt-1.5 flex items-center gap-1">
                            <i class="fas fa-memory text-xs"></i> {{ $barang->tipe_memory }}
                        </div>
                        @endif

                        <!-- Form Factor -->
                        @if($barang->form_factor)
                        <div class="text-slate-500 pt-1.5">Form Factor</div>
                        <div class="font-bold text-slate-700 pt-1.5 flex items-center gap-1">
                            <i class="fas fa-border-all text-xs"></i> {{ $barang->form_factor }}
                        </div>
                        @endif

                        <!-- Konsumsi Daya / TDP -->
                        @if($barang->power_watt > 0)
                        <div class="text-slate-500 pt-1.5">Est. Daya / TDP</div>
                        <div class="font-bold text-amber-600 pt-1.5 flex items-center gap-1">
                            <i class="fas fa-bolt text-xs"></i> {{ $barang->power_watt }} Watt
                        </div>
                        @endif

                        <div class="text-slate-500 pt-1.5">Satuan</div>
                        <div class="font-bold text-slate-800 pt-1.5">{{ $barang->satuan ?? 'Unit' }}</div>
                    </div>

                    <!-- ========================================================= -->
                    <!-- 👇 2. KHUSUS PC READY STOCK: TAMPILKAN DAFTAR KOMPONEN BUNDLE 👇 -->
                    <!-- ========================================================= -->
                    @if($barang->components && $barang->components->count() > 0)
                    <div class="mb-6 bg-gradient-to-br from-blue-50/80 to-indigo-50/80 border border-blue-200/80 rounded-2xl p-5 shadow-sm">
                        <div class="flex items-center gap-2 mb-3 border-b border-blue-200/60 pb-3">
                            <div class="w-7 h-7 bg-blue-600 text-white rounded-lg flex items-center justify-center text-xs shadow-sm">
                                <i class="fas fa-list-check"></i>
                            </div>
                            <div>
                                <h4 class="text-xs font-black text-blue-900 uppercase tracking-wider">Spesifikasi Komponen Terpasang</h4>
                                <p class="text-[10px] text-blue-600">Rincian hardware di dalam paket PC ini:</p>
                            </div>
                        </div>

                        <ul class="space-y-2 text-xs">
                            @foreach($barang->components as $comp)
                            <li class="flex items-start justify-between gap-3 bg-white/80 p-2.5 rounded-xl border border-blue-100">
                                <div>
                                    <span class="text-[9px] font-bold text-slate-400 uppercase block">{{ $comp->kategori }}</span>
                                    <span class="font-bold text-slate-800 leading-snug">{{ $comp->nama_barang }}</span>
                                </div>
                                <span class="bg-blue-100 text-blue-800 text-[10px] font-black px-2 py-0.5 rounded-md shrink-0 self-center">
                                    x{{ $comp->pivot->qty }}
                                </span>
                            </li>
                            @endforeach
                        </ul>
                        
                        <div class="mt-3 flex items-center gap-1.5 text-[10px] text-blue-700 font-medium italic">
                            <i class="fas fa-shield-alt text-blue-500"></i>
                            <span>Semua komponen dirakit rapi, teruji stress-test, dan bergaransi resmi.</span>
                        </div>
                    </div>
                    @endif

                    <!-- ========================================================= -->
                    <!-- 👇 3. DESKRIPSI PRODUK 👇 -->
                    <!-- ========================================================= -->
                    <h3 class="text-sm font-black text-slate-800 uppercase tracking-widest mb-3">Deskripsi Produk</h3>
                    <div class="text-slate-600 text-sm leading-relaxed whitespace-pre-line text-justify">
                        {{ $barang->deskripsi ?? 'Tidak ada deskripsi untuk produk ini.' }}
                    </div>
            </div>

            <!-- KOTAK KERANJANG MELAYANG -->
            <div class="md:col-span-3">
                <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-lg shadow-slate-200/50 sticky top-28">
                    <h3 class="font-black text-slate-800 mb-4">Atur Jumlah</h3>
                    
                    <form action="{{ route('customer.cart.add', $barang->id) }}" method="POST" class="ajax-cart-form">
                        @csrf
                        <div class="flex items-center gap-4 mb-4">
                            <div class="flex items-center border border-slate-300 rounded-lg overflow-hidden">
                                <button type="button" @click="if(qty > 1) qty--" class="w-8 h-8 flex items-center justify-center bg-slate-50 hover:bg-slate-100 text-slate-600 transition-colors text-lg font-black pb-1" :disabled="qty <= 1">
                                    &minus;
                                </button>
                                <input type="number" name="quantity" x-model="qty" min="1" :max="stok" class="w-12 h-8 text-center text-sm font-bold bg-white focus:outline-none border-x border-slate-300 pointer-events-none" readonly>
                                <button type="button" @click="if(qty < stok) qty++" class="w-8 h-8 flex items-center justify-center bg-slate-50 hover:bg-slate-100 text-slate-600 transition-colors text-lg font-black pb-1" :disabled="qty >= stok">
                                    &plus;
                                </button>
                            </div>
                            <div class="text-xs font-bold text-slate-500">
                                Stok: <span class="text-slate-800" x-text="stok"></span>
                            </div>
                        </div>

                        <div class="flex items-center justify-between mt-6 mb-6">
                            <span class="text-sm text-slate-500 font-bold">Subtotal</span>
                            <span class="text-lg font-black text-slate-900" x-text="formatRupiah(qty * harga)"></span>
                        </div>

                        @if($barang->stok > 0)
                            <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-4 rounded-xl transition-all shadow-md flex items-center justify-center gap-2 mb-3">
                                <i class="fas fa-cart-plus"></i> Keranjang
                            </button>
                        @else
                            <button type="button" disabled class="w-full bg-slate-300 text-slate-500 font-bold py-3 px-4 rounded-xl cursor-not-allowed">
                                Stok Habis
                            </button>
                        @endif
                    </form>
                </div>
            </div>

        </div>
    </div>

    <!-- UI REKOMENDASI KOMPONEN PASANGAN -->
    @if(isset($rekomendasiCocok) && $rekomendasiCocok->count() > 0)
    <div class="mt-12">
        <div class="flex items-center gap-3 mb-6">
            <div class="w-10 h-10 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-600 shadow-sm">
                <i class="fas fa-link text-lg"></i>
            </div>
            <div>
                <h3 class="text-xl font-black text-slate-800 tracking-tight">Komponen Pasangan Cocok</h3>
                <p class="text-sm text-slate-500">Rekomendasi sistem yang sesuai dengan spesifikasi komponen ini.</p>
            </div>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            @foreach($rekomendasiCocok as $item)
                <div class="bg-white rounded-xl shadow-sm border-2 border-emerald-100 hover:border-emerald-400 overflow-hidden flex flex-col transition-all duration-300 group">
                    <div class="aspect-square bg-slate-50 flex items-center justify-center p-4 relative">
                        <span class="absolute top-2 left-2 bg-slate-800 text-white text-[9px] font-bold px-2 py-1 rounded shadow-sm z-10">
                            {{ $item->kategori }}
                        </span>
                        
                        @if($item->gambar)
                            <img src="{{ asset('storage/' . $item->gambar) }}" class="h-full object-contain mix-blend-multiply group-hover:scale-110 transition-transform duration-500">
                        @else
                            <i class="fas fa-box text-4xl text-slate-300"></i>
                        @endif
                    </div>

                    <div class="p-3 flex-1 flex flex-col justify-between">
                        <div>
                            <a href="{{ route('customer.katalog.detail', $item->id) }}" class="text-xs sm:text-sm font-bold text-slate-800 line-clamp-2 hover:text-emerald-600 transition-colors">
                                {{ $item->nama_barang }}
                            </a>
                            
                            <div class="mt-1.5 flex flex-wrap gap-1">
                                @if($item->socket && stripos($item->socket, $barang->socket) !== false)
                                    <span class="text-[9px] font-black bg-emerald-100 text-emerald-700 px-1.5 py-0.5 rounded border border-emerald-200">
                                        <i class="fas fa-check mr-0.5"></i> Kecocokan Soket
                                    </span>
                                @endif
                                @if($item->tipe_memory && $item->tipe_memory == $barang->tipe_memory)
                                    <span class="text-[9px] font-black bg-emerald-100 text-emerald-700 px-1.5 py-0.5 rounded border border-emerald-200">
                                        <i class="fas fa-check mr-0.5"></i> RAM Sesuai 
                                    </span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="mt-3 font-black text-slate-900">
                            Rp {{ number_format($item->harga_jual, 0, ',', '.') }}
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- PRODUK SERUPA -->
    @if(isset($produkSerupa) && $produkSerupa->count() > 0)
        <div class="mt-12">
            <h2 class="text-xl font-black text-slate-800 mb-6">Produk Serupa</h2>
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                @foreach($produkSerupa as $item)
                    <a href="{{ route('customer.katalog.detail', $item->id) }}" class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden flex flex-col hover:shadow-md transition-all group">
                        <div class="aspect-square bg-slate-50 border-b border-slate-100 p-4">
                            @if($item->gambar)
                                <img src="{{ asset('storage/' . $item->gambar) }}" class="h-full w-full object-contain mix-blend-multiply group-hover:scale-105 transition-transform">
                            @endif
                        </div>
                        <div class="p-4 flex-1 flex flex-col justify-between">
                            <h3 class="text-xs font-bold text-slate-800 line-clamp-2 mb-2">{{ $item->nama_barang }}</h3>
                            <p class="text-sm font-black text-slate-900">Rp {{ number_format($item->harga_jual, 0, ',', '.') }}</p>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    @endif
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    window.addEventListener('load', function () {
        const cartBadge = document.getElementById('nav-cart-badge'); 
        const form = document.querySelector('.ajax-cart-form');
        
        if (form) {
            form.addEventListener('submit', function (e) {
                e.preventDefault(); 

                const btn = form.querySelector('button[type="submit"]');
                const originalContent = btn.innerHTML;

                btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Proses...';
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
                        
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'success',
                            title: 'Masuk Keranjang!',
                            showConfirmButton: false,
                            timer: 1500,
                            timerProgressBar: true,
                            customClass: {
                                container: 'mt-[100px] mr-4' 
                            }
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
        }
    });
</script>
@endsection