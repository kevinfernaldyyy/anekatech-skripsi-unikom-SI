@extends('layouts.admin') <!-- Sesuaikan dengan nama layout utamamu -->

@section('content')
<div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
    <div>
        <h2 class="text-2xl font-black text-slate-800">Log Aktivitas Sistem</h2>
        <p class="text-slate-500 text-sm">Pantau semua perubahan dan aktivitas pengguna secara real-time.</p>
    </div>
    
    <!-- 👇 Tombol Cetak Hanya Muncul di Tab Audit 👇 -->
    @if($tab == 'audit')
    <a href="{{ route('admin.logs.cetak_pdf') }}" target="_blank" class="bg-rose-600 hover:bg-rose-700 text-white px-4 py-2.5 rounded-lg text-sm font-bold shadow-md flex items-center gap-2 transition-colors">
        <i class="fas fa-print"></i> Cetak Laporan Audit Admin
    </a>
    @endif
</div>

<div class="mb-6 border-b border-slate-200">
    <nav class="-mb-px flex space-x-8 overflow-x-auto scrollbar-hide" aria-label="Tabs">
        <!-- Tab Lama -->
        <a href="{{ route('admin.logs.index', ['tab' => 'semua']) }}" 
           class="whitespace-nowrap py-4 px-1 border-b-2 font-black text-sm transition-colors {{ $tab == 'semua' ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-slate-400 hover:text-slate-700 hover:border-slate-300' }}">
            <i class="fas fa-list-ul mr-2"></i> Semua Riwayat
        </a>
        <a href="{{ route('admin.logs.index', ['tab' => 'aktivitas']) }}" 
           class="whitespace-nowrap py-4 px-1 border-b-2 font-black text-sm transition-colors {{ $tab == 'aktivitas' ? 'border-blue-600 text-blue-600' : 'border-transparent text-slate-400 hover:text-slate-700 hover:border-slate-300' }}">
            <i class="fas fa-users-cog mr-2"></i> Aktivitas Pengguna
        </a>
        <a href="{{ route('admin.logs.index', ['tab' => 'stok']) }}" 
           class="whitespace-nowrap py-4 px-1 border-b-2 font-black text-sm transition-colors {{ $tab == 'stok' ? 'border-amber-500 text-amber-600' : 'border-transparent text-slate-400 hover:text-slate-700 hover:border-slate-300' }}">
            <i class="fas fa-box-open mr-2"></i> Mutasi Stok
        </a>
        
        <!-- 👇 TAB BARU: AUDIT ADMIN 👇 -->
        <a href="{{ route('admin.logs.index', ['tab' => 'audit']) }}" 
           class="whitespace-nowrap py-4 px-1 border-b-2 font-black text-sm transition-colors {{ $tab == 'audit' ? 'border-rose-500 text-rose-600' : 'border-transparent text-slate-400 hover:text-slate-700 hover:border-slate-300' }}">
            <i class="fas fa-user-shield mr-2"></i> Audit Admin
        </a>
    </nav>
</div>

<div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden mb-8">
    <table class="w-full text-left text-sm">
        <thead class="bg-slate-50 border-b border-slate-200 text-slate-600 font-bold uppercase tracking-wider text-[10px]">
            <tr>
                <th class="px-6 py-4 w-48">Waktu & IP</th>
                <th class="px-6 py-4">Pengguna</th>
                <th class="px-6 py-4 w-32 text-center">Jenis Aksi</th>
                <th class="px-6 py-4">Deskripsi Aktivitas</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            @forelse($logs as $log)
            <tr class="hover:bg-slate-50/50 transition-colors">
                <!-- Waktu & IP Address -->
                <td class="px-6 py-4">
                    <p class="font-bold text-slate-700">{{ $log->created_at->format('d M Y') }}</p>
                    <p class="text-[11px] text-slate-500 font-medium">{{ $log->created_at->format('H:i:s') }} WIB</p>
                    <p class="text-[10px] text-slate-400 mt-1"><i class="fas fa-network-wired"></i> {{ $log->ip_address ?? 'Unknown IP' }}</p>
                </td>
                
                <!-- Info Pengguna -->
                <td class="px-6 py-4">
                    @if($log->user)
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 rounded-full bg-slate-100 text-slate-600 flex items-center justify-center font-bold text-xs border border-slate-200">
                                {{ substr($log->user->name, 0, 1) }}
                            </div>
                            <div>
                                <p class="font-bold text-slate-800 text-xs">{{ $log->user->name }}</p>
                                <span class="text-[9px] font-black uppercase tracking-wider {{ $log->user->role == 'admin' ? 'text-purple-600' : 'text-blue-600' }}">
                                    {{ $log->user->role }}
                                </span>
                            </div>
                        </div>
                    @else
                        <span class="text-xs font-bold text-slate-400 italic"><i class="fas fa-robot"></i> Sistem / Terhapus</span>
                    @endif
                </td>

                <!-- Badge Aksi (Warna-warni sesuai jenis aksi) -->
                <td class="px-6 py-4 text-center">
                  @php
                        $badgeClass = 'bg-slate-100 text-slate-600';
                        $icon = 'fas fa-cog';
                        
                        if($log->action == 'create') { $badgeClass = 'bg-emerald-100 text-emerald-700'; $icon = 'fas fa-plus'; }
                        elseif($log->action == 'update') { $badgeClass = 'bg-indigo-100 text-indigo-700'; $icon = 'fas fa-pen'; }
                        elseif($log->action == 'delete') { $badgeClass = 'bg-rose-100 text-rose-700'; $icon = 'fas fa-trash'; }
                        elseif($log->action == 'block') { $badgeClass = 'bg-slate-200 text-slate-700'; $icon = 'fas fa-ban'; }
                        // 👇 Tambahan khusus Mutasi Stok 👇
                        elseif($log->action == 'stock_out') { $badgeClass = 'bg-amber-100 text-amber-700'; $icon = 'fas fa-box-open'; }
                        elseif($log->action == 'stock_in') { $badgeClass = 'bg-teal-100 text-teal-700'; $icon = 'fas fa-box'; }
                    @endphp
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md text-[10px] font-black uppercase tracking-wider {{ $badgeClass }}">
                        <i class="{{ $icon }}"></i> {{ $log->action }}
                    </span>
                </td>

                <!-- Deskripsi -->
                <td class="px-6 py-4">
                    <p class="text-slate-700 text-xs font-medium leading-relaxed">{{ $log->description }}</p>
                    
                    <!-- Kalau mau menampilkan JSON detailnya nanti, bisa ditambahkan tombol di sini -->
                   @if($log->new_values || $log->old_values)
                        <button type="button" 
                            data-old="{{ json_encode($log->old_values) }}" 
                            data-new="{{ json_encode($log->new_values) }}"
                            data-user="{{ $log->user ? $log->user->name . ' (' . ucfirst($log->user->role) . ')' : 'Sistem / Terhapus' }}"
                            data-desc="{{ $log->description }}"
                            onclick="openDetailModal(this)"
                            class="mt-1.5 text-[10px] font-bold text-indigo-500 hover:text-indigo-700 flex items-center gap-1 transition-colors">
                            <i class="fas fa-code"></i> Lihat Detail Data
                        </button>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="px-6 py-12 text-center">
                    <div class="text-slate-400 mb-2"><i class="fas fa-history text-3xl"></i></div>
                    <p class="text-sm font-medium text-slate-500">Belum ada aktivitas yang terekam di sistem.</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    
    <!-- Paginasi -->
    @if($logs->hasPages())
        <div class="px-6 py-4 border-t border-slate-100 bg-slate-50">
            {{ $logs->links() }}
        </div>
    @endif
</div>


<!-- MODAL DETAIL LOG -->
<div id="modalLogDetail" class="fixed inset-0 z-[99] hidden">
    <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" onclick="closeDetailModal()"></div>
    
    <div class="fixed inset-0 z-10 overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
            <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-3xl border border-slate-200">
                
                <!-- Header Modal -->
               <div class="bg-slate-50 px-6 py-4 border-b border-slate-100 flex justify-between items-start">
                    <div>
                        <h3 class="text-lg font-black text-slate-800 flex items-center gap-2">
                            <i class="fas fa-exchange-alt text-indigo-600"></i> Rincian Perubahan Data
                        </h3>
                        <div class="mt-2.5 space-y-1.5">
                            <p class="text-xs text-slate-500 font-medium flex items-center">
                                <i class="fas fa-user-circle w-4 text-slate-400"></i> Aksi oleh: 
                                <span id="modalUserName" class="font-bold text-indigo-600 px-2 py-0.5 bg-indigo-100 rounded-md ml-2"></span>
                            </p>
                            <p class="text-xs text-slate-500 font-medium flex items-center">
                                <i class="fas fa-box w-4 text-slate-400"></i> Aktivitas: 
                                <span id="modalDesc" class="font-bold text-slate-700 px-2 py-0.5 bg-slate-200/70 border border-slate-200 rounded-md ml-2"></span>
                            </p>
                        </div>
                    </div>
                    <button type="button" onclick="closeDetailModal()" class="text-slate-400 hover:text-red-500 transition-colors mt-1">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                <!-- Isi Modal (Tabel Perbandingan) -->
                <div class="px-6 py-5 bg-slate-50/50">
                    <div class="border border-slate-200 rounded-xl overflow-hidden bg-white shadow-sm">
                        <table class="w-full text-left">
                            <thead class="bg-slate-100/80 border-b border-slate-200 text-slate-500 text-[10px] uppercase font-bold tracking-wider">
                                <tr>
                                    <th class="px-5 py-3 w-1/3"><i class="fas fa-tag mr-1"></i> Informasi</th>
                                    <th class="px-5 py-3 w-1/3"><i class="fas fa-history mr-1"></i> Data Sebelumnya</th>
                                    <th class="px-5 py-3 w-1/3"><i class="fas fa-bolt mr-1"></i> Data Baru</th>
                                </tr>
                            </thead>
                            <tbody id="comparisonTableBody" class="divide-y divide-slate-100">
                                <!-- Data akan disuntikkan via JavaScript di sini -->
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Footer Modal -->
                <div class="bg-white px-6 py-4 border-t border-slate-100 flex justify-end rounded-b-2xl">
                    <button type="button" onclick="closeDetailModal()" class="bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold py-2.5 px-6 rounded-lg text-sm transition-colors shadow-sm">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

<script>
   function openDetailModal(btn) {
        // Ambil data dari tombol
        const oldDataStr = btn.getAttribute('data-old');
        const newDataStr = btn.getAttribute('data-new');
        const userName = btn.getAttribute('data-user');
        const desc = btn.getAttribute('data-desc'); // 👇 Tangkap deskripsi/nama barang
        
        // Suntikkan teks ke dalam header modal
        document.getElementById('modalUserName').textContent = userName;
        document.getElementById('modalDesc').textContent = desc; // 👇 Suntikkan ke HTML
        
        const oldData = oldDataStr !== 'null' && oldDataStr !== '[]' ? JSON.parse(oldDataStr) : {};
        const newData = newDataStr !== 'null' && newDataStr !== '[]' ? JSON.parse(newDataStr) : {};
        
        const tableBody = document.getElementById('comparisonTableBody');
        tableBody.innerHTML = ''; // Bersihkan tabel
        
        // Daftar kolom teknis yang TIDAK PERLU ditampilin ke user awam
        const ignoreKeys = ['id', 'created_at', 'updated_at', 'deleted_at', 'gambar', 'remember_token'];
        
        // Fungsi untuk merapikan nama kolom (contoh: harga_jual -> Harga Jual)
        const formatKey = (key) => {
            return key.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
        };

        // Fungsi untuk format uang/angka biar lebih cantik
        const formatValue = (key, value) => {
            if (value === null || value === '') return '<span class="text-slate-400 italic">Kosong</span>';
            if (key.includes('harga')) return 'Rp ' + parseInt(value).toLocaleString('id-ID');
            return value;
        };

        let hasChanges = false;

        // Loop data baru (karena getChanges() Laravel hanya nyimpen kolom yang berubah)
        if (newData && Object.keys(newData).length > 0) {
            for (const key in newData) {
                if (ignoreKeys.includes(key)) continue;

                let oldVal = oldData && oldData[key] !== undefined ? oldData[key] : null;
                let newVal = newData[key];

                const row = `
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-5 py-4 font-bold text-slate-700 text-xs bg-slate-50/50 border-r border-slate-100">
                            ${formatKey(key)}
                        </td>
                        <td class="px-5 py-4 text-rose-600 font-medium text-xs line-through decoration-rose-300">
                            ${formatValue(key, oldVal)}
                        </td>
                        <td class="px-5 py-4 text-emerald-600 font-bold text-xs bg-emerald-50/30">
                            <div class="flex items-center gap-2">
                                <i class="fas fa-arrow-right text-[10px] text-emerald-400"></i> 
                                ${formatValue(key, newVal)}
                            </div>
                        </td>
                    </tr>
                `;
                tableBody.insertAdjacentHTML('beforeend', row);
                hasChanges = true;
            }
        }

        // Jika tidak ada perubahan relevan yang bisa ditampilkan
        if (!hasChanges) {
            tableBody.innerHTML = `
                <tr>
                    <td colspan="3" class="px-5 py-8 text-center">
                        <i class="fas fa-info-circle text-slate-300 text-3xl mb-2"></i>
                        <p class="text-slate-500 text-xs font-medium">Hanya terjadi pembaruan pada sistem (Waktu / Data Teknis).</p>
                    </td>
                </tr>
            `;
        }
        
        document.getElementById('modalLogDetail').classList.remove('hidden');
    }

    function closeDetailModal() {
        document.getElementById('modalLogDetail').classList.add('hidden');
    }
</script>