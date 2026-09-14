<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Portal Pelanggan | DiabloTech')</title>
    <link rel="icon" type="image/jpeg" href="{{ asset('img/logo.jpeg') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
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
<body class="bg-[#f8fafc] text-slate-800 antialiased">

    <!-- Konsep Atas Bawah (Bukan Kiri Kanan lagi) -->
    <div class="min-h-screen flex flex-col">
        
        <!-- Header / Top Navbar -->
        @include('layouts.partials.customer.header')

        <!-- Main Content (Di tengah) -->
        <main class="flex-1 w-full max-w-7xl mx-auto px-4 sm:px-8 py-8">
            @yield('content')
        </main>

    </div>


<!-- Tombol Floating Chat -->
<button onclick="document.getElementById('chat-modal').classList.toggle('hidden')" class="fixed bottom-6 right-6 bg-blue-600 text-white p-4 rounded-full shadow-2xl hover:bg-blue-700 transition transform hover:scale-110 z-50 flex items-center justify-center w-14 h-14">
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-7 h-7">
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 20.25c4.97 0 9-3.694 9-8.25s-4.03-8.25-9-8.25S3 7.444 3 11.996c0 2.29.98 4.36 2.58 5.895l-1.39 2.78a.75.75 0 00.992.992l2.78-1.39a8.91 8.91 0 004.038.977z" />
    </svg>
</button>

<!-- Jendela Chatbot -->
<div id="chat-modal" class="hidden fixed bottom-24 right-6 w-80 sm:w-96 bg-white rounded-2xl shadow-2xl border border-gray-200 z-50 overflow-hidden">
    <div class="bg-slate-900 text-white px-4 py-3 font-bold flex justify-between items-center">
        <span>Anekatech AI</span>
        <button onclick="document.getElementById('chat-modal').classList.add('hidden')" class="text-gray-400 hover:text-white">&times;</button>
    </div>
    
    <!-- Kotak Pesan -->
    <div id="chat-box" class="h-80 overflow-y-auto p-4 bg-slate-50 text-sm space-y-3">
        <div class="bg-white p-3 rounded-lg border border-gray-200 inline-block">
            Halo! Ada budget berapa nih? Sini aku bantu rakit PC impianmu!
        </div>
    </div>

    <!-- Form Input -->
    <div class="p-3 bg-white border-t flex gap-2">
        <input type="text" id="chat-input" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-blue-500" placeholder="Ketik budget kamu...">
        <button id="send-chat" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-bold">Kirim</button>
    </div>
</div>

<script>
    const chatBox = document.getElementById('chat-box');
    const STORAGE_KEY = 'diablotech_chat_history';

    // 1. FITUR AUTO-LOAD: Memuat riwayat chat saat halaman di-refresh
    document.addEventListener('DOMContentLoaded', function() {
        let savedChat = localStorage.getItem(STORAGE_KEY);
        if (savedChat) {
            // Jika ada riwayat, timpa isi kotak chat dengan riwayat lama
            chatBox.innerHTML = savedChat;
            chatBox.scrollTop = chatBox.scrollHeight;
        }
    });

    // Fungsi kecil untuk menyimpan chat ke memori browser
    function saveChatHistory() {
        localStorage.setItem(STORAGE_KEY, chatBox.innerHTML);
    }

    // 2. FITUR KIRIM PAKAI TOMBOL ENTER
    document.getElementById('chat-input').addEventListener('keypress', function (e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            document.getElementById('send-chat').click();
        }
    });

    // 3. LOGIKA UTAMA CHATBOT
    document.getElementById('send-chat').addEventListener('click', async function() {
        let input = document.getElementById('chat-input');
        let pesan = input.value;
        if(pesan.trim() === '') return;

        // Tambah chat user ke layar (kasih mb-2 biar ada jarak)
        chatBox.innerHTML += `<div class="text-right mb-2"><div class="bg-blue-600 text-white p-3 rounded-lg inline-block text-left">${pesan}</div></div>`;
        input.value = '';
        chatBox.scrollTop = chatBox.scrollHeight;
        
        // Simpan ke memori tiap kali Kustomer ngetik
        saveChatHistory(); 

        // Tampilkan loading
        let loadingId = 'loading-' + Date.now();
        chatBox.innerHTML += `<div id="${loadingId}" class="text-left mb-2"><div class="bg-slate-200 p-3 rounded-lg border border-gray-300 inline-block text-gray-500 italic">AI sedang meracik...</div></div>`;
        chatBox.scrollTop = chatBox.scrollHeight;

        try {
            // SESUAIKAN ROUTE INI JIKA TADI PAKAI PREFIX CUSTOMER
            let response = await fetch("{{ route('customer.chatbot.ask') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({ pesan: pesan })
            });

            let data = await response.json();
            document.getElementById(loadingId).remove();
            
            // Tambah balasan AI ke layar
            chatBox.innerHTML += `<div class="text-left mb-2"><div class="bg-white p-3 rounded-lg border border-gray-200 inline-block text-gray-800">${data.reply}</div></div>`;
            chatBox.scrollTop = chatBox.scrollHeight;
            
            // Simpan ke memori tiap kali AI membalas
            saveChatHistory();
            
        } catch (error) {
            document.getElementById(loadingId).innerText = 'Terjadi kesalahan server.';
        }
    });
</script>
</body>
</html>