@extends('layouts.admin')

@section('title', 'Stok & Produk')

@section('content')
<div class="min-h-screen bg-gray-50/50">
    
    <!-- HEADER -->
    <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-800 tracking-tight">Stok & Produk</h2>
            <p class="text-sm text-gray-500">Manajemen inventory, resep rakitan, dan barang jadi.</p>
        </div>
        
        <a href="{{ route('admin.barang.create') }}" class="inline-flex items-center justify-center rounded-xl bg-gray-900 px-5 py-3 text-center text-sm font-medium text-white hover:bg-black shadow-lg shadow-gray-200 transition-all hover:-translate-y-0.5">
            <svg class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
            Tambah Produk Baru
        </a>
    </div>

    <!-- KONDISI 1: MENU UTAMA (PILIH JENIS) -->
    @if($barangs === null)
    <div class="flex flex-col items-center justify-center py-12">
        <h3 class="text-xl font-bold text-gray-800 mb-2">Pilih Kategori Produk</h3>
        <p class="text-gray-500 mb-8 text-center max-w-md">Silakan pilih jenis data yang ingin Anda kelola hari ini.</p>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 w-full max-w-5xl">
            <!-- Card PC Ready Stock -->
            <a href="{{ route('admin.barang.index', ['jenis' => 'paket_ready']) }}" class="group relative overflow-hidden rounded-2xl bg-white p-8 shadow-sm hover:shadow-xl transition-all duration-300 border border-gray-100 hover:border-green-200 text-center">
                <div class="absolute top-0 right-0 -mt-4 -mr-4 h-24 w-24 rounded-full bg-green-50 transition-all group-hover:scale-150"></div>
                <div class="relative z-10 flex flex-col items-center">
                    <div class="h-16 w-16 bg-green-100 text-green-600 rounded-2xl flex items-center justify-center mb-4 shadow-sm group-hover:scale-110 transition-transform">
                        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                    <h4 class="text-lg font-bold text-gray-900 group-hover:text-green-700">PC Ready Stock</h4>
                    <p class="text-sm text-gray-500 mt-2">Unit PC yang sudah dirakit dan siap dijual di kasir.</p>
                </div>
            </a>

            <!-- Card Master Rakitan -->
            <a href="{{ route('admin.barang.index', ['jenis' => 'paket_menu']) }}" class="group relative overflow-hidden rounded-2xl bg-white p-8 shadow-sm hover:shadow-xl transition-all duration-300 border border-gray-100 hover:border-purple-200 text-center">
                <div class="absolute top-0 right-0 -mt-4 -mr-4 h-24 w-24 rounded-full bg-purple-50 transition-all group-hover:scale-150"></div>
                <div class="relative z-10 flex flex-col items-center">
                    <div class="h-16 w-16 bg-purple-100 text-purple-600 rounded-2xl flex items-center justify-center mb-4 shadow-sm group-hover:scale-110 transition-transform">
                        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>
                    </div>
                    <h4 class="text-lg font-bold text-gray-900 group-hover:text-purple-700">Master Rakitan</h4>
                    <p class="text-sm text-gray-500 mt-2">Resep / Blueprint PC. Stok selalu 0. Kelola spesifikasi di sini.</p>
                </div>
            </a>

            <!-- Card Komponen -->
            <a href="{{ route('admin.barang.index', ['jenis' => 'komponen']) }}" class="group relative overflow-hidden rounded-2xl bg-white p-8 shadow-sm hover:shadow-xl transition-all duration-300 border border-gray-100 hover:border-blue-200 text-center">
                <div class="absolute top-0 right-0 -mt-4 -mr-4 h-24 w-24 rounded-full bg-blue-50 transition-all group-hover:scale-150"></div>
                <div class="relative z-10 flex flex-col items-center">
                    <div class="h-16 w-16 bg-blue-100 text-blue-600 rounded-2xl flex items-center justify-center mb-4 shadow-sm group-hover:scale-110 transition-transform">
                        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" /></svg>
                    </div>
                    <h4 class="text-lg font-bold text-gray-900 group-hover:text-blue-700">Komponen / Part</h4>
                    <p class="text-sm text-gray-500 mt-2">Stok eceran. Processor, VGA, RAM, Casing, dll.</p>
                </div>
            </a>
        </div>
        
        <a href="{{ route('admin.barang.index', ['jenis' => 'komponen']) }}" class="mt-8 text-xs text-gray-400 hover:text-gray-600 hover:underline">
            Atau tampilkan semua data sekaligus &rarr;
        </a>
    </div>

    <!-- KONDISI 2: DATA TABEL -->
    @else
    <div class="mb-6 space-y-4">
        
        <!-- BARIS ATAS: BREADCRUMBS & SEARCH BAR -->
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
            
            <!-- Breadcrumbs -->
            <div class="flex flex-wrap items-center gap-2">
                <a href="{{ route('admin.barang.index') }}" class="p-2 rounded-lg bg-white border border-gray-200 text-gray-500 hover:bg-gray-50 hover:text-gray-800 transition shadow-sm" title="Kembali ke Menu Utama">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /></svg>
                </a>

                <div class="h-8 w-px bg-gray-300 mx-2"></div>

                @if(request('jenis') == 'paket_ready')
                    <div class="flex items-center gap-2 px-4 py-2 bg-green-100 text-green-800 rounded-lg font-bold border border-green-200 shadow-sm">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        PC Ready Stock
                    </div>
                @elseif(request('jenis') == 'paket_menu')
                    <div class="flex items-center gap-2 px-4 py-2 bg-purple-100 text-purple-800 rounded-lg font-bold border border-purple-200 shadow-sm">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>
                        Master Rakitan
                    </div>
                @elseif(request('jenis') == 'komponen')
                    <div class="flex items-center gap-2 px-4 py-2 bg-blue-100 text-blue-800 rounded-lg font-bold border border-blue-200 shadow-sm">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" /></svg>
                        Komponen / Part
                    </div>
                @endif
            </div>

            <!-- Search Bar -->
            <form action="{{ url()->current() }}" method="GET" class="w-full lg:w-auto flex items-center gap-2">
                @if(request('jenis')) <input type="hidden" name="jenis" value="{{ request('jenis') }}"> @endif
                @if(request('kategori')) <input type="hidden" name="kategori" value="{{ request('kategori') }}"> @endif

                <div class="relative w-full lg:w-72">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none">
                        <i class="fas fa-search text-slate-400"></i>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}" 
                           class="bg-white border border-slate-200 text-slate-800 text-sm rounded-xl focus:ring-blue-600 focus:border-blue-600 block w-full pl-10 p-2.5 shadow-sm transition-all" 
                           placeholder="Cari produk / kode...">
                </div>
                
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl text-sm font-bold shadow-sm transition-colors">
                    Cari
                </button>

                @if(request('search'))
                    <a href="{{ url()->current() }}?jenis={{ request('jenis') }}{{ request('kategori') ? '&kategori='.request('kategori') : '' }}" class="bg-white hover:bg-red-50 text-slate-400 hover:text-red-500 border border-slate-200 px-3.5 py-2.5 rounded-xl text-sm font-bold shadow-sm transition-colors" title="Reset Pencarian">
                        <i class="fas fa-times"></i>
                    </a>
                @endif
            </form>
        </div>

        <!-- BARIS BAWAH: FILTER KATEGORI (Khusus Komponen) -->
        @if(request('jenis') == 'komponen')
        <div class="overflow-x-auto pb-2">
            <div class="flex gap-2 w-max">
                <a href="{{ route('admin.barang.index', ['jenis' => 'komponen']) }}" 
                   class="px-3 py-1.5 rounded-full border text-xs font-medium transition-all {{ !request('kategori') ? 'bg-gray-800 text-white border-gray-800' : 'bg-white text-gray-600 border-gray-200 hover:border-gray-400' }}">
                   Semua Kategori
                </a>
                @foreach($kategoris as $cat)
                    <a href="{{ route('admin.barang.index', ['jenis' => 'komponen', 'kategori' => $cat]) }}" 
                       class="px-3 py-1.5 rounded-full border text-xs font-medium transition-all whitespace-nowrap {{ request('kategori') == $cat ? 'bg-blue-600 text-white border-blue-600' : 'bg-white text-gray-600 border-gray-200 hover:border-blue-300 hover:text-blue-600' }}">
                       {{ $cat }}
                    </a>
                @endforeach
            </div>
        </div>
        @endif
    </div>

    <!-- TABEL DATA -->
    <div class="relative overflow-hidden shadow-sm sm:rounded-xl bg-white border border-gray-200">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-500">
                <thead class="bg-gray-50 text-xs uppercase text-gray-500 border-b border-gray-100">
                    <tr>
                        <th scope="col" class="px-6 py-4 font-semibold w-1/3">Produk & Spesifikasi</th>
                        <th scope="col" class="px-6 py-4 font-semibold">Kategori</th>
                        <th scope="col" class="px-6 py-4 font-semibold">Harga Jual</th>
                        <th scope="col" class="px-6 py-4 font-semibold text-center">Status</th>
                        <th scope="col" class="px-6 py-4 text-center font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($barangs as $barang)
                    <tr class="hover:bg-gray-50/80 transition duration-150 group">
                        
                        <!-- Kolom Produk & Spesifikasi -->
                        <td class="px-6 py-4">
                            <div class="flex items-start gap-4">
                                <!-- Kotak Gambar -->
                                <div class="h-14 w-14 flex-shrink-0 overflow-hidden rounded-lg border border-gray-200 bg-white p-1">
                                    @if($barang->gambar)
                                        <img src="{{ asset('storage/' . $barang->gambar) }}" class="h-full w-full object-contain rounded-md">
                                    @else
                                        <div class="h-full w-full flex items-center justify-center bg-gray-50 text-gray-300">
                                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                        </div>
                                    @endif
                                </div>
                                
                                <!-- Informasi Teks -->
                                <div class="flex-1">
                                    <div class="font-bold text-base text-gray-900 group-hover:text-blue-600 transition-colors">
                                        {{ $barang->nama_barang }}
                                    </div>
                                    
                                    @if($barang->jenis == 'paket')
                                        @if(request('jenis') == 'paket_menu')
                                            <div class="mt-1">
                                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded text-[10px] font-bold bg-purple-50 text-purple-700 border border-purple-200">
                                                    🛠️ Blueprint / Resep
                                                </span>
                                            </div>
                                        @elseif(request('jenis') == 'paket_ready')
                                            <div class="mt-2">
                                                <a href="{{ route('admin.barang.show', $barang->id) }}" 
                                                   class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 hover:bg-emerald-600 hover:text-white transition-all shadow-2xs group-hover:border-emerald-300">
                                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                                    <span>Lihat Detail & QC ({{ $barang->components->count() }} Part)</span>
                                                </a>
                                            </div>
                                        @else
                                            <div class="mt-1 flex flex-wrap gap-1">
                                                @forelse($barang->components as $comp)
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-medium bg-blue-50 text-blue-700 border border-blue-100">
                                                        {{ $comp->nama_barang }}
                                                    </span>
                                                @empty
                                                    <span class="text-[10px] text-red-400 italic">Belum ada resep</span>
                                                @endforelse
                                            </div>
                                        @endif
                                    @else
                                        <!-- Kode Barang -->
                                        <div class="text-xs text-gray-400 font-mono mt-0.5 mb-1">{{ $barang->kode_barang }}</div>
                                        
                                        <!-- Badge Socket & RAM -->
                                        @if($barang->socket || $barang->tipe_memory)
                                            <div class="mt-1.5 flex flex-wrap gap-1.5">
                                                @if($barang->socket)
                                                    <span class="inline-flex items-center gap-1 bg-blue-50 text-blue-600 text-[10px] font-black px-2 py-0.5 rounded border border-blue-200 uppercase tracking-wider">
                                                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z" /></svg>
                                                        {{ $barang->socket }}
                                                    </span>
                                                @endif
                                                
                                                @if($barang->tipe_memory)
                                                    <span class="inline-flex items-center gap-1 bg-purple-50 text-purple-600 text-[10px] font-black px-2 py-0.5 rounded border border-purple-200 uppercase tracking-wider">
                                                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4" /></svg>
                                                        {{ $barang->tipe_memory }}
                                                    </span>
                                                @endif
                                            </div>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </td>

                        <!-- Kolom Kategori -->
                        <td class="px-6 py-4">
                            @if($barang->jenis == 'paket')
                                <span class="inline-flex items-center gap-1 rounded px-2 py-1 text-xs font-bold uppercase tracking-wide bg-purple-50 text-purple-700 border border-purple-100">
                                    📦 Paket PC
                                </span>
                            @else
                                <span class="inline-flex items-center rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-medium text-gray-600 border border-gray-200">
                                    {{ $barang->kategori }}
                                </span>
                            @endif
                        </td>

                        <!-- Kolom Harga -->
                        <td class="px-6 py-4">
                            <div class="font-bold text-gray-900">Rp {{ number_format($barang->harga_jual, 0, ',', '.') }}</div>
                        </td>

                        <!-- Kolom Status -->
                        <td class="px-6 py-4 text-center">
                            @if($barang->jenis == 'paket')
                                @if(request('jenis') == 'paket_menu')
                                    <div class="inline-flex flex-col items-center">
                                        <span class="px-3 py-1 rounded-full bg-gray-100 text-gray-700 text-xs font-bold border border-gray-200 shadow-sm">
                                            📋 Master Data
                                        </span>
                                        <span class="text-[10px] text-blue-600 mt-1 font-bold">Stok Fisik: {{ $barang->stok }}</span>
                                    </div>
                                @elseif($barang->stok > 0)
                                    <div class="inline-flex flex-col items-center">
                                        <span class="px-3 py-1 rounded-full bg-green-100 text-green-700 text-xs font-extrabold border border-green-200 shadow-sm">
                                            ✅ {{ $barang->stok }} Unit Ready
                                        </span>
                                        <span class="text-[10px] text-green-600 mt-1 font-medium">Siap Dijual</span>
                                    </div>
                                @else
                                    <div class="inline-flex flex-col items-center">
                                        <span class="px-3 py-1 rounded-full bg-gray-200 text-gray-600 text-xs font-bold border border-gray-300 border-dashed">
                                            🛠️ Kosong
                                        </span>
                                    </div>
                                @endif
                            @else
                                @if($barang->stok <= 5)
                                    <span class="text-red-600 font-bold text-sm">{{ $barang->stok }} {{ $barang->satuan }}</span>
                                @else
                                    <span class="text-gray-700 font-bold text-sm">{{ $barang->stok }} {{ $barang->satuan }}</span>
                                @endif
                            @endif
                        </td>

                        <!-- Kolom Aksi -->
                        <td class="px-6 py-4 text-center">
                            <div class="flex justify-center items-center gap-2">
                                @if($barang->jenis == 'paket' && request('jenis') != 'paket_ready')
                                    <a href="{{ route('admin.bundle.edit', $barang->id) }}" class="rounded-lg border border-gray-800 bg-gray-800 p-2 text-white hover:bg-black transition shadow-sm" title="Kelola Resep & Produksi">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2V7a2 2 0 00-2-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                    </a>
                                @endif

                                <a href="{{ route('admin.barang.edit', $barang->id) }}" class="rounded-lg border border-gray-200 bg-white p-2 text-blue-600 hover:bg-blue-600 hover:text-white transition shadow-sm">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                                </a>

                                <form action="{{ route('admin.barang.destroy', $barang->id) }}" method="POST" onsubmit="return confirm('Hapus item ini?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="rounded-lg border border-gray-200 bg-white p-2 text-red-600 hover:bg-red-600 hover:text-white transition shadow-sm">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1-0 00-1-1h-4a1 1-0 00-1 1v3M4 7h16" /></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                            Data tidak ditemukan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($barangs->hasPages())
        <div class="p-4 border-t border-gray-100 bg-gray-50">
            {{ $barangs->withQueryString()->links() }}
        </div>
        @endif
    </div>
    @endif
</div>
@endsection