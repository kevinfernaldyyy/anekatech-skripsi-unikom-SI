<aside class="w-64 bg-slate-900 min-h-screen text-white flex flex-col shadow-xl">
    <div class="h-16 flex items-center justify-center border-b border-slate-800">
        <h2 class="text-xl font-black tracking-widest flex items-center gap-2">
            <span class="text-blue-500">DIABLO</span>TECH
        </h2>
    </div>

    <div class="p-4 border-b border-slate-800 flex items-center gap-3">
        <div class="w-10 h-10 rounded-full bg-blue-600 flex items-center justify-center font-bold text-lg">
            🧑‍🔧
        </div>
        <div>
            <p class="text-sm font-bold">{{ Auth::user()->name ?? 'Teknisi' }}</p>
            <p class="text-[10px] text-slate-400 uppercase tracking-wider">Divisi Perakitan</p>
        </div>
    </div>

    <nav class="flex-1 px-4 py-6 space-y-2">
        <p class="text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-2 px-2">Menu Utama</p>

        <a href="{{ route('teknisi.dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 {{ request()->routeIs('teknisi.dashboard') ? 'bg-blue-600 text-white shadow-md' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>
            <span class="text-sm font-medium">Dashboard</span>
        </a>
        <a href="{{ route('teknisi.orders.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 {{ request()->routeIs('teknisi.orders.*') ? 'bg-blue-600 text-white shadow-md' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
            <span class="text-sm font-medium">Tugas Rakitan</span>
        </a>
        <a href="{{ route('teknisi.history') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 {{ request()->routeIs('teknisi.history') ? 'bg-blue-600 text-white shadow-md' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            <span class="text-sm font-medium">Riwayat Tugas</span>
        </a>
    </nav>

    <div class="p-4 border-t border-slate-800">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="w-full flex items-center justify-center gap-2 px-4 py-2 bg-red-500/10 text-red-500 hover:bg-red-500 hover:text-white rounded-lg transition-colors text-sm font-bold">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                Keluar
            </button>
        </form>
    </div>
</aside>