<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DiabloTech - Solusi Komponen PC & Rakitan Terbaik</title>
    <link rel="icon" type="image/jpeg" href="{{ asset('img/logo.jpeg') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <style>
        .hide-scrollbar::-webkit-scrollbar { display: none; }
        .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>
<body class="bg-slate-100 font-sans antialiased text-slate-800 flex flex-col min-h-screen relative">

    <!-- ================= NAVBAR UNIFIED ================= -->
    <header class="bg-white border-b border-slate-200 sticky top-0 z-50 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                
                <!-- BAGIAN KIRI: LOGO -->
                <div class="flex items-center gap-6 shrink-0">
                    <a href="{{ url('/') }}" class="flex items-center gap-3 group">
                        <img src="{{ asset('img/logo.jpeg') }}" alt="Logo DiabloTech" class="h-9 w-9 rounded-lg object-cover shadow-sm group-hover:scale-105 transition-transform">
                        <div class="text-xl font-black text-slate-900 tracking-tight hidden sm:block">
                            Diablo<span class="text-blue-600">Tech</span>
                        </div>
                    </a>
                </div>

                <!-- BAGIAN TENGAH: MENU -->
                    <nav class="hidden md:flex items-center space-x-1">
    
                        @guest
                            <!-- MENU HOME (CUMA MUNCUL BUAT TAMU / BELUM LOGIN) -->
                            <a href="{{ url('/') }}" 
                            class="text-sm font-semibold px-4 py-2 rounded-xl transition-all {{ request()->is('/') ? 'bg-blue-100 text-blue-700' : 'text-slate-500 hover:text-slate-900 hover:bg-slate-50' }}">
                                Home
                            </a>
                        @endguest

                        @auth
                            <!-- MENU KHUSUS YANG SUDAH LOGIN -->
                            <a href="{{ route('customer.dashboard', ['tab' => 'aktif']) }}" 
                            class="text-sm font-semibold px-4 py-2 rounded-xl transition-all {{ request()->routeIs('customer.dashboard') && request('tab', 'aktif') == 'aktif' ? 'bg-blue-100 text-blue-700' : 'text-slate-500 hover:text-slate-900 hover:bg-slate-50' }}">
                                Dashboard
                            </a>
                            <a href="{{ route('customer.katalog') }}" 
                            class="text-sm font-semibold px-4 py-2 rounded-xl transition-all {{ request()->routeIs('customer.katalog') ? 'bg-blue-100 text-blue-700' : 'text-slate-500 hover:text-slate-900 hover:bg-slate-50' }}">
                                Katalog Belanja
                            </a>
                            <a href="{{ route('customer.dashboard', ['tab' => 'riwayat']) }}" 
                            class="text-sm font-semibold px-4 py-2 rounded-xl transition-all {{ request()->routeIs('customer.dashboard') && request('tab') == 'riwayat' ? 'bg-blue-100 text-blue-700' : 'text-slate-500 hover:text-slate-900 hover:bg-slate-50' }}">
                                Riwayat Selesai
                            </a>
                        @endauth
                    </nav>

                <!-- BAGIAN KANAN: ACTIONS -->
                <div class="flex items-center gap-2 shrink-0">
                    @auth
                        <a href="#" class="flex items-center gap-2 text-slate-600 hover:text-slate-900 hover:bg-slate-50 px-3 py-2 rounded-xl transition-all font-semibold text-sm relative">
                            <i class="fas fa-shopping-cart text-lg text-blue-600"></i>
                            <span class="hidden sm:inline">Keranjang</span>
                        </a>

                        <div class="h-6 w-px bg-slate-200 mx-1 hidden sm:block"></div>

                        <!-- Dropdown Profil Alpine.js -->
                        <div x-data="{ dropdownOpen: false }" class="relative">
                            <a @click.prevent="dropdownOpen = !dropdownOpen" href="#" class="flex items-center gap-3 text-slate-600 hover:text-slate-900 hover:bg-slate-50 p-2 rounded-xl transition-all font-semibold text-sm cursor-pointer">
                                <span class="hidden sm:block text-right">
                                    <span class="block text-sm font-bold text-slate-800 leading-none">{{ Auth::user()->name ?? 'Customer' }}</span>
                                </span>
                                <div class="w-9 h-9 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold text-sm shadow-inner border border-blue-200">
                                    {{ substr(Auth::user()->name ?? 'C', 0, 1) }}
                                </div>
                                <i class="fas fa-chevron-down text-xs text-slate-400 transition-transform duration-200" :class="dropdownOpen ? 'rotate-180' : ''"></i>
                            </a>

                            <div x-show="dropdownOpen" @click.outside="dropdownOpen = false" 
                                 x-transition:enter="transition ease-out duration-200" 
                                 x-transition:enter-start="opacity-0 scale-95 translate-y-2" 
                                 x-transition:enter-end="opacity-100 scale-100 translate-y-0" 
                                 x-transition:leave="transition ease-in duration-150" 
                                 x-transition:leave-start="opacity-100 scale-100 translate-y-0" 
                                 x-transition:leave-end="opacity-0 scale-95 translate-y-2" 
                                 class="absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-xl border border-slate-100 overflow-hidden z-50" style="display: none;">
                                
                                <a href="{{ route('customer.customer.profile') }}" class="flex items-center gap-3 px-4 py-3 text-sm text-slate-700 hover:bg-blue-50 hover:text-blue-700 transition-colors font-semibold">
                                    <i class="fas fa-user-cog w-4 text-center"></i> Profil Saya
                                </a>
                                <div class="border-t border-slate-100"></div>
                                <form action="{{ route('logout') }}" method="POST" class="m-0">
                                    @csrf
                                    <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 text-sm text-red-600 hover:bg-red-50 transition-colors font-bold text-left">
                                        <i class="fas fa-sign-out-alt w-4 text-center"></i> Keluar
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <button onclick="openAuthModal()" class="flex items-center gap-2 text-slate-600 hover:text-slate-900 hover:bg-slate-50 px-3 py-2 rounded-xl transition-all font-semibold text-sm cursor-pointer">
                            <i class="fas fa-shopping-cart text-lg text-slate-500"></i>
                            <span class="hidden sm:inline">Keranjang</span>
                        </button>
                        <div class="h-6 w-px bg-slate-200 mx-2 hidden sm:block"></div>
                        <a href="{{ route('login') }}" class="text-sm font-bold text-slate-600 hover:text-blue-600 px-3 py-2 transition-colors">Masuk</a>
                        <a href="{{ route('register') }}" class="text-sm font-bold bg-slate-900 hover:bg-blue-600 text-white px-5 py-2.5 rounded-full transition-all shadow-md">Daftar</a>
                    @endauth
                </div>
            </div>
        </div>
        
        <!-- MENU VERSI MOBILE -->
        <div class="md:hidden border-t border-slate-100 bg-white px-4 py-3 flex overflow-x-auto space-x-2 hide-scrollbar">
            <a href="{{ url('/') }}" class="whitespace-nowrap text-xs font-semibold px-4 py-2 rounded-xl {{ request()->is('/') ? 'bg-blue-100 text-blue-700' : 'bg-slate-50 text-slate-600 border border-slate-200' }}">Home</a>
            @auth
                <a href="{{ route('customer.dashboard', ['tab' => 'aktif']) }}" class="whitespace-nowrap text-xs font-semibold px-4 py-2 rounded-xl {{ request('tab', 'aktif') == 'aktif' && request()->routeIs('customer.dashboard') ? 'bg-blue-100 text-blue-700' : 'bg-slate-50 text-slate-600 border border-slate-200' }}">Dashboard</a>
                <a href="{{ route('customer.katalog') }}" class="whitespace-nowrap text-xs font-semibold px-4 py-2 rounded-xl {{ request()->routeIs('customer.katalog') ? 'bg-blue-100 text-blue-700' : 'bg-slate-50 text-slate-600 border border-slate-200' }}">Katalog</a>
                <a href="{{ route('customer.dashboard', ['tab' => 'riwayat']) }}" class="whitespace-nowrap text-xs font-semibold px-4 py-2 rounded-xl {{ request('tab') == 'riwayat' && request()->routeIs('customer.dashboard') ? 'bg-blue-100 text-blue-700' : 'bg-slate-50 text-slate-600 border border-slate-200' }}">Riwayat</a>
            @endauth
        </div>
    </header>

    <!-- ================= HERO BANNER: REKOMENDASI HARIAN (DARK NAVY ACCENT CARD) ================= -->
    @if(!request('search') && !request('kategori'))
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-8 pb-6 w-full">
        @if(isset($dailyBuild) && $dailyBuild->items->count() > 0)
            <!-- CONTAINER REKOMENDASI HARIAN -->
            <div class="bg-slate-900 text-white rounded-3xl p-6 md:p-8 shadow-xl border border-slate-800 relative overflow-hidden">
                <!-- Glowing Subtles -->
                <div class="absolute -right-16 -top-16 w-80 h-80 bg-blue-600/20 rounded-full blur-3xl pointer-events-none"></div>

                <!-- Header Info & Harga -->
                <div class="flex flex-col md:flex-row md:items-start justify-between gap-6 mb-8 pb-6 border-b border-slate-800 relative z-10">
                    <div>
                        <span class="inline-flex items-center gap-1.5 bg-blue-600/30 text-blue-400 border border-blue-500/30 text-[10px] font-black px-3 py-1 rounded-full uppercase tracking-wider mb-3">
                            <i class="fas fa-microchip"></i> AUTO-MATCH SYSTEM
                        </span>
                        <h1 class="text-2xl sm:text-3xl md:text-4xl font-black tracking-tight text-white leading-tight">
                            Rekomendasi Rakitan <span class="text-blue-400">Hari Ini</span>
                        </h1>
                        <p class="text-slate-400 text-xs md:text-sm mt-1.5 max-w-2xl leading-relaxed">
                            Sistem kami meracik PC khusus untukmu! Dijamin 100% kompatibel dari soket, ukuran, hingga daya listrik.
                        </p>
                    </div>

                    <!-- Estimasi Harga -->
                    <div class="md:text-right shrink-0 bg-slate-800/80 md:bg-transparent p-4 md:p-0 rounded-2xl border border-slate-700/60 md:border-none flex flex-row md:flex-col justify-between items-center md:items-end">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">ESTIMASI HARGA 1 SET</span>
                        <span class="text-2xl md:text-3xl font-black text-emerald-400 md:mt-1">
                            Rp {{ number_format($dailyBuild->total_harga, 0, ',', '.') }}
                        </span>
                    </div>
                </div>

                <!-- Grid 6 Komponen Racikan -->
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3 md:gap-4 relative z-10">
                    @foreach($dailyBuild->items as $labelKategori => $item)
                        @auth
                            <!-- KALAU SUDAH LOGIN: Card Biasa / Tambah ke Keranjang -->
                            <div class="bg-white rounded-2xl p-3 flex flex-col justify-between hover:shadow-lg transition-all duration-300 group relative border border-slate-100 cursor-pointer">
                                <div class="absolute top-4 left-4 z-10">
                                    <span class="bg-slate-900 text-white text-[9px] font-bold px-2 py-0.5 rounded-md shadow-sm">
                                        {{ $labelKategori }}
                                    </span>
                                </div>
                                <div class="aspect-square bg-slate-50 rounded-xl p-3 flex items-center justify-center relative overflow-hidden mb-3">
                                    @if(isset($item->gambar) && $item->gambar)
                                        <img src="{{ asset('storage/' . $item->gambar) }}" alt="{{ $item->nama_barang }}" class="h-full object-contain group-hover:scale-105 transition-transform duration-300">
                                    @else
                                        <div class="text-slate-300 flex flex-col items-center gap-1">
                                            <i class="fas fa-microchip text-2xl text-slate-400"></i>
                                            <span class="text-[9px] text-slate-400">No Image</span>
                                        </div>
                                    @endif
                                </div>
                                <div>
                                    <h4 class="text-xs font-bold text-slate-800 leading-snug line-clamp-2 min-h-[32px] group-hover:text-blue-600 transition-colors" title="{{ $item->nama_barang }}">
                                        {{ $item->nama_barang }}
                                    </h4>
                                    <p class="text-xs font-black text-blue-600 mt-1.5">
                                        Rp {{ number_format($item->harga_jual ?? $item->harga ?? $item->harga_barang ?? 0, 0, ',', '.') }}
                                    </p>
                                </div>
                            </div>
                        @else
                            <!-- KALAU BELUM LOGIN: Diklik langsung muncul Modal Auth (Sama seperti katalog) -->
                            <div onclick="openAuthModal()" class="bg-white rounded-2xl p-3 flex flex-col justify-between hover:shadow-lg hover:scale-[1.02] transition-all duration-300 group relative border border-slate-100 cursor-pointer">
                                <div class="absolute top-4 left-4 z-10">
                                    <span class="bg-slate-900 text-white text-[9px] font-bold px-2 py-0.5 rounded-md shadow-sm">
                                        {{ $labelKategori }}
                                    </span>
                                </div>
                                <div class="aspect-square bg-slate-50 rounded-xl p-3 flex items-center justify-center relative overflow-hidden mb-3">
                                    @if(isset($item->gambar) && $item->gambar)
                                        <img src="{{ asset('storage/' . $item->gambar) }}" alt="{{ $item->nama_barang }}" class="h-full object-contain group-hover:scale-105 transition-transform duration-300">
                                    @else
                                        <div class="text-slate-300 flex flex-col items-center gap-1">
                                            <i class="fas fa-microchip text-2xl text-slate-400"></i>
                                            <span class="text-[9px] text-slate-400">No Image</span>
                                        </div>
                                    @endif
                                </div>
                                <div>
                                    <h4 class="text-xs font-bold text-slate-800 leading-snug line-clamp-2 min-h-[32px] group-hover:text-blue-600 transition-colors" title="{{ $item->nama_barang }}">
                                        {{ $item->nama_barang }}
                                    </h4>
                                    <p class="text-xs font-black text-blue-600 mt-1.5">
                                        Rp {{ number_format($item->harga_jual ?? $item->harga ?? $item->harga_barang ?? 0, 0, ',', '.') }}
                                    </p>
                                </div>
                            </div>
                        @endauth
                    @endforeach
                </div>

            </div>
        @else
            <!-- FALLBACK JIKA STOK KOSONG -->
            <div class="bg-white border border-slate-200 rounded-3xl p-8 text-center shadow-sm">
                <h1 class="text-2xl md:text-3xl font-black text-slate-900">Rakit PC Impian Anda Dengan Teknisi Ahli</h1>
                <p class="text-slate-500 text-sm mt-2">Pilih komponen Anda, dan biarkan teknisi bersertifikasi kami merakitnya siap pakai.</p>
            </div>
        @endif
    </section>
    @endif
    <!-- ================= END HERO BANNER ================= -->

    <!-- PRODUK HIGHLIGHT KATALOG -->
    <main id="katalog-produk" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4 mb-20 flex-1 w-full relative z-10">
        
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-bold text-slate-900">Komponen Terjamin</h2>
            <a href="{{ route('customer.katalog') ?? '#' }}" class="text-sm font-bold text-blue-600 hover:text-blue-800 hidden sm:block">Lihat Semua Katalog &rarr;</a>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
            @forelse($katalog ?? [] as $item)
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden flex flex-col hover:shadow-xl hover:border-blue-300 transition-all duration-300 relative group">                  
                    <span class="absolute top-2 left-2 bg-blue-600 text-white text-[9px] font-black px-2 py-0.5 rounded-md z-10 uppercase shadow-sm">
                        READY ({{ $item->stok ?? 10 }})
                    </span>
                    
                    <div class="aspect-square bg-slate-50 border-b border-slate-100 flex items-center justify-center p-4 relative overflow-hidden">
                        @if(isset($item->gambar) && $item->gambar)
                            <img src="{{ asset('storage/' . $item->gambar) }}" alt="{{ $item->nama_barang }}" class="h-full object-contain group-hover:scale-105 transition-transform duration-300">
                        @else
                            <div class="text-slate-300 flex flex-col items-center gap-2">
                                <i class="fas fa-image text-4xl"></i>
                                <span class="text-[10px] text-slate-400">No Image</span>
                            </div>
                        @endif
                    </div>

                    <div class="p-3.5 flex-1 flex flex-col justify-between gap-2">
                        <div>
                            <p class="text-[10px] font-bold text-blue-600 uppercase tracking-wide mb-1">{{ $item->kategori ?? 'Komponen PC' }}</p>
                            <h3 class="text-xs font-bold text-slate-800 leading-snug line-clamp-2 min-h-[32px] group-hover:text-blue-600 transition-colors">
                                {{ $item->nama_barang }}
                            </h3>
                        </div>
                        
                        <div>
                            <p class="text-sm font-black text-slate-900 tracking-tight">
                                Rp {{ number_format($item->harga ?? $item->harga_jual ?? $item->harga_barang ?? 0, 0, ',', '.') }}
                            </p>

                            <div class="flex items-center gap-1.5 mt-2 text-[11px] text-slate-500 font-medium">
                                <i class="fas fa-check-circle text-emerald-500"></i>
                                <span>
                                    Terjual 
                                    @php
                                        try {
                                            $totalTerjual = $item->details()->sum('qty');
                                        } catch (\Exception $e) {
                                            $totalTerjual = 0; 
                                        }
                                    @endphp
                                    {{ $totalTerjual > 0 ? $totalTerjual : '0' }} Unit
                                </span>
                            </div>
                            
                            <div class="mt-3">
                                @auth
                                    <form action="{{ route('cart.add', $item->id ?? 1) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="w-full text-center bg-slate-950 hover:bg-blue-600 text-white font-bold py-2 rounded-xl text-xs transition-colors shadow-sm">
                                            <i class="fas fa-shopping-cart mr-1"></i> + Keranjang
                                        </button>
                                    </form>
                                @else
                                    <button type="button" onclick="openAuthModal()" class="w-full text-center bg-blue-50 text-blue-700 hover:bg-blue-600 hover:text-white border border-blue-200 font-bold py-2 rounded-xl text-xs transition-all shadow-sm">
                                        <i class="fas fa-lock text-[10px] mr-1"></i> Beli Sekarang
                                    </button>
                                @endauth
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-20 text-center bg-white rounded-2xl shadow-sm border border-slate-200">
                    <h3 class="text-lg font-bold text-slate-700">Barang tidak ditemukan</h3>
                </div>
            @endforelse
        </div>

        <div class="mt-10 text-center">
            @auth
                <a href="{{ route('customer.katalog') }}" class="inline-flex items-center gap-2 bg-slate-900 hover:bg-blue-600 text-white font-bold px-8 py-3.5 rounded-xl transition-all shadow-md group">
                    Lihat Semua Katalog <i class="fas fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
                </a>
            @else
                <button onclick="openAuthModal()" class="inline-flex items-center gap-2 bg-slate-900 hover:bg-blue-600 text-white font-bold px-8 py-3.5 rounded-xl transition-all shadow-md group">
                    Lihat Semua Katalog <i class="fas fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
                </button>
            @endauth
        </div>
    </main>

    <!-- FOOTER -->
    <footer class="bg-white border-t border-slate-200 text-slate-500 py-6 text-xs mt-auto">
        <div class="max-w-7xl mx-auto px-4 text-center text-[11px]">
            <p>&copy; {{ date('Y') }} PT Aneka Teknologi Solusindo. Seluruh hak cipta dilindungi undang-undang.</p>
        </div>
    </footer>

    <!-- MODAL POP-UP AUTHENTICATION -->
    <div id="authModal" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-slate-900 bg-opacity-75 transition-opacity backdrop-blur-sm" id="modalOverlay"></div>
        <div class="fixed inset-0 z-10 overflow-y-auto">
            <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
                <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-md scale-95 opacity-0" id="modalContent">
                    <button onclick="closeAuthModal()" class="absolute top-4 right-4 w-8 h-8 flex items-center justify-center rounded-full bg-slate-100 text-slate-500 hover:bg-slate-200 hover:text-slate-700 transition-colors">
                        <i class="fas fa-times"></i>
                    </button>
                    <div class="px-6 py-8 sm:px-10 text-center">
                        <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-blue-50 mb-5 border-[4px] border-white shadow-sm">
                            <i class="fas fa-user-lock text-2xl text-blue-600"></i>
                        </div>
                        <h3 class="text-xl font-black text-slate-800 mb-2">Belum Punya Akses?</h3>
                        <p class="text-sm text-slate-500 mb-8 leading-relaxed">
                            Oops! Kamu harus <strong class="text-slate-700">Masuk</strong> atau <strong class="text-slate-700">Daftar Akun</strong> dulu ya supaya bisa mengakses fitur ini.
                        </p>
                        <div class="space-y-3">
                            <a href="{{ route('login') }}" class="flex w-full items-center justify-center gap-2 rounded-xl bg-slate-900 px-4 py-3.5 text-sm font-bold text-white shadow-lg hover:bg-blue-600 transition-all transform hover:-translate-y-0.5">
                                <i class="fas fa-sign-in-alt"></i> Masuk Sekarang
                            </a>
                            <a href="{{ route('register') }}" class="flex w-full items-center justify-center gap-2 rounded-xl bg-white border-2 border-slate-200 px-4 py-3 text-sm font-bold text-slate-700 hover:border-blue-600 hover:text-blue-600 transition-colors">
                                Daftar Akun Baru
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openAuthModal() {
            const modal = document.getElementById('authModal');
            const content = document.getElementById('modalContent');
            modal.classList.remove('hidden');
            setTimeout(() => {
                content.classList.remove('scale-95', 'opacity-0');
                content.classList.add('scale-100', 'opacity-100');
            }, 10);
        }
        function closeAuthModal() {
            const modal = document.getElementById('authModal');
            const content = document.getElementById('modalContent');
            content.classList.remove('scale-100', 'opacity-100');
            content.classList.add('scale-95', 'opacity-0');
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 300);
        }
        document.getElementById('modalOverlay').addEventListener('click', closeAuthModal);
    </script>
</body>
</html>