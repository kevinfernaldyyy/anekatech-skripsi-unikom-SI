<header class="sticky top-0 z-30 flex w-full bg-white drop-shadow-sm">
    <div class="flex flex-grow items-center justify-between px-4 py-4 shadow-2 md:px-6 2xl:px-11">
        
        <div class="flex items-center gap-2 sm:gap-4 lg:hidden">
            <button @click="sidebarOpen = !sidebarOpen" class="z-50 block rounded-sm border border-stroke bg-white p-1.5 shadow-sm">
                <span class="relative block h-5.5 w-5.5 cursor-pointer">
                    <span class="du-block absolute right-0 h-full w-full">
                        <span class="relative top-0 my-1 block h-0.5 w-0 rounded-sm bg-black delay-[0] duration-200 ease-in-out" :class="!sidebarOpen && '!w-full delay-300'"></span>
                        <span class="relative top-0 my-1 block h-0.5 w-0 rounded-sm bg-black delay-150 duration-200 ease-in-out" :class="!sidebarOpen && 'delay-400 !w-full'"></span>
                        <span class="relative top-0 my-1 block h-0.5 w-0 rounded-sm bg-black delay-200 duration-200 ease-in-out" :class="!sidebarOpen && '!w-full delay-500'"></span>
                    </span>
                </span>
            </button>
        </div>

        <div class="hidden sm:block">
            <form action="#" method="POST">
                <div class="relative">
                    <button class="absolute left-0 top-1/2 -translate-y-1/2">
                        <svg class="fill-body hover:fill-primary h-5 w-5 text-gray-500" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" /></svg>
                    </button>
                    <input type="text" placeholder="Cari sesuatu..." class="w-full bg-transparent pl-9 pr-4 text-sm focus:outline-none xl:w-125" />
                </div>
            </form>
        </div>

        <div class="flex items-center gap-3 2xsm:gap-7">
            <div x-data="{ dropdownOpen: false }" class="relative">
                <a @click.prevent="dropdownOpen = !dropdownOpen" class="flex items-center gap-4 cursor-pointer" href="#">
                    <span class="hidden text-right lg:block">
                        <span class="block text-sm font-medium text-black">{{ Auth::user()->name }}</span>
                        <span class="block text-xs text-gray-500">{{ ucfirst(Auth::user()->role) }}</span>
                    </span>
                    <span class="h-10 w-10 rounded-full bg-slate-200 overflow-hidden">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=random" alt="User">
                    </span>
                    <svg :class="dropdownOpen && 'rotate-180'" class="hidden fill-current sm:block h-4 w-4 transition-transform" viewBox="0 0 20 20"><path d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"/></svg>
                </a>

                <div x-show="dropdownOpen" @click.outside="dropdownOpen = false" class="absolute right-0 mt-4 flex w-62.5 flex-col rounded-sm border border-stroke bg-white shadow-default shadow-lg z-50 min-w-[200px]" style="display: none;">
                    <ul class="flex flex-col gap-5 border-b border-stroke px-6 py-4">
                        <li><a href="#" class="flex items-center gap-3.5 text-sm font-medium duration-300 ease-in-out hover:text-blue-600 lg:text-base">Profile Saya</a></li>
                        <li><a href="#" class="flex items-center gap-3.5 text-sm font-medium duration-300 ease-in-out hover:text-blue-600 lg:text-base">Pengaturan</a></li>
                    </ul>
                    <div class="p-4">
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="flex items-center gap-3.5 px-2 text-sm font-medium duration-300 ease-in-out hover:text-red-600 lg:text-base text-red-500 w-full text-left">
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>