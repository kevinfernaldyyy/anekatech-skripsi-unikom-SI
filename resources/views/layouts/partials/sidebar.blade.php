<aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" 
       class="absolute left-0 top-0 z-50 flex h-screen w-[280px] flex-col bg-white border-r border-slate-200 text-slate-600 transition-transform duration-300 ease-in-out lg:static lg:translate-x-0 shadow-xl">
    
    <!-- 👇 1. HEADER LOGO (LIGHT MODE) 👇 -->
    <div class="flex h-20 shrink-0 items-center gap-3 px-6 border-b border-slate-100 bg-white">
        <div class="h-10 w-10 flex shrink-0 items-center justify-center rounded-xl bg-white border border-slate-200 shadow-sm overflow-hidden p-0.5">
            <img src="{{ asset('img/logo.jpeg') }}" alt="DB" class="h-full w-full object-cover rounded-lg">
        </div>
        
        <div class="flex flex-col justify-center">
            <span class="text-lg font-extrabold tracking-wide text-slate-800">DIABLO<span class="text-blue-600">TECH</span></span>
            <!-- Nama Perusahaan Skripsi Here! -->
            <span class="text-[10px] uppercase tracking-[0.15em] text-slate-500 font-bold">AnekaTech</span>
        </div>
    </div>

    <!-- 👇 2. MENU NAVIGASI 👇 -->
    <div class="flex-1 overflow-y-auto py-6 scrollbar-hide space-y-6">
        
        <!-- KELOMPOK: OPERASIONAL -->
        <div>
            <p class="px-6 text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-3">Operasional</p>
            <div class="space-y-1.5 px-3">
                
                <a href="{{ route('admin.dashboard') }}" 
                   class="group relative flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm transition-all duration-200 {{ request()->routeIs('admin.dashboard') ? 'bg-blue-50 text-blue-600 font-bold' : 'font-medium text-slate-500 hover:bg-slate-50 hover:text-blue-600' }}">
                    <svg class="h-5 w-5 shrink-0 transition-colors {{ request()->routeIs('admin.dashboard') ? 'text-blue-600' : 'text-slate-400 group-hover:text-blue-500' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                    </svg>
                    Dashboard
                </a>

                <a href="{{ route('admin.transaksi.index') }}" 
                   class="group relative flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm transition-all duration-200 {{ request()->routeIs('transaksi.*') ? 'bg-blue-50 text-blue-600 font-bold' : 'font-medium text-slate-500 hover:bg-slate-50 hover:text-blue-600' }}">
                    <svg class="h-5 w-5 shrink-0 transition-colors {{ request()->routeIs('transaksi.*') ? 'text-blue-600' : 'text-slate-400 group-hover:text-blue-500' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    Pemesanan
                </a>

                <!-- DROPDOWN TRANSAKSI -->
                <div class="relative" id="dropdown-transaksi-wrapper">
                    <button type="button" onclick="toggleDropdownTransaksi()" 
                       class="w-full group relative flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm transition-all duration-200 {{ request()->routeIs('orders.*') ? 'bg-blue-50 text-blue-600 font-bold' : 'font-medium text-slate-500 hover:bg-slate-50 hover:text-blue-600' }}">
                        <svg class="h-5 w-5 shrink-0 transition-colors {{ request()->routeIs('orders.*') ? 'text-blue-600' : 'text-slate-400 group-hover:text-blue-500' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Transaksi 
                        <span class="ml-2 rounded-md bg-blue-100 px-2 py-0.5 text-[9px] font-bold text-blue-600">ADMIN</span>
                        <svg id="chevron-transaksi" class="ml-auto h-4 w-4 shrink-0 transition-transform duration-300 {{ request()->routeIs('orders.*') ? 'rotate-180' : '' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    @php $currentJenis = request()->query('jenis', 'reguler'); @endphp
                    
                    <div id="submenu-transaksi" class="mt-1 overflow-hidden transition-all duration-300 {{ request()->routeIs('orders.*') ? 'block' : 'hidden' }}">
                        <div class="ml-5 border-l border-slate-200 pl-3 py-1 space-y-1">
                            
                            <a href="{{ route('admin.orders.index', ['jenis' => 'belum_bayar']) }}" 
                               class="group flex items-center gap-3 rounded-lg px-3 py-2 text-[13px] transition-colors {{ $currentJenis == 'belum_bayar' ? 'bg-slate-100 text-slate-800 font-bold' : 'font-medium text-slate-500 hover:bg-slate-50 hover:text-slate-700' }}">
                                <div class="h-1.5 w-1.5 rounded-full {{ $currentJenis == 'belum_bayar' ? 'bg-blue-500' : 'bg-slate-300 group-hover:bg-blue-400' }}"></div>
                                Belum Bayar
                            </a>
                            
                            <a href="{{ route('admin.orders.index', ['jenis' => 'po']) }}" 
                               class="group flex items-center gap-3 rounded-lg px-3 py-2 text-[13px] transition-colors {{ $currentJenis == 'po' ? 'bg-slate-100 text-slate-800 font-bold' : 'font-medium text-slate-500 hover:bg-slate-50 hover:text-slate-700' }}">
                                <div class="h-1.5 w-1.5 rounded-full {{ $currentJenis == 'po' ? 'bg-amber-500' : 'bg-slate-300 group-hover:bg-amber-400' }}"></div>
                                Pre-Order (PO)
                            </a>

                            <a href="{{ route('admin.orders.index', ['jenis' => 'reguler']) }}" 
                               class="group flex items-center gap-3 rounded-lg px-3 py-2 text-[13px] transition-colors {{ ($currentJenis == 'reguler' && request()->routeIs('orders.index')) ? 'bg-slate-100 text-slate-800 font-bold' : 'font-medium text-slate-500 hover:bg-slate-50 hover:text-slate-700' }}">
                                <div class="h-1.5 w-1.5 rounded-full {{ ($currentJenis == 'reguler' && request()->routeIs('orders.index')) ? 'bg-blue-500' : 'bg-slate-300 group-hover:bg-blue-400' }}"></div>
                                Diproses
                            </a>

                            <a href="{{ route('admin.orders.index', ['jenis' => 'ready']) }}" 
                               class="group flex items-center gap-3 rounded-lg px-3 py-2 text-[13px] transition-colors {{ $currentJenis == 'ready' ? 'bg-slate-100 text-slate-800 font-bold' : 'font-medium text-slate-500 hover:bg-slate-50 hover:text-slate-700' }}">
                                <div class="h-1.5 w-1.5 rounded-full {{ $currentJenis == 'ready' ? 'bg-emerald-500' : 'bg-slate-300 group-hover:bg-emerald-400' }}"></div>
                                Ready
                            </a>

                            <a href="{{ route('admin.orders.index', ['jenis' => 'retur']) }}" 
                               class="group flex items-center gap-3 rounded-lg px-3 py-2 text-[13px] transition-colors {{ $currentJenis == 'retur' ? 'bg-slate-100 text-slate-800 font-bold' : 'font-medium text-slate-500 hover:bg-slate-50 hover:text-slate-700' }}">
                                <div class="h-1.5 w-1.5 rounded-full {{ $currentJenis == 'retur' ? 'bg-rose-500' : 'bg-slate-300 group-hover:bg-rose-400' }}"></div>
                                Return
                            </a>

                            <a href="{{ route('admin.orders.index', ['jenis' => 'riwayat']) }}" 
                               class="group flex items-center gap-3 rounded-lg px-3 py-2 text-[13px] transition-colors {{ $currentJenis == 'riwayat' ? 'bg-slate-100 text-slate-800 font-bold' : 'font-medium text-slate-500 hover:bg-slate-50 hover:text-slate-700' }}">
                                <div class="h-1.5 w-1.5 rounded-full {{ $currentJenis == 'riwayat' ? 'bg-purple-500' : 'bg-slate-300 group-hover:bg-purple-400' }}"></div>
                                Riwayat
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- KELOMPOK: MANAJEMEN DATA -->
        <div>
            <p class="px-6 text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-3">Manajemen Data</p>
            <div class="space-y-1.5 px-3">
                <a href="{{ route('admin.customers.index') }}" 
                   class="group relative flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm transition-all duration-200 {{ request()->routeIs('admin.customers.*') ? 'bg-blue-50 text-blue-600 font-bold' : 'font-medium text-slate-500 hover:bg-slate-50 hover:text-blue-600' }}">
                    <svg class="h-5 w-5 shrink-0 transition-colors {{ request()->routeIs('admin.customers.*') ? 'text-blue-600' : 'text-slate-400 group-hover:text-blue-500' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    Data Pelanggan
                </a>

                <a href="{{ route('admin.barang.index') }}" 
                   class="group relative flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm transition-all duration-200 {{ request()->routeIs('admin.barang.*') || request()->routeIs('admin.bundle.*') ? 'bg-blue-50 text-blue-600 font-bold' : 'font-medium text-slate-500 hover:bg-slate-50 hover:text-blue-600' }}">
                    <svg class="h-5 w-5 shrink-0 transition-colors {{ request()->routeIs('admin.barang.*') || request()->routeIs('admin.bundle.*') ? 'text-blue-600' : 'text-slate-400 group-hover:text-blue-500' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                    Master Barang
                </a>

                <a href="{{ route('admin.users.index') }}" 
                   class="group relative flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm transition-all duration-200 {{ request()->routeIs('admin.users.*') ? 'bg-blue-50 text-blue-600 font-bold' : 'font-medium text-slate-500 hover:bg-slate-50 hover:text-blue-600' }}">
                    <svg class="h-5 w-5 shrink-0 transition-colors {{ request()->routeIs('admin.users.*') ? 'text-blue-600' : 'text-slate-400 group-hover:text-blue-500' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.956 11.956 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                    Data Pengguna
                </a>
            </div>
        </div>

        <!-- KELOMPOK: Laporan -->
        <div>
            <p class="px-6 text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-3">Laporan</p>
             <div class="space-y-1.5 px-3">
                <a href="{{ route('admin.laporan.penjualan') }}" 
                   class="group relative flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm transition-all duration-200 {{ request()->routeIs('admin.laporan.penjualan') ? 'bg-blue-50 text-blue-600 font-bold' : 'font-medium text-slate-500 hover:bg-slate-50 hover:text-blue-600' }}">
                    <svg class="h-5 w-5 shrink-0 transition-colors {{ request()->routeIs('admin.laporan.penjualan') ? 'text-blue-600' : 'text-slate-400 group-hover:text-blue-500' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
    <path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
</svg>
                    Laporan Penjualan
                </a>
            </div>

            <div class="space-y-1.5 px-3">
                <a href="{{ route('admin.logs.index') }}" 
                   class="group relative flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm transition-all duration-200 {{ request()->routeIs('admin.logs.*') ? 'bg-blue-50 text-blue-600 font-bold' : 'font-medium text-slate-500 hover:bg-slate-50 hover:text-blue-600' }}">
                    <svg class="h-5 w-5 shrink-0 transition-colors {{ request()->routeIs('admin.logs.*') ? 'text-blue-600' : 'text-slate-400 group-hover:text-blue-500' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Log Aktivitas
                </a>
            </div>
        </div>
    </div>

    <!-- 👇 3. BOTTOM PROFILE AREA 👇 -->
    <div class="border-t border-slate-100 p-4 bg-white">
        <div class="flex items-center gap-3 rounded-xl bg-slate-50 p-2.5 border border-slate-200 transition-colors hover:bg-slate-100">
            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-gradient-to-br from-blue-500 to-blue-600 text-sm font-bold text-white shadow-sm">
                A
            </div>
            <div class="flex flex-col overflow-hidden">
                <span class="truncate text-sm font-bold text-slate-700">Administrator</span>
                <span class="truncate text-[10px] font-medium text-emerald-500">Online</span>
            </div>
        </div>
    </div>
</aside>

<script>
    function toggleDropdownTransaksi() {
        const submenu = document.getElementById('submenu-transaksi');
        const chevron = document.getElementById('chevron-transaksi');
        
        if (submenu.classList.contains('hidden')) {
            submenu.classList.remove('hidden');
            submenu.classList.add('block');
            chevron.classList.add('rotate-180');
        } else {
            submenu.classList.add('hidden');
            submenu.classList.remove('block');
            chevron.classList.remove('rotate-180');
        }
    }
</script>