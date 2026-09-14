@extends('layouts.app') 
@section('title', 'Pesanan Saya')
@section('content')
<div class="max-w-4xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    <div class="mb-8">
        <h1 class="text-3xl font-black text-gray-900 tracking-tight">Pesanan Saya</h1>
        <p class="text-gray-500 mt-1">Pantau status transaksi dan pengiriman PC Anda di sini.</p>
    </div>
    @if($transaksis->isEmpty())
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-12 text-center">
            <div class="text-5xl mb-4">🛒</div>
            <h3 class="text-xl font-bold text-gray-800">Belum Ada Pesanan</h3>
            <p class="text-gray-500 mt-2 mb-6">Anda belum pernah melakukan transaksi di DiabloTech.</p>
            <a href="{{ url('/') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg transition-colors">
                Mulai Belanja
            </a>
        </div>
    @else
        <div class="space-y-4">
            @foreach($transaksis as $trx)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 hover:border-blue-300 transition-colors p-5 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                
                <div>
                    <div class="flex items-center gap-3 mb-2">
                        <span class="font-bold text-gray-900">{{ $trx->nomor_transaksi ?? 'TRX-'.$trx->id }}</span>
                        
                        @php
                            $badgeColor = match(strtolower($trx->status_order)) {
                                'pending' => 'bg-yellow-100 text-yellow-800',
                                'process' => 'bg-blue-100 text-blue-800',
                                'preorder' => 'bg-orange-100 text-orange-800',
                                'ready' => 'bg-purple-100 text-purple-800',
                                'completed' => 'bg-green-100 text-green-800',
                                'cancel' => 'bg-red-100 text-red-800',
                                default => 'bg-gray-100 text-gray-800'
                            };
                        @endphp
                        <span class="px-2.5 py-0.5 rounded text-xs font-bold uppercase tracking-wide {{ $badgeColor }}">
                            {{ $trx->status_order }}
                        </span>
                    </div>
                    <div class="text-sm text-gray-500">
                        🗓️ {{ $trx->created_at->format('d M Y') }} • 💰 Rp {{ number_format($trx->total_harga) }}
                    </div>
                </div>

                <a href="{{ route('customer.orders.show', $trx->id) }}" class="w-full sm:w-auto text-center bg-white hover:bg-gray-50 text-blue-600 border border-blue-200 font-bold py-2 px-6 rounded-lg transition-colors">
                    Lacak Pesanan →
                </a>
                
            </div>
            @endforeach
        </div>
    @endif

</div>
@endsection