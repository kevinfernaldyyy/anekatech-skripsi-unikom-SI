@extends('layouts.admin')

@section('title', 'Edit Barang')

@section('content')
<div class="mx-auto max-w-4xl" x-data="{ kategori: '{{ old('kategori', $barang->kategori) }}' }">
    <div class="mb-5 flex items-center justify-between">
        <h2 class="text-2xl font-bold text-gray-800">Edit Barang: {{ $barang->nama_barang }}</h2>
        <a href="{{ route('admin.barang.index') }}" class="text-sm font-medium text-blue-600 hover:underline">&larr; Kembali</a>
    </div>

    <div class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
        <form action="{{ route('admin.barang.update', $barang->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="grid gap-6 md:grid-cols-2">
                <div class="md:col-span-2">
                    <label class="mb-2 block text-sm font-medium text-gray-900">Jenis Produk</label>
                    <div class="flex gap-4">
                        <div class="flex items-center ps-4 border border-gray-200 rounded-lg w-full">
                            <input id="jenis-komponen" type="radio" value="komponen" name="jenis" class="w-4 h-4 text-blue-600" {{ $barang->jenis == 'komponen' ? 'checked' : '' }}>
                            <label for="jenis-komponen" class="w-full py-4 ms-2 text-sm font-medium text-gray-900">Komponen Satuan</label>
                        </div>
                        <div class="flex items-center ps-4 border border-gray-200 rounded-lg w-full">
                            <input id="jenis-paket" type="radio" value="paket" name="jenis" class="w-4 h-4 text-blue-600" {{ $barang->jenis == 'paket' ? 'checked' : '' }}>
                            <label for="jenis-paket" class="w-full py-4 ms-2 text-sm font-medium text-gray-900">Paket PC Rakitan</label>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-900">Kode Barang</label>
                    <input type="text" name="kode_barang" value="{{ $barang->kode_barang }}" class="block w-full rounded-lg border border-gray-300 bg-gray-100 p-2.5 text-sm text-gray-500 cursor-not-allowed" readonly>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-900">Nama Barang</label>
                    <input type="text" name="nama_barang" value="{{ $barang->nama_barang }}" class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm focus:border-blue-500 focus:ring-blue-500" required>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-900">Kategori</label>
                    <select name="kategori" x-model="kategori" class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm focus:border-blue-500 focus:ring-blue-500">
                       @foreach([
                           'Processor', 'Motherboard', 'RAM', 'Storage', 
                           'VGA / GPU', 'Casing', 'PSU', 'Cooler', 'Monitor', 
                           'Keyboard & Mouse', 'Accessories', 'Laptop', 'Mini PC', 'PC Rakitan / Build'
                       ] as $cat)
                            <option value="{{ $cat }}" {{ $barang->kategori == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex gap-4">
                    <div class="flex-1">
                        <label class="mb-2 block text-sm font-medium text-gray-900">Stok</label>
                        <input type="number" name="stok" value="{{ $barang->stok }}" class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm focus:border-blue-500 focus:ring-blue-500" required>
                    </div>
                    <div class="w-1/3">
                        <label class="mb-2 block text-sm font-medium text-gray-900">Satuan</label>
                        <input type="text" name="satuan" value="{{ $barang->satuan }}" class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                </div>

                <!-- 👇 KOTAK SPESIFIKASI DEWA (SOCKET, RAM, FORM FACTOR, POWER) 👇 -->
                <div class="md:col-span-2 grid grid-cols-1 sm:grid-cols-2 gap-4 bg-blue-50/50 p-4 rounded-xl border border-blue-100" 
                     x-show="['Processor', 'Motherboard', 'RAM', 'VGA / GPU', 'Casing', 'PSU', 'Cooler'].includes(kategori)" style="display: none;">
                    
                    <!-- Input Socket (Processor, Motherboard, Cooler) -->
                    <div x-show="['Processor', 'Motherboard', 'Cooler'].includes(kategori)">
                        <label class="mb-2 block text-sm font-medium text-blue-900">Soket <span class="text-xs text-blue-500 font-normal">(Bisa isi koma utk Cooler)</span></label>
                        <input type="text" name="socket" value="{{ old('socket', $barang->socket) }}" class="block w-full rounded-lg border border-blue-200 bg-white p-2.5 text-sm focus:border-blue-500 focus:ring-blue-500 uppercase" placeholder="Contoh: LGA1700, AM4">
                    </div>

                    <!-- Input Tipe Memory (Motherboard & RAM) -->
                    <div x-show="['Motherboard', 'RAM'].includes(kategori)">
                        <label class="mb-2 block text-sm font-medium text-blue-900">Tipe RAM <span class="text-xs text-blue-500 font-normal">(Wajib sama)</span></label>
                        <input type="text" name="tipe_memory" value="{{ old('tipe_memory', $barang->tipe_memory) }}" class="block w-full rounded-lg border border-blue-200 bg-white p-2.5 text-sm focus:border-blue-500 focus:ring-blue-500 uppercase" placeholder="Contoh: DDR4, DDR5">
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
                        <input type="number" name="power_watt" value="{{ old('power_watt', $barang->power_watt) }}" class="block w-full rounded-lg border border-blue-200 bg-white p-2.5 text-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Contoh: 65">
                    </div>
                </div>
                <!-- 👆 AKHIR KOTAK SPESIFIKASI 👆 -->

                

                <div class="w-full">
                <label class="mb-2 block text-sm font-medium text-gray-900">Harga Jual (Rp)</label>
                <input type="number" name="harga_jual" value="{{ $barang->harga_jual }}" class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm focus:border-blue-500 focus:ring-blue-500">
            </div>

                <div class="md:col-span-2">
                    <label class="mb-2 block text-sm font-medium text-gray-900">Ganti Gambar (Opsional)</label>
                    @if($barang->gambar)
                        <div class="mb-2">
                            <img src="{{ asset('storage/' . $barang->gambar) }}" class="h-20 w-20 rounded object-cover border">
                        </div>
                    @endif
                    <input type="file" name="gambar" class="block w-full cursor-pointer rounded-lg border border-gray-300 bg-gray-50 text-sm focus:outline-none">
                </div>

                <div class="md:col-span-2">
                    <label class="mb-2 block text-sm font-medium text-gray-900">Deskripsi</label>
                    <textarea name="deskripsi" rows="4" class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm focus:border-blue-500 focus:ring-blue-500">{{ $barang->deskripsi }}</textarea>
                </div>
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <button type="submit" class="rounded-lg bg-blue-700 px-5 py-2.5 text-center text-sm font-medium text-white hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300">
                    Update Data
                </button>
            </div>
        </form>
    </div>
</div>
@endsection