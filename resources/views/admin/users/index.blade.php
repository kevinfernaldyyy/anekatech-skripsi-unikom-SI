@extends('layouts.admin')
@section('title', 'Manajemen Pengguna')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
    <div>
        <h2 class="text-2xl font-black text-slate-800">Manajemen Pengguna</h2>
        <p class="text-slate-500 text-sm">Kelola akses Admin dan Teknisi AnekaTech.</p>
    </div>
    
    <div class="flex items-center gap-2 w-full sm:w-auto">
        @if($inactiveUsers->count() > 0)
        <button type="button" onclick="toggleInactiveTable()" id="btnToggleInactive" class="w-full sm:w-auto bg-white border border-slate-300 text-slate-700 hover:bg-slate-50 font-bold py-2 px-4 rounded-lg flex items-center justify-center gap-2 shadow-sm transition-all text-sm">
            <i class="fas fa-eye text-slate-500"></i> Lihat Terblokir ({{ $inactiveUsers->count() }})
        </button>
        @endif
        
        <button onclick="toggleModalUser(true)" class="w-full sm:w-auto bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg flex items-center justify-center gap-2 shadow-lg shadow-indigo-200 transition-all text-sm">
            <i class="fas fa-plus"></i> Tambah Pengguna
        </button>
    </div>
</div>

<div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden mb-8">
    <table class="w-full text-left text-sm">
        <thead class="bg-slate-50 border-b border-slate-200 text-slate-600 font-bold uppercase tracking-wider text-[10px]">
            <tr>
                <th class="px-6 py-4">Nama Pengguna</th>
                <th class="px-6 py-4">Role & Status</th>
                <th class="px-6 py-4">Aktivitas Terakhir</th>
                <th class="px-6 py-4 text-center">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            @forelse($activeUsers as $user)
            <tr class="hover:bg-slate-50/50 transition-colors">
                <td class="px-6 py-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center font-black text-lg border border-indigo-200">
                            {{ substr($user->name, 0, 1) }}
                        </div>
                        <div>
                            <p class="font-bold text-slate-800">{{ $user->name }}</p>
                            <p class="text-xs text-slate-500">{{ $user->email }} • {{ $user->phone ?? '-' }}</p>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4">
                    <span class="inline-block px-2.5 py-1 rounded-md text-[10px] font-black uppercase tracking-wider {{ $user->role == 'admin' ? 'bg-purple-100 text-purple-700' : 'bg-blue-100 text-blue-700' }} mb-1">
                        {{ $user->role }}
                    </span>
                    <br>
                    <span class="text-[10px] font-bold text-emerald-600"><i class="fas fa-check-circle"></i> Akun Aktif</span>
                </td>
                <td class="px-6 py-4">
                    @if($user->is_online)
                        <div class="flex items-center gap-1.5 text-xs font-bold text-emerald-600 mb-0.5">
                            <span class="relative flex h-2 w-2">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                            </span>
                            Sedang Online
                        </div>
                    @else
                        <div class="flex items-center gap-1.5 text-xs font-bold text-slate-400 mb-0.5">
                            <div class="h-2 w-2 rounded-full bg-slate-300"></div> Offline
                        </div>
                    @endif
                    <p class="text-[10px] text-slate-500">Login: {{ $user->last_login_at ? \Carbon\Carbon::parse($user->last_login_at)->diffForHumans() : 'Belum pernah' }}</p>
                </td>
                <td class="px-6 py-4 text-center">
                    <button type="button" onclick="openEditModal('{{ route('admin.users.update', $user->id) }}', '{{ addslashes($user->name) }}', '{{ $user->email }}', '{{ $user->phone }}', '{{ $user->role }}', '{{ $user->account_status }}')" class="text-indigo-600 hover:text-white bg-indigo-50 hover:bg-indigo-500 px-4 py-2 rounded-lg transition-all font-bold text-xs shadow-sm flex items-center justify-center gap-1.5 mx-auto">
                        <i class="fas fa-edit"></i> Edit
                    </button>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="px-6 py-8 text-center text-slate-500 text-sm font-medium">Belum ada pengguna aktif.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($inactiveUsers->count() > 0)
<div id="inactiveTableContainer" class="hidden transition-all duration-500 ease-in-out">
    <div class="mb-4 flex items-center gap-2 mt-8">
        <i class="fas fa-user-lock text-rose-500 text-lg"></i>
        <h3 class="text-lg font-black text-slate-800">Akun Diblokir</h3>
        <span class="bg-rose-100 text-rose-700 text-[10px] font-bold px-2 py-0.5 rounded-full">{{ $inactiveUsers->count() }}</span>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden mb-8 opacity-80 hover:opacity-100 transition-opacity">
        <table class="w-full text-left text-sm">
            <thead class="bg-slate-50 border-b border-slate-200 text-slate-600 font-bold uppercase tracking-wider text-[10px]">
                <tr>
                    <th class="px-6 py-4">Nama Pengguna</th>
                    <th class="px-6 py-4">Role & Status</th>
                    <th class="px-6 py-4">Aktivitas Terakhir</th>
                    <th class="px-6 py-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @foreach($inactiveUsers as $user)
                <tr class="bg-slate-50/30">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-slate-200 text-slate-500 flex items-center justify-center font-black text-lg border border-slate-300">
                                {{ substr($user->name, 0, 1) }}
                            </div>
                            <div>
                                <p class="font-bold text-slate-700 line-through decoration-slate-400">{{ $user->name }}</p>
                                <p class="text-xs text-slate-400">{{ $user->email }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="inline-block px-2.5 py-1 rounded-md text-[10px] font-black uppercase tracking-wider bg-slate-200 text-slate-600 mb-1">
                            {{ $user->role }}
                        </span>
                        <br>
                        <span class="text-[10px] font-bold text-rose-600"><i class="fas fa-ban"></i> Diblokir</span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-1.5 text-xs font-bold text-slate-400 mb-0.5">
                            <div class="h-2 w-2 rounded-full bg-slate-300"></div> Akses Ditutup
                        </div>
                        <p class="text-[10px] text-slate-400">Logout: {{ $user->last_logout_at ? \Carbon\Carbon::parse($user->last_logout_at)->diffForHumans() : '-' }}</p>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <button type="button" onclick="openEditModal('{{ route('admin.users.update', $user->id) }}', '{{ addslashes($user->name) }}', '{{ $user->email }}', '{{ $user->phone }}', '{{ $user->role }}', '{{ $user->account_status }}')" class="text-slate-500 hover:text-indigo-600 bg-white border border-slate-200 hover:border-indigo-200 hover:bg-indigo-50 px-4 py-2 rounded-lg transition-all font-bold text-xs shadow-sm flex items-center justify-center gap-1.5 mx-auto">
                            <i class="fas fa-undo"></i> Pulihkan
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    </div>
@endif

<div id="modalTambahUser" class="fixed inset-0 z-[99] hidden">
    <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" onclick="toggleModalUser(false)"></div>
    
    <div class="fixed inset-0 z-10 overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
            <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg border border-slate-200">
                
                <div class="bg-slate-50 px-6 py-4 border-b border-slate-100 flex justify-between items-center">
                    <h3 class="text-lg font-black text-slate-800 flex items-center gap-2">
                        <i class="fas fa-user-plus text-indigo-600"></i> Tambah Pengguna Internal
                    </h3>
                    <button type="button" onclick="toggleModalUser(false)" class="text-slate-400 hover:text-red-500 transition-colors">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>

                <form action="{{ route('admin.users.store') }}" method="POST">
                    @csrf
                    <div class="px-6 py-5 space-y-4">
                        
                        <div>
                            <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
                            <input type="text" name="name" required placeholder="Cth: Teknisi Budi" class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2.5 px-3">
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1">Email <span class="text-red-500">*</span></label>
                                <input type="email" name="email" required placeholder="budi@anekatech.com" class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2.5 px-3">
                            </div>
                            
                            <div>
                                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1">No. WhatsApp</label>
                                <input type="text" name="phone" placeholder="0812..." class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2.5 px-3">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1">Role / Hak Akses <span class="text-red-500">*</span></label>
                                <select name="role" required class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2.5 px-3 bg-white">
                                    <option value="teknisi">Teknisi</option>
                                    <option value="admin">Admin</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1">Password Awal <span class="text-red-500">*</span></label>
                                <input type="password" name="password" required minlength="8" placeholder="Minimal 8 karakter" class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2.5 px-3">
                            </div>
                        </div>

                        <div class="bg-blue-50 border border-blue-100 rounded-lg p-3 flex gap-3 mt-2">
                            <i class="fas fa-info-circle text-blue-500 mt-0.5"></i>
                            <p class="text-[11px] text-blue-700 leading-relaxed">Pengguna baru akan secara otomatis berstatus <strong>Aktif</strong>. Anda dapat memblokir akses pengguna melalui fitur Edit nantinya.</p>
                        </div>
                    </div>

                    <div class="bg-slate-50 px-6 py-4 border-t border-slate-100 flex justify-end gap-2 rounded-b-2xl">
                        <button type="button" onclick="toggleModalUser(false)" class="bg-white border border-slate-300 text-slate-700 hover:bg-slate-50 font-bold py-2.5 px-5 rounded-lg text-sm transition-colors shadow-sm">
                            Batal
                        </button>
                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2.5 px-6 rounded-lg text-sm transition-all shadow-md shadow-indigo-200 flex items-center gap-2">
                            <i class="fas fa-save"></i> Simpan Pengguna
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div id="modalEditUser" class="fixed inset-0 z-[99] hidden">
    <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" onclick="toggleEditModal(false)"></div>
    
    <div class="fixed inset-0 z-10 overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
            <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg border border-slate-200">
                
                <div class="bg-slate-50 px-6 py-4 border-b border-slate-100 flex justify-between items-center">
                    <h3 class="text-lg font-black text-slate-800 flex items-center gap-2">
                        <i class="fas fa-user-edit text-indigo-600"></i> Edit Pengguna
                    </h3>
                    <button type="button" onclick="toggleEditModal(false)" class="text-slate-400 hover:text-red-500 transition-colors">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>

                <form id="formEditUser" method="POST">
                    @csrf
                    @method('PUT') <div class="px-6 py-5 space-y-4">
                        
                        <div>
                            <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
                            <input type="text" name="name" id="edit_name" required class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2.5 px-3">
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1">Email <span class="text-red-500">*</span></label>
                                <input type="email" name="email" id="edit_email" required class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2.5 px-3">
                            </div>
                            
                            <div>
                                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1">No. WhatsApp</label>
                                <input type="text" name="phone" id="edit_phone" class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2.5 px-3">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1">Role / Hak Akses <span class="text-red-500">*</span></label>
                                <select name="role" id="edit_role" required class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2.5 px-3 bg-white">
                                    <option value="teknisi">Teknisi</option>
                                    <option value="admin">Admin</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1">Status Akun <span class="text-red-500">*</span></label>
                                <select name="account_status" id="edit_status" required class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2.5 px-3 bg-white">
                                    <option value="active">Aktif</option>
                                    <option value="inactive">Blokir / Nonaktif</option>
                                </select>
                            </div>
                        </div>

                        <div class="mt-4 pt-4 border-t border-slate-100">
                            <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1">Reset Password (Opsional)</label>
                            <input type="password" name="password" minlength="8" placeholder="Kosongkan jika tidak ingin mengubah password" class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2.5 px-3 bg-slate-50 focus:bg-white transition-colors">
                        </div>
                    </div>

                    <div class="bg-slate-50 px-6 py-4 border-t border-slate-100 flex justify-end gap-2 rounded-b-2xl">
                        <button type="button" onclick="toggleEditModal(false)" class="bg-white border border-slate-300 text-slate-700 hover:bg-slate-50 font-bold py-2.5 px-5 rounded-lg text-sm transition-colors shadow-sm">
                            Batal
                        </button>
                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2.5 px-6 rounded-lg text-sm transition-all shadow-md shadow-indigo-200 flex items-center gap-2">
                            <i class="fas fa-save"></i> Update Data
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function toggleEditModal(show) {
        const modal = document.getElementById('modalEditUser');
        if (show) modal.classList.remove('hidden');
        else modal.classList.add('hidden');
    }

    // Ubah parameter pertama jadi 'url'
    function openEditModal(url, name, email, phone, role, status) {
        // Isi form dengan data dari baris yang diklik
        document.getElementById('edit_name').value = name;
        document.getElementById('edit_email').value = email;
        document.getElementById('edit_phone').value = phone || '';
        document.getElementById('edit_role').value = role;
        document.getElementById('edit_status').value = status;
        
        // Pake URL sakti bawaan Laravel yang anti-nyasar
        document.getElementById('formEditUser').action = url;
        
        // Tampilkan Modal
        toggleEditModal(true);
    }
    function toggleModalUser(show) {
        const modal = document.getElementById('modalTambahUser');
        if (show) {
            modal.classList.remove('hidden');
        } else {
            modal.classList.add('hidden');
        }
    }

    function toggleInactiveTable() {
        const container = document.getElementById('inactiveTableContainer');
        const btn = document.getElementById('btnToggleInactive');
        
        // Cek apakah tabel sedang disembunyikan
        if (container.classList.contains('hidden')) {
            // Tampilkan tabel
            container.classList.remove('hidden');
            // Ubah teks dan icon tombol
            btn.innerHTML = '<i class="fas fa-eye-slash text-rose-500"></i> Sembunyikan Terblokir';
            btn.classList.add('bg-rose-50', 'border-rose-200');
            btn.classList.remove('bg-white', 'border-slate-300');
        } else {
            // Sembunyikan tabel
            container.classList.add('hidden');
            // Kembalikan teks dan icon tombol ke semula
            btn.innerHTML = '<i class="fas fa-eye text-slate-500"></i> Lihat Terblokir';
            btn.classList.remove('bg-rose-50', 'border-rose-200');
            btn.classList.add('bg-white', 'border-slate-300');
        }
    }
</script>
@endsection