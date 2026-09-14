<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password | Diablo Tech</title>
    <link rel="icon" type="image/jpeg" href="{{ asset('img/logo.jpeg') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <style>
        [x-cloak] { display: none !important; }
        @font-face {
            font-family: 'Plus Jakarta Sans';
            src: url('{{ asset('fonts/PlusJakartaSans-Regular.ttf') }}') format('truetype');
            font-weight: 400;
            font-display: swap;
        }
        @font-face {
            font-family: 'Plus Jakarta Sans';
            src: url('{{ asset('fonts/PlusJakartaSans-SemiBold.ttf') }}') format('truetype');
            font-weight: 600;
            font-display: swap;
        }
        @font-face {
            font-family: 'Plus Jakarta Sans';
            src: url('{{ asset('fonts/PlusJakartaSans-Bold.ttf') }}') format('truetype');
            font-weight: 700;
            font-display: swap;
        }
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 font-sans antialiased text-slate-800 flex items-center justify-center min-h-screen relative overflow-x-hidden p-6 py-10">

    <!-- Dekorasi Background -->
    <div class="fixed top-[-10%] left-[-10%] w-[500px] h-[500px] bg-blue-400/20 rounded-full mix-blend-multiply filter blur-[100px] pointer-events-none"></div>
    <div class="fixed bottom-[-10%] right-[-10%] w-[500px] h-[500px] bg-emerald-400/20 rounded-full mix-blend-multiply filter blur-[100px] pointer-events-none"></div>

    <div class="w-full max-w-[440px] relative z-10">
        <!-- Logo & Title -->
        <div class="flex flex-col items-center mb-8">
            <a href="{{ url('/') }}" class="flex flex-col items-center group">
                <div class="bg-white p-3 rounded-2xl shadow-sm border border-slate-100 mb-4 transition-transform group-hover:scale-105">
                    <img src="{{ asset('img/logo.jpeg') }}" alt="Diablo Tech" class="h-16 w-16 object-cover rounded-lg">
                </div>
                <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Diablo Tech</h1>
            </a>
            <p class="text-slate-500 text-sm mt-1">Sistem Manajemen Rakit PC</p>
        </div>

        <!-- Global Error Alert -->
        @if ($errors->any())
            <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200 text-red-700 flex items-start gap-3 shadow-sm">
                <i class="fas fa-exclamation-circle text-red-500 mt-0.5 text-base flex-shrink-0"></i>
                <ul class="text-xs font-semibold list-none space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Card Form Reset Password -->
        <div class="bg-white p-8 sm:p-10 rounded-[24px] shadow-xl border border-slate-100 relative overflow-hidden">
            <!-- Garis Dekorasi Card -->
            <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-blue-600 via-indigo-600 to-emerald-500"></div>

            <div class="mb-7">
                <h2 class="text-xl font-bold text-slate-900">Buat Password Baru 🔑</h2>
                <p class="text-slate-500 text-xs sm:text-sm mt-1">
                    Silakan atur kata sandi baru untuk mengamankan akunmu.
                </p>
            </div>

            <form action="{{ route('password.update') }}" method="POST" id="resetForm" class="space-y-5" novalidate>
                @csrf

                <!-- Token Tersembunyi -->
                <input type="hidden" name="token" value="{{ $token }}">

                <!-- Input Email (Readonly) -->
                <div>
                    <label for="email" class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2 ml-1">
                        Alamat Email
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <i class="fas fa-envelope text-slate-400"></i>
                        </div>
                        <input type="email" id="email" name="email" value="{{ $email ?? old('email') }}" required readonly
                            class="w-full pl-10 pr-4 py-3 bg-slate-100 border border-slate-200 rounded-xl text-slate-500 text-sm cursor-not-allowed">
                    </div>
                </div>

                <!-- Input Password Baru -->
                <div>
                    <label for="password" class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2 ml-1">
                        Password Baru <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <i class="fas fa-lock text-slate-400"></i>
                        </div>
                        <input type="password" id="password" name="password" required autofocus
                            placeholder="Minimal 8 karakter" minlength="8"
                            class="w-full pl-10 pr-10 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all duration-200 text-sm placeholder:text-slate-400">
                        
                        <button type="button" onclick="toggleVisibility('password', 'eye-icon-1')"
                            class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-blue-600 transition-colors" tabindex="-1">
                            <i class="fas fa-eye" id="eye-icon-1"></i>
                        </button>
                    </div>
                </div>

                <!-- Konfirmasi Password Baru -->
                <div>
                    <label for="password_confirmation" class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2 ml-1">
                        Ulangi Password Baru <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <i class="fas fa-check-double text-slate-400"></i>
                        </div>
                        <input type="password" id="password_confirmation" name="password_confirmation" required
                            placeholder="Ketik ulang password baru"
                            class="w-full pl-10 pr-10 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all duration-200 text-sm placeholder:text-slate-400">
                        
                        <button type="button" onclick="toggleVisibility('password_confirmation', 'eye-icon-2')"
                            class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-blue-600 transition-colors" tabindex="-1">
                            <i class="fas fa-eye" id="eye-icon-2"></i>
                        </button>
                    </div>
                </div>

                <!-- Alert Konfirmasi Password Tidak Cocok -->
                <div id="password-alert" class="hidden flex items-center gap-2 text-xs text-red-500 mt-1">
                    <i class="fas fa-times-circle"></i>
                    <span>Konfirmasi password tidak cocok!</span>
                </div>

                <!-- Tombol Simpan -->
                <button type="submit" id="submitBtn"
                    class="w-full bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white font-bold py-3.5 px-4 rounded-xl transition-all duration-200 shadow-lg shadow-blue-600/30 flex items-center justify-center gap-2 text-sm">
                    <i class="fas fa-save" id="btnIcon"></i>
                    <span id="btnText">SIMPAN PASSWORD BARU</span>
                </button>
            </form>
        </div>

        <p class="text-center text-slate-400 text-[11px] mt-8 uppercase tracking-[0.2em] font-medium">
            &copy; {{ date('Y') }} DIABLO TECH SOLUTIONS • SECURE GATEWAY
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

        const password = document.getElementById('password');
        const confirmPassword = document.getElementById('password_confirmation');
        const alertBox = document.getElementById('password-alert');

        function checkPasswordsMatch() {
            if (confirmPassword.value !== '' && password.value !== confirmPassword.value) {
                alertBox.classList.remove('hidden');
                confirmPassword.classList.replace('border-slate-200', 'border-red-400');
            } else {
                alertBox.classList.add('hidden');
                confirmPassword.classList.replace('border-red-400', 'border-slate-200');
            }
        }

        password.addEventListener('keyup', checkPasswordsMatch);
        confirmPassword.addEventListener('keyup', checkPasswordsMatch);

        document.getElementById('resetForm').addEventListener('submit', function(e) {
            if (password.value !== confirmPassword.value) {
                e.preventDefault();
                checkPasswordsMatch();
                confirmPassword.focus();
                return false;
            }
            
            const btn = document.getElementById('submitBtn');
            const btnIcon = document.getElementById('btnIcon');
            const btnText = document.getElementById('btnText');

            btn.disabled = true;
            btn.classList.add('opacity-80', 'cursor-not-allowed');
            btnIcon.className = 'fas fa-circle-notch fa-spin';
            btnText.innerText = 'MEMPROSES PASSWORD...';
        });
    </script>
</body>
</html>