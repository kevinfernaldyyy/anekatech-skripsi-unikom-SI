@extends('layouts.customer')

@section('title', 'Profil Saya | DiabloTech')

@section('content')
<div class="max-w-4xl mx-auto pb-10 pt-4 sm:pt-8">

    <!-- HEADER PROFIL -->
    <div class="mb-8 flex items-center gap-4">
        <div class="w-16 h-16 rounded-2xl bg-blue-600 text-white flex items-center justify-center text-3xl font-black shadow-lg shadow-blue-600/30">
            {{ strtoupper(substr($user->name, 0, 1)) }}
        </div>
        <div>
            <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">Pengaturan Profil</h1>
            <p class="text-sm text-slate-500 font-medium">Kelola informasi data diri, instansi, dan keamanan akun Anda.</p>
        </div>
    </div>

    <!-- NOTIFIKASI SUKSES -->
    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-4 rounded-xl mb-6 flex items-center gap-3 shadow-sm">
            <i class="fas fa-check-circle text-emerald-500 text-xl"></i>
            <p class="font-bold text-sm">{{ session('success') }}</p>
        </div>
    @endif

    <!-- NOTIFIKASI ERROR GLOBAL (Opsional, buat jaga-jaga) -->
    @if($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-4 rounded-xl mb-6 flex items-start gap-3 shadow-sm">
            <i class="fas fa-exclamation-triangle text-red-500 text-xl mt-0.5"></i>
            <div>
                <p class="font-bold text-sm mb-1">Gagal menyimpan perubahan!</p>
                <ul class="text-xs list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        
        <!-- KOLOM KIRI: MENU NAVIGASI KECIL -->
        <div class="md:col-span-1">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden sticky top-28">
                <div class="p-2">
                    <a href="#informasi-dasar" class="flex items-center gap-3 px-4 py-3 rounded-xl bg-blue-50 text-blue-700 font-bold text-sm transition-colors mb-1">
                        <i class="fas fa-user-edit w-5 text-center"></i> Informasi Dasar
                    </a>
                    <a href="#keamanan" class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-600 hover:bg-slate-50 hover:text-slate-900 font-bold text-sm transition-colors">
                        <i class="fas fa-lock w-5 text-center"></i> Kata Sandi
                    </a>
                </div>
            </div>
        </div>

        <!-- KOLOM KANAN: FORM INPUT -->
        <div class="md:col-span-2 space-y-6">
            
            <!-- FORM 1: INFORMASI DASAR -->
            <div id="informasi-dasar" class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="px-6 py-5 border-b border-slate-100 flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center">
                        <i class="fas fa-id-card text-sm"></i>
                    </div>
                    <h2 class="text-lg font-black text-slate-800">Informasi Dasar</h2>
                </div>
                
                <form action="{{ route('customer.profile.update_info') }}" method="POST" class="p-6">
                    @csrf
                    @method('PUT')
                    
                    <div class="space-y-5">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Nama Lengkap</label>
                                <input type="text" name="name" value="{{ old('name', $user->name) }}" class="w-full bg-slate-50 border {{ $errors->has('name') ? 'border-red-500' : 'border-slate-200' }} text-slate-800 text-sm px-4 py-3 rounded-xl focus:outline-none focus:border-blue-500 focus:bg-white transition-all font-semibold" required>
                                @error('name')
                                    <p class="text-red-500 text-[10px] font-bold mt-1.5"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Alamat Email</label>
                                <input type="email" name="email" value="{{ old('email', $user->email) }}" class="w-full bg-slate-100 border border-slate-200 text-slate-500 text-sm px-4 py-3 rounded-xl focus:outline-none transition-all font-semibold cursor-not-allowed" required readonly title="Email tidak dapat diubah">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Instansi / Perusahaan</label>
                                <input type="text" name="perusahaan" value="{{ old('perusahaan', $customer->perusahaan ?? '') }}" placeholder="Contoh: PT Aneka Teknologi..." class="w-full bg-slate-50 border {{ $errors->has('perusahaan') ? 'border-red-500' : 'border-slate-200' }} text-slate-800 text-sm px-4 py-3 rounded-xl focus:outline-none focus:border-blue-500 focus:bg-white transition-all font-semibold">
                                @error('perusahaan')
                                    <p class="text-red-500 text-[10px] font-bold mt-1.5"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Nomor Telepon</label>
                                <input type="text" name="nomor_telepon" value="{{ old('nomor_telepon', $customer->nomor_telepon ?? $user->phone ?? '') }}" placeholder="Contoh: 081234567890" class="w-full bg-slate-50 border {{ $errors->has('nomor_telepon') ? 'border-red-500' : 'border-slate-200' }} text-slate-800 text-sm px-4 py-3 rounded-xl focus:outline-none focus:border-blue-500 focus:bg-white transition-all font-semibold">
                                @error('nomor_telepon')
                                    <p class="text-red-500 text-[10px] font-bold mt-1.5"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Alamat Lengkap</label>
                            <textarea name="alamat" rows="3" placeholder="Masukkan alamat lengkap untuk keperluan pengiriman..." class="w-full bg-slate-50 border {{ $errors->has('alamat') ? 'border-red-500' : 'border-slate-200' }} text-slate-800 text-sm px-4 py-3 rounded-xl focus:outline-none focus:border-blue-500 focus:bg-white transition-all font-semibold">{{ old('alamat', $customer->alamat ?? $user->address ?? '') }}</textarea>
                            @error('alamat')
                                <p class="text-red-500 text-[10px] font-bold mt-1.5"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-8 flex justify-end">
                        <button type="submit" class="bg-slate-900 hover:bg-blue-600 text-white font-bold py-3 px-8 rounded-xl text-sm transition-all shadow-md">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>

            <!-- FORM 2: GANTI PASSWORD -->
<div id="keamanan" class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden mt-8">
    <div class="px-6 py-5 border-b border-slate-100 flex items-center gap-3">
        <div class="w-8 h-8 rounded-full bg-red-50 text-red-600 flex items-center justify-center">
            <!-- Ikon Kunci pakai SVG murni -->
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" /></svg>
        </div>
        <h2 class="text-lg font-black text-slate-800">Ganti Kata Sandi</h2>
    </div>
    
    <form action="{{ route('customer.profile.update_password') }}" method="POST" class="p-6">
        @csrf
        @method('PUT')
        
        <div class="space-y-5">
            <!-- Kata Sandi Saat Ini -->
            <div x-data="{ show: false }">
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Kata Sandi Saat Ini</label>
                <div class="relative">
                    <input :type="show ? 'text' : 'password'" name="current_password" class="w-full bg-slate-50 border {{ $errors->has('current_password') ? 'border-red-500' : 'border-slate-200' }} text-slate-800 text-sm pl-4 pr-12 py-3 rounded-xl focus:outline-none focus:border-red-500 focus:bg-white transition-all font-semibold" required>
                    <button type="button" @click="show = !show" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 focus:outline-none">
                        <!-- Ikon Mata Terbuka -->
                        <svg x-show="!show" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                        <!-- Ikon Mata Dicoret -->
                        <svg x-show="show" class="w-5 h-5" style="display: none;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a10.05 10.05 0 015.458-5.922m5.016-1.12A10.04 10.04 0 0112 5c4.478 0 8.268 2.943 9.542 7-.425 1.352-1.144 2.56-2.062 3.52M3 3l18 18" /></svg>
                    </button>
                </div>
                @error('current_password')
                    <p class="text-red-500 text-[10px] font-bold mt-1.5"><i class="fas fa-exclamation-circle"></i> Kata sandi lama tidak sesuai.</p>
                @enderror
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <!-- Kata Sandi Baru -->
                <div x-data="{ show: false }">
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Kata Sandi Baru</label>
                    <div class="relative">
                        <input :type="show ? 'text' : 'password'" name="password" class="w-full bg-slate-50 border {{ $errors->has('password') ? 'border-red-500' : 'border-slate-200' }} text-slate-800 text-sm pl-4 pr-12 py-3 rounded-xl focus:outline-none focus:border-blue-500 focus:bg-white transition-all font-semibold" required>
                        <button type="button" @click="show = !show" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 focus:outline-none">
                            <svg x-show="!show" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                            <svg x-show="show" class="w-5 h-5" style="display: none;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a10.05 10.05 0 015.458-5.922m5.016-1.12A10.04 10.04 0 0112 5c4.478 0 8.268 2.943 9.542 7-.425 1.352-1.144 2.56-2.062 3.52M3 3l18 18" /></svg>
                        </button>
                    </div>
                    @error('password')
                        <p class="text-red-500 text-[10px] font-bold mt-1.5"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>
                    @enderror
                </div>

                <!-- Ulangi Kata Sandi Baru -->
                <div x-data="{ show: false }">
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Ulangi Kata Sandi Baru</label>
                    <div class="relative">
                        <input :type="show ? 'text' : 'password'" name="password_confirmation" class="w-full bg-slate-50 border border-slate-200 text-slate-800 text-sm pl-4 pr-12 py-3 rounded-xl focus:outline-none focus:border-blue-500 focus:bg-white transition-all font-semibold" required>
                        <button type="button" @click="show = !show" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 focus:outline-none">
                            <svg x-show="!show" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                            <svg x-show="show" class="w-5 h-5" style="display: none;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a10.05 10.05 0 015.458-5.922m5.016-1.12A10.04 10.04 0 0112 5c4.478 0 8.268 2.943 9.542 7-.425 1.352-1.144 2.56-2.062 3.52M3 3l18 18" /></svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-8 flex justify-end">
            <button type="submit" class="bg-white border-2 border-slate-200 hover:border-slate-900 text-slate-800 font-bold py-2.5 px-8 rounded-xl text-sm transition-all">
                Update Kata Sandi
            </button>
        </div>
    </form>
</div>
<script>
    function togglePassword(fieldId, iconId) {
        const inputField = document.getElementById(fieldId);
        const iconElement = document.getElementById(iconId);

        if (inputField.type === "password") {
            inputField.type = "text";
            iconElement.classList.remove("fa-eye");
            iconElement.classList.add("fa-eye-slash");
        } else {
            inputField.type = "password";
            iconElement.classList.remove("fa-eye-slash");
            iconElement.classList.add("fa-eye");
        }
    }
</script>
        </div>
    </div>
</div>
@endsection
