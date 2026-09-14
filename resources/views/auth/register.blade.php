<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun - DiabloTech</title>
    <link rel="icon" type="image/jpeg" href="{{ asset('img/logo.jpeg') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <style>
        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-slide-down {
            animation: slideDown 0.3s ease-out forwards;
        }
    </style>
</head>
<body class="bg-slate-50 font-sans antialiased text-slate-800 flex items-center justify-center min-h-screen relative overflow-x-hidden py-10">

    <!-- Dekorasi Background -->
    <div class="fixed top-[-10%] left-[-10%] w-[500px] h-[500px] bg-blue-400/20 rounded-full mix-blend-multiply filter blur-[100px] pointer-events-none"></div>
    <div class="fixed bottom-[-10%] right-[-10%] w-[500px] h-[500px] bg-emerald-400/20 rounded-full mix-blend-multiply filter blur-[100px] pointer-events-none"></div>

    <div class="w-full max-w-md px-4 relative z-10">
        
        <!-- Header & Logo -->
        <div class="text-center mb-8">
            <a href="{{ url('/') }}" class="inline-flex items-center gap-3 transition-transform hover:scale-105">
                <div class="w-12 h-12 bg-white rounded-xl shadow-md flex items-center justify-center overflow-hidden border border-slate-100">
                    <img src="{{ asset('img/logo.jpeg') }}" alt="Logo DiabloTech" class="w-full h-full object-cover">
                </div>
                <span class="font-black text-3xl tracking-wider text-slate-900">DIABLO<span class="text-blue-600">TECH</span></span>
            </a>
            <p class="text-slate-500 text-sm mt-3 font-medium">Buat akun untuk mulai merakit PC impianmu!</p>
        </div>

        <!-- Global Error Alert (Server-Side Laravel) -->
        @if ($errors->any())
            <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-r-xl shadow-sm animate-slide-down relative" role="alert">
                <div class="flex items-center mb-1">
                    <i class="fas fa-exclamation-circle text-red-500 mr-2 text-lg"></i>
                    <p class="font-bold text-red-700 text-sm">Pendaftaran Gagal!</p>
                </div>
                <ul class="list-disc pl-7 text-xs text-red-600 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Card Form Register -->
        <div class="bg-white rounded-2xl shadow-xl border border-slate-100 p-6 sm:p-8 relative overflow-hidden">
            
            <!-- Garis Dekorasi Card -->
            <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-blue-600 via-indigo-600 to-emerald-500"></div>

            <form action="{{ route('register') }}" method="POST" id="registerForm" class="space-y-5" novalidate>
                @csrf

                <!-- Input Nama Lengkap -->
                <div>
                    <label for="name" class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Nama Lengkap <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <i class="fas fa-user text-slate-400"></i>
                        </div>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" required autofocus placeholder="Cth: Kevin Fernaldy" 
                               class="w-full pl-10 pr-4 py-3 bg-slate-50 border @error('name') border-red-300 focus:border-red-500 focus:ring-red-500/20 @else border-slate-200 focus:border-blue-500 focus:ring-blue-500/20 @enderror text-sm rounded-xl focus:outline-none focus:ring-4 transition-all duration-200">
                    </div>
                </div>

                <!-- Input Email -->
                <div>
                    <label for="email" class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Alamat Email <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <i class="fas fa-envelope text-slate-400"></i>
                        </div>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required placeholder="email@contoh.com" 
                               class="w-full pl-10 pr-4 py-3 bg-slate-50 border @error('email') border-red-300 focus:border-red-500 focus:ring-red-500/20 @else border-slate-200 focus:border-blue-500 focus:ring-blue-500/20 @enderror text-sm rounded-xl focus:outline-none focus:ring-4 transition-all duration-200">
                    </div>
                </div>

                <!-- Input No WA (Diperbaiki agar konsisten) -->
                <div>
                    <label for="phone" class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Nomor WhatsApp <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <i class="fab fa-whatsapp text-slate-400 text-base"></i>
                        </div>
                        <input type="tel" id="phone" name="phone" value="{{ old('phone') }}" required placeholder="081234567890" pattern="^(08|628)[0-9]{8,11}$"
                               class="w-full pl-10 pr-4 py-3 bg-slate-50 border @error('phone') border-red-300 focus:border-red-500 focus:ring-red-500/20 @else border-slate-200 focus:border-blue-500 focus:ring-blue-500/20 @enderror text-sm rounded-xl focus:outline-none focus:ring-4 transition-all duration-200">
                    </div>
                    @error('phone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Grid Password & Konfirmasi -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Password <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                <i class="fas fa-lock text-slate-400"></i>
                            </div>
                            <input type="password" id="password" name="password" required placeholder="••••••••" minlength="8"
                                   class="w-full pl-10 pr-10 py-3 bg-slate-50 border @error('password') border-red-300 focus:border-red-500 focus:ring-red-500/20 @else border-slate-200 focus:border-blue-500 focus:ring-blue-500/20 @enderror text-sm rounded-xl focus:outline-none focus:ring-4 transition-all duration-200">
                            <!-- Toggle Button -->
                            <button type="button" onclick="toggleVisibility('password', 'eye-icon-1')" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-blue-600 transition-colors">
                                <i class="fas fa-eye" id="eye-icon-1"></i>
                            </button>
                        </div>
                    </div>
                    
                    <!-- Ulangi Password -->
                    <div>
                        <label for="password_confirmation" class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Ulangi Password <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                <i class="fas fa-check-double text-slate-400"></i>
                            </div>
                            <input type="password" id="password_confirmation" name="password_confirmation" required placeholder="••••••••" 
                                   class="w-full pl-10 pr-10 py-3 bg-slate-50 border border-slate-200 text-sm rounded-xl focus:outline-none focus:ring-4 focus:border-blue-500 focus:ring-blue-500/20 transition-all duration-200">
                            <button type="button" onclick="toggleVisibility('password_confirmation', 'eye-icon-2')" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-blue-600 transition-colors">
                                <i class="fas fa-eye" id="eye-icon-2"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div id="password-alert" class="hidden flex items-center gap-2 text-xs text-red-500 mt-1 animate-slide-down">
                    <i class="fas fa-times-circle"></i>
                    <span>Konfirmasi password tidak cocok!</span>
                </div>

                <div class="pt-4">
                    <button type="submit" id="submitBtn" class="w-full flex justify-center items-center gap-2 bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white font-bold py-3.5 px-4 rounded-xl transition-all shadow-lg shadow-blue-600/30 transform hover:-translate-y-0.5 disabled:opacity-70 disabled:cursor-not-allowed disabled:transform-none">
                        <i class="fas fa-user-plus" id="btn-icon"></i> 
                        <span id="btn-text">Buat Akun Sekarang</span>
                    </button>
                </div>
            </form>
            <div class="mt-8 text-center text-sm text-slate-500 border-t border-slate-100 pt-6">
                Sudah memiliki akun? 
                <a href="{{ route('login') }}" class="font-bold text-blue-600 hover:text-blue-700 hover:underline transition-colors">Masuk di sini</a>
            </div>
        </div>  
        <p class="text-center text-xs text-slate-400 mt-6 px-4 leading-relaxed">
            Dengan mendaftar, Anda menyetujui <a href="#" class="underline hover:text-slate-600">Syarat & Ketentuan</a> serta <a href="#" class="underline hover:text-slate-600">Kebijakan Privasi</a> kami.
        </p>
    </div>

    <script>
        function toggleVisibility(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        }

        // Validasi input WhatsApp hanya menerima angka
        document.getElementById('phone').addEventListener('input', function(e) {
            this.value = this.value.replace(/[^0-9]/g, '');
        });

        const password = document.getElementById('password');
        const confirmPassword = document.getElementById('password_confirmation');
        const alertBox = document.getElementById('password-alert');
        const submitBtn = document.getElementById('submitBtn');

        function checkPasswordsMatch() {
            if (confirmPassword.value !== '' && password.value !== confirmPassword.value) {
                alertBox.classList.remove('hidden');
                confirmPassword.classList.replace('border-slate-200', 'border-red-400');
                confirmPassword.classList.replace('focus:border-blue-500', 'focus:border-red-500');
                confirmPassword.classList.replace('focus:ring-blue-500/20', 'focus:ring-red-500/20');
            } else {
                alertBox.classList.add('hidden');
                confirmPassword.classList.replace('border-red-400', 'border-slate-200');
                confirmPassword.classList.replace('focus:border-red-500', 'focus:border-blue-500');
                confirmPassword.classList.replace('focus:ring-red-500/20', 'focus:ring-blue-500/20');
            }
        }

        password.addEventListener('keyup', checkPasswordsMatch);
        confirmPassword.addEventListener('keyup', checkPasswordsMatch);

        document.getElementById('registerForm').addEventListener('submit', function(e) {
            // Validasi client-side sebelum submit
            if (password.value !== confirmPassword.value) {
                e.preventDefault();
                checkPasswordsMatch();
                confirmPassword.focus();
                return false;
            }
            submitBtn.disabled = true;
            document.getElementById('btn-icon').className = 'fas fa-circle-notch fa-spin';
            document.getElementById('btn-text').innerText = 'Memproses Pendaftaran...';
        });
    </script>

</body>
</html>