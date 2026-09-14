@extends('layouts.admin')

@section('title', 'Detail History Pelanggan')

@section('content')
<!-- TOMBOL KEMBALI -->
<div class="mb-6">
    <a href="{{ route('admin.customers.index') }}" class="inline-flex items-center text-sm font-bold text-slate-500 hover:text-blue-600 transition-colors">
        <i class="fas fa-arrow-left mr-2"></i> Kembali ke Daftar Pelanggan
    </a>
</div>

<!-- KARTU PROFIL PELANGGAN & TOTAL BELANJA -->
<div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 mb-6 flex flex-col md:flex-row gap-6 items-center md:items-start justify-between">
    <div class="flex items-center gap-4">
        <div class="w-16 h-16 bg-blue-50 border border-blue-100 text-blue-600 rounded-full flex items-center justify-center text-2xl font-black shrink-0 shadow-inner">
            {{ strtoupper(substr($customer->nama ?? $customer->name ?? 'U', 0, 1)) }}
        </div>
        <div>
            <h2 class="text-xl font-black text-slate-800">{{ $customer->nama ?? $customer->name }}</h2>
            <div class="flex flex-col sm:flex-row sm:gap-4 mt-1.5 text-sm font-medium text-slate-500">
                <span class="flex items-center gap-1.5"><i class="fas fa-envelope text-slate-400"></i> {{ $customer->email ?? 'Tidak ada email' }}</span>
                <span class="flex items-center gap-1.5"><i class="fab fa-whatsapp text-emerald-500"></i> {{ $customer->nomor_telepon ?? '-' }}</span>
            </div>
        </div>
    </div>
    
    <div class="bg-slate-50/30 border border-slate-200 rounded-xl p-4 text-center md:text-right min-w-[220px]">
        <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-1">Total Belanja (Lifetime)</p>
        <p class="text-3xl font-black text-blue-600">Rp {{ number_format($totalBelanja, 0, ',', '.') }}</p>
        <p class="text-xs font-medium text-slate-400 mt-1">Dari {{ $transaksis->count() }} Transaksi</p>
    </div>
</div>

<!-- AUDIT TRAIL / RIWAYAT PER BULAN DENGAN ALPINE JS MODAL -->
<div class="space-y-6" x-data="{ modalOpen: false, modalType: '', modalTitle: '', modalData: {} }">
    @forelse($riwayatPerBulan as $bulan => $transaksiBulanIni)
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="bg-slate-50/80 px-6 py-4 border-b border-slate-200 flex justify-between items-center">
                <h3 class="font-black text-slate-800 flex items-center gap-2">
                    <i class="fas fa-calendar-alt text-blue-500"></i> {{ $bulan }}
                </h3>
                <span class="text-[10px] font-bold bg-white border border-slate-200 text-slate-600 px-3 py-1.5 rounded-lg uppercase tracking-wider shadow-sm">
                    {{ $transaksiBulanIni->count() }} Pesanan
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-slate-600">
                    <thead class="text-[10px] text-slate-500 uppercase tracking-widest bg-white border-b border-slate-100">
                        <tr>
                            <th class="px-6 py-4">ID Pesanan & Detail Dokumen</th>
                            <th class="px-6 py-4">Total Transaksi</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($transaksiBulanIni as $trx)
                        
                        @php
                            $status = strtolower($trx->status_order ?? $trx->status_pesanan ?? 'pending');
                            
                            // Ambil data terkait pakai DB facade biar aman
                            $retur = \Illuminate\Support\Facades\DB::table('pengajuan_return')->where('transaksi_id', $trx->id)->first();
                            $adaRetur = $retur ? true : false;
                            
                            $qc = \Illuminate\Support\Facades\DB::table('dokumen_qcs')->where('transaksi_id', $trx->id)->first();
                            $teknisiLog = \Illuminate\Support\Facades\DB::table('pengiriman_teknisi')->where('transaksi_id', $trx->id)->first();
                            
                            // Format URL Gambar Modal Pembayaran
                            $dpUrl = $trx->bukti_pembayaran ? asset('storage/'.$trx->bukti_pembayaran) : '';
                            $lunasUrl = $trx->bukti_pelunasan ? asset('storage/'.$trx->bukti_pelunasan) : '';
                            
                            // Format URL Gambar Modal Pengiriman (Ekspedisi)
                            $fotoResiUrl = $trx->foto_resi_pengiriman ? asset('storage/'.$trx->foto_resi_pengiriman) : '';
                            $fotoPaketUrl = $trx->foto_paket_ekspedisi ? asset('storage/'.$trx->foto_paket_ekspedisi) : '';
                            
                            // Format Data Modal Pengiriman (Teknisi)
                            $wktBerangkat = ($teknisiLog && $teknisiLog->waktu_berangkat) ? \Carbon\Carbon::parse($teknisiLog->waktu_berangkat)->format('d M Y, H:i') : 'Belum berangkat';
                            $wktSelesai = ($teknisiLog && $teknisiLog->waktu_selesai) ? \Carbon\Carbon::parse($teknisiLog->waktu_selesai)->format('d M Y, H:i') : 'Belum selesai';
                            $fotoInstalasi = ($teknisiLog && $teknisiLog->foto_bukti_instalasi) ? asset('storage/'.$teknisiLog->foto_bukti_instalasi) : '';
                            $catatanLap = $teknisiLog ? str_replace(["\r", "\n", "'", '"', "`"], ' ', $teknisiLog->catatan_lapangan) : 'Tidak ada catatan';

                            // Format URL Gambar Modal QC & Garansi
                            $fotoQcDepan = ($qc && $qc->foto_depan) ? asset('storage/'.$qc->foto_depan) : '';
                            $fotoQcMenyala = ($qc && $qc->foto_menyala) ? asset('storage/'.$qc->foto_menyala) : '';
                            $pdfGaransi = ($qc && $qc->file_pdf) ? asset('storage/'.$qc->file_pdf) : '';
                            
                            // Format URL Gambar Modal Retur
                            $fotoReturArr = $retur ? json_decode($retur->foto_kerusakan, true) : [];
                            $fotoReturUrl = (is_array($fotoReturArr) && count($fotoReturArr) > 0) ? asset('storage/'.$fotoReturArr[0]) : '';
                            $refundUrl = ($retur && $retur->bukti_refund) ? asset('storage/'.$retur->bukti_refund) : '';
                            $catatanRetur = $retur ? str_replace(["\r", "\n", '"', "'", "`"], ' ', $retur->catatan_retur) : '';
                        @endphp
                        
                        <tr class="bg-white hover:bg-slate-50/50 transition-colors items-start">
                            <td class="px-6 py-5">
                                <div class="font-black text-slate-800 text-base">#{{ $trx->order_id ?? $trx->no_transaksi ?? $trx->id }}</div>
                                <div class="text-[11px] font-bold text-slate-400 mt-0.5 mb-3">{{ \Carbon\Carbon::parse($trx->created_at)->format('d M Y, H:i') }} WIB</div>
                                
                                <div class="bg-slate-50 border border-slate-200 rounded-lg p-3 mb-3">
                                    <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-2 border-b border-slate-200 pb-1.5">Item yang dibeli:</p>
                                    <ul class="space-y-1.5">
                                        @forelse($trx->details ?? $trx->detail_transaksi ?? [] as $detail)
                                            <li class="text-xs font-medium text-slate-700 flex items-start gap-2">
                                                <span class="font-black text-blue-600 bg-blue-100 px-1.5 py-0.5 rounded text-[10px]">{{ $detail->qty }}x</span> 
                                                <span class="line-clamp-1 mt-0.5" title="{{ $detail->barang->nama_barang ?? $detail->nama_barang_custom ?? 'Komponen' }}">
                                                    {{ $detail->barang->nama_barang ?? $detail->nama_barang_custom ?? 'Komponen' }}
                                                </span>
                                            </li>
                                        @empty
                                            <li class="text-xs text-slate-400 italic">- Detail tidak tersedia -</li>
                                        @endforelse
                                    </ul>
                                </div>

                                <!-- 👇 PUSAT DOKUMEN & LAPORAN 👇 -->
                                <div class="flex flex-wrap gap-2">
                                    <!-- TOMBOL PEMBAYARAN -->
                                    <button @click.prevent="
                                        modalOpen = true; 
                                        modalType = 'pembayaran'; 
                                        modalTitle = 'Dokumen Pembayaran #{{ $trx->no_transaksi ?? $trx->id }}';
                                        modalData = { status: '{{ $trx->status_pembayaran }}', dp: '{{ $dpUrl }}', lunas: '{{ $lunasUrl }}' }"
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-blue-200 bg-blue-50 text-blue-700 hover:bg-blue-600 hover:text-white transition-colors text-[10px] font-bold uppercase tracking-wider shadow-sm">
                                        <i class="fas fa-file-invoice-dollar text-[11px]"></i> Pembayaran
                                    </button>
                                    
                                    <!-- TOMBOL PENGIRIMAN -->
                                    <button @click.prevent="
                                        modalOpen = true; 
                                        modalType = 'pengiriman'; 
                                        modalTitle = 'Logistik & Pengiriman #{{ $trx->no_transaksi ?? $trx->id }}';
                                        modalData = { 
                                            metode: '{{ $trx->metode_pengiriman }}', 
                                            resi: '{{ $trx->resi_pengiriman ?? 'Belum ada resi' }}', 
                                            fotoResi: '{{ $fotoResiUrl }}', 
                                            fotoPaket: '{{ $fotoPaketUrl }}',
                                            berangkat: '{{ $wktBerangkat }}',
                                            selesai: '{{ $wktSelesai }}',
                                            fotoInstalasi: '{{ $fotoInstalasi }}',
                                            catatanLap: `{{ $catatanLap }}`
                                        }"
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-amber-200 bg-amber-50 text-amber-700 hover:bg-amber-600 hover:text-white transition-colors text-[10px] font-bold uppercase tracking-wider shadow-sm">
                                        <i class="fas fa-truck text-[11px]"></i> Pengiriman
                                    </button>
                                    
                                    @if($adaRetur)
                                        <!-- TOMBOL RETUR (NYALA) & QC (MATI) -->
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-slate-200 bg-slate-50 text-slate-400 cursor-not-allowed text-[10px] font-bold uppercase tracking-wider shadow-sm opacity-60">
                                            <i class="fas fa-clipboard-check text-[11px]"></i> QC & Rakit
                                        </span>
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-slate-200 bg-slate-50 text-slate-400 cursor-not-allowed text-[10px] font-bold uppercase tracking-wider shadow-sm opacity-60">
                                            <i class="fas fa-shield-alt text-[11px]"></i> Garansi
                                        </span>
                                        
                                        <button @click.prevent="
                                            modalOpen = true; 
                                            modalType = 'retur'; 
                                            modalTitle = 'Laporan Retur #{{ $trx->no_transaksi ?? $trx->id }}';
                                            modalData = { status: '{{ $retur->status ?? '' }}', catatan: `{{ $catatanRetur }}`, foto: '{{ $fotoReturUrl }}', refund: '{{ $refundUrl }}' }"
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-rose-200 bg-rose-50 text-rose-700 hover:bg-rose-600 hover:text-white transition-colors text-[10px] font-bold uppercase tracking-wider shadow-sm">
                                            <i class="fas fa-undo text-[11px]"></i> Retur
                                        </button>
                                    @else
                                        <!-- TOMBOL QC (NYALA) & RETUR (MATI) -->
                                        <button @click.prevent="
                                            modalOpen = true; 
                                            modalType = 'qc'; 
                                            modalTitle = 'Hasil Quality Control (QC) #{{ $trx->no_transaksi ?? $trx->id }}';
                                            modalData = { depan: '{{ $fotoQcDepan }}', menyala: '{{ $fotoQcMenyala }}' }"
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-emerald-200 bg-emerald-50 text-emerald-700 hover:bg-emerald-600 hover:text-white transition-colors text-[10px] font-bold uppercase tracking-wider shadow-sm">
                                            <i class="fas fa-clipboard-check text-[11px]"></i> QC & Rakit
                                        </button>
                                        
                                        @if($pdfGaransi)
                                            <a href="{{ $pdfGaransi }}" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-purple-200 bg-purple-50 text-purple-700 hover:bg-purple-600 hover:text-white transition-colors text-[10px] font-bold uppercase tracking-wider shadow-sm">
                                                <i class="fas fa-shield-alt text-[11px]"></i> Garansi
                                            </a>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-slate-200 bg-slate-50 text-slate-400 cursor-not-allowed text-[10px] font-bold uppercase tracking-wider shadow-sm opacity-60">
                                                <i class="fas fa-shield-alt text-[11px]"></i> Garansi
                                            </span>
                                        @endif
                                        
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-slate-200 bg-slate-50 text-slate-400 cursor-not-allowed text-[10px] font-bold uppercase tracking-wider shadow-sm opacity-60">
                                            <i class="fas fa-undo text-[11px]"></i> Retur
                                        </span>
                                    @endif
                                </div>
                                <!-- 👆 SELESAI PUSAT DOKUMEN 👆 -->

                            </td>
                            
                            <td class="px-6 py-5 font-black text-slate-800 align-top pt-6">
                                Rp {{ number_format($trx->total_harga, 0, ',', '.') }}
                            </td>
                            
                            <td class="px-6 py-5 align-top pt-6">
                                @if(in_array($status, ['selesai', 'completed', 'diterima_kustomer']))
                                    <span class="bg-emerald-100 border border-emerald-200 text-emerald-700 text-[10px] uppercase tracking-wider font-black px-2.5 py-1.5 rounded-md shadow-sm">Selesai</span>
                                @elseif(in_array($status, ['batal', 'cancel']))
                                    <span class="bg-red-100 border border-red-200 text-red-700 text-[10px] uppercase tracking-wider font-black px-2.5 py-1.5 rounded-md shadow-sm">Batal</span>
                                @else
                                    <span class="bg-blue-100 border border-blue-200 text-blue-700 text-[10px] uppercase tracking-wider font-black px-2.5 py-1.5 rounded-md shadow-sm">
                                        {{ str_replace('_', ' ', $status) }}
                                    </span>
                                @endif
                            </td>
                            
                            <td class="px-6 py-5 text-right align-top pt-5">
                                <a href="{{ route('admin.orders.show', $trx->id) }}" class="inline-flex items-center justify-center w-9 h-9 rounded-full bg-slate-100 border border-slate-200 text-slate-600 hover:bg-slate-900 hover:text-white transition-colors shadow-sm" title="Buka Detail Transaksi">
                                    <i class="fas fa-chevron-right text-sm"></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @empty
        <div class="bg-white rounded-3xl shadow-sm border border-slate-200 p-16 text-center">
            <div class="w-20 h-20 bg-slate-50 border border-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-receipt text-3xl text-slate-300"></i>
            </div>
            <h3 class="text-slate-800 font-black text-lg mb-1">Belum Ada Riwayat</h3>
            <p class="text-sm text-slate-500 max-w-sm mx-auto">Pelanggan ini belum pernah melakukan transaksi pembelian di DiabloTech.</p>
        </div>
    @endforelse

    <!-- ============================================== -->
    <!-- MODAL POP-UP VIEWER DOKUMEN (ALPINE JS)        -->
    <!-- ============================================== -->
    <div x-show="modalOpen" class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm" style="display: none;" x-transition.opacity.duration.300ms>
        <div @click.outside="modalOpen = false" class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl overflow-hidden flex flex-col max-h-[90vh] scale-100 transform transition-transform" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100">
            
            <!-- Modal Header -->
            <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50">
                <h3 class="font-black text-slate-800 text-lg" x-text="modalTitle"></h3>
                <button @click="modalOpen = false" class="text-slate-400 hover:text-rose-500 transition-colors bg-white rounded-full w-8 h-8 flex items-center justify-center border border-slate-200 shadow-sm">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="p-6 overflow-y-auto flex-1">

                <!-- 1. VIEW PEMBAYARAN -->
                <template x-if="modalType === 'pembayaran'">
                    <div class="space-y-6 animate-fade-in-down">
                        <div class="flex items-center gap-3 mb-4">
                            <span class="px-3 py-1 bg-blue-100 text-blue-700 font-bold uppercase tracking-wider text-[10px] rounded-md border border-blue-200" x-text="'Status Tagihan: ' + modalData.status"></span>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div>
                                <p class="text-xs font-bold text-slate-500 uppercase tracking-widest mb-2"><i class="fas fa-receipt text-blue-500 mr-1"></i> Bukti DP / Awal</p>
                                <template x-if="modalData.dp">
                                    <a :href="modalData.dp" target="_blank" class="block border-2 border-slate-200 rounded-xl overflow-hidden hover:border-blue-500 transition-all shadow-sm">
                                        <img :src="modalData.dp" class="w-full h-56 object-cover hover:scale-105 transition-transform">
                                    </a>
                                </template>
                                <template x-if="!modalData.dp">
                                    <div class="w-full h-56 bg-slate-50 rounded-xl border-2 border-dashed border-slate-200 flex flex-col items-center justify-center text-slate-400">
                                        <i class="fas fa-image-slash text-3xl mb-2"></i>
                                        <span class="text-xs font-bold">Tidak ada bukti DP</span>
                                    </div>
                                </template>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-slate-500 uppercase tracking-widest mb-2"><i class="fas fa-check-double text-blue-500 mr-1"></i> Bukti Pelunasan</p>
                                <template x-if="modalData.lunas">
                                    <a :href="modalData.lunas" target="_blank" class="block border-2 border-slate-200 rounded-xl overflow-hidden hover:border-blue-500 transition-all shadow-sm">
                                        <img :src="modalData.lunas" class="w-full h-56 object-cover hover:scale-105 transition-transform">
                                    </a>
                                </template>
                                <template x-if="!modalData.lunas">
                                    <div class="w-full h-56 bg-slate-50 rounded-xl border-2 border-dashed border-slate-200 flex flex-col items-center justify-center text-slate-400">
                                        <i class="fas fa-clock text-3xl mb-2"></i>
                                        <span class="text-xs font-bold">Belum ada pelunasan</span>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>
                </template>

                <!-- 2. VIEW PENGIRIMAN (BISA EKSPEDISI ATAU TEKNISI) -->
                <template x-if="modalType === 'pengiriman'">
                    <div class="space-y-6 animate-fade-in-down">
                        <div class="flex items-center gap-3 mb-4">
                            <span class="px-3 py-1 bg-amber-100 text-amber-700 font-bold uppercase tracking-wider text-[10px] rounded-md border border-amber-200" x-text="'Metode: ' + modalData.metode"></span>
                            <template x-if="modalData.metode !== 'technician'">
                                <span class="px-3 py-1 bg-slate-100 text-slate-700 font-bold uppercase tracking-wider text-[10px] rounded-md border border-slate-200" x-text="'Resi: ' + modalData.resi"></span>
                            </template>
                        </div>

                        <!-- 2A. Jika Pengiriman via Kurir / Ekspedisi -->
                        <template x-if="modalData.metode !== 'technician'">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                <div>
                                    <p class="text-xs font-bold text-slate-500 uppercase tracking-widest mb-2"><i class="fas fa-box text-amber-500 mr-1"></i> Foto Paket (Ekspedisi)</p>
                                    <template x-if="modalData.fotoPaket">
                                        <a :href="modalData.fotoPaket" target="_blank" class="block border-2 border-slate-200 rounded-xl overflow-hidden hover:border-amber-500 transition-all shadow-sm">
                                            <img :src="modalData.fotoPaket" class="w-full h-56 object-cover hover:scale-105 transition-transform">
                                        </a>
                                    </template>
                                    <template x-if="!modalData.fotoPaket">
                                        <div class="w-full h-56 bg-slate-50 rounded-xl border-2 border-dashed border-slate-200 flex flex-col items-center justify-center text-slate-400">
                                            <i class="fas fa-image-slash text-3xl mb-2"></i>
                                            <span class="text-xs font-bold">Tidak ada foto paket</span>
                                        </div>
                                    </template>
                                </div>
                                <div>
                                    <p class="text-xs font-bold text-slate-500 uppercase tracking-widest mb-2"><i class="fas fa-receipt text-amber-500 mr-1"></i> Foto Resi Asli</p>
                                    <template x-if="modalData.fotoResi">
                                        <a :href="modalData.fotoResi" target="_blank" class="block border-2 border-slate-200 rounded-xl overflow-hidden hover:border-amber-500 transition-all shadow-sm">
                                            <img :src="modalData.fotoResi" class="w-full h-56 object-cover hover:scale-105 transition-transform">
                                        </a>
                                    </template>
                                    <template x-if="!modalData.fotoResi">
                                        <div class="w-full h-56 bg-slate-50 rounded-xl border-2 border-dashed border-slate-200 flex flex-col items-center justify-center text-slate-400">
                                            <i class="fas fa-file-invoice text-3xl mb-2"></i>
                                            <span class="text-xs font-bold">Tidak ada foto resi</span>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </template>

                        <!-- 2B. Jika Pengiriman via Teknisi (Diantar Langsung) -->
                        <template x-if="modalData.metode === 'technician'">
                            <div class="space-y-4">
                                <div class="grid grid-cols-2 gap-4 mb-2">
                                    <div class="bg-blue-50 border border-blue-100 p-4 rounded-xl shadow-sm">
                                        <p class="text-[10px] font-bold text-blue-500 uppercase tracking-widest mb-1"><i class="fas fa-plane-departure mr-1"></i> Waktu Berangkat</p>
                                        <p class="text-sm font-black text-blue-800" x-text="modalData.berangkat"></p>
                                    </div>
                                    <div class="bg-emerald-50 border border-emerald-100 p-4 rounded-xl shadow-sm">
                                        <p class="text-[10px] font-bold text-emerald-500 uppercase tracking-widest mb-1"><i class="fas fa-home mr-1"></i> Waktu Selesai (Tiba)</p>
                                        <p class="text-sm font-black text-emerald-800" x-text="modalData.selesai"></p>
                                    </div>
                                </div>
                                
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                    <div>
                                        <p class="text-xs font-bold text-slate-500 uppercase tracking-widest mb-2"><i class="fas fa-camera text-amber-500 mr-1"></i> Bukti Instalasi</p>
                                        <template x-if="modalData.fotoInstalasi">
                                            <a :href="modalData.fotoInstalasi" target="_blank" class="block border-2 border-slate-200 rounded-xl overflow-hidden hover:border-amber-500 transition-all shadow-sm">
                                                <img :src="modalData.fotoInstalasi" class="w-full h-48 object-cover hover:scale-105 transition-transform">
                                            </a>
                                        </template>
                                        <template x-if="!modalData.fotoInstalasi">
                                            <div class="w-full h-48 bg-slate-50 rounded-xl border-2 border-dashed border-slate-200 flex flex-col items-center justify-center text-slate-400">
                                                <i class="fas fa-image-slash text-3xl mb-2"></i>
                                                <span class="text-xs font-bold">Belum ada foto instalasi</span>
                                            </div>
                                        </template>
                                    </div>
                                    <div>
                                        <p class="text-xs font-bold text-slate-500 uppercase tracking-widest mb-2"><i class="fas fa-clipboard-list text-amber-500 mr-1"></i> Catatan Teknisi</p>
                                        <div class="bg-slate-50 border border-slate-200 rounded-xl p-4 h-48 overflow-y-auto">
                                            <p class="text-sm text-slate-700 leading-relaxed whitespace-pre-line" x-text="modalData.catatanLap"></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>

                    </div>
                </template>

                <!-- 3. VIEW QC & RAKIT -->
                <template x-if="modalType === 'qc'">
                    <div class="space-y-6 animate-fade-in-down">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div>
                                <p class="text-xs font-bold text-slate-500 uppercase tracking-widest mb-2"><i class="fas fa-camera text-emerald-500 mr-1"></i> Dokumentasi Rakit (Depan)</p>
                                <template x-if="modalData.depan">
                                    <a :href="modalData.depan" target="_blank" class="block border-2 border-slate-200 rounded-xl overflow-hidden hover:border-emerald-500 transition-all shadow-sm">
                                        <img :src="modalData.depan" class="w-full h-56 object-cover hover:scale-105 transition-transform">
                                    </a>
                                </template>
                                <template x-if="!modalData.depan">
                                    <div class="w-full h-56 bg-slate-50 rounded-xl border-2 border-dashed border-slate-200 flex flex-col items-center justify-center text-slate-400">
                                        <i class="fas fa-image-slash text-3xl mb-2"></i>
                                        <span class="text-xs font-bold">Belum ada foto</span>
                                    </div>
                                </template>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-slate-500 uppercase tracking-widest mb-2"><i class="fas fa-power-off text-emerald-500 mr-1"></i> Uji Menyala (Stress Test)</p>
                                <template x-if="modalData.menyala">
                                    <a :href="modalData.menyala" target="_blank" class="block border-2 border-slate-200 rounded-xl overflow-hidden hover:border-emerald-500 transition-all shadow-sm">
                                        <img :src="modalData.menyala" class="w-full h-56 object-cover hover:scale-105 transition-transform">
                                    </a>
                                </template>
                                <template x-if="!modalData.menyala">
                                    <div class="w-full h-56 bg-slate-50 rounded-xl border-2 border-dashed border-slate-200 flex flex-col items-center justify-center text-slate-400">
                                        <i class="fas fa-image-slash text-3xl mb-2"></i>
                                        <span class="text-xs font-bold">Belum ada foto uji</span>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>
                </template>

                <!-- 4. VIEW RETUR -->
                <template x-if="modalType === 'retur'">
                    <div class="space-y-6 animate-fade-in-down">
                        <div class="flex items-center gap-3 mb-2">
                            <span class="px-3 py-1 bg-rose-100 text-rose-700 font-bold uppercase tracking-wider text-[10px] rounded-md border border-rose-200" x-text="'Status Klaim: ' + modalData.status"></span>
                        </div>
                        
                        <div class="bg-white border border-slate-200 rounded-xl p-4 shadow-sm border-l-4 border-l-rose-500">
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Alasan Retur / Komplain Kustomer</p>
                            <p class="text-sm font-medium text-slate-700 leading-relaxed" x-text="modalData.catatan"></p>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div>
                                <p class="text-xs font-bold text-slate-500 uppercase tracking-widest mb-2"><i class="fas fa-heart-broken text-rose-500 mr-1"></i> Bukti Kerusakan</p>
                                <template x-if="modalData.foto">
                                    <a :href="modalData.foto" target="_blank" class="block border-2 border-slate-200 rounded-xl overflow-hidden hover:border-rose-500 transition-all shadow-sm">
                                        <img :src="modalData.foto" class="w-full h-56 object-cover hover:scale-105 transition-transform">
                                    </a>
                                </template>
                                <template x-if="!modalData.foto">
                                    <div class="w-full h-56 bg-slate-50 rounded-xl border-2 border-dashed border-slate-200 flex flex-col items-center justify-center text-slate-400">
                                        <i class="fas fa-image-slash text-3xl mb-2"></i>
                                        <span class="text-xs font-bold">Tidak ada foto</span>
                                    </div>
                                </template>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-slate-500 uppercase tracking-widest mb-2"><i class="fas fa-hand-holding-usd text-rose-500 mr-1"></i> Bukti Refund Dana</p>
                                <template x-if="modalData.refund">
                                    <a :href="modalData.refund" target="_blank" class="block border-2 border-slate-200 rounded-xl overflow-hidden hover:border-rose-500 transition-all shadow-sm">
                                        <img :src="modalData.refund" class="w-full h-56 object-cover hover:scale-105 transition-transform">
                                    </a>
                                </template>
                                <template x-if="!modalData.refund">
                                    <div class="w-full h-56 bg-slate-50 rounded-xl border-2 border-dashed border-slate-200 flex flex-col items-center justify-center text-slate-400">
                                        <i class="fas fa-ban text-3xl mb-2"></i>
                                        <span class="text-xs font-bold mt-1 text-center">Belum direfund<br>atau Klaim Ditolak</span>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>
                </template>

            </div>
            
            <!-- Modal Footer -->
            <div class="px-6 py-4 border-t border-slate-100 bg-white text-right">
                <button @click="modalOpen = false" class="px-6 py-2.5 bg-slate-900 text-white text-xs font-bold rounded-lg hover:bg-indigo-600 transition-all shadow-md">Tutup Jendela</button>
            </div>
        </div>
    </div>
</div>
@endsection