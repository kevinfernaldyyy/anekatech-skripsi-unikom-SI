<header class="bg-white border-b border-slate-200 sticky top-0 z-50 shadow-sm">
    <div class="w-full px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-20">
            
            <!-- BAGIAN KIRI: LOGO -->
            <div class="flex items-center gap-6 shrink-0">
                <a href="{{ route('customer.katalog') }}" class="flex items-center gap-2">
                    <img src="{{ asset('img/logo.jpeg') }}" alt="Logo DiabloTech" class="h-9 w-9 rounded-lg object-cover shadow-sm">
                    <div class="text-xl font-black text-slate-900 tracking-tight hidden sm:block">
                        Diablo<span class="text-blue-600">Tech</span>
                    </div>
                </a>
            </div>

            <!-- BAGIAN TENGAH: MENU -->
            <nav class="hidden md:flex items-center space-x-1">
            
                <nav class="hidden md:flex items-center space-x-1">
    
    @guest
        <!-- MENU HOME (CUMA MUNCUL BUAT TAMU / BELUM LOGIN) -->
        <a href="{{ url('/') }}" 
           class="text-sm font-semibold px-4 py-2 rounded-xl transition-all {{ request()->is('/') ? 'bg-blue-100 text-blue-700' : 'text-slate-500 hover:text-slate-900 hover:bg-slate-50' }}">
            Home
        </a>
    @endguest

    @auth
        <!-- MENU KHUSUS YANG SUDAH LOGIN -->
        <a href="{{ route('customer.dashboard', ['tab' => 'aktif']) }}" 
           class="text-sm font-semibold px-4 py-2 rounded-xl transition-all {{ request()->routeIs('customer.dashboard') && request('tab', 'aktif') == 'aktif' ? 'bg-blue-100 text-blue-700' : 'text-slate-500 hover:text-slate-900 hover:bg-slate-50' }}">
            Dashboard
        </a>
        <a href="{{ route('customer.katalog') }}" 
           class="text-sm font-semibold px-4 py-2 rounded-xl transition-all {{ request()->routeIs('customer.katalog') ? 'bg-blue-100 text-blue-700' : 'text-slate-500 hover:text-slate-900 hover:bg-slate-50' }}">
            Katalog Belanja
        </a>
        <a href="{{ route('customer.dashboard', ['tab' => 'riwayat']) }}" 
           class="text-sm font-semibold px-4 py-2 rounded-xl transition-all {{ request()->routeIs('customer.dashboard') && request('tab') == 'riwayat' ? 'bg-blue-100 text-blue-700' : 'text-slate-500 hover:text-slate-900 hover:bg-slate-50' }}">
            Riwayat Selesai
        </a>
    @endauth
</nav>
        

            <!-- BAGIAN KANAN: ACTIONS -->
            <div class="flex items-center gap-2 shrink-0">
                @auth
                    <a href="{{ route('customer.cart.index') }}" class="relative flex items-center gap-2 text-sm font-semibold px-4 py-2 rounded-xl transition-all {{ request()->routeIs('cart.index') ? 'bg-blue-100 text-blue-700' : 'text-slate-500 hover:text-slate-900 hover:bg-slate-50' }}">
                        <i class="fas fa-shopping-cart text-lg"></i>
                        <span>Keranjang</span>
                        
                        <!-- Badge Angka Keranjang -->
                        <span id="nav-cart-badge" class="absolute -top-1.5 -right-1.5 bg-red-500 text-white text-[10px] font-black w-5 h-5 flex items-center justify-center rounded-full shadow-md border border-white {{ (session('cart') && count(session('cart')) > 0) ? '' : 'hidden' }}">
                            {{ session('cart') ? count(session('cart')) : 0 }}
                        </span>
                    </a>

                    <div class="h-6 w-px bg-slate-200 mx-1 hidden sm:block"></div>

                    <!-- Dropdown Profil Alpine.js -->
                    <div x-data="{ dropdownOpen: false }" class="relative">
                        <a @click.prevent="dropdownOpen = !dropdownOpen" href="#" class="flex items-center gap-3 text-slate-600 hover:text-slate-900 hover:bg-slate-50 p-2 rounded-xl transition-all font-semibold text-sm cursor-pointer">
                            <span class="hidden sm:block text-right">
                                <span class="block text-sm font-bold text-slate-800 leading-none">{{ Auth::user()->name ?? 'Customer' }}</span>
                            </span>
                            <div class="w-9 h-9 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold text-sm shadow-inner border border-blue-200">
                                {{ substr(Auth::user()->name ?? 'C', 0, 1) }}
                            </div>
                            <i class="fas fa-chevron-down text-xs text-slate-400 transition-transform duration-200" :class="dropdownOpen ? 'rotate-180' : ''"></i>
                        </a>

                        <div x-show="dropdownOpen" @click.outside="dropdownOpen = false" 
                             x-transition:enter="transition ease-out duration-200" z
                             x-transition:enter-start="opacity-0 scale-95 translate-y-2" 
                             x-transition:enter-end="opacity-100 scale-100 translate-y-0" 
                             x-transition:leave="transition ease-in duration-150" 
                             x-transition:leave-start="opacity-100 scale-100 translate-y-0" 
                             x-transition:leave-end="opacity-0 scale-95 translate-y-2" 
                             class="absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-xl border border-slate-100 overflow-hidden z-50" style="display: none;">
                            
                            <a href="{{ route('customer.profile') }}" class="flex items-center gap-3 px-4 py-3 text-sm text-slate-700 hover:bg-blue-50 hover:text-blue-700 transition-colors font-semibold">
                                <i class="fas fa-user-cog w-4 text-center"></i> Profil Saya
                            </a>
                            <div class="border-t border-slate-100"></div>
                            <form action="{{ route('logout') }}" method="POST" class="m-0">
                                @csrf
                                <button type="submit" onclick="localStorage.removeItem('diablotech_chat_history');" class="w-full flex items-center gap-3 px-4 py-3 text-sm text-red-600 hover:bg-red-50 transition-colors font-bold text-left">
                                    <i class="fas fa-sign-out-alt w-4 text-center"></i> Keluar
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <!-- AKSI KALAU BELUM LOGIN -->
                    <button onclick="openAuthModal()" class="flex items-center gap-2 text-slate-600 hover:text-slate-900 hover:bg-slate-50 px-3 py-2 rounded-xl transition-all font-semibold text-sm cursor-pointer">
                        <i class="fas fa-shopping-cart text-lg"></i>
                        <span class="hidden sm:inline">Keranjang</span>
                    </button>
                    <div class="h-6 w-px bg-slate-200 mx-2 hidden sm:block"></div>
                    <a href="{{ route('login') }}" class="text-sm font-bold text-slate-600 hover:text-blue-600 px-3 py-2 transition-colors">Masuk</a>
                    <a href="{{ route('register') }}" class="text-sm font-bold bg-slate-900 hover:bg-blue-600 text-white px-5 py-2.5 rounded-full transition-all shadow-md">Daftar</a>
                @endauth
            </div>
        </div>
    </div>
    
    <!-- MENU VERSI MOBILE -->
   <div class="md:hidden border-t border-slate-100 bg-white px-4 py-3 flex overflow-x-auto space-x-2 hide-scrollbar">
    
    @guest
        <!-- MENU HOME MOBILE (Cuma Muncul Buat Tamu) -->
        <a href="{{ url('/') }}" class="whitespace-nowrap text-xs font-semibold px-4 py-2 rounded-xl {{ request()->is('/') ? 'bg-blue-100 text-blue-700' : 'bg-slate-50 text-slate-600 border border-slate-200' }}">Home</a>
    @endguest
    
    @auth
        <!-- MENU KHUSUS MOBILE YANG SUDAH LOGIN -->
        <a href="{{ route('customer.dashboard', ['tab' => 'aktif']) }}" class="whitespace-nowrap text-xs font-semibold px-4 py-2 rounded-xl {{ request('tab', 'aktif') == 'aktif' && request()->routeIs('customer.dashboard') ? 'bg-blue-100 text-blue-700' : 'bg-slate-50 text-slate-600 border border-slate-200' }}">Dashboard</a>
        <a href="{{ route('customer.katalog') }}" class="whitespace-nowrap text-xs font-semibold px-4 py-2 rounded-xl {{ request()->routeIs('customer.katalog') ? 'bg-blue-100 text-blue-700' : 'bg-slate-50 text-slate-600 border border-slate-200' }}">Katalog</a>
        <a href="{{ route('customer.dashboard', ['tab' => 'riwayat']) }}" class="whitespace-nowrap text-xs font-semibold px-4 py-2 rounded-xl {{ request('tab') == 'riwayat' && request()->routeIs('customer.dashboard') ? 'bg-blue-100 text-blue-700' : 'bg-slate-50 text-slate-600 border border-slate-200' }}">Riwayat</a>
    @endauth
</div>
</header>