<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password | Diablo Tech</title>
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
<body class="bg-slate-50 font-sans antialiased text-slate-800 flex items-center justify-center min-h-screen relative overflow-x-hidden p-6">

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
            <p class="text-slate-500 text-sm mt-1">Pemulihan Kata Sandi Akun</p>
        </div>

        <!-- Notifikasi Sukses (Status Kirim Email) -->
        @if (session('status'))
            <div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 flex items-start gap-3 shadow-sm">
                <i class="fas fa-check-circle text-emerald-500 mt-0.5 text-base flex-shrink-0"></i>
                <p class="text-xs font-semibold leading-relaxed">{{ session('status') }}</p>
            </div>
        @endif

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

        <!-- Card Form Lupa Password -->
        <div class="bg-white p-8 sm:p-10 rounded-[24px] shadow-xl border border-slate-100 relative overflow-hidden">
            <!-- Garis Dekorasi Card -->
            <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-blue-600 via-indigo-600 to-emerald-500"></div>

            <div class="mb-7 text-center sm:text-left">
                <h2 class="text-xl font-bold text-slate-900">Lupa Password? 🔒</h2>
                <p class="text-slate-500 text-xs sm:text-sm mt-1 leading-relaxed">
                    Masukkan email terdaftar kamu dan kami akan mengirimkan tautan untuk mengatur ulang kata sandi.
                </p>
            </div>

            <form action="{{ route('password.email') }}" method="POST" id="forgotForm" class="space-y-5" novalidate>
                @csrf

                <!-- Input Email -->
                <div>
                    <label for="email" class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2 ml-1">
                        Alamat Email <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <i class="fas fa-envelope text-slate-400"></i>
                        </div>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus
                            placeholder="nama@email.com"
                            class="w-full pl-10 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all duration-200 text-sm placeholder:text-slate-400">
                    </div>
                </div>

                <!-- Tombol Submit -->
                <button type="submit" id="submitBtn"
                    class="w-full bg-slate-900 text-white font-bold py-3.5 px-4 rounded-xl hover:bg-slate-800 active:scale-[0.98] transition-all duration-200 shadow-xl shadow-slate-200 flex items-center justify-center gap-2 text-sm">
                    <i class="fas fa-paper-plane" id="btnIcon"></i>
                    <span id="btnText">KIRIM LINK RESET PASSWORD</span>
                </button>
            </form>

            <!-- Kembali ke Login -->
            <div class="mt-8 text-center text-sm text-slate-500 border-t border-slate-100 pt-6">
                Ingin kembali masuk? 
                <a href="{{ route('login') }}" class="font-bold text-indigo-600 hover:text-indigo-700 hover:underline transition-colors">
                    Login di sini
                </a>
            </div>
        </div>

        <p class="text-center text-slate-400 text-[11px] mt-8 uppercase tracking-[0.2em] font-medium">
            &copy; {{ date('Y') }} DIABLO TECH SOLUTIONS • SECURE GATEWAY
        </p>
    </div>

    <script>
        document.getElementById('forgotForm').addEventListener('submit', function() {
            const btn = document.getElementById('submitBtn');
            const btnIcon = document.getElementById('btnIcon');
            const btnText = document.getElementById('btnText');

            btn.disabled = true;
            btn.classList.add('opacity-80', 'cursor-not-allowed');
            btnIcon.className = 'fas fa-circle-notch fa-spin';
            btnText.innerText = 'MENGIRIM TAUTAN...';
        });
    </script>
</body>
</html>