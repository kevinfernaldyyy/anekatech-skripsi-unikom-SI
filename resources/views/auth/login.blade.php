<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Diablo Tech</title>

    <link rel="icon" type="image/jpeg" href="{{ asset('img/logo.jpeg') }}">

    @vite('resources/css/app.css')
    
    <style>
        [x-cloak] { display: none !important; }
        @font-face {
            font-family: 'Plus Jakarta Sans';
            src: url('{{ asset('fonts/PlusJakartaSans-Light.ttf') }}') format('truetype');
            font-weight: 300;
            font-display: swap;
        }
        @font-face {
            font-family: 'Plus Jakarta Sans';
            src: url('{{ asset('fonts/PlusJakartaSans-Regular.ttf') }}') format('truetype');
            font-weight: 400;
            font-display: swap;
        }
        @font-face {
            font-family: 'Plus Jakarta Sans';
            src: url('{{ asset('fonts/PlusJakartaSans-Medium.ttf') }}') format('truetype');
            font-weight: 500;
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
<body class="bg-[#f8fafc] flex items-center justify-center min-h-screen p-6 antialiased text-slate-800">
    <div class="w-full max-w-[440px]">
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

        <!-- Card Login -->
        <div class="bg-white p-8 sm:p-10 rounded-[24px] shadow-sm border border-slate-200/60 relative overflow-hidden">
            <div class="mb-8">
                <h2 class="text-xl font-bold text-slate-900">Selamat Datang</h2>
                <p class="text-slate-500 text-sm mt-1">Silakan masuk untuk mengelola pesanan.</p>
            </div>

            <!-- Session Alert Error -->
            @if(session('error'))
                <div class="mb-5 flex items-center gap-3 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl shadow-sm">
                    <svg class="h-5 w-5 text-red-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                    <p class="font-semibold text-xs leading-relaxed">{{ session('error') }}</p>
                </div>
            @endif

            <!-- Global Error Laravel Validation -->
            @if ($errors->any())
                <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-100">
                    <div class="flex items-start">
                        <svg class="h-5 w-5 text-red-400 mr-2 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                        <ul class="text-xs text-red-700 font-medium list-none space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <form action="{{ route('login') }}" method="POST" id="loginForm">
                @csrf
                <div class="space-y-5">
                    <!-- Field Email -->
                    <div>
                        <label class="block text-[13px] font-semibold text-slate-700 mb-2 ml-1">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}"
                            class="w-full px-4 py-3 bg-slate-50/50 border border-slate-200 rounded-xl focus:outline-none focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all duration-200 text-sm placeholder:text-slate-400" 
                            placeholder="nama@email.com" required autofocus>
                    </div>

                    <!-- Field Password -->
                    <div>
                        <label class="block text-[13px] font-semibold text-slate-700 mb-2 ml-1">Password</label>
                        <div class="relative group">
                            <input type="password" name="password" id="passwordField"
                                class="w-full px-4 py-3 bg-slate-50/50 border border-slate-200 rounded-xl focus:outline-none focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all duration-200 pr-12 text-sm placeholder:text-slate-400" 
                                placeholder="••••••••" required>
                            
                            <button type="button" onclick="togglePassword()" 
                                class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-400 hover:text-indigo-600 transition-colors"
                                tabindex="-1">
                                <svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div class="flex justify-end mt-1">
                        <a href="{{ route('password.request') }}" class="text-xs text-blue-400 hover:text-blue-300">
                            Lupa Password?
                        </a>
                    </div>

                    <button type="submit" id="submitBtn"
                        class="w-full bg-slate-900 text-white font-bold py-3.5 rounded-xl hover:bg-slate-800 active:scale-[0.98] transition-all duration-200 shadow-xl shadow-slate-200 flex items-center justify-center text-sm">
                        <span id="btnText">MASUK KE DASHBOARD</span>
                    </button>
                </div>
            </form>

            <div class="mt-8 text-center text-sm text-slate-500 border-t border-slate-100 pt-6">
                Belum memiliki akun? 
                <a href="{{ route('register') }}" class="font-bold text-indigo-600 hover:text-indigo-700 hover:underline transition-colors">Daftar di sini</a>
            </div>
        </div>

        <p class="text-center text-slate-400 text-[11px] mt-8 uppercase tracking-[0.2em] font-medium">
            &copy; {{ date('Y') }} DIABLO TECH SOLUTIONS • SECURE GATEWAY
        </p>
    </div>

    <script>
        function togglePassword() {
            const passwordField = document.getElementById('passwordField');
            const eyeIcon = document.getElementById('eyeIcon');
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                eyeIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88" />';
            } else {
                passwordField.type = 'password';
                eyeIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />';
            }
        }

        const form = document.getElementById('loginForm');
        const btn = document.getElementById('submitBtn');
        const btnText = document.getElementById('btnText');

        form.addEventListener('submit', function() {
            btnText.innerHTML = '<svg class="animate-spin h-5 w-5 text-white" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';
            btn.classList.add('opacity-80', 'cursor-not-allowed');
        });
    </script>
</body>
</html>