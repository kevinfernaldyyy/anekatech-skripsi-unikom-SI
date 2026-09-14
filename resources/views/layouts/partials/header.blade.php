<header class="sticky top-0 z-40 flex h-20 w-full items-center justify-between bg-white px-6 border-b border-slate-200 shadow-sm">
    
    <!-- 👇 KIRI: Hamburger Menu & Sapaan (Pengganti Search Bar) 👇 -->
    <div class="flex items-center gap-4">
        <!-- Tombol Toggle Sidebar (Untuk tampilan Mobile/HP) -->
        <button @click="sidebarOpen = !sidebarOpen" class="block lg:hidden text-slate-500 hover:text-blue-600 transition-colors">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>

        <!-- Teks Sapaan (Akan hilang kalau di layar HP biar gak sempit) -->
        <div class="hidden sm:block">
            <h2 class="text-[15px] font-extrabold text-slate-800">
                Selamat bekerja, {{ Auth::user()->name ?? 'Ahmad Djaki' }} 👋
            </h2>
            <p class="text-[11px] text-slate-500 font-medium mt-0.5">
                Pantau pesanan AnekaTech hari ini | <span class="text-blue-600">{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</span>
            </p>
        </div>
    </div>

    <!-- 👇 KANAN: Profil User (Lonceng Dihapus) 👇 -->
    <div class="flex items-center gap-3">
        
        <!-- Dropdown Profil Menggunakan Alpine.js (x-data) -->
        <div class="relative" x-data="{ profileOpen: false }">
            
            <button @click="profileOpen = !profileOpen" class="flex items-center gap-3 rounded-full py-1.5 px-2 transition-all hover:bg-slate-50 border border-transparent hover:border-slate-200">
                <!-- Teks Nama & Role -->
                <div class="text-right hidden md:block">
                    <p class="text-[13px] font-bold text-slate-800 leading-none mb-1.5">{{ Auth::user()->name ?? 'Ahmad Djaki' }}</p>
                    <p class="text-[10px] uppercase tracking-wider text-slate-500 font-bold leading-none">{{ Auth::user()->role ?? 'Admin' }}</p>
                </div>
                
                <!-- Lingkaran Avatar Biru -->
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-blue-600 text-sm font-bold text-white shadow-md shadow-blue-500/20 border-2 border-white">
                    <!-- Ambil 2 huruf depan nama secara dinamis -->
                    {{ strtoupper(substr(Auth::user()->name ?? 'AD', 0, 2)) }}
                </div>
                
                <!-- Panah Kecil (Chevron) -->
                <svg class="h-4 w-4 text-slate-400 transition-transform duration-200" :class="profileOpen ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                </svg>
            </button>

            <!-- Isi Dropdown Menu Profil -->
            <div x-cloak x-show="profileOpen" @click.away="profileOpen = false" 
                 x-transition:enter="transition ease-out duration-100" 
                 x-transition:enter-start="transform opacity-0 scale-95" 
                 x-transition:enter-end="transform opacity-100 scale-100" 
                 x-transition:leave="transition ease-in duration-75" 
                 x-transition:leave-start="transform opacity-100 scale-100" 
                 x-transition:leave-end="transform opacity-0 scale-95" 
                 class="absolute right-0 mt-2 w-48 rounded-xl bg-white shadow-xl border border-slate-100 py-2">
                
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full text-left px-4 py-2 text-sm text-rose-500 hover:bg-rose-50 font-bold transition-colors">
                        Logout
                    </button>
                </form>
            </div>
        </div>
        
    </div>
</header>