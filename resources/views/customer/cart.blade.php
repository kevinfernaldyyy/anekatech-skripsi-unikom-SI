@extends('layouts.customer')

@section('content')

<!-- 1. HEADER SECTION -->
<div class="mb-6 flex items-end justify-between">
    <div>
        <h2 class="text-2xl md:text-3xl font-black text-slate-800 tracking-tight">Keranjang <span class="text-blue-600">Belanja</span></h2>
        <p class="text-slate-500 text-sm mt-1">Selesaikan pesananmu sebelum stok komponen habis.</p>
    </div>
</div>

<!-- 2. ALERT SUKSES -->
@if(session('success'))
    <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl mb-6 flex items-center justify-between shadow-sm">
        <div class="flex items-center gap-3">
            <i class="fas fa-check-circle text-emerald-500 text-lg"></i>
            <p class="font-bold text-sm">{{ session('success') }}</p>
        </div>
    </div>
@endif
@if(session('error'))
    <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl mb-6 flex items-center justify-between shadow-sm">
        <div class="flex items-center gap-3">
            <i class="fas fa-exclamation-triangle text-red-500 text-lg"></i>
            <p class="font-bold text-sm">{{ session('error') }}</p>
        </div>
    </div>
@endif
@if(isset($pesanPeringatan))
    <div class="bg-amber-50 border-l-4 border-amber-500 p-4 rounded-xl shadow-sm mb-6 animate-pulse">
        <div class="flex items-start">
            <div class="flex-shrink-0 mt-0.5">
                <i class="fas fa-exclamation-triangle text-amber-500 text-lg"></i>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-bold text-amber-800">Cek Kembali Keranjang Anda</h3>
                <p class="text-xs text-amber-700 mt-1">{{ $pesanPeringatan }}</p>
            </div>
        </div>
    </div>
@endif
<div class="mb-6 bg-gradient-to-r from-blue-50 via-indigo-50 to-blue-50 border border-blue-200 rounded-2xl p-5 shadow-sm">
    <div class="flex items-start gap-4">
        <div class="w-10 h-10 bg-blue-600 text-white rounded-xl flex items-center justify-center flex-shrink-0 shadow-md shadow-blue-500/20 mt-0.5">
            <i class="fas fa-layer-group text-base"></i>
        </div>
        <div class="flex-1">
            <div class="flex items-center justify-between">
                <h3 class="text-sm font-black text-slate-800 tracking-tight flex items-center gap-2">
                    <span>Tips Merakit Lebih dari 1 PC (Multi-Unit Custom Build)</span>
                    <span class="bg-blue-600 text-white text-[9px] font-extrabold px-2 py-0.5 rounded-full uppercase tracking-wider">Fitur Cerdas</span>
                </h3>
            </div>
            <p class="text-xs text-slate-600 mt-1 leading-relaxed">
                Ingin merakit 2 PC atau lebih dengan spesifikasi berbeda dalam satu transaksi? Ikuti langkah mudah ini:
            </p>
            
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 mt-3.5">
                <!-- Langkah 1 -->
                <div class="bg-white/80 border border-blue-100 rounded-xl p-3 shadow-2xs">
                    <span class="text-[10px] font-black text-blue-600 uppercase tracking-wider block mb-0.5">1. Rakit PC Pertama</span>
                    <p class="text-[11px] text-slate-600 leading-snug">
                        Masukkan komponen secara berurutan dimulai dari <b>Processor (CPU)</b> hingga komponen terakhir (Casing/Cooler).
                    </p>
                </div>
                <!-- Langkah 2 -->
                <div class="bg-white/80 border border-blue-100 rounded-xl p-3 shadow-2xs">
                    <span class="text-[10px] font-black text-blue-600 uppercase tracking-wider block mb-0.5">2. Tambah PC Kedua</span>
                    <p class="text-[11px] text-slate-600 leading-snug">
                        Untuk merakit PC selanjutnya, cukup tambahkan <b>Processor (CPU) baru</b> lagi ke keranjang, lalu ikuti komponen penunjangnya.
                    </p>
                </div>
                <!-- Langkah 3 -->
                <div class="bg-white/80 border border-blue-100 rounded-xl p-3 shadow-2xs">
                    <span class="text-[10px] font-black text-emerald-600 uppercase tracking-wider block mb-0.5">3. Terpisah Otomatis</span>
                    <p class="text-[11px] text-slate-600 leading-snug">
                        Sistem cerdas kami akan otomatis mengelompokkan spesifikasi menjadi <b>PC Unit #1, Unit #2, dst.</b> secara berurutan!
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">
    
    <!-- BAGIAN KIRI: DAFTAR ITEM & FORM PENGIRIMAN -->
    <div class="lg:col-span-2 space-y-6">
        
        <!-- A. DAFTAR KOMPONEN (CART ITEMS) -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
    <div class="px-6 py-4 border-b border-slate-100 flex items-center gap-2">
        <i class="fas fa-box text-blue-600"></i>
        <h3 class="font-bold text-slate-800">Daftar Komponen ({{ session('cart') ? count(session('cart')) : 0 }})</h3>
    </div>
    
    <div class="divide-y divide-slate-100">
        @forelse($cart as $id => $details)
            <div class="p-6 flex flex-col sm:flex-row gap-4 sm:gap-6 items-start sm:items-center hover:bg-slate-50 transition-colors">
                
                <div class="w-24 h-24 bg-white rounded-xl border border-slate-200 flex items-center justify-center flex-shrink-0 p-2 shadow-sm relative overflow-hidden">
                    @if(isset($details['gambar']) && $details['gambar'])
                        <img src="{{ asset('storage/' . $details['gambar']) }}" class="w-full h-full object-contain mix-blend-multiply hover:scale-110 transition-transform duration-300">
                    @elseif(isset($details['is_po']) && $details['is_po'])
                        <div class="absolute inset-0 bg-gradient-to-br from-amber-100 to-amber-200 flex items-center justify-center border border-amber-300 rounded-lg m-1">
                            <span class="font-black text-amber-600 text-2xl tracking-widest drop-shadow-sm">PO</span>
                        </div>
                    @else
                        <i class="fas fa-microchip text-3xl text-slate-300"></i>
                    @endif
                </div>
                
                <div class="flex-1 w-full">
                    <!-- Tag Kategori -->
                    <span class="text-[10px] font-bold {{ isset($details['is_po']) && $details['is_po'] ? 'bg-amber-100 text-amber-600' : 'bg-slate-100 text-slate-500' }} px-2 py-0.5 rounded uppercase tracking-wider">
                        {{ isset($details['is_po']) && $details['is_po'] ? 'Request Custom' : ($details['kategori'] ?? 'Komponen') }}
                    </span>
                    
                    <!-- 👇 WRAPPER JUDUL & LABEL BENTROK 👇 -->
                    <div class="flex flex-wrap items-center gap-2 mt-1.5 mb-1">
                        <h4 class="text-sm font-bold text-slate-800 leading-snug line-clamp-2">
                            {{ $details['nama_barang'] ?? $details['name'] ?? 'Komponen Pre-Order' }}
                        </h4>
                        
                        <!-- LABEL TIDAK COCOK -->
                        @if(isset($isClash) && $isClash && in_array($id, $clashItemIds ?? []))
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 text-[10px] font-black text-red-700 bg-red-100 rounded border border-red-200 shadow-sm animate-pulse">
                            <i class="fas fa-times-circle"></i> TIDAK COCOK
                        </span>
                    @endif
                    </div>
                    <!-- 👆 ------------------------------- 👆 -->

                    <!-- Harga -->
                    <p class="text-sm font-black {{ (isset($details['harga']) && $details['harga'] > 0) || (isset($details['price']) && $details['price'] > 0) ? 'text-slate-900' : 'text-amber-500' }}">
                        @if((isset($details['harga']) && $details['harga'] > 0) || (isset($details['price']) && $details['price'] > 0))
                            Rp {{ number_format($details['harga'] ?? $details['price'] ?? 0, 0, ',', '.') }}
                        @else
                            <i class="fas fa-clock text-xs"></i> Menunggu Estimasi Harga
                        @endif
                    </p>
                    
                    <!-- Stok -->
                    @if(!isset($details['is_po']) || !$details['is_po'])
                        <p class="text-[10px] font-bold text-slate-400 mt-1">Sisa Stok: {{ $details['stok'] ?? 99 }}</p>
                    @endif
                </div>
                
                <div class="flex sm:flex-col items-center sm:items-end justify-between w-full sm:w-auto mt-2 sm:mt-0 gap-3 border-t sm:border-0 border-slate-100 pt-4 sm:pt-0">
                    
                   <form action="{{ route('customer.cart.update', $id) }}" method="POST" class="form-update-qty flex items-center bg-white border border-slate-200 rounded-lg shadow-sm overflow-hidden">
                        @csrf
                        <button type="button" class="btn-minus w-8 h-8 flex items-center justify-center bg-slate-50 hover:bg-slate-100 text-slate-600 hover:text-slate-900 transition-colors border-r border-slate-200 font-black text-lg pb-0.5">
                            &minus;
                        </button>
                        
                        <input type="number" name="qty" 
                            value="{{ $details['qty'] }}" 
                            min="1" 
                            @if(!isset($details['is_po']) || !$details['is_po']) max="{{ $details['stok'] ?? 99 }}" @endif 
                            class="input-qty w-12 h-8 text-center text-xs font-bold text-slate-800 border-none outline-none focus:ring-0 p-0 hide-arrow bg-transparent" readonly>
                        
                        <button type="button" class="btn-plus w-8 h-8 flex items-center justify-center bg-slate-50 hover:bg-slate-100 text-slate-600 hover:text-slate-900 transition-colors border-l border-slate-200 font-black text-lg pb-0.5">
                            &plus;
                        </button>
                    </form>
                    
                    <form action="{{ route('customer.cart.remove', $id) }}" method="POST">
                        @csrf
                        <button type="submit" class="text-xs font-bold text-slate-400 hover:text-red-600 transition-colors flex items-center gap-1 group">
                            <i class="fas fa-trash-alt group-hover:scale-110 transition-transform"></i> Hapus
                        </button>
                    </form>
                </div>
            </div>
              @empty
                        <div class="py-16 px-6 text-center flex flex-col items-center">
                            <img src="https://cdn-icons-png.flaticon.com/512/11329/11329060.png" alt="Empty Cart" class="w-24 h-24 mb-4 opacity-50 grayscale">
                            <h4 class="text-lg font-bold text-slate-700 mb-1">Keranjang masih kosong</h4>
                            <p class="text-sm text-slate-500 mb-6 max-w-sm mx-auto">Yuk, cari komponen impianmu di etalase dan mulai merakit PC sekarang!</p>
                            <a href="{{ route('customer.katalog') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 px-6 rounded-xl transition-all shadow-md shadow-blue-600/20 transform hover:-translate-y-0.5">
                                Belanja Sekarang
                            </a>
                        </div>
                    @endforelse
                </div>
                
                @if(session('cart') && count(session('cart')) > 0)
                <div class="bg-slate-50 border-t border-slate-100 p-4 text-center sm:text-right">
                    <a href="{{ route('customer.katalog') }}" class="text-xs font-bold text-blue-600 hover:text-blue-800 hover:underline inline-flex items-center gap-1">
                        <i class="fas fa-plus"></i> Tambah Komponen Lain
                    </a>
                </div>
                @endif
            </div>
                    <!-- B. FORM PENGIRIMAN & PEMBAYARAN -->
                    @if(session('cart') && count(session('cart')) > 0)
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                        <div class="px-6 py-4 border-b border-slate-100 flex items-center gap-2">
                            <i class="fas fa-truck text-blue-600"></i>
                            <h3 class="font-bold text-slate-800">Detail Pengiriman & Pembayaran</h3>
                        </div>
                        
                        <div class="p-6">
                            <form id="form-checkout" action="{{ route('customer.cart.checkout') }}" method="POST" class="space-y-6">
                                @csrf
                                @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                    <strong>Checkout Gagal!</strong>
                    <ul class="list-disc pl-5 mt-1 text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
                    <!-- Info Pemesan -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Nama Pemesan</label>
                            <input type="text" name="nama" value="{{ Auth::user()->name }}" readonly class="w-full bg-slate-50 border border-slate-200 text-slate-500 text-sm rounded-xl px-4 py-2.5 focus:outline-none cursor-not-allowed">
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Nomor WhatsApp</label>
                            <input type="text" name="nomor_telepon" value="{{ Auth::user()->phone ?? '08xxxxxxxxxx' }}" readonly class="w-full bg-slate-50 border border-slate-200 text-slate-500 text-sm rounded-xl px-4 py-2.5 focus:outline-none cursor-not-allowed">
                        </div>
                    </div>

                    <!-- Metode Pengiriman & Alamat -->
                 <div class="bg-slate-50 p-4 rounded-xl border border-slate-100 space-y-4">
    <div>
        <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Metode Pengiriman <span class="text-red-500">*</span></label>
        
        <select name="metode_pengiriman" id="metode_pengiriman" onchange="toggleAreaTeknisi()" required class="w-full bg-white border border-slate-300 text-slate-700 text-sm rounded-xl px-4 py-3 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all cursor-pointer shadow-sm">
            <option value="">-- Pilih Metode Pengiriman --</option>
            <option value="pickup">Ambil di Toko (Pickup / Self-Service)</option>
            <option value="courier">Kirim via Kurir Ekspedisi (Kantor POS)</option>
            
            <!-- 👇 Opsi teknisi disatukan, status default pakai inline blade 👇 -->
            <option value="technician" id="opsi_teknisi" {{ $total < 20000000 ? 'disabled' : '' }}>
                {{ $total >= 20000000 ? 'Teknisi Datang ke Rumah (Instalasi Langsung)' : 'Teknisi ke Rumah (Min. Belanja Rp 20.000.000)' }}
            </option>
        </select>
        
        <!-- 👇 Peringatan disembunyikan/dimunculkan lewat class 'hidden' dari Tailwind 👇 -->
        <p id="pesan_teknisi" class="text-[10px] text-amber-600 mt-1.5 flex items-center gap-1 {{ $total >= 20000000 ? 'hidden' : '' }}">
            <i class="fas fa-info-circle"></i> Layanan teknisi ke rumah hanya tersedia untuk transaksi di atas 20 Juta.
        </p>
    </div>

    <!-- 👇 TAMBAHAN BLOK AREA LAYANAN TEKNISI (Hidden by default) 👇 -->
<div id="area_teknisi_wrapper" class="hidden bg-blue-50/50 p-3 rounded-xl border border-blue-100 mb-5">
        <label class="block text-[11px] font-bold text-blue-700 uppercase tracking-wider mb-1.5">
            <i class="fas fa-map-marker-alt mr-1"></i> Area Layanan Teknisi <span class="text-red-500">*</span>
        </label>
        <select name="area_layanan" id="area_layanan" class="w-full bg-white border border-blue-200 text-slate-700 text-sm rounded-xl px-4 py-3 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all cursor-pointer shadow-sm">
            <option value="">-- Pilih Jangkauan Area --</option>
            <option value="bandung_raya">Bandung Raya (Kota, Cimahi, Kab)</option>
            <option value="jawa_barat">Jawa Barat Sekitarnya</option>
            <option value="jabodetabek">Jabodetabek</option>
        </select>
        <div class="mt-3 bg-blue-100/80 border border-blue-200 rounded-lg p-3 flex gap-2.5 items-start shadow-sm">
            <i class="fas fa-info-circle text-blue-600 mt-0.5 text-sm"></i>
            <div>
                <p class="text-[11px] font-bold text-blue-800 uppercase tracking-wider mb-1">Informasi Penagihan</p>
                <p class="text-[10px] text-blue-700 leading-relaxed">
                    Biaya layanan teknisi tidak ditagihkan saat ini. Biaya tersebut akan diakumulasi ke dalam tagihan <b>setelah PC selesai dirakit</b> dan siap diberangkatkan ke lokasimu.
                </p>
            </div>
        </div>
    </div>
    <!-- 👆 SELESAI TAMBAHAN BLOK AREA 👇 -->

    <!-- 👇 BLOK ALAMAT DENGAN PILIHAN (Alpine.js) 👇 -->
    <div class="mb-5" x-data="{ 
        opsiAlamat: 'profil', 
        alamatProfil: `{{ Auth::user()->address ?? (Auth::user()->customer->alamat ?? '') }}`,
        alamatBaru: '' 
    }">
        <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2">Alamat Pengiriman <span class="text-red-500">*</span></label>
        
        <!-- INPUT HIDDEN INI YANG AKAN DIKIRIM KE CONTROLLER (name="alamat") -->
        <input type="hidden" name="alamat" :value="opsiAlamat === 'profil' ? alamatProfil : alamatBaru">

        <!-- Opsi Pilihan Alamat (Radio Button UI Card) -->
        <div class="grid grid-cols-2 gap-3 mb-4">
            <!-- Opsi 1: Alamat Profil -->
            <label class="cursor-pointer">
                <input type="radio" x-model="opsiAlamat" value="profil" class="peer sr-only">
                <div class="border-2 border-slate-200 rounded-xl p-3 text-center transition-all peer-checked:border-blue-500 peer-checked:bg-blue-50 peer-checked:text-blue-700 text-slate-500 hover:bg-slate-50 shadow-sm">
                    <i class="fas fa-home mb-1.5 text-lg"></i>
                    <p class="text-[10px] font-bold uppercase tracking-wider">Alamat Utama</p>
                </div>
            </label>

            <!-- Opsi 2: Alamat Baru -->
            <label class="cursor-pointer">
                <input type="radio" x-model="opsiAlamat" value="baru" class="peer sr-only">
                <div class="border-2 border-slate-200 rounded-xl p-3 text-center transition-all peer-checked:border-blue-500 peer-checked:bg-blue-50 peer-checked:text-blue-700 text-slate-500 hover:bg-slate-50 shadow-sm">
                    <i class="fas fa-map-marked-alt mb-1.5 text-lg"></i>
                    <p class="text-[10px] font-bold uppercase tracking-wider">Alamat Baru</p>
                </div>
            </label>
        </div>

        <!-- TAMPILAN JIKA ALAMAT PROFIL TERPILIH -->
        <div x-show="opsiAlamat === 'profil'" x-transition class="bg-slate-50 border border-slate-200 rounded-xl p-4 shadow-sm">
            <template x-if="alamatProfil !== ''">
                <div>
                    <div class="flex items-center gap-2 mb-2">
                        <span class="bg-emerald-100 text-emerald-700 text-[9px] font-black px-2 py-0.5 rounded uppercase tracking-wider flex items-center gap-1">
                            <i class="fas fa-check-circle"></i> Alamat Utama
                        </span>
                        <p class="text-xs font-bold text-slate-800">{{ Auth::user()->name }}</p>
                    </div>
                    <p class="text-sm text-slate-600 leading-relaxed" x-text="alamatProfil"></p>
                </div>
            </template>
            <template x-if="alamatProfil === ''">
                <div class="text-center py-3">
                    <i class="fas fa-exclamation-triangle text-red-400 text-2xl mb-2"></i>
                    <p class="text-sm text-red-500 font-bold mb-1">Alamat profil Anda masih kosong!</p>
                    <p class="text-xs text-slate-500">Silakan pilih "Alamat Baru" untuk transaksi ini.</p>
                </div>
            </template>
        </div>

        <!-- TAMPILAN JIKA ALAMAT BARU TERPILIH -->
        <div x-show="opsiAlamat === 'baru'" x-transition style="display: none;">
            <div class="flex flex-col border border-slate-300 rounded-xl overflow-hidden shadow-sm focus-within:border-blue-500 focus-within:ring-2 focus-within:ring-blue-500/20 transition-all bg-white">
                
                <!-- Label Area (Sekadar Visual UI) -->
                <div id="label_area_terpilih" class="hidden bg-blue-50 text-blue-700 text-[11px] font-bold px-4 py-2.5 border-b border-blue-100 uppercase tracking-wide">
                </div>

                <!-- Textarea (Atribut name dihapus karena sudah di-handle oleh input hidden di atas) -->
                <textarea x-model="alamatBaru" rows="3" :required="opsiAlamat === 'baru'" placeholder="Cth: Jl. Teknologi No. 99, RT 01/02, Kel. Maju, Kec. Jaya..." class="w-full border-none focus:ring-0 text-sm text-slate-700 px-4 py-3 resize-none bg-transparent"></textarea>
            </div>
        </div>
    </div>
</div>

                    <!-- Sistem Pembayaran (Custom Radio Cards) -->
                    <div>
                        <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2">Sistem Pembayaran <span class="text-red-500">*</span></label>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                            
                            <!-- Opsi Lunas -->
                            <label class="relative cursor-pointer group">
                                <input type="radio" name="status_pembayaran" value="lunas" required checked class="peer sr-only">
                                <div class="p-4 border-2 border-slate-200 rounded-xl bg-white peer-checked:border-blue-600 peer-checked:bg-blue-50 hover:border-blue-300 transition-all flex items-start gap-3">
                                    <div class="w-4 h-4 rounded-full border-2 border-slate-300 peer-checked:border-blue-600 peer-checked:bg-blue-600 flex-shrink-0 mt-0.5 flex items-center justify-center">
                                        <div class="w-1.5 h-1.5 bg-white rounded-full opacity-0 peer-checked:opacity-100"></div>
                                    </div>
                                    <div>
                                        <span class="block text-sm font-bold text-slate-800">Bayar Lunas</span>
                                        <span class="block text-[11px] text-slate-500 mt-0.5">Total Bayar: Rp {{ number_format($total, 0, ',', '.') }}</span>
                                    </div>
                                </div>
                            </label>
                            
                            <!-- Opsi DP -->
                            <label class="relative cursor-pointer group">
                                <input type="radio" name="status_pembayaran" value="dp" required class="peer sr-only">
                                <div class="p-4 border-2 border-slate-200 rounded-xl bg-white peer-checked:border-blue-600 peer-checked:bg-blue-50 hover:border-blue-300 transition-all flex items-start gap-3">
                                    <div class="w-4 h-4 rounded-full border-2 border-slate-300 peer-checked:border-blue-600 peer-checked:bg-blue-600 flex-shrink-0 mt-0.5 flex items-center justify-center">
                                        <div class="w-1.5 h-1.5 bg-white rounded-full opacity-0 peer-checked:opacity-100"></div>
                                    </div>
                                    <div>
                                        <span class="block text-sm font-bold text-slate-800">Down Payment (DP 50%)</span>
                                        <span class="block text-[11px] text-blue-600 font-bold mt-0.5">Tagihan Awal: Rp {{ number_format($total * 0.50, 0, ',', '.') }}</span>
                                    </div>
                                </div>
                            </label>
                        </div>

                        <!-- Kotak Informasi Rekening -->
                        <div class="p-4 bg-gradient-to-br from-slate-50 to-slate-100 border border-slate-200 rounded-xl flex items-start gap-4 shadow-inner">
                            <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center flex-shrink-0 shadow border border-slate-100">
                                <i class="fas fa-university text-blue-600"></i>
                            </div>
                            <div class="w-full">
                                <h4 class="text-sm font-bold text-slate-800 mb-1 flex justify-between items-center">
                                    Transfer Bank Manual
                                    <span class="bg-emerald-100 text-emerald-700 text-[9px] px-2 py-0.5 rounded-full uppercase tracking-wider">Aman</span>
                                </h4>
                                <p class="text-xs text-slate-500 mb-3">Silakan transfer sesuai nominal ke rekening resmi DiabloTech:</p>
                                
                                <div class="bg-white px-4 py-3 border border-slate-200 rounded-lg shadow-sm flex justify-between items-center">
                                    <div>
                                        <span class="block text-[10px] text-blue-600 font-bold uppercase tracking-wider mb-0.5">Bank BCA</span>
                                        <span class="block text-lg font-black text-slate-800 tracking-widest font-mono">8765-4321-00</span>
                                        <span class="block text-[10px] text-slate-500 uppercase mt-0.5 font-medium">A.N PT Aneka Teknologi Solusindo</span>
                                    </div>
                                    <i class="fab fa-cc-mastercard text-3xl text-slate-200"></i>
                                </div>
                                <p class="text-[10px] text-amber-600 mt-2.5 font-medium flex items-start gap-1.5">
                                    <i class="fas fa-exclamation-triangle mt-0.5"></i> 
                                    Anda dapat mengunggah bukti transfer/struk di halaman "Pesanan Aktif" setelah menyelesaikan proses checkout ini.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Opsi Perakitan (Custom Radio Cards) -->
                    <div>
                        <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2">Layanan Perakitan <span class="text-red-500">*</span></label>
                        
                        @if($hasReadyPC)
                            <!-- 👇 JIKA BELI PC READY STOCK / BUNDLE: HILANGKAN TOMBOL PILIHAN & AUTO RAKIT 👇 -->
                            <input type="hidden" name="is_rakit" value="0">
                            <div class="p-4 border-2 border-blue-500/30 rounded-xl bg-blue-50/50 flex items-center gap-3.5 shadow-sm">
                                <div class="w-10 h-10 bg-blue-600 text-white rounded-xl flex items-center justify-center flex-shrink-0 shadow-md shadow-blue-500/30">
                                    <i class="fas fa-desktop text-base"></i>
                                </div>
                                <div>
                                    <span class="block text-sm font-bold text-slate-800">PC Pre-Built / Ready Stock (Siap Pakai)</span>
                                    <span class="block text-[11px] text-blue-600 font-bold mt-0.5">Sudah Dirakit, Cable Management Rapi & Lolos Stress Test QC</span>
                                </div>
                            </div>
                        @else
                            <!-- 👇 JIKA BELI KOMPONEN ECERAN: TAMPILKAN PILIHAN RAKIT VS KIRIM PART 👇 -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <label class="relative cursor-pointer group">
                                    <input type="radio" name="is_rakit" value="1" required checked class="peer sr-only">
                                    <div class="p-3 border-2 border-slate-200 rounded-xl bg-white peer-checked:border-blue-600 peer-checked:bg-blue-50 hover:border-blue-300 transition-all flex items-center gap-3">
                                        <div class="w-10 h-10 bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center flex-shrink-0">
                                            <i class="fas fa-tools"></i>
                                        </div>
                                        <div>
                                            <span class="block text-sm font-bold text-slate-800">Rakit PC (Terima Beres)</span>
                                            <span class="block text-[10px] text-emerald-600 font-bold">Gratis Jasa Rakit & Stress Test</span>
                                        </div>
                                    </div>
                                </label>
                                
                                <label class="relative cursor-pointer group">
                                    <input type="radio" name="is_rakit" value="0" required form="form-checkout" class="peer sr-only" onchange="cekBypassRakitan(this.value)">
                                    <div class="p-3 border-2 border-slate-200 rounded-xl bg-white peer-checked:border-blue-600 peer-checked:bg-blue-50 hover:border-blue-300 transition-all flex items-center gap-3">
                                        <div class="w-10 h-10 bg-slate-100 text-slate-500 rounded-lg flex items-center justify-center flex-shrink-0">
                                            <i class="fas fa-box-open"></i>
                                        </div>
                                        <div>
                                            <span class="block text-sm font-bold text-slate-800">Kirim Komponen Saja</span>
                                            <span class="block text-[10px] text-slate-500">Box segel utuh, rakit mandiri</span>
                                        </div>
                                    </div>
                                </label>
                            </div>
                        @endif
                    </div>

                    <!-- Catatan -->
                    <div>
                        <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Catatan Pesanan (Opsional)</label>
                        <textarea name="catatan" rows="2" placeholder="Cth: Tolong kabel management dirapikan, install Linux, dll" class="w-full bg-white border border-slate-300 text-slate-700 text-sm rounded-xl px-4 py-3 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all shadow-sm resize-none"></textarea>
                    </div>
                </form>
            </div>
        </div>
        @endif
    </div>

    <!-- BAGIAN KANAN: RINGKASAN TRANSAKSI (STICKY SIDEBAR) -->
    <div class="lg:col-span-1">
        <div class="bg-white rounded-2xl shadow-md border border-slate-200 p-6 sticky top-24">
            <h3 class="font-bold text-lg text-slate-800 mb-4 border-b border-slate-100 pb-4">Ringkasan Belanja</h3>
            
           @php
                $hasPO = false;
                $hasReadyPC = false; 

                if(session('cart')) {
                    foreach(session('cart') as $item) {
                        if(isset($item['is_po']) && $item['is_po']) {
                            $hasPO = true;
                        }
                        
                        // Deteksi apakah item ini adalah Paket PC Ready / Build
                        $kat   = strtolower($item['kategori'] ?? '');
                        $jenis = strtolower($item['jenis'] ?? '');
                        if(str_contains($kat, 'pc') || str_contains($kat, 'rakitan') || str_contains($kat, 'build') || $jenis == 'paket_ready') {
                            $hasReadyPC = true;
                        }
                    }
                }
            @endphp

            <div class="space-y-3 mb-6 text-sm">
                <div class="flex justify-between text-slate-600">
                    <span id="ringkasan-qty">Total Harga ({{ session('cart') ? count(session('cart')) : 0 }} Barang)</span>
                    <span id="ringkasan-total" class="font-medium">Rp {{ number_format($total, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-slate-600">
                    <span>Asuransi Pengiriman</span>
                    <span class="font-medium text-emerald-600">Terlindungi</span>
                </div>
                <div class="flex justify-between text-slate-600">
                    <span>Biaya Jasa Rakit</span>
                    <span class="font-bold text-emerald-600">GRATIS</span>
                </div>
            </div>
            
            <div class="border-t border-slate-200 pt-4 mb-6">
                <div class="flex justify-between items-end mb-1">
                    <span class="text-slate-800 font-bold">{{ $hasPO ? 'Total Sementara' : 'Total Tagihan' }}</span>
                    <span id="ringkasan-tagihan" class="text-2xl font-black {{ $hasPO && $total == 0 ? 'text-amber-500' : 'text-blue-600' }}">Rp {{ number_format($total, 0, ',', '.') }}</span>
                </div>

                @if($hasPO)
                <div class="bg-amber-50 border border-amber-200 rounded-lg p-3 mt-3 shadow-inner">
                    <p class="text-[10px] text-amber-700 leading-relaxed">
                        <i class="fas fa-info-circle mr-1 text-amber-500 text-xs"></i> 
                        <strong>Perhatian:</strong> Total harga di atas <strong>belum termasuk</strong> komponen Pre-Order. Harga final (RAB) akan dihitungkan oleh Admin setelah pesanan diajukan.
                    </p>
                </div>
                @endif
            </div>

           @if(session('cart') && count(session('cart')) > 0)
    
    <!-- 👇 JIKA RAKITAN BENTROK (INTEL VS AMD) 👇 -->
@if($isClash)
    <button id="btn-locked" disabled class="w-full bg-slate-100 text-slate-400 font-bold py-3.5 px-4 rounded-xl cursor-not-allowed border border-slate-200 flex justify-center items-center gap-2 mb-2">
        <i class="fas fa-lock text-red-500"></i> Checkout Terkunci
    </button>
    <button id="btn-checkout-bypass" type="submit" form="form-checkout" class="hidden w-full {{ $hasPO ? 'bg-amber-500 hover:bg-amber-600' : 'bg-blue-600 hover:bg-blue-700' }} text-white font-bold py-3.5 px-4 rounded-xl transition-all shadow-lg transform flex justify-center items-center gap-2 mb-2">
        @if($hasPO)
            <i class="fas fa-paper-plane"></i> Ajukan Request PO
        @else
            <i class="fas fa-shield-check"></i> Proses Pembayaran
        @endif
    </button>
    
    <div id="teks-peringatan-clash" class="mt-3 bg-red-50 border border-red-200 rounded-lg p-3 text-left transition-opacity">
        <p class="text-xs font-bold text-red-600 mb-1"><i class="fas fa-exclamation-triangle"></i> Masalah Ditemukan:</p>
        <ul class="list-disc pl-5 text-[10px] text-red-500 font-bold tracking-wide space-y-1">
            @foreach($pesanClashArr ?? ['Pilih "Kirim Komponen Saja" untuk melanjutkan.'] as $pesan)
                <li>{{ $pesan }}</li>
            @endforeach
        </ul>
        <p class="text-[10px] text-red-400 font-bold italic mt-2 text-center">* Pilih opsi "Kirim Komponen Saja" jika tetap ingin membeli.</p>
    </div>

    <!-- 👇 JIKA RAKITAN AMAN (TIDAK BENTROK) 👇 -->
    @else
        <button type="submit" form="form-checkout" class="w-full {{ $hasPO ? 'bg-amber-500 hover:bg-amber-600 active:bg-amber-700 shadow-amber-500/30' : 'bg-blue-600 hover:bg-blue-700 active:bg-blue-800 shadow-blue-600/30' }} text-white font-bold py-3.5 px-4 rounded-xl transition-all shadow-lg transform hover:-translate-y-0.5 flex justify-center items-center gap-2">
            @if($hasPO)
                <i class="fas fa-paper-plane"></i> Ajukan Request PO
            @else
                <i class="fas fa-shield-check"></i> Proses Pembayaran
            @endif
        </button>
    @endif

@else
    <button disabled class="w-full bg-slate-100 text-slate-400 font-bold py-3.5 px-4 rounded-xl cursor-not-allowed border border-slate-200 flex justify-center items-center gap-2">
        <i class="fas fa-box-open"></i> Pilih Produk Dahulu
    </button>
@endif
            
            <div class="mt-5 flex items-start gap-2 bg-slate-50 p-3 rounded-lg border border-slate-100">
                <i class="fas fa-shield-alt text-blue-600 mt-0.5 text-sm"></i>
                <p class="text-[10px] text-slate-500 leading-relaxed">
                    Transaksi aman. Semua komponen dijamin 100% Original & Bergaransi Resmi. Teknisi bersertifikasi kami akan melakukan <i>Stress Test</i> sebelum PC diserahkan.
                </p>
            </div>
        </div>
    </div>

</div>

@endsection
<style>
    /* Menyembunyikan panah atas-bawah bawaan input type="number" */
    .hide-arrow::-webkit-outer-spin-button,
    .hide-arrow::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
    .hide-arrow {
        -moz-appearance: textfield;
    }
</style>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function cekStatusTeknisi(totalHarga) {
    const opsiTeknisi = document.getElementById('opsi_teknisi');
    const pesanTeknisi = document.getElementById('pesan_teknisi');
    const selectMetode = document.getElementById('metode_pengiriman');

    if (!opsiTeknisi) return; 
    
    // 👇 Bersihkan string "Rp 23.860.000" menjadi angka murni 23860000
    let rawTotal = parseInt(totalHarga.toString().replace(/[^0-9]/g, ''), 10);

    if (rawTotal >= 20000000) {
        // Hapus status disabled & ubah teks
        opsiTeknisi.removeAttribute('disabled');
        opsiTeknisi.textContent = 'Teknisi Datang ke Rumah (Instalasi Langsung)';
        pesanTeknisi.classList.add('hidden'); 
    } else {
        opsiTeknisi.setAttribute('disabled', 'true');
        opsiTeknisi.textContent = 'Teknisi ke Rumah (Min. Belanja Rp 20.000.000)';
        pesanTeknisi.classList.remove('hidden'); 
        if (selectMetode.value === 'technician') {
            selectMetode.value = ''; 
            toggleAreaTeknisi();    
        }
    }
}

function toggleAreaTeknisi() {
    const metode = document.getElementById('metode_pengiriman').value;
    const areaWrapper = document.getElementById('area_teknisi_wrapper');
    const areaInput = document.getElementById('area_layanan');

    if(areaWrapper && areaInput) {
        if (metode === 'technician') {
            areaWrapper.classList.remove('hidden');
            areaInput.setAttribute('required', 'required');
        } else {
            areaWrapper.classList.add('hidden');
            areaInput.removeAttribute('required');
            areaInput.value = ''; 
        }
    }
}

document.addEventListener('DOMContentLoaded', function () {
    
    // 1. Inisialisasi Area Layanan
    if(document.getElementById('metode_pengiriman')) {
        toggleAreaTeknisi();
    }

    // =====================================================================
    // FITUR 1: UPDATE QTY KERANJANG (Bawaan aslimu)
    // =====================================================================
    const qtyForms = document.querySelectorAll('.form-update-qty');
    qtyForms.forEach(form => {
        const btnMinus = form.querySelector('.btn-minus');
        const btnPlus = form.querySelector('.btn-plus');
        const inputQty = form.querySelector('.input-qty');
        
        btnMinus.addEventListener('click', function(e) {
            e.preventDefault();
            let currentValue = parseInt(inputQty.value);
            let minValue = parseInt(inputQty.getAttribute('min'));
            
            if (currentValue > minValue) {
                inputQty.value = currentValue - 1;
                updateCartData(form); 
            }
        });
        
        btnPlus.addEventListener('click', function(e) {
            e.preventDefault();
            let currentValue = parseInt(inputQty.value);
            let maxValue = inputQty.hasAttribute('max') ? parseInt(inputQty.getAttribute('max')) : 999;
            
            if (currentValue < maxValue) {
                inputQty.value = currentValue + 1;
                updateCartData(form); 
            } else {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'warning',
                    title: 'Maksimal stok tercapai!',
                    showConfirmButton: false,
                    timer: 1500
                });
            }
        });
    });
    
    function updateCartData(form) {
        let url = form.getAttribute('action');
        let formData = new FormData(form);
        
        fetch(url, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            },
            body: formData
        })
 .then(response => response.json())
        .then(data => {
            if (data.success) {
                const ringkasanQty = document.getElementById('ringkasan-qty');
                if(ringkasanQty && data.cart_count !== undefined) {
                    ringkasanQty.innerText = 'Total Harga (' + data.cart_count + ' Barang)';
                }

                const ringkasanTotal = document.getElementById('ringkasan-total');
                if(ringkasanTotal && data.new_total) {
                    ringkasanTotal.innerText = data.new_total;
                }

                const ringkasanTagihan = document.getElementById('ringkasan-tagihan');
                if(ringkasanTagihan && data.new_total) {
                    ringkasanTagihan.innerText = data.new_total;
                }

                // 👇 INI DIA YANG KETINGGALAN BOS! Panggil fungsi teknisi di sini 👇
                if(data.new_total) {
                    cekStatusTeknisi(data.new_total);
                }

                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 800,
                });
                Toast.fire({
                    icon: 'success',
                    title: 'Keranjang diperbarui'
                });
            }
        })
        .catch(error => console.error('Error:', error));
    } 

    // =====================================================================
    // FITUR 2: ADD TO CART & COMPATIBILITY CHECKER (Baru ditambahkan)
    // =====================================================================
    // Pastikan form "Tambah ke Keranjang" di HTML kamu dikasih class "form-add-to-cart"
    const addToCartForms = document.querySelectorAll('.form-add-to-cart');
    
    addToCartForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault(); // Cegah halaman refresh
            
            let url = this.getAttribute('action');
            let formData = new FormData(this);

            fetch(url, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update angka di icon keranjang navbar (kalau ada)
                    const cartBadge = document.getElementById('cart-count-badge');
                    if(cartBadge) {
                        cartBadge.innerText = data.cart_count;
                    }

                    // 👇 BACA STATUS COMPATIBILITY CHECKER 👇
                    if (data.is_warning) {
                        // Jika bentrok (Intel vs AMD), munculkan popup peringatan warna Kuning
                        Swal.fire({
                            icon: 'warning',
                            title: 'Peringatan Rakitan!',
                            text: data.message,
                            confirmButtonColor: '#f59e0b', // Warna Amber Tailwind
                            confirmButtonText: 'Mengerti'
                        });
                    } else {
                        // Jika aman, munculkan toast sukses warna Hijau di pojok
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'success',
                            title: data.message,
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }
                } else {
                    // Jika gagal (contoh: stok habis)
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: data.message || 'Terjadi kesalahan sistem.'
                    });
                }
            })
            .catch(error => console.error('Error Add to Cart:', error));
        });
    });
    // =====================================================================

    // Logika Label Area Terpilih
    const areaInput = document.getElementById('area_layanan');
    const labelArea = document.getElementById('label_area_terpilih');

    if (areaInput && labelArea) {
        areaInput.addEventListener('change', function() {
            if (this.value !== '') {
                let namaArea = this.options[this.selectedIndex].text.split(' - ')[0];
                labelArea.innerHTML = `<i class="fas fa-motorcycle mr-1.5"></i> Area Tujuan: ${namaArea}`;
                labelArea.classList.remove('hidden');
            } else {
                labelArea.classList.add('hidden');
            }
        });
    }
window.cekBypassRakitan = function(nilaiRadio) {
        const btnLocked = document.getElementById('btn-locked');
        const btnBypass = document.getElementById('btn-checkout-bypass');
        const teksPeringatan = document.getElementById('teks-peringatan-clash');

        // Eksekusi hanya jika tombol-tombolnya terdeteksi di halaman
        if (btnLocked && btnBypass) {
            if (nilaiRadio === '0') { 
                // BUKA KUNCI: Sembunyikan gembok, munculkan tombol proses pembayaran
                btnLocked.classList.add('hidden');
                btnBypass.classList.remove('hidden');
                if (teksPeringatan) teksPeringatan.classList.add('opacity-30');
            } else { 
                // KUNCI KEMBALI: Munculkan gembok, sembunyikan tombol proses pembayaran
                btnLocked.classList.remove('hidden');
                btnBypass.classList.add('hidden');
                if (teksPeringatan) teksPeringatan.classList.remove('opacity-30');
            }
        }
    };

});
</script>