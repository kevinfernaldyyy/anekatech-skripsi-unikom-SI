<aside class="w-64 bg-slate-900 text-white hidden md:flex flex-col flex-shrink-0 transition-all duration-300">
    
    <div class="h-20 flex items-center px-6 border-b border-slate-800 gap-3">
        <img src="{{ asset('img/logo.jpeg') }}" alt="Logo DiabloTech" class="h-10 w-10 rounded-lg object-cover shadow-sm">
        <div class="text-xl font-black text-white tracking-wider">
            Diablo<span class="text-indigo-500">Tech</span>
        </div>
    </div>

    <div class="px-6 py-6 border-b border-slate-800">
        <p class="text-xs text-slate-400 font-bold uppercase tracking-wider mb-1">Selamat Datang,</p>
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-full bg-indigo-500 flex items-center justify-center font-bold text-lg shadow-lg">
                {{ substr(Auth::user()->name, 0, 1) }}
            </div>
            <div>
                <p class="font-bold text-sm truncate w-32">{{ Auth::user()->name }}</p>
                <p class="text-xs text-slate-400 truncate w-32">{{ Auth::user()->email }}</p>
            </div>
        </div>
    </div>

<nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
    <p class="px-4 text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Menu</p>

    <!-- Menu 1: Pesanan Aktif -->
    <a href="{{ route('customer.dashboard', ['tab' => 'aktif']) }}" 
       class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 {{ request('tab', 'aktif') == 'aktif' && request()->routeIs('customer.dashboard') ? 'bg-blue-600 text-white font-bold shadow-md shadow-blue-500/20' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <!-- Icon Shopping Bag -->
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
        </svg>
        <span>Pesanan Aktif</span>
    </a>

    <!-- Menu 2: Katalog Belanja -->
    <a href="{{ route('customer.katalog') }}" 
       class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('customer.katalog') ? 'bg-blue-600 text-white font-bold shadow-md shadow-blue-500/20' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <!-- Icon Shopping Cart -->
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
        </svg>
        <span>Katalog Belanja</span>
    </a>

    <!-- Menu 3: Riwayat Selesai -->
    <a href="{{ route('customer.dashboard', ['tab' => 'riwayat']) }}" 
       class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 {{ request('tab') == 'riwayat' && request()->routeIs('customer.dashboard') ? 'bg-blue-600 text-white font-bold shadow-md shadow-blue-500/20' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <!-- Icon Document Check -->
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
        </svg>
        <span>Riwayat Selesai</span>
    </a>
</nav>

    <div class="p-4 border-t border-slate-800">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="w-full flex items-center justify-center gap-2 bg-slate-800 hover:bg-red-500 text-slate-300 hover:text-white px-4 py-3 rounded-xl font-bold transition-all duration-200">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                Keluar
            </button>
        </form>
    </div>
</aside>