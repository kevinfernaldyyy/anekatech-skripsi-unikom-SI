@extends('layouts.admin')

@section('title', 'Kasir (POS)')

@section('content')
<div class="flex flex-col md:flex-row h-[calc(100vh-100px)] -mx-4 -my-4 bg-gray-100" x-data="posSystem()">
    
    <div x-show="toast.visible" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 -translate-y-5"
         x-transition:enter-end="opacity-100 translate-y-0"
         class="fixed top-6 left-1/2 transform -translate-x-1/2 z-[100] flex items-center gap-3 px-6 py-3 rounded-full shadow-2xl border"
         :class="toast.type === 'error' ? 'bg-red-600 text-white border-red-700' : (toast.type === 'warning' ? 'bg-yellow-500 text-white border-yellow-600' : 'bg-emerald-600 text-white border-emerald-700')"
         style="display: none;">
        <span class="font-bold text-sm" x-text="toast.message"></span>
    </div>

    <div class="flex-1 flex flex-col h-full overflow-hidden border-r border-gray-200">
        <div class="p-4 bg-white border-b border-gray-200 shadow-sm z-10 flex flex-col gap-3">
            <div class="relative flex-1">
                <form action="{{ route('admin.transaksi.index') }}" method="GET" class="w-full relative">
                    <button type="submit" class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400 hover:text-blue-600 transition-colors cursor-pointer z-10" title="Klik untuk mencari">
                        🔍
                    </button>
                    <input type="text" name="search" value="{{ request('search') }}" class="w-full py-2 pl-9 pr-4 text-sm bg-gray-50 border border-gray-300 rounded-lg focus:ring-blue-500 focus:bg-white transition-colors" placeholder="Cari Barang (Stok Ready / PO)... (Ketik lalu Enter)" autofocus>
                </form>
            </div>
            <div class="w-full">
               <div class="relative w-full min-w-0"> <div class="flex gap-2 overflow-x-auto pb-2 pt-1 w-full custom-scrollbar">
                    
                    <a href="{{ route('admin.transaksi.index') }}" class="px-4 py-1.5 rounded-lg border text-xs font-semibold flex-none transition-colors {{ !request('kategori') ? 'bg-gray-800 text-white' : 'bg-white text-gray-600 hover:bg-gray-50' }}">
                        Semua
                    </a>
                    
                    @foreach($kategoris as $cat)
                        <a href="{{ route('admin.transaksi.index', ['kategori' => $cat]) }}" class="px-4 py-1.5 rounded-lg border text-xs font-semibold flex-none transition-colors {{ request('kategori') == $cat ? 'bg-blue-600 text-white border-blue-600 shadow-sm' : 'bg-white text-gray-600 hover:bg-gray-50' }}">
                            {{ $cat }}
                        </a>
                    @endforeach
                    
                </div>
            </div>
            </div>
        </div>

        <div class="flex-1 overflow-y-auto p-4 bg-slate-50 custom-scrollbar">
            @if($barangs->count() > 0)
                <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-3 pb-20">
                    @foreach($barangs as $barang)
                        <div class="group bg-white rounded-lg border shadow-sm cursor-pointer flex flex-col h-full relative transition-all" 
                             :class="isPoMode ? 'hover:border-yellow-400 hover:ring-2 hover:ring-yellow-100 border-gray-200' : 'hover:border-blue-400 border-gray-200'"
                             @click="addToCart({{ $barang }})">
                            <div class="h-32 w-full bg-white relative p-2 flex items-center justify-center border-b border-gray-100 rounded-t-lg"
                                 :class="(!isPoMode && {{ $barang->stok }} <= 0) ? 'opacity-50 grayscale' : ''">
                                @if($barang->gambar) <img src="{{ asset('storage/' . $barang->gambar) }}" class="max-h-full max-w-full object-contain">
                                @else <span class="text-3xl">📦</span> @endif
                                
                                <span x-show="isPoMode" class="absolute top-2 left-2 bg-yellow-400 text-yellow-900 text-[9px] font-black px-1.5 py-0.5 rounded shadow-sm" style="display: none;">MODE PO</span>
                                
                                <button @click.stop="openDetail({{ $barang }})" class="absolute top-2 right-2 bg-white text-gray-500 border border-gray-200 hover:bg-blue-50 hover:text-blue-600 p-1.5 rounded-full shadow-sm">
                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                </button>
                            </div>
                            
                            <div class="p-3 flex flex-col flex-1 justify-between" :class="(!isPoMode && {{ $barang->stok }} <= 0) ? 'opacity-60' : ''">
                                <div>
                                    <h5 class="text-xs font-bold text-gray-700 line-clamp-2">{{ $barang->nama_barang }}</h5>
                                    
                                    <!-- ========================================================= -->
                                    <!-- 👇 TAMBAHAN KRUSIAL: BADGE SPEK TEKNIS (SOKET, WATT, DLL) 👇 -->
                                    <!-- ========================================================= -->
                                    <div class="flex flex-wrap gap-1 mt-1.5">
                                        @if($barang->socket)
                                            <span class="text-[9px] font-bold bg-purple-50 text-purple-700 border border-purple-200 px-1.5 py-0.5 rounded">
                                                ⚡ {{ $barang->socket }}
                                            </span>
                                        @endif
                                        @if($barang->tipe_memory)
                                            <span class="text-[9px] font-bold bg-blue-50 text-blue-700 border border-blue-200 px-1.5 py-0.5 rounded">
                                                💾 {{ $barang->tipe_memory }}
                                            </span>
                                        @endif
                                        @if($barang->power_watt && $barang->power_watt > 0)
                                            <span class="text-[9px] font-bold bg-amber-50 text-amber-700 border border-amber-200 px-1.5 py-0.5 rounded">
                                                🔋 {{ $barang->power_watt }}W
                                            </span>
                                        @endif
                                        @if($barang->form_factor)
                                            <span class="text-[9px] font-bold bg-slate-100 text-slate-600 border border-slate-200 px-1.5 py-0.5 rounded">
                                                📐 {{ $barang->form_factor }}
                                            </span>
                                        @endif
                                    </div>
                                    <!-- ========================================================= -->

                                    <div class="flex items-center gap-1 mt-2">
                                        <div class="h-1.5 w-1.5 rounded-full {{ $barang->stok > 0 ? 'bg-green-500' : 'bg-red-500' }}"></div>
                                        <span class="text-[10px] font-medium {{ $barang->stok > 0 ? 'text-gray-500' : 'text-red-500 font-bold' }}">
                                            {{ $barang->stok > 0 ? 'Stok: ' . $barang->stok : 'Stok Habis (PO)' }}
                                        </span>
                                    </div>
                                </div>
                                <div class="mt-2 text-right"><span class="block text-sm font-bold text-gray-900">Rp {{ number_format($barang->harga_jual, 0, ',', '.') }}</span></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="h-full flex items-center justify-center text-gray-400"><p class="text-sm">Barang tidak ditemukan</p></div>
            @endif
        </div>
    </div>

    <div class="w-full md:w-96 bg-white border-l border-gray-200 shadow-xl flex flex-col h-full z-20 shrink-0 relative transition-colors duration-300"
         :class="isPoMode ? 'bg-yellow-50/30' : 'bg-white'">
        
        <div class="p-4 border-b border-gray-100 bg-white shrink-0 shadow-sm z-10">
            <div class="flex items-center justify-between mb-3">
                <h3 class="font-bold text-gray-800 text-base flex items-center gap-2">
                    🛒 Current Order
                </h3>
                <button @click="showResetModal = true" x-show="cart.length > 0" class="text-[10px] font-bold text-red-500 hover:text-red-700 bg-red-50 px-2 py-1 rounded flex items-center gap-1">
                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                    RESET
                </button>
            </div>
            
            <div class="flex bg-gray-100 rounded-lg p-1 w-full border border-gray-200">
                <button @click="toggleMode(false)" 
                        :class="!isPoMode ? 'bg-white shadow text-blue-700 ring-1 ring-gray-200' : 'text-gray-500 hover:text-gray-800 hover:bg-gray-200'" 
                        class="flex-1 py-1.5 text-xs font-black rounded-md transition-all">
                    ✅ TRANSAKSI REGULER
                </button>
                <button @click="toggleMode(true)" 
                        :class="isPoMode ? 'bg-yellow-400 shadow text-yellow-900 ring-1 ring-yellow-500' : 'text-gray-500 hover:text-gray-800 hover:bg-gray-200'" 
                        class="flex-1 py-1.5 text-xs font-black rounded-md transition-all">
                    ⏳ MODE PRE-ORDER
                </button>
            </div>

            <div x-show="isPoMode" class="mt-3" x-transition style="display: none;">
                <button @click="showCustomPoModal = true" class="w-full py-2 border-2 border-dashed border-yellow-400 text-yellow-700 bg-yellow-50 rounded-lg text-xs font-bold hover:bg-yellow-100 transition-all flex justify-center items-center gap-1 shadow-sm">
                    <span>➕</span> Input Barang Custom (Luar Katalog)
                </button>
            </div>
        </div>

        <div class="flex-1 overflow-y-auto p-4 space-y-3 custom-scrollbar">
            <template x-for="(item, index) in cart" :key="item.id">
                <div class="flex gap-3 bg-white p-3 rounded-xl border shadow-sm transition-all relative"
                     :class="isPoMode ? 'border-yellow-300 ring-1 ring-yellow-100' : 'border-gray-200'">
                    
                    <div class="h-14 w-14 bg-gray-50 rounded-lg flex-shrink-0 flex items-center justify-center border border-gray-100 relative">
                        <template x-if="item.image"><img :src="'/storage/' + item.image" class="h-full w-full object-contain p-1"></template>
                        <template x-if="!item.image"><span class="text-lg">📦</span></template>
                        <span x-show="item.is_custom" class="absolute -top-2 -left-2 bg-blue-500 text-white text-[8px] px-1.5 py-0.5 rounded shadow font-bold">CUSTOM</span>
                        <span x-show="item.is_po && !item.is_custom" class="absolute -top-2 -left-2 bg-yellow-500 text-white text-[8px] px-1.5 py-0.5 rounded shadow font-bold">PO</span>
                    </div>

                    <div class="flex-1 min-w-0 flex flex-col justify-between py-0.5">
                        <div class="flex justify-between items-start gap-2">
                            <h4 class="font-bold text-gray-700 text-xs line-clamp-2" x-text="item.name"></h4>
                            <button @click="removeFromCart(index)" class="text-gray-300 hover:text-red-500">✕</button>
                        </div>
                        
                        <div class="flex justify-between items-end mt-2">
                            <div class="flex items-center bg-gray-100 rounded-lg h-6">
                                <button @click="updateQty(index, -1)" class="w-6 font-bold hover:text-red-600">-</button>
                                <span class="text-[10px] font-bold w-6 text-center" x-text="item.qty"></span>
                                <button @click="updateQty(index, 1)" class="w-6 font-bold hover:text-green-600">+</button>
                            </div>

                            <div class="text-right">
                                <template x-if="!isPoMode">
                                    <span class="font-black text-blue-600 text-xs" x-text="formatRupiah(item.price * item.qty)"></span>
                                </template>
                                
                                <template x-if="isPoMode">
                                    <div>
                                        <span class="text-[8px] text-gray-400 block mb-0.5">Edit Harga PO (Satuan):</span>
                                        <div class="flex items-center bg-yellow-50 border border-yellow-300 rounded px-1 py-0.5">
                                            <span class="text-[9px] font-bold text-yellow-700 mr-1">Rp</span>
                                            <input type="number" x-model.number="item.price" class="w-20 text-xs font-black text-yellow-800 bg-transparent border-none p-0 text-right focus:ring-0">
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
            
            <div x-show="cart.length === 0" class="flex flex-col items-center justify-center h-64 text-gray-400 opacity-60">
                <span class="text-4xl mb-2" x-text="isPoMode ? '⏳' : '🛒'"></span>
                <p class="text-xs font-bold" x-text="isPoMode ? 'Keranjang PO Kosong' : 'Keranjang Kosong'"></p>
                <p class="text-[10px] mt-1 text-center px-4" x-text="isPoMode ? 'Silakan pilih dari katalog atau input barang custom.' : 'Silakan pilih barang yang ready stock.'"></p>
            </div>
        </div>

        <div class="p-5 bg-white border-t border-gray-200 shrink-0 shadow-lg z-20">
            <div x-show="cart.length > 0" class="flex justify-between items-center mb-3 text-[10px] font-bold text-gray-500 uppercase tracking-wide">
                <div class="flex gap-2">
                    <span x-show="!isPoMode" class="bg-green-100 text-green-700 px-2 py-1 rounded">✅ TRANSAKSI REGULER</span>
                    <span x-show="isPoMode" class="bg-yellow-100 text-yellow-700 px-2 py-1 rounded">⏳ TRANSAKSI PRE-ORDER</span>
                </div>
            </div>

            <div class="flex justify-between items-end mb-3">
                <span class="text-xs text-gray-500 font-medium">Total Tagihan</span>
                <span class="text-xl font-black text-gray-900 tracking-tight" x-text="'Rp ' + formatRupiah(totalCart)"></span>
            </div>
            
            <button type="button" @click="openCheckout()" class="w-full py-3.5 rounded-xl font-bold text-white shadow-lg transition-all flex justify-center items-center gap-2 text-sm transform active:scale-95" 
                    :class="cart.length > 0 ? (isPoMode ? 'bg-yellow-500 hover:bg-yellow-600 text-yellow-900 shadow-yellow-200' : 'bg-gray-900 hover:bg-black') : 'bg-gray-300 cursor-not-allowed'" 
                    :disabled="cart.length === 0">
                LANJUT PEMBAYARAN
            </button>
        </div>
    </div>

  <div x-show="showCustomPoModal" style="display: none;" class="fixed inset-0 z-[100] overflow-y-auto" role="dialog" aria-modal="true">
    <div x-show="showCustomPoModal" x-transition.opacity class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity"></div>
    <div class="flex min-h-full items-center justify-center p-4 text-center">
        <div x-show="showCustomPoModal" 
             x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
             class="relative transform overflow-hidden rounded-xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-md border-2 border-yellow-400">
            
            <div class="bg-yellow-400 px-4 py-3 flex justify-between items-center">
                <h3 class="text-sm font-black text-yellow-900 uppercase">Input Barang Custom (PO)</h3>
                <button type="button" @click="showCustomPoModal = false" class="text-yellow-800 hover:text-red-600 font-bold">✕</button>
            </div>
            
            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Nama Barang Custom *</label>
                    <input type="text" x-model="customItem.name" class="w-full rounded-lg border-gray-300 text-sm focus:ring-yellow-400 focus:border-yellow-400" placeholder="Contoh: Casing Lian Li O11 Dynamic EVO">
                </div>

                <div class="grid grid-cols-3 gap-3">
                    <div class="col-span-3 sm:col-span-2">
                        <label class="block text-xs font-bold text-gray-700 mb-1">Harga Satuan (Rp) *</label>
                        <input type="text" 
                            :value="formatRupiah(customItem.price)" 
                            @input="customItem.price = parseRupiah($event.target.value)" 
                            class="w-full rounded-lg border-gray-300 text-sm focus:ring-yellow-400 focus:border-yellow-400" 
                            placeholder="0">
                    </div>
                    
                    <div class="col-span-3 sm:col-span-1">
                        <label class="block text-xs font-bold text-gray-700 mb-1">Qty *</label>
                        <input type="number" x-model.number="customItem.qty" min="1" class="w-full rounded-lg border-gray-300 text-sm focus:ring-yellow-400 focus:border-yellow-400 text-center">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Estimasi Waktu Pengadaan *</label>
                    <div class="relative rounded-lg shadow-sm">
                        <input type="number" x-model.number="customItem.estimasi_po" min="1" class="w-full rounded-lg border-gray-300 text-sm focus:ring-yellow-400 focus:border-yellow-400 pr-12" placeholder="Contoh: 7">
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                            <span class="text-xs font-bold text-gray-500">Hari</span>
                        </div>
                    </div>
                    <p class="text-[10px] text-gray-400 mt-1">* Lama waktu pengerjaan rakitan atau inden barang ke distributor.</p>
                </div>
            </div>
            
            <div class="bg-gray-50 px-6 py-4 flex flex-row-reverse gap-2 border-t border-gray-100">
                <button type="button" @click="addCustomToCart()" class="w-full justify-center rounded-lg bg-yellow-500 px-4 py-2 text-sm font-bold text-white shadow-sm hover:bg-yellow-600">Tambahkan ke Keranjang</button>
            </div>
        </div>
    </div>
</div>

<!-- checkout -->
<div x-show="showCheckout" style="display: none;" class="fixed inset-0 z-[60] overflow-y-auto" role="dialog" aria-modal="true">
        <div x-show="showCheckout" x-transition.opacity class="fixed inset-0 bg-slate-900/70 backdrop-blur-sm transition-opacity"></div>
        <div class="flex min-h-full items-center justify-center p-4 text-center">
            
            <div x-show="showCheckout" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
                 class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-5xl border border-slate-200">
                
                <div class="bg-slate-900 px-6 py-4 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-blue-600 flex items-center justify-center text-white">
                            <i class="fas fa-shopping-cart text-xs"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-black text-white tracking-wide" x-text="isPoMode ? 'TRANSAKSI PRE-ORDER (PO)' : 'CHECKOUT TRANSAKSI'"></h3>
                            <p class="text-xs text-slate-400 font-medium">Lengkapi data untuk memproses pesanan</p>
                        </div>
                    </div>
                    <button type="button" @click="showCheckout = false" class="text-slate-400 hover:text-white transition-colors">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>

                <form action="{{ route('admin.transaksi.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="cart_data" :value="JSON.stringify(cart)">
                    <input type="hidden" name="total_amount" :value="totalCart">
                    <input type="hidden" name="has_po" :value="isPoMode ? 1 : 0">
                    <input type="hidden" name="is_rakit" :value="isAssembly ? 1 : 0">

                    <div class="px-6 py-6 md:px-8">
                        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                            
                            <div class="lg:col-span-7 space-y-5" x-data="{ customerMode: 'baru' }">
                                <h4 class="text-sm font-black text-slate-800 uppercase tracking-widest border-b border-slate-200 pb-2 mb-4">
                                    <i class="fas fa-user-circle text-blue-600 mr-1"></i> Data Customer
                                </h4>
                                
                                <div class="flex p-1 bg-slate-100 rounded-lg border border-slate-200/60">
                                    <button type="button" @click="customerMode = 'baru'" 
                                            :class="customerMode === 'baru' ? 'bg-white shadow-sm text-blue-700 font-bold' : 'text-slate-500 hover:text-slate-700 font-medium'" 
                                            class="flex-1 py-1.5 text-xs rounded-md transition-all flex items-center justify-center gap-2">
                                        <i class="fas fa-user-plus"></i> Pelanggan Baru
                                    </button>
                                    <button type="button" @click="customerMode = 'lama'" 
                                            :class="customerMode === 'lama' ? 'bg-white shadow-sm text-blue-700 font-bold' : 'text-slate-500 hover:text-slate-700 font-medium'" 
                                            class="flex-1 py-1.5 text-xs rounded-md transition-all flex items-center justify-center gap-2">
                                        <i class="fas fa-database"></i> Database Pelanggan
                                    </button>
                                </div>

                                <div x-show="customerMode === 'lama'" x-transition.opacity class="bg-blue-50/50 p-4 rounded-lg border border-blue-100">
                                    <label class="block text-xs font-bold text-blue-800 mb-2">Pilih Pelanggan <span class="text-blue-500 font-normal italic">(Auto-fill)</span></label>
                                    <select name="customer_id" class="w-full rounded-md border-blue-200 text-sm py-2 px-3 focus:ring-blue-500 focus:border-blue-500 text-slate-700 shadow-sm" 
                                            @change="
                                                if($event.target.value !== '') {
                                                    let opt = $event.target.options[$event.target.selectedIndex];
                                                    document.getElementById('c_nama').value = opt.dataset.nama || '';
                                                    document.getElementById('c_wa').value = opt.dataset.wa || '';
                                                    document.getElementById('c_perusahaan').value = opt.dataset.perusahaan || '';
                                                    document.getElementById('c_email').value = opt.dataset.email || '';
                                                    document.getElementById('c_alamat').value = opt.dataset.alamat || '';
                                                } else {
                                                    document.getElementById('c_nama').value = '';
                                                    document.getElementById('c_wa').value = '';
                                                    document.getElementById('c_perusahaan').value = '';
                                                    document.getElementById('c_email').value = '';
                                                    document.getElementById('c_alamat').value = '';
                                                }
                                            ">
                                        <option value="">-- Ketik Nama atau WA --</option>
                                        @foreach($customers ?? [] as $cust)
                                            <option value="{{ $cust->id }}" 
                                                    data-nama="{{ $cust->nama_customer ?? $cust->nama }}" 
                                                    data-wa="{{ $cust->nomor_telepon ?? $cust->whatsapp }}" 
                                                    data-perusahaan="{{ $cust->perusahaan }}" 
                                                    data-email="{{ $cust->email }}" 
                                                    data-alamat="{{ $cust->alamat }}">
                                                {{ $cust->nama_customer ?? $cust->nama }} - {{ $cust->nomor_telepon ?? $cust->whatsapp }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Nama Lengkap <span class="text-red-500">*</span></label>
                                        <input type="text" name="nama_customer" id="c_nama" required class="w-full rounded-md border-slate-300 text-sm py-2 px-3 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 bg-slate-50 focus:bg-white transition-colors" placeholder="Cth: John Doe">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-slate-700 mb-1.5">WhatsApp <span class="text-red-500">*</span></label>
                                        <input type="text" name="nomor_telepon" id="c_wa" required class="w-full rounded-md border-slate-300 text-sm py-2 px-3 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 bg-slate-50 focus:bg-white transition-colors" placeholder="0812...">
                                    </div>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Perusahaan <span class="text-slate-400 font-normal">(Opsional)</span></label>
                                        <input type="text" name="perusahaan" id="c_perusahaan" class="w-full rounded-md border-slate-300 text-sm py-2 px-3 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 bg-slate-50 focus:bg-white transition-colors" placeholder="PT / CV...">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Email <span class="text-slate-400 font-normal">(Wajib)</span></label>
                                        <input type="email" name="email" id="c_email" class="w-full rounded-md border-slate-300 text-sm py-2 px-3 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 bg-slate-50 focus:bg-white transition-colors" placeholder="email@contoh.com">
                                    </div>
                                </div>

                                <div class="mb-5">
    <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2">
        Opsi Perakitan <span class="text-red-500">*</span>
    </label>
    
    <div class="grid grid-cols-2 gap-3">
        <div @click="isAssembly = true" 
             class="cursor-pointer p-3 border-2 rounded-xl transition-all flex items-center gap-3" 
             :class="isAssembly ? 'border-blue-600 bg-blue-50' : 'border-slate-200 bg-white hover:border-blue-300'">
            <div class="w-10 h-10 rounded-lg flex items-center justify-center flex-shrink-0"
                 :class="isAssembly ? 'bg-blue-100 text-blue-600' : 'bg-slate-100 text-slate-500'">
                <i class="fas fa-tools"></i>
            </div>
            <div class="text-left">
                <span class="block text-sm font-bold text-slate-800">Dirakit</span>
                <span class="block text-[10px]" :class="isAssembly ? 'text-blue-600' : 'text-slate-500'">Terima beres</span>
            </div>
        </div>

        <div @click="isAssembly = false" 
             class="cursor-pointer p-3 border-2 rounded-xl transition-all flex items-center gap-3" 
             :class="!isAssembly ? 'border-blue-600 bg-blue-50' : 'border-slate-200 bg-white hover:border-blue-300'">
            <div class="w-10 h-10 rounded-lg flex items-center justify-center flex-shrink-0"
                 :class="!isAssembly ? 'bg-blue-100 text-blue-600' : 'bg-slate-100 text-slate-500'">
                <i class="fas fa-box-open"></i>
            </div>
            <div class="text-left">
                <span class="block text-sm font-bold text-slate-800">Komponen Saja</span>
                <span class="block text-[10px]" :class="!isAssembly ? 'text-blue-600' : 'text-slate-500'">Box segel utuh</span>
            </div>
        </div>
    </div>
    </div>
                                
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Alamat Lengkap Pengiriman</label>
                                    <textarea name="alamat" id="c_alamat" rows="2" class="w-full rounded-md border-slate-300 text-sm py-2 px-3 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 bg-slate-50 focus:bg-white transition-colors resize-none" placeholder="Detail alamat pengiriman atau instalasi..."></textarea>
                                </div>

                                <div x-show="isPoMode" x-transition class="bg-amber-50 p-4 rounded-lg border border-amber-200 mt-4">
                                    <label class="block text-xs font-black text-amber-800 uppercase tracking-wider mb-2 flex items-center gap-2">
                                        <i class="fas fa-clock text-amber-600"></i> Estimasi Barang PO Ready <span class="text-red-500">*</span>
                                    </label>
                                    <input type="date" name="estimasi_ready" class="w-full rounded-md border-amber-300 bg-white text-sm py-2 px-3 font-bold text-slate-800 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500" :required="isPoMode">
                                </div>
                            </div>
                            
                            <div class="lg:col-span-5 bg-slate-50/50 p-6 rounded-xl border border-slate-200 h-fit">
                                <h4 class="text-sm font-black text-slate-800 uppercase tracking-widest border-b border-slate-200 pb-2 mb-4 flex items-center gap-2">
                                    <i class="fas fa-cash-register text-blue-600"></i> Logistik & Kasir
                                </h4>
                                
                                <div class="mb-5">
                                    <label class="block text-xs font-bold text-slate-700 mb-2">Metode Serah Terima</label>
                                    <div class="grid grid-cols-3 gap-2">
                                        <label class="cursor-pointer">
                                            <input type="radio" name="metode_pengiriman" value="pickup" class="peer sr-only" x-model="deliveryMethod" @change="updateShipping(0)">
                                            <div class="rounded-md border border-slate-300 bg-white p-2.5 text-center text-[10px] font-bold text-slate-500 peer-checked:bg-blue-50 peer-checked:text-blue-700 peer-checked:border-blue-600 hover:bg-slate-50 transition-all flex flex-col items-center gap-1.5">
                                                <i class="fas fa-store text-base"></i><span>Ambil Toko</span>
                                            </div>
                                        </label>
                                        <label class="cursor-pointer">
                                            <input type="radio" name="metode_pengiriman" value="delivery" class="peer sr-only" x-model="deliveryMethod">
                                            <div class="rounded-md border border-slate-300 bg-white p-2.5 text-center text-[10px] font-bold text-slate-500 peer-checked:bg-blue-50 peer-checked:text-blue-700 peer-checked:border-blue-600 hover:bg-slate-50 transition-all flex flex-col items-center gap-1.5">
                                                <i class="fas fa-truck text-base"></i><span>Ekspedisi</span>
                                            </div>
                                        </label>
                                        <label class="cursor-pointer relative">
                                            <input type="radio" name="metode_pengiriman" value="technician" class="peer sr-only" x-model="deliveryMethod" :disabled="totalCart < 20000000"> 
                                            <div class="rounded-md border p-2.5 text-center text-[10px] font-bold flex flex-col items-center gap-1.5 transition-all"
                                                 :class="totalCart < 20000000 ? 'bg-slate-100 text-slate-400 border-slate-200 cursor-not-allowed' : 'bg-white border-slate-300 text-slate-500 peer-checked:bg-blue-50 peer-checked:text-blue-700 peer-checked:border-blue-600 hover:bg-slate-50 cursor-pointer'">
                                                <i class="fas fa-tools text-base"></i><span>Kurir Teknisi</span>
                                            </div>
                                        </label>
                                    </div>
                                    <div x-show="totalCart < 20000000 && deliveryMethod !== 'technician'" class="text-[9px] text-red-500 mt-1.5 text-right font-medium">*Teknisi min. belanja 20Jt</div>
                                </div>
                                
                                <div x-show="deliveryMethod !== 'pickup'" x-transition class="mb-5 bg-white p-3 rounded-md border border-slate-200 shadow-sm">
                                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Biaya Logistik / Ongkir (Rp)</label>
                                    
                                    <input type="number" 
                                        name="biaya_ongkir" 
                                        x-model="shippingCost" 
                                        @input="updateShipping($event.target.value)" 
                                        @focus="if(shippingCost == 0) shippingCost = ''"
                                        @blur="if(shippingCost === '') shippingCost = 0"
                                        class="w-full rounded-md border-slate-300 font-bold text-sm py-2 px-3 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500" 
                                        placeholder="0">
                                </div>
                                
                                <div class="mb-5">
                                    <label class="block text-xs font-bold text-slate-700 mb-2">Status Pembayaran</label>
                                    <div class="grid grid-cols-3 gap-2"> 
                                        <label class="cursor-pointer">
                                            <input type="radio" name="status_pembayaran" value="lunas" x-model="paymentStatus" @change="payAmount = grandTotal" class="peer sr-only">
                                            <div class="rounded-md border border-slate-300 bg-white py-2 text-center text-xs font-bold text-slate-500 peer-checked:bg-emerald-50 peer-checked:text-emerald-700 peer-checked:border-emerald-600 hover:bg-slate-50 transition-all">
                                                Lunas
                                            </div>
                                        </label>
                                        <label class="cursor-pointer">
                                            <input type="radio" name="status_pembayaran" value="dp" x-model="paymentStatus" @change="payAmount = minDp" class="peer sr-only">
                                            <div class="rounded-md border border-slate-300 bg-white py-2 text-center text-xs font-bold text-slate-500 peer-checked:bg-amber-50 peer-checked:text-amber-700 peer-checked:border-amber-500 hover:bg-slate-50 transition-all">
                                                DP (50%)
                                            </div>
                                        </label>
                                        <label class="cursor-pointer">
                                            <input type="radio" name="status_pembayaran" value="belum_bayar" x-model="paymentStatus" @change="payAmount = 0" class="peer sr-only">
                                            <div class="rounded-md border border-slate-300 bg-white py-2 text-center text-xs font-bold text-slate-500 peer-checked:bg-slate-100 peer-checked:text-slate-800 peer-checked:border-slate-500 hover:bg-slate-50 transition-all">
                                                Pending
                                            </div>
                                        </label>
                                    </div>
                                </div>
                                
                                <div class="bg-slate-900 p-4 rounded-xl border-2 transition-colors relative overflow-hidden" :class="paymentStatus === 'dp' && payAmount < minDp ? 'border-red-500' : 'border-slate-800'">
                                    <div class="absolute right-[-10px] top-[-10px] opacity-10">
                                        <i class="fas fa-calculator text-6xl text-white"></i>
                                    </div>
                                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1 relative z-10">Nominal Diterima Kasir</label>
                                    <div class="relative z-10 flex items-center">
                                        <span class="text-slate-400 font-bold text-xl mr-2">Rp</span>
                                        <input type="number" name="bayar_nominal" x-model.number="payAmount" class="w-full border-none bg-transparent text-3xl font-black text-emerald-400 py-1 px-0 focus:ring-0" :readonly="paymentStatus === 'lunas' || paymentStatus === 'belum_bayar'" placeholder="0">
                                    </div>
                                    <div x-show="paymentStatus === 'dp'" class="mt-2 pt-2 border-t border-slate-700/50 flex justify-between text-[10px] relative z-10">
                                        <span class="text-slate-400">Minimal DP Wajib:</span>
                                        <span class="font-black" :class="payAmount < minDp ? 'text-red-400' : 'text-emerald-400'" x-text="formatRupiah(minDp)"></span>
                                    </div>
                                </div>
                                
                                <div class="mt-4">
                                    <textarea name="catatan_customer" rows="1" class="w-full rounded-md border-slate-300 text-xs py-2 px-3 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 bg-white" placeholder="Catatan internal transaksi (Opsional)..."></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="mt-8 pt-5 border-t border-slate-200 flex flex-col md:flex-row justify-between items-center gap-4">
                            <div class="text-left hidden md:block text-xs text-slate-500">
                                Pastikan semua data sudah divalidasi sebelum disimpan.
                            </div>
                            <div class="text-right w-full md:w-auto bg-blue-50/50 py-3 px-6 rounded-xl border border-blue-100">
                                <div class="text-xs text-slate-500 mb-1 flex justify-between gap-8"><span>Subtotal:</span> <span class="font-bold text-slate-700" x-text="formatRupiah(totalCart)"></span></div>
                                <div class="text-xs text-slate-500 mb-2 flex justify-between gap-8" x-show="shippingCost > 0"><span>Logistik:</span> <span class="font-bold text-slate-700" x-text="'+ ' + formatRupiah(shippingCost)"></span></div>
                                <div class="flex justify-between items-center gap-8 border-t border-blue-200/50 pt-2">
                                    <span class="text-[10px] uppercase font-black text-blue-800 tracking-widest">Grand Total</span>
                                    <span class="text-2xl font-black text-blue-700" x-text="'Rp ' + formatRupiah(grandTotal)"></span>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="bg-slate-50 px-6 py-4 flex flex-col-reverse md:flex-row justify-end gap-3 border-t border-slate-200 rounded-b-2xl">
                        <button type="button" @click="showCheckout = false" class="w-full md:w-auto rounded-md bg-white px-6 py-2.5 text-sm font-bold text-slate-600 shadow-sm border border-slate-300 hover:bg-slate-100 transition-colors">
                            Batalkan
                        </button>
                        <button type="submit" class="w-full md:w-auto rounded-md px-8 py-2.5 text-sm font-black text-white shadow-md transition-all transform active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2" 
                                :class="paymentStatus === 'belum_bayar' ? 'bg-slate-700 hover:bg-slate-800' : 'bg-blue-600 hover:bg-blue-700'"
                                :disabled="(paymentStatus === 'dp' && payAmount < minDp)">
                            <i class="fas" :class="paymentStatus === 'belum_bayar' ? 'fa-file-invoice' : 'fa-check-circle'"></i>
                            <span x-text="paymentStatus === 'belum_bayar' ? 'Simpan Penawaran' : 'Proses Pesanan'"></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>



    <div x-show="showResetModal" style="display: none;" class="fixed inset-0 z-[80] overflow-y-auto" role="dialog" aria-modal="true">
        <div x-show="showResetModal" x-transition.opacity class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm transition-opacity"></div>
        <div class="flex min-h-full items-center justify-center p-4 text-center">
            <div x-show="showResetModal" 
                 x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                 class="relative transform overflow-hidden rounded-xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-sm border border-gray-100">
                <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4 text-center">
                    <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-red-100 mb-4">
                        <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                    </div>
                    <h3 class="text-lg font-bold leading-6 text-gray-900">Kosongkan Keranjang?</h3>
                    <div class="mt-2"><p class="text-sm text-gray-500">Tindakan ini akan menghapus semua barang dari keranjang belanja.</p></div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6 justify-center gap-2">
                    <button type="button" @click="resetCartConfirmed()" class="inline-flex w-full justify-center rounded-lg bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 sm:w-auto">Ya, Kosongkan</button>
                    <button type="button" @click="showResetModal = false" class="mt-3 inline-flex w-full justify-center rounded-lg bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">Batal</button>
                </div>
            </div>
        </div>
    </div>
    
    <div x-show="showModal" style="display: none;" class="fixed inset-0 z-[60] overflow-y-auto">
        <div class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm"></div>
        <div class="flex min-h-full items-center justify-center p-4">
            <div class="bg-white rounded-xl shadow-xl w-full max-w-lg p-6 relative" @click.away="closeDetail()">
                <button @click="closeDetail()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-700">✕</button>
                <h3 class="font-bold text-lg" x-text="detailItem.nama_barang"></h3>
                <p class="text-xs text-gray-500 font-mono mt-1" x-text="detailItem.kode_barang"></p>
                <div class="flex flex-wrap gap-1.5 mt-2.5 pb-2 border-b border-gray-100">
                    <template x-if="detailItem.socket">
                        <span class="text-xs font-bold bg-purple-50 text-purple-700 border border-purple-200 px-2 py-0.5 rounded" x-text="'⚡ Soket: ' + detailItem.socket"></span>
                    </template>
                    <template x-if="detailItem.tipe_memory">
                        <span class="text-xs font-bold bg-blue-50 text-blue-700 border border-blue-200 px-2 py-0.5 rounded" x-text="'💾 RAM: ' + detailItem.tipe_memory"></span>
                    </template>
                    <template x-if="detailItem.power_watt > 0">
                        <span class="text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200 px-2 py-0.5 rounded" x-text="'🔋 Daya: ' + detailItem.power_watt + ' Watt'"></span>
                    </template>
                    <template x-if="detailItem.form_factor">
                        <span class="text-xs font-bold bg-slate-100 text-slate-700 border border-slate-200 px-2 py-0.5 rounded" x-text="'📐 Form: ' + detailItem.form_factor"></span>
                    </template>
                </div>
                
                <div class="mt-4 max-h-60 overflow-y-auto custom-scrollbar p-1">
                    <p class="text-sm text-gray-600 whitespace-pre-line mb-4" x-text="detailItem.deskripsi"></p>
                    
                    <template x-if="detailItem.components && detailItem.components.length > 0">
                        <div class="bg-gray-50 p-4 rounded-xl border border-gray-200 shadow-inner mt-2">
                            <h4 class="text-xs font-bold text-gray-500 uppercase mb-3 flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" /></svg>
                                Komponen Termasuk:
                            </h4>
                            <ul class="space-y-2">
                                <template x-for="comp in detailItem.components" :key="comp.id">
                                    <li class="flex justify-between items-center border-b border-gray-200 pb-2 last:border-0 last:pb-0">
                                        <span class="text-sm text-gray-700" x-text="comp.nama_barang"></span>
                                        <span class="font-bold text-gray-900 bg-white px-2 py-0.5 rounded text-xs border shadow-sm" x-text="'x' + (comp.pivot ? comp.pivot.qty : 1)"></span>
                                    </li>
                                </template>
                            </ul>
                        </div>
                    </template>
                </div>
                
                <div class="mt-5 flex justify-end">
                    <button @click="closeDetail()" class="bg-gray-200 text-gray-800 px-5 py-2 rounded-lg font-bold hover:bg-gray-300 transition">Tutup</button>
                </div>
            </div>
        </div>
    </div>

</div>

<script>
function posSystem() {
        return {
            // 👇 1. BACA BRANKAS SAAT HALAMAN DIMUAT 👇
            cart: JSON.parse(localStorage.getItem('pos_cart')) || [],
            
            isPoMode: false, 
            showCheckout: false,
            showResetModal: false, 
            showModal: false, 
            showCustomPoModal: false,
            detailItem: {},
            customItem: { name: '', price: '', qty: 1, estimasi_po: 7 },
            
            deliveryMethod: 'pickup', 
            paymentStatus: 'lunas', 
            shippingCost: 0, 
            payAmount: 0,
            isAssembly: false,
            toast: { visible: false, message: '', type: 'success' },

            // 👇 2. FUNGSI UNTUK MENYIMPAN KE BRANKAS 👇
            saveCart() {
                localStorage.setItem('pos_cart', JSON.stringify(this.cart));
            },

            init() {
                @if(session('success')) 
                    this.notify("{{ session('success') }}", 'success'); 
                    // Kalau transaksi berhasil, kosongkan keranjang dan brankasnya!
                    this.cart = [];
                    localStorage.removeItem('pos_cart');
                @endif
                
                @if(session('error')) 
                    this.notify("{{ session('error') }}", 'error'); 
                @endif
            },

            notify(message, type = 'success') {
                this.toast.message = message; this.toast.type = type; this.toast.visible = true;
                setTimeout(() => { this.toast.visible = false; }, 3000);
            },

            toggleMode(toPoMode) {
                if (this.isPoMode === toPoMode) return; 
                this.isPoMode = toPoMode;
                this.notify(toPoMode ? 'Fitur Input PO Dibuka' : 'Mode Reguler Aktif', toPoMode ? 'warning' : 'success');
            },
            
            openCheckout() {
                this.showCheckout = true;
                if(this.isPoMode) { 
                    this.paymentStatus = 'dp'; 
                    this.payAmount = this.minDp; 
                } else { 
                    this.paymentStatus = 'lunas'; 
                    this.payAmount = this.grandTotal; 
                }
                this.deliveryMethod = 'pickup'; this.shippingCost = 0;
            },

            checkCustomer(val) { console.log("Cek: " + val); },
            
            updateShipping(val) { 
                this.shippingCost = val || 0; 
                if(this.paymentStatus === 'lunas') this.payAmount = this.grandTotal;
                else if (this.paymentStatus === 'dp') this.payAmount = this.minDp;
                else this.payAmount = 0; 
            },
            
            openDetail(item) { this.detailItem = item; this.showModal = true; },
            closeDetail() { this.showModal = false; },

            addToCart(product) {
                let existingItem = this.cart.find(item => item.id === product.id);
                let isStockEmpty = product.stok <= 0;
                
                if (!this.isPoMode) {
                    if (isStockEmpty) {
                        this.notify('Stok habis! Ubah ke Mode PO untuk memesan.', 'error');
                        return;
                    }
                    if (existingItem) {
                        if (existingItem.qty < product.stok) existingItem.qty++;
                        else {
                            this.notify('Stok maksimal di gudang!', 'error');
                            return;
                        }
                    } else {
                        this.cart.push({ id: product.id, name: product.nama_barang, price: product.harga_jual, qty: 1, max_stock: product.stok, image: product.gambar, is_po: false, is_custom: false });
                        this.notify('Berhasil masuk keranjang', 'success');
                    }
                } else {
                    if (existingItem) {
                        existingItem.qty++;
                    } else {
                        this.cart.push({ id: product.id, name: product.nama_barang, price: product.harga_jual, qty: 1, max_stock: 0, image: product.gambar, is_po: true, is_custom: false });
                        this.notify('Masuk keranjang PO', 'warning');
                    }
                }
                
                // 👇 Simpan perubahan
                this.saveCart();
            },
            
            addCustomToCart() {
                if (!this.customItem.name || this.customItem.name.trim() === '') {
                    this.notify('Nama barang harus diisi!', 'error');
                    return;
                }
                if (!this.customItem.price || this.customItem.price <= 0) {
                    this.notify('Harga barang tidak valid!', 'error');
                    return;
                }

                this.cart.push({ 
                    id: 'custom-' + Date.now(),
                    name: this.customItem.name, 
                    price: this.customItem.price, 
                    qty: this.customItem.qty > 0 ? this.customItem.qty : 1, 
                    max_stock: 0, 
                    image: null, 
                    estimasi_po: this.customItem.estimasi_po,
                    is_po: true,
                    is_custom: true
                });
                
                this.notify('Barang custom ditambahkan!', 'success');
                this.customItem = { name: '', price: '', qty: 1, estimasi_po: 7 };
                this.showCustomPoModal = false;
                
                // 👇 Simpan perubahan
                this.saveCart();
            },

            updateQty(index, amount) {
                let item = this.cart[index];
                let newQty = item.qty + amount;
                
                if (this.isPoMode) {
                    if (newQty > 0) item.qty = newQty; else this.removeFromCart(index);
                } else {
                    if (newQty > item.max_stock) { this.notify('Stok maksimal!', 'error'); return; }
                    if (newQty > 0) item.qty = newQty; else this.removeFromCart(index);
                }
                
                // 👇 Simpan perubahan
                this.saveCart();
            },

            removeFromCart(index) { 
                this.cart.splice(index, 1); 
                // 👇 Simpan perubahan
                this.saveCart();
            },
            
            resetCartConfirmed() { 
                this.cart = []; 
                this.showResetModal = false; 
                this.notify('Keranjang dikosongkan', 'success'); 
                // 👇 Bersihkan brankas juga
                localStorage.removeItem('pos_cart');
            },

            get totalCart() { return this.cart.reduce((t, i) => t + (Number(i.price) * Number(i.qty)), 0); },
            get grandTotal() { return this.totalCart + parseInt(this.shippingCost || 0); },
            get minDp() { return Math.ceil(this.grandTotal * 0.5); },

            formatRupiah(angka) {
                if (!angka) return '';
                let number_string = angka.toString().replace(/[^,\d]/g, '');
                return number_string.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
            },

            parseRupiah(text) {
                if (!text) return 0;
                return parseInt(text.toString().replace(/\./g, '')) || 0;
            }
        } 
    }
        
        
</script>

<style>
    .scrollbar-hide::-webkit-scrollbar { display: none; }
    .custom-scrollbar::-webkit-scrollbar { width: 4px; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
    
    input[type=number]::-webkit-inner-spin-button, 
    input[type=number]::-webkit-outer-spin-button { 
        -webkit-appearance: none; 
        margin: 0; 
    }
</style>
@endsection