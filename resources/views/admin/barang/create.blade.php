@extends('layouts.admin')

@section('title', 'Tambah Barang Baru')

@section('content')
<div class="mx-auto max-w-4xl">
    <div class="mb-5 flex items-center justify-between">
        <h2 class="text-2xl font-bold text-gray-800">Tambah Barang / Paket Baru</h2>
        <a href="{{ route('admin.barang.index') }}" class="text-sm font-medium text-blue-600 hover:underline">&larr; Kembali</a>
    </div>

    <div class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
        <form action="{{ route('admin.barang.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <!-- 👇 Tambahin state kategori dan kodeBarang di x-data 👇 -->
            <div class="grid gap-6 md:grid-cols-2" x-data="{ 
                jenis: '{{ old('jenis', 'komponen') }}',
                kategori: '{{ old('kategori') }}',
                kodeBarang: '{{ old('kode_barang') }}',
                generatePrefix() {
                    const prefixes = {
                        'Processor': 'PRC',
                        'Motherboard': 'MOBO',
                        'RAM': 'RAM',
                        'Storage': 'SSD',
                        'VGA / GPU': 'GPU',
                        'Casing': 'CASE',
                        'PSU': 'PSU',
                        'Cooler': 'CPUC',
                        'Monitor': 'MNTR',
                        'Keyboard & Mouse': 'KB',
                        'Accessories': 'ACC',
                        'Laptop': 'LTP',
                        'Mini PC': 'MPC',
                        'PC Rakitan / Build': 'PC'
                    };
                    
                    if (this.kategori && prefixes[this.kategori]) {
                        // Otomatis ngasih prefix saat kategori dipilih
                        this.kodeBarang = prefixes[this.kategori] + '-';
                    } else {
                        this.kodeBarang = '';
                    }
                }
            }">
                
                <div class="md:col-span-2">
                    <label class="mb-3 block text-sm font-medium text-gray-900">Jenis Produk</label>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <label class="cursor-pointer relative">
                            <input type="radio" name="jenis" value="komponen" x-model="jenis" class="peer sr-only">
                            <div class="rounded-lg border-2 border-gray-200 bg-white p-4 hover:bg-gray-50 peer-checked:border-blue-600 peer-checked:bg-blue-50 transition-all">
                                <div class="flex items-center gap-3">
                                    <div class="p-2 bg-blue-100 text-blue-600 rounded-lg">
                                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" /></svg>
                                    </div>
                                    <div>
                                        <span class="block font-bold text-gray-800 peer-checked:text-blue-700">Komponen / Part</span>
                                        <span class="text-xs text-gray-500">Dijual satuan (CPU, RAM, VGA)</span>
                                    </div>
                                </div>
                            </div>
                            <div class="absolute top-4 right-4 hidden peer-checked:block text-blue-600">
                                <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
                            </div>
                        </label>

                        <label class="cursor-pointer relative">
                            <input type="radio" name="jenis" value="paket" x-model="jenis" class="peer sr-only">
                            <div class="rounded-lg border-2 border-gray-200 bg-white p-4 hover:bg-gray-50 peer-checked:border-purple-600 peer-checked:bg-purple-50 transition-all">
                                <div class="flex items-center gap-3">
                                    <div class="p-2 bg-purple-100 text-purple-600 rounded-lg">
                                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
                                    </div>
                                    <div>
                                        <span class="block font-bold text-gray-800 peer-checked:text-purple-700">Paket PC Rakitan</span>
                                        <span class="text-xs text-gray-500">PC Fullset siap pajang/bundling</span>
                                    </div>
                                </div>
                            </div>
                            <div class="absolute top-4 right-4 hidden peer-checked:block text-purple-600">
                                <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
                            </div>
                        </label>
                    </div>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-900">Kategori <span class="text-red-500">*</span></label>
                    <select name="kategori" x-model="kategori" @change="generatePrefix()" class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm focus:border-blue-500 focus:ring-blue-500" required>
                        <option value="">Pilih Kategori...</option>
                        @foreach([
                            'Processor', 'Motherboard', 'RAM', 'Storage', 
                            'VGA / GPU', 'Casing', 'PSU', 'Cooler', 'Monitor', 
                            'Keyboard & Mouse', 'Accessories', 'Laptop', 'Mini PC', 'PC Rakitan / Build'
                        ] as $cat)
                            <option value="{{ $cat }}">{{ $cat }}</option>
                        @endforeach
                    </select>
                    @error('kategori') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-900">Kode Barang / SKU</label>
                    <input type="text" disabled class="block w-full rounded-lg border border-gray-300 bg-gray-200 p-2.5 text-sm text-gray-500 font-mono italic cursor-not-allowed" placeholder="Otomatis digenerate oleh sistem saat disimpan">
                </div>

                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-900">Nama Barang / Paket <span class="text-red-500">*</span></label>
                    <input type="text" name="nama_barang" value="{{ old('nama_barang') }}" class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Contoh: Intel Core i5-13400F" required>
                    @error('nama_barang') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                </div>

                <div class="flex gap-4">
                    <div class="flex-1">
                        <label class="mb-2 block text-sm font-medium text-gray-900">Stok Awal <span class="text-red-500">*</span></label>
                        <input type="number" 
                               name="stok" 
                               id="stokInput"
                               value="{{ old('stok', 0) }}" 
                               class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm focus:border-blue-500 focus:ring-blue-500 transition-colors"
                               :class="jenis === 'paket' ? 'bg-gray-200 text-gray-500 cursor-not-allowed' : ''"
                               :readonly="jenis === 'paket'"
                               x-on:click="if(jenis === 'paket') alert('Stok Paket PC harus 0 (Kosong) di awal. Gunakan fitur Rakit untuk menambah stok.')"
                               required>
                        
                        <p class="mt-1 text-[10px]" :class="jenis === 'paket' ? 'text-purple-600 font-bold' : 'text-gray-500'">
                            <span x-show="jenis === 'komponen'">*Stok fisik saat ini.</span>
                            <span x-show="jenis === 'paket'" style="display: none;">
                                <svg class="w-3 h-3 inline mr-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                Stok paket otomatis 0. Rakit nanti untuk nambah stok.
                            </span>
                        </p>
                    </div>

                    <div class="w-1/3">
                        <label class="mb-2 block text-sm font-medium text-gray-900">Satuan</label>
                        <input type="text" name="satuan" value="{{ old('satuan', 'Unit') }}" class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                </div>

                <!-- 👇 KOTAK SPESIFIKASI SOCKET & RAM 👇 -->
                <div class="md:col-span-2 grid grid-cols-1 sm:grid-cols-2 gap-4 bg-blue-50/50 p-4 rounded-xl border border-blue-100" 
     x-show="['Processor', 'Motherboard', 'RAM', 'VGA / GPU', 'Casing', 'PSU', 'Cooler'].includes(kategori)" style="display: none;">
    
    <!-- Input Socket (Processor, Motherboard, Cooler) -->
    <div x-show="['Processor', 'Motherboard', 'Cooler'].includes(kategori)">
        <label class="mb-2 block text-sm font-medium text-blue-900">Soket <span class="text-xs text-blue-500 font-normal">(Bisa isi koma utk Cooler)</span></label>
        <input type="text" name="socket" value="{{ old('socket', $barang->socket ?? '') }}" class="block w-full rounded-lg border border-blue-200 bg-white p-2.5 text-sm focus:border-blue-500 focus:ring-blue-500 uppercase" placeholder="Contoh: LGA1700, AM4">
    </div>

    <!-- Input Tipe Memory (Motherboard & RAM) -->
    <div x-show="['Motherboard', 'RAM'].includes(kategori)">
        <label class="mb-2 block text-sm font-medium text-blue-900">Tipe RAM <span class="text-xs text-blue-500 font-normal">(Wajib sama)</span></label>
        <input type="text" name="tipe_memory" value="{{ old('tipe_memory', $barang->tipe_memory ?? '') }}" class="block w-full rounded-lg border border-blue-200 bg-white p-2.5 text-sm focus:border-blue-500 focus:ring-blue-500 uppercase" placeholder="Contoh: DDR4, DDR5">
    </div>

    <!-- Input Form Factor (Motherboard & Casing) -->
   <div x-show="['Motherboard', 'Casing', 'VGA / GPU', 'Cooler'].includes(kategori)">
    <label class="mb-2 block text-sm font-medium text-blue-900">Form Factor <span class="text-xs text-blue-500 font-normal">(Batas Ukuran Fisik)</span></label>
    <select name="form_factor" class="block w-full rounded-lg border border-blue-200 bg-white p-2.5 text-sm focus:border-blue-500 focus:ring-blue-500">
        <option value="">Pilih Ukuran...</option>
        <option value="E-ATX" {{ old('form_factor', $barang->form_factor ?? '') == 'E-ATX' ? 'selected' : '' }}>E-ATX (Sangat Besar)</option>
        <option value="ATX" {{ old('form_factor', $barang->form_factor ?? '') == 'ATX' ? 'selected' : '' }}>ATX (Standar/Besar)</option>
        <option value="Micro-ATX" {{ old('form_factor', $barang->form_factor ?? '') == 'Micro-ATX' ? 'selected' : '' }}>Micro-ATX (Sedang)</option>
        <option value="Mini-ITX" {{ old('form_factor', $barang->form_factor ?? '') == 'Mini-ITX' ? 'selected' : '' }}>Mini-ITX (Kecil)</option>
    </select>
</div>

    <!-- Input Power/Watt (Processor, VGA, PSU) -->
    <div x-show="['Processor', 'VGA / GPU', 'PSU'].includes(kategori)">
        <label class="mb-2 block text-sm font-medium text-blue-900">Daya / TDP (Watt) <span class="text-xs text-blue-500 font-normal">(Kebutuhan / Kapasitas)</span></label>
        <input type="number" name="power_watt" value="{{ old('power_watt', $barang->power_watt ?? 0) }}" class="block w-full rounded-lg border border-blue-200 bg-white p-2.5 text-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Contoh: 65">
    </div>
</div>
                <!-- 👆 AKHIR KOTAK SPESIFIKASI 👆 -->

                <div class="md:col-span-2" x-data="{ 
                    rawHarga: '{{ old('harga_jual') }}', 
                    tampilHarga: '' 
                }" 
                x-init="tampilHarga = rawHarga ? parseInt(rawHarga, 10).toLocaleString('id-ID') : ''">
                    
                    <label class="mb-2 block text-sm font-medium text-gray-900">
                        Harga Jual (Rp) <span x-show="jenis === 'komponen'" class="text-red-500">*</span>
                    </label>
                    
                    <input type="text" 
                           x-model="tampilHarga"
                           @input="
                               let angka = $event.target.value.replace(/\D/g, ''); 
                               rawHarga = angka; 
                               tampilHarga = angka ? parseInt(angka, 10).toLocaleString('id-ID') : '';
                           "
                           class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm focus:border-blue-500 focus:ring-blue-500" 
                           placeholder="0" 
                           x-bind:required="jenis === 'komponen'"
                           :class="jenis === 'paket' ? 'bg-purple-50 border-purple-200' : ''">
                           
                    <input type="hidden" name="harga_jual" :value="rawHarga">

                    <p class="mt-1 text-xs text-purple-600 italic" x-show="jenis === 'paket'" style="display: none;">
                        Boleh dikosongkan (Bisa diatur otomatis dari harga komponen nanti)
                    </p>
                </div>
                
                <div class="md:col-span-2">
                    <label class="mb-2 block text-sm font-medium text-gray-900">Deskripsi / Spesifikasi</label>
                    <textarea name="deskripsi" rows="3" class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm focus:border-blue-500 focus:ring-blue-500">{{ old('deskripsi') }}</textarea>
                </div>

                <div class="md:col-span-2" x-data="{ photoName: null, photoPreview: null }">
                    <label class="mb-2 block text-sm font-medium text-gray-900">Foto Produk</label>
                    
                    <input type="file" name="gambar" class="hidden" x-ref="photo"
                        x-on:change="
                            photoName = $refs.photo.files[0].name;
                            const reader = new FileReader();
                            reader.onload = (e) => { photoPreview = e.target.result; };
                            reader.readAsDataURL($refs.photo.files[0]);
                        ">

                    <div class="mt-2 flex justify-center rounded-lg border border-dashed border-gray-900/25 px-6 py-10 hover:bg-gray-50 cursor-pointer transition"
                         x-on:click.prevent="$refs.photo.click()">
                        
                        <div class="text-center">
                            <div x-show="!photoPreview">
                                <svg class="mx-auto h-12 w-12 text-gray-300" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M1.5 6a2.25 2.25 0 012.25-2.25h16.5A2.25 2.25 0 0122.5 6v12a2.25 2.25 0 01-2.25 2.25H3.75A2.25 2.25 0 011.5 18V6zM3 16.06V18c0 .414.336.75.75.75h16.5A.75.75 0 0021 18v-1.94l-2.69-2.689a1.5 1.5 0 00-2.12 0l-.88.879.97.97a.75.75 0 11-1.06 1.06l-5.16-5.159a1.5 1.5 0 00-2.12 0L3 16.061zm10.125-7.81a1.125 1.125 0 112.25 0 1.125 1.125 0 01-2.25 0z" clip-rule="evenodd" />
                                </svg>
                                <div class="mt-4 flex text-sm leading-6 text-gray-600 justify-center">
                                    <span class="relative cursor-pointer rounded-md bg-white font-semibold text-blue-600 hover:text-blue-500">
                                        <span>Upload file</span>
                                    </span>
                                    <p class="pl-1">atau drag and drop</p>
                                </div>
                                <p class="text-xs leading-5 text-gray-600">PNG, JPG, GIF max 2MB</p>
                            </div>

                            <div x-show="photoPreview" style="display: none;" class="relative">
                                <span class="block h-48 w-48 overflow-hidden rounded-lg mx-auto shadow-md">
                                    <img :src="photoPreview" class="h-full w-full object-cover">
                                </span>
                                <p class="mt-2 text-sm text-gray-500" x-text="photoName"></p>
                                <button type="button" class="mt-2 text-xs text-red-600 hover:text-red-800 font-bold" 
                                        x-on:click.stop="photoPreview = null; photoName = null; $refs.photo.value = null">
                                    Hapus Foto
                                </button>
                            </div>
                        </div>
                    </div>
                    @error('gambar') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <button type="submit" class="rounded-lg bg-blue-700 px-5 py-2.5 text-center text-sm font-medium text-white hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300 shadow-lg">
                    Simpan Data
                </button>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const radioButtons = document.querySelectorAll('input[name="jenis"]');
            const stokInput = document.getElementById('stokInput');
            
            radioButtons.forEach(radio => {
                radio.addEventListener('change', (e) => {
                    if(e.target.value === 'paket') {
                        stokInput.value = 0;
                    }
                });
            });
        });
    </script>
</div>
@endsection