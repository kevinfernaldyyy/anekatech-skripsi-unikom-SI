@extends('layouts.admin')

@section('title', 'Atur Paket PC')

@section('content')
<div class="max-w-6xl mx-auto">
    
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Manajemen Rakitan: {{ $paket->nama_barang }}</h2>
            <div class="flex items-center gap-2 mt-1">
                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                    🧩 Master Template
                </span>
                <p class="text-gray-500 text-sm ml-2">Stok Master: <span class="font-bold text-gray-700">0 (Selalu 0)</span></p>
            </div>
        </div>
        <a href="{{ route('admin.barang.index', ['jenis' => 'paket_menu']) }}" class="text-blue-600 hover:underline">&larr; Kembali</a>
    </div>

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 shadow-sm flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
            {{ session('error') }}
        </div>
    @endif
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 shadow-sm flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- KOLOM KIRI: KOMPOSISI RESEP -->
        <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-200 overflow-visible relative">
            <div class="p-6 border-b border-gray-100 bg-gray-50 rounded-t-xl flex justify-between items-center">
                <h3 class="font-bold text-gray-800 text-lg">1. Komposisi Resep (Master)</h3>
                <span class="text-xs font-medium text-green-600 bg-green-50 px-3 py-1 rounded-full border border-green-200">
                    Mode Edit Aktif
                </span>
            </div>
            
            <div class="p-6">
                <form action="{{ route('admin.bundle.update', $paket->id) }}" method="POST" x-data="resepHandler()">
                    <div class="mb-6 p-4 bg-slate-900 text-white rounded-xl shadow-md border border-slate-800">
                        <div class="flex items-center justify-between border-b border-slate-800 pb-3 mb-3">
                            <span class="text-xs font-black uppercase tracking-widest text-slate-400 flex items-center gap-1.5">
                                <i class="fas fa-microchip text-blue-400"></i> Live Spesifikasi Rakitan
                            </span>
                            <!-- Badge Kompatibilitas Otomatis -->
                            <template x-if="!summary.hasConflict">
                                <span class="text-[10px] font-bold bg-emerald-500/20 text-emerald-400 border border-emerald-500/30 px-2.5 py-0.5 rounded-full">
                                    ✓ Kompatibel
                                </span>
                            </template>
                            <template x-if="summary.hasConflict">
                                <span class="text-[10px] font-bold bg-red-500/20 text-red-400 border border-red-500/30 px-2.5 py-0.5 rounded-full animate-pulse">
                                    ⚠ Ada Konflik Soket/RAM!
                                </span>
                            </template>
                        </div>

                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 text-center">
                            <!-- 1. Soket Sistem -->
                            <div class="bg-slate-800/80 p-2.5 rounded-lg border" :class="summary.socketConflict ? 'border-red-500 bg-red-950/30' : 'border-slate-700'">
                                <span class="text-[10px] text-slate-400 uppercase font-bold block">Soket CPU/Mobo</span>
                                <span class="text-sm font-black mt-0.5 block" :class="summary.socketConflict ? 'text-red-400' : 'text-purple-400'" x-text="summary.socket"></span>
                            </div>

                            <!-- 2. Tipe Memory RAM -->
                            <div class="bg-slate-800/80 p-2.5 rounded-lg border" :class="summary.memoryConflict ? 'border-red-500 bg-red-950/30' : 'border-slate-700'">
                                <span class="text-[10px] text-slate-400 uppercase font-bold block">Tipe RAM</span>
                                <span class="text-sm font-black mt-0.5 block" :class="summary.memoryConflict ? 'text-red-400' : 'text-blue-400'" x-text="summary.memory"></span>
                            </div>

                            <!-- 3. Total Kebutuhan Daya (TDP) -->
                            <div class="bg-slate-800/80 p-2.5 rounded-lg border border-slate-700">
                                <span class="text-[10px] text-slate-400 uppercase font-bold block">Est. Beban Daya</span>
                                <span class="text-sm font-black text-amber-400 mt-0.5 block" x-text="summary.tdpWatt > 0 ? summary.tdpWatt + ' W' : '-'"></span>
                            </div>

                            <!-- 4. Kapasitas PSU -->
                            <div class="bg-slate-800/80 p-2.5 rounded-lg border" :class="summary.isPsuUnderpowered ? 'border-red-500 bg-red-950/30' : 'border-slate-700'">
                                <span class="text-[10px] text-slate-400 uppercase font-bold block">Kapasitas PSU</span>
                                <span class="text-sm font-black mt-0.5 block" 
                                      :class="summary.isPsuUnderpowered ? 'text-red-400' : 'text-emerald-400'" 
                                      x-text="summary.psuWatt > 0 ? summary.psuWatt + ' W' : 'Belum Dipilih'"></span>
                            </div>
                        </div>

                        <!-- Peringatan Daya PSU Kurang -->
                        <template x-if="summary.isPsuUnderpowered">
                            <div class="mt-3 text-[11px] text-red-300 bg-red-900/40 border border-red-800 p-2 rounded text-center font-medium">
                                ⚠ Peringatan: Kapasitas PSU (<span x-text="summary.psuWatt+'W'"></span>) lebih kecil dari kebutuhan daya komponen (<span x-text="summary.tdpWatt+'W'"></span>)!
                            </div>
                        </template>
                    </div>

                    @csrf
                    @method('PUT')
        
                    <div class="space-y-4">
                        <template x-for="(row, index) in rows" :key="row.id">
                            <div class="flex gap-3 items-start relative group">
                                
                                <div class="flex-1 relative" x-data="{ 
                                        open: false, 
                                        search: '', 
                                        selectedName: '',
                                        maxStok: 0,
                                        
                                        init() {
                                            if(row.val) {
                                                let item = this.findItem(row.val);
                                                if(item) {
                                                    this.selectedName = item.nama_barang;
                                                    this.maxStok = item.stok;
                                                }
                                            }
                                            this.$watch('row.val', (value) => {
                                                let item = this.findItem(value);
                                                if(item) {
                                                    this.selectedName = item.nama_barang;
                                                    this.maxStok = item.stok;
                                                    if(row.qty > item.stok) {
                                                        row.qty = item.stok > 0 ? item.stok : 1;
                                                    }
                                                } else {
                                                    this.selectedName = '';
                                                    this.maxStok = 0;
                                                }
                                            });
                                        },
                                        findItem(id) {
                                            return allKomponens.find(i => i.id == id);
                                        },
                                        get filteredItems() {
                                            let usedIds = [];
                                            let usedCategories = [];
                                            const singleChoiceCategories = [
                                                'Processor', 
                                                'Motherboard', 
                                                'VGA / GPU', 
                                                'Casing', 
                                                'PSU', 
                                                'RAM'
                                            ];

                                            this.rows.forEach(r => {
                                                if (r.val !== '' && r.val != row.val) {
                                                    usedIds.push(parseInt(r.val));
                                                    let foundItem = allKomponens.find(i => i.id == r.val);
                                                    if (foundItem && foundItem.kategori) {
                                                        usedCategories.push(foundItem.kategori);
                                                    }
                                                }
                                            });

                                            return allKomponens.filter(i => {
                                                if (usedIds.includes(parseInt(i.id))) {
                                                    return false;
                                                }
                                                if (singleChoiceCategories.includes(i.kategori) && usedCategories.includes(i.kategori)) {
                                                    return false;
                                                }
                                                if (this.search === '') {
                                                    return true;
                                                }
                                                return i.nama_barang.toLowerCase().includes(this.search.toLowerCase()) || 
                                                       i.kode_barang.toLowerCase().includes(this.search.toLowerCase());
                                            });
                                        }
                                    }">
        
                                    <input type="hidden" name="components[]" :value="row.val">
        
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                                        </div>
                                        <input type="text" 
                                               x-model="selectedName"
                                               @click="open = true; search = '';"
                                               @input="open = true; search = $el.value"
                                               @click.away="open = false"
                                               class="w-full pl-10 pr-8 py-2.5 rounded-lg border border-gray-300 text-sm focus:ring-2 focus:ring-blue-100 focus:border-blue-500 shadow-sm"
                                               placeholder="Cari komponen..."
                                               autocomplete="off"
                                               required>
                                        
                                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                            <svg class="h-4 w-4 text-gray-400" :class="{'rotate-180': open}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                                        </div>
                                    </div>
        
                                    <div x-show="open" 
                                         class="absolute z-50 w-full mt-2 bg-white rounded-xl shadow-2xl border border-gray-100 max-h-72 overflow-auto"
                                         style="display: none;">
                                        <ul class="py-1 divide-y divide-gray-50">
                                            <template x-for="item in filteredItems" :key="item.id">
                                                <li @click="row.val = item.id; selectedName = item.nama_barang; maxStok = item.stok; open = false;"
                                                    class="cursor-pointer px-4 py-3 hover:bg-blue-50 transition-colors border-b border-gray-50 last:border-none">
                                                    <div class="flex justify-between items-start gap-2">
                                                        
                                                        <div class="flex-1">
                                                            <span class="font-bold text-gray-800 text-sm block leading-snug" x-text="item.nama_barang"></span>
                                                            
                                                            <div class="flex flex-wrap items-center gap-1 mt-1.5">
                                                                <span class="text-[10px] text-gray-500 font-mono bg-gray-100 px-1.5 py-0.5 rounded border border-gray-200" 
                                                                      x-text="item.kode_barang"></span>
                                                                
                                                                <span x-show="item.socket" 
                                                                      class="text-[9px] font-bold bg-purple-100 text-purple-700 px-1.5 py-0.5 rounded border border-purple-200 inline-flex items-center gap-1" 
                                                                      style="display: none;">
                                                                    <i class="fas fa-microchip"></i>
                                                                    <span x-text="item.socket"></span>
                                                                </span>

                                                                <span x-show="item.tipe_memory" 
                                                                      class="text-[9px] font-bold bg-blue-100 text-blue-700 px-1.5 py-0.5 rounded border border-blue-200 inline-flex items-center gap-1" 
                                                                      style="display: none;">
                                                                    <i class="fas fa-memory"></i>
                                                                    <span x-text="item.tipe_memory"></span>
                                                                </span>

                                                                <span x-show="item.form_factor" 
                                                                      class="text-[9px] font-bold bg-slate-100 text-slate-700 px-1.5 py-0.5 rounded border border-slate-200 inline-flex items-center gap-1" 
                                                                      style="display: none;">
                                                                    <i class="fas fa-border-all"></i>
                                                                    <span x-text="item.form_factor"></span>
                                                                </span>

                                                                <span x-show="item.power_watt > 0" 
                                                                      class="text-[9px] font-bold bg-amber-100 text-amber-700 px-1.5 py-0.5 rounded border border-amber-200 inline-flex items-center gap-1" 
                                                                      style="display: none;">
                                                                    <i class="fas fa-bolt"></i>
                                                                    <span x-text="item.power_watt + 'W'"></span>
                                                                </span>
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="text-right shrink-0">
                                                            <span class="block text-xs font-black px-2 py-0.5 rounded" 
                                                                  :class="item.stok > 0 ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'" 
                                                                  x-text="'Stok: ' + item.stok"></span>
                                                        </div>

                                                    </div>
                                                </li>
                                            </template>
                                            <li x-show="filteredItems.length === 0" class="px-4 py-8 text-center text-gray-400 text-sm">Tidak ditemukan</li>
                                        </ul>
                                    </div>
                                    
                                    <div class="absolute -bottom-4 left-2 text-[10px] text-gray-500" x-show="maxStok > 0">
                                        Stok Gudang saat ini: <span class="font-bold text-blue-600" x-text="maxStok"></span>
                                    </div>
                                </div>
                                
                                <div class="w-24 relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"><span class="text-gray-400 text-xs font-bold">x</span></div>
                                    <input type="number" name="qtys[]" 
                                           x-model="row.qty"
                                           @input="if(maxStok > 0 && row.qty > maxStok) row.qty = maxStok"
                                           :max="maxStok > 0 ? maxStok : 1"
                                           class="w-full pl-6 pr-2 py-2.5 rounded-lg border border-gray-300 text-sm font-bold text-center text-gray-700 shadow-sm focus:ring-blue-500 focus:border-blue-500" 
                                           placeholder="1" min="1" required>
                                </div>
                                
                                <button type="button" @click="removeRow(index)" 
                                        class="p-2.5 rounded-lg text-gray-400 hover:text-red-600 hover:bg-red-50 border border-transparent hover:border-red-100 transition-all self-center">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1-0 00-1-1h-4a1 1-0 00-1 1v3M4 7h16" /></svg>
                                </button>
                            </div>
                        </template>
                    </div>
        
                    <div class="mt-8 pt-6 border-t border-gray-100 flex justify-between items-center">
                        <button type="button" @click="addNewRow()" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold text-blue-700 bg-blue-50 hover:bg-blue-100 transition-colors">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                            Tambah Baris
                        </button>
                        <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 rounded-lg bg-gray-900 text-white font-medium text-sm hover:bg-black shadow-lg hover:shadow-xl transition-all">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- KOLOM KANAN: RAKIT & PROMO -->
        <div class="lg:col-span-1 space-y-6">
            
            <div class="bg-blue-50 rounded-xl shadow-sm p-6 border border-blue-200">
                <h3 class="font-bold text-blue-900 mb-2 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" /></svg>
                    Produksi / Rakit Baru
                </h3>
                <p class="text-xs text-blue-700 mb-4 leading-relaxed">
                    Fitur ini akan menambah stok Master PC di tab Ready Stock dan otomatis memotong stok komponen.
                </p>

                @php
                    $maxBuild = 999999;
                    $totalHargaKomponen = 0;
                    $validResep = false;

                    foreach($paket->components as $c) {
                        $qtyButuh = $c->pivot->qty;
                        $totalHargaKomponen += $c->harga_jual * $qtyButuh;
                        
                        if($qtyButuh > 0) {
                            $validResep = true;
                            $possible = floor($c->stok / $qtyButuh);
                            if($possible < $maxBuild) {
                                $maxBuild = $possible;
                            }
                        }
                    }

                    if($paket->components->isEmpty() || !$validResep) {
                        $maxBuild = 0;
                    }

                    // Perhitungan Promo
                    $hargaPaket = $paket->harga_jual;
                    $selisihHemat = $totalHargaKomponen - $hargaPaket;
                    $persenDiskon = ($totalHargaKomponen > 0 && $selisihHemat > 0) 
                        ? round(($selisihHemat / $totalHargaKomponen) * 100) 
                        : 0;
                @endphp

                <form action="{{ route('admin.bundle.build', $paket->id) }}" method="POST">
                    @csrf
                    
                    <div class="mb-4">
                        <div class="flex justify-between items-end mb-1">
                            <label class="block text-xs font-bold text-blue-900">Jumlah Rakit (Unit)</label>
                            <span class="text-[10px] font-bold {{ $maxBuild == 0 ? 'text-red-500' : 'text-blue-600' }} bg-white px-2 py-0.5 rounded border border-blue-100">
                                Maks. bisa rakit: {{ $maxBuild }} Unit
                            </span>
                        </div>
                        
                        <div class="flex shadow-sm">
                            <button type="button" onclick="this.parentNode.querySelector('input[type=number]').stepDown()" class="bg-white border border-blue-200 text-blue-600 px-3 rounded-l-lg hover:bg-blue-50 transition">-</button>
                            <input type="number" name="qty_build" value="{{ $maxBuild > 0 ? 1 : 0 }}" min="1" max="{{ $maxBuild }}" 
                                   oninput="if(this.value > {{ $maxBuild }}) this.value = {{ $maxBuild }};"
                                   class="w-full text-center border-y border-blue-200 font-bold text-lg text-blue-900 focus:ring-0 z-10 {{ $maxBuild == 0 ? 'bg-gray-100 cursor-not-allowed text-gray-400' : '' }}" 
                                   {{ $maxBuild == 0 ? 'readonly' : '' }}>
                            <button type="button" onclick="if(this.parentNode.querySelector('input[type=number]').value < {{ $maxBuild }}) this.parentNode.querySelector('input[type=number]').stepUp()" class="bg-white border border-blue-200 text-blue-600 px-3 rounded-r-lg hover:bg-blue-50 transition">+</button>
                        </div>
                    </div>

                    <!-- TOGGLE DISKON -->
                    <div class="mb-4 p-3.5 bg-amber-50/90 border border-amber-200/80 rounded-xl flex items-center justify-between gap-3 shadow-sm">
                        <div>
                            <label class="font-bold text-slate-800 text-xs block">Terapkan Diskon Bundle 10%</label>
                            <p class="text-[10px] text-slate-500 mt-0.5 leading-tight">
                                Harga jual menjadi <span class="font-bold text-amber-700">10% lebih murah</span> dari eceran.
                            </p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer shrink-0">
                            <input type="checkbox" id="toggleDiskon" name="is_diskon_bundle" value="1" 
                                   class="sr-only peer" 
                                   onchange="updateTampilanHarga(this.checked)"
                                   {{ $paket->is_diskon_bundle ? 'checked' : '' }}>
                            <div class="w-10 h-5 bg-gray-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-amber-600"></div>
                        </label>
                    </div>

                    <button type="button" 
                            onclick="confirmBuild(this)"
                            class="w-full font-bold py-3 rounded-xl shadow-lg transition transform active:scale-95 flex justify-center items-center gap-2 {{ $maxBuild == 0 ? 'bg-gray-400 text-white cursor-not-allowed shadow-none' : 'bg-blue-600 text-white hover:bg-blue-700 shadow-blue-200' }}" 
                            {{ $maxBuild == 0 ? 'disabled' : '' }}>
                        <span>🚀 PROSES RAKIT</span>
                    </button>
                    
                    @if($maxBuild == 0 && !$paket->components->isEmpty())
                        <p class="text-xs text-red-500 mt-2 text-center font-medium">*Tidak bisa merakit, stok komponen di gudang tidak mencukupi.</p>
                    @endif
                </form>

                <!-- PANEL INFORMASI HARGA (2 Box: Normal & Promo) -->
                @php
                    $normal = $totalHargaKomponen;
                    $promo  = $totalHargaKomponen * 0.9;
                    $hemat  = $normal - $promo;
                @endphp

                <div class="mt-5 pt-4 border-t border-blue-200 space-y-2">
                    <!-- BOX PROMO (Aktif saat Toggle ON) -->
                    <div id="boxPromo" class="{{ $paket->is_diskon_bundle ? '' : 'hidden' }}">
                        <div class="flex justify-between items-center px-2 mb-2">
                            <span class="text-xs font-semibold text-gray-600">Total Harga Normal Eceran:</span>
                            <span class="font-bold text-xs text-gray-500 line-through">
                                Rp {{ number_format($normal, 0, ',', '.') }}
                            </span>
                        </div>
                        <div class="p-3 bg-amber-50 rounded-xl border border-amber-200 text-center shadow-sm relative overflow-hidden">
                            <div class="absolute -right-6 -top-6 w-16 h-16 bg-red-500 rotate-45 flex items-end justify-center pb-1 shadow">
                                <span class="text-[9px] font-black text-white">-10%</span>
                            </div>
                            <p class="text-[10px] text-amber-700 font-bold uppercase tracking-wider mb-1">🔥 Harga Promo Paket PC</p>
                            <p class="font-black text-xl text-amber-800">Rp {{ number_format($promo, 0, ',', '.') }}</p>
                            <p class="text-[11px] text-green-600 font-bold mt-1">Hemat Rp {{ number_format($hemat, 0, ',', '.') }}!</p>
                        </div>
                        <p class="text-[10px] text-gray-400 text-center italic mt-1">*Harga promo paket aktif (Diskon Bundle 10%).</p>
                    </div>

                    <!-- BOX NORMAL (Aktif saat Toggle OFF) -->
                    <div id="boxNormal" class="{{ $paket->is_diskon_bundle ? 'hidden' : '' }}">
                        <div class="p-3 bg-slate-50 rounded-xl border border-slate-200 text-center shadow-sm">
                            <p class="text-[10px] text-slate-500 font-bold uppercase tracking-wider mb-1">💎 Harga Jual Normal (Eceran)</p>
                            <p class="font-black text-xl text-slate-800">Rp {{ number_format($normal, 0, ',', '.') }}</p>
                            <p class="text-[11px] text-slate-400 font-medium mt-1">Tanpa potongan diskon bundle</p>
                        </div>
                        <p class="text-[10px] text-gray-400 text-center italic mt-1">*Aktifkan toggle di atas untuk memberikan diskon 10%.</p>
                    </div>
                </div>
            </div>

            <!-- DAFTAR KOMPONEN MASTER (RINGKASAN) -->
            <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm">
                <h4 class="text-xs font-bold text-gray-500 uppercase mb-3 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" /></svg>
                    Daftar Komponen Master:
                </h4>
                <ul class="text-sm space-y-2">
                    @forelse($paket->components as $c)
                        <li class="flex justify-between items-center border-b border-gray-50 pb-2 last:border-0 last:pb-0">
                            <span class="text-gray-700">{{ $c->nama_barang }}</span>
                            <span class="font-bold text-gray-900 bg-gray-100 px-2 py-0.5 rounded text-xs">x{{ $c->pivot->qty }}</span>
                        </li>
                    @empty
                        <li class="text-gray-400 italic text-xs text-center py-2">Belum ada resep.</li>
                    @endforelse
                </ul>
            </div>

        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    const allKomponens = @json($komponens);

    function resepHandler() {
        return {
            rows: {!! $paket->components->isEmpty() ? '[{id: Date.now(), val: "", qty: 1}]' : $paket->components->map(fn($c) => ['id' => $c->id, 'val' => $c->id, 'qty' => $c->pivot->qty]) !!},
            
            addNewRow() { 
                this.rows.push({id: Date.now(), val: "", qty: 1});
            },
            
            removeRow(index) { 
                this.rows.splice(index, 1);
            },

            // PERHITUNGAN OTOMATIS WATT, SOCKET, & RAM
            get summary() {
                let totalTdp = 0;
                let psuCapacity = 0;
                let sockets = [];
                let memories = [];

                this.rows.forEach(r => {
                    if (r.val) {
                        let item = allKomponens.find(i => i.id == r.val);
                        if (item) {
                            if (item.kategori === 'PSU') {
                                psuCapacity = parseInt(item.power_watt) || 0;
                            } else {
                                if (item.power_watt > 0) {
                                    totalTdp += (parseInt(item.power_watt) || 0) * (parseInt(r.qty) || 1);
                                }
                            }

                            if (item.socket && !sockets.includes(item.socket)) {
                                sockets.push(item.socket);
                            }

                            if (item.tipe_memory && !memories.includes(item.tipe_memory)) {
                                memories.push(item.tipe_memory);
                            }
                        }
                    }
                });

                let socketConflict = sockets.length > 1;
                let memoryConflict = memories.length > 1;
                let psuUnderpowered = (psuCapacity > 0 && psuCapacity < totalTdp);

                return {
                    tdpWatt: totalTdp,
                    psuWatt: psuCapacity,
                    socket: sockets.length > 0 ? sockets.join(' vs ') : '-',
                    memory: memories.length > 0 ? memories.join(' vs ') : '-',
                    socketConflict: socketConflict,
                    memoryConflict: memoryConflict,
                    isPsuUnderpowered: psuUnderpowered,
                    hasConflict: socketConflict || memoryConflict || psuUnderpowered
                };
            }
        }
    }

    function updateTampilanHarga(isDiskon) {
        const boxPromo = document.getElementById('boxPromo');
        const boxNormal = document.getElementById('boxNormal');
        
        if (isDiskon) {
            boxPromo.classList.remove('hidden');
            boxNormal.classList.add('hidden');
        } else {
            boxPromo.classList.add('hidden');
            boxNormal.classList.remove('hidden');
        }
    }

    function confirmBuild(button) {
        Swal.fire({
            title: 'Mulai Rakit?',
            text: "Stok komponen di gudang akan otomatis dipotong untuk merakit PC ini.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#2563eb',
            cancelButtonColor: '#9ca3af', 
            confirmButtonText: '🚀 Ya, Proses Rakit!',
            cancelButtonText: 'Batal',
            reverseButtons: true,
            customClass: {
                popup: 'rounded-2xl',
                confirmButton: 'rounded-lg font-bold shadow-lg',
                cancelButton: 'rounded-lg font-bold'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                button.closest('form').submit();
            }
        });
    }
</script>
@endsection