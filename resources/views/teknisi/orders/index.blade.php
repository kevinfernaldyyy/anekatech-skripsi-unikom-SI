@extends('layouts.teknisi')

@section('content')
<div class="p-6 bg-slate-50 min-h-screen">
    <div class="flex justify-between items-center mb-6">
    @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl shadow-sm flex items-center gap-3">
            <span class="text-xl">✅</span>
            <span class="font-bold text-sm">{{ session('success') }}</span>
        </div>
    @endif
        <div>
            <h2 class="text-2xl font-bold text-slate-800">Tugas Rakitan Saya</h2>
            <p class="text-sm text-slate-500">Daftar pesanan Custom Build yang menunggu untuk dirakit.</p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-100 text-slate-600 text-[10px] uppercase tracking-wider">
                        <th class="p-4 font-bold">No. Pesanan</th>
                        <th class="p-4 font-bold">Customer</th>
                        <th class="p-4 font-bold">Status</th>
                        <th class="p-4 font-bold">Waktu Tugas</th>
                        <th class="p-4 text-center font-bold">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    @forelse($transaksis as $trx)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="p-4 font-bold text-blue-600">{{ $trx->no_transaksi }}</td>
                            <td class="p-4">
                                <p class="font-bold text-slate-800">{{ $trx->nama_customer }}</p>
                                <p class="text-xs text-slate-500">{{ $trx->customer->nomor_telepon ?? '-' }}</p>
                            </td>
                            <td class="p-4">
                                <span class="bg-amber-100 text-amber-700 px-2.5 py-1 rounded text-[10px] font-bold uppercase tracking-wide flex items-center gap-1.5 w-max">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                    Menunggu Dirakit
                                </span>
                            </td>
                            <td class="p-4 text-slate-500 text-xs">
                                {{ $trx->updated_at->diffForHumans() }}
                            </td>
                            <td class="p-4 text-center">
                                <a href="{{ route('teknisi.orders.show', $trx->id) }}" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-xs font-bold transition-colors shadow-sm">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                    Mulai Rakit
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-8 text-center text-slate-400">
                                <div class="text-4xl mb-3">☕</div>
                                <p class="font-bold">Belum ada tugas rakitan.</p>
                                <p class="text-xs">Silakan bersantai sambil menunggu tugas dari Admin.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($transaksis->hasPages())
            <div class="p-4 border-t border-slate-100 bg-slate-50">
                {{ $transaksis->links() }}
            </div>
        @endif
    </div>
</div>
@endsection