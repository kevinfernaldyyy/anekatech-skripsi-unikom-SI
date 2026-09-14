@extends('layouts.admin')

@section('title', 'Data Pelanggan')

@section('content')

<!-- HEADER SECTION -->
<div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    <div>
        <h1 class="text-2xl font-black text-slate-800 tracking-tight">Data Pelanggan</h1>
        <p class="text-sm text-slate-500 mt-1">Kelola data pelanggan dan pantau riwayat transaksi mereka.</p>
    </div>
    <div>
        <span class="bg-blue-50 text-blue-700 border border-blue-200 text-xs font-bold px-4 py-2.5 rounded-xl shadow-sm flex items-center gap-2">
            <i class="fas fa-users text-blue-500"></i> Total: {{ $customers->total() }} Pelanggan
        </span>
    </div>
</div>

<!-- TABLE CONTAINER -->
<div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-200 text-slate-500 text-[10px] uppercase tracking-widest font-bold">
                    <th class="px-6 py-4">Informasi Pelanggan</th>
                    <th class="px-6 py-4">Kontak</th>
                    <th class="px-6 py-4 text-center">Riwayat Order</th>
                    <th class="px-6 py-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($customers as $cust)
                <tr class="hover:bg-slate-50/80 transition-colors group">
                    
                    <!-- INFORMASI PELANGGAN -->
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-4">
                            <!-- Avatar Inisial -->
                            <div class="w-10 h-10 rounded-full bg-gradient-to-tr from-indigo-100 to-blue-50 text-blue-600 flex items-center justify-center font-black text-sm shrink-0 border border-blue-200 shadow-inner">
                                {{ strtoupper(substr($cust->nama ?? $cust->name ?? 'U', 0, 1)) }}
                            </div>
                            
                            <div>
                                <div class="flex items-center gap-2 mb-0.5">
                                    <h3 class="font-bold text-slate-800 text-sm group-hover:text-blue-600 transition-colors">
                                        {{ $cust->nama ?? $cust->name }}
                                    </h3>
                                    <!-- ID Asli yang disamarkan jadi badge elegan -->
                                    <span class="text-[9px] font-black text-slate-400 bg-slate-100 px-1.5 py-0.5 rounded uppercase tracking-wider border border-slate-200">
                                        ID-{{ $cust->id }}
                                    </span>
                                </div>
                                <p class="text-xs text-slate-500 font-medium">{{ $cust->email ?? 'Tidak ada email' }}</p>
                            </div>
                        </div>
                    </td>

                    <!-- KONTAK -->
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2 text-sm font-bold text-slate-600">
                            <i class="fab fa-whatsapp text-emerald-500 text-lg"></i>
                            {{ $cust->nomor_telepon ?? '-' }}
                        </div>
                    </td>

                    <!-- TOTAL TRANSAKSI -->
                    <td class="px-6 py-4 text-center">
                        <span class="inline-flex items-center gap-1.5 bg-emerald-50 text-emerald-700 border border-emerald-200 text-xs font-bold px-3 py-1.5 rounded-lg shadow-sm">
                            <i class="fas fa-shopping-bag text-emerald-500"></i>
                            {{ $cust->transaksi_count }}x Order
                        </span>
                    </td>

                    <!-- AKSI -->
                    <td class="px-6 py-4 text-center">
                        <a href="{{ route('admin.customers.show', $cust->id) }}" class="inline-flex items-center gap-2 bg-white border border-slate-200 hover:border-slate-900 hover:text-white hover:bg-slate-900 text-slate-600 text-xs font-bold px-4 py-2.5 rounded-lg transition-all shadow-sm">
                            Lihat History <i class="fas fa-arrow-right text-[10px]"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <!-- TAMPILAN KOSONG -->
                <tr>
                    <td colspan="4" class="px-6 py-16 text-center">
                        <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-400 border border-slate-100">
                            <i class="fas fa-users text-2xl"></i>
                        </div>
                        <h3 class="text-sm font-bold text-slate-700 mb-1">Belum Ada Pelanggan</h3>
                        <p class="text-xs text-slate-500 max-w-sm mx-auto">Data pelanggan yang mendaftar atau bertransaksi akan muncul di sini.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <!-- PAGINATION -->
    @if($customers->hasPages())
    <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50">
        {{ $customers->links() }}
    </div>
    @endif
</div>
@endsection