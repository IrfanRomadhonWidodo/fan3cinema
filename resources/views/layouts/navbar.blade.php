{{-- layouts/navbar.blade.php --}}
<nav class="bg-gray-900 border-b border-purple-700/40 shadow-lg">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            {{-- Logo --}}
            <div class="flex items-center space-x-2">
            <svg class="h-8 w-8 text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
            </svg>
                <span class="ml-2 text-2xl font-extrabold tracking-wide bg-gradient-to-r from-purple-400 via-white to-purple-500 text-transparent bg-clip-text">
                    Fan3Cinema
                </span>
            </div>

            {{-- Menu --}}
            <div class="hidden md:flex gap-8 px-4">
                <a href="{{ route('home') }}" 
                class="relative text-sm font-medium text-gray-300 transition duration-200
                        hover:bg-gradient-to-r hover:from-purple-400 hover:to-purple-600 hover:bg-clip-text hover:text-transparent
                        after:content-[''] after:absolute after:bottom-0 after:left-0 after:w-0 after:h-[2px] after:bg-gradient-to-r after:from-purple-400 after:to-purple-600 hover:after:w-full after:transition-all after:duration-300">
                    Studio
                </a>
                <a href="#" 
                class="relative text-sm font-medium text-gray-300 transition duration-200
                        hover:bg-gradient-to-r hover:from-purple-400 hover:to-purple-600 hover:bg-clip-text hover:text-transparent
                        after:content-[''] after:absolute after:bottom-0 after:left-0 after:w-0 after:h-[2px] after:bg-gradient-to-r after:from-purple-400 after:to-purple-600 hover:after:w-full after:transition-all after:duration-300">
                    Film
                </a>
                <a href="#" 
                class="relative text-sm font-medium text-gray-300 transition duration-200
                        hover:bg-gradient-to-r hover:from-purple-400 hover:to-purple-600 hover:bg-clip-text hover:text-transparent
                        after:content-[''] after:absolute after:bottom-0 after:left-0 after:w-0 after:h-[2px] after:bg-gradient-to-r after:from-purple-400 after:to-purple-600 hover:after:w-full after:transition-all after:duration-300">
                    Jadwal
                </a>
                <a href="#" 
                class="relative text-sm font-medium text-gray-300 transition duration-200
                        hover:bg-gradient-to-r hover:from-purple-400 hover:to-purple-600 hover:bg-clip-text hover:text-transparent
                        after:content-[''] after:absolute after:bottom-0 after:left-0 after:w-0 after:h-[2px] after:bg-gradient-to-r after:from-purple-400 after:to-purple-600 hover:after:w-full after:transition-all after:duration-300">
                    Tiket Saya
                </a>
                <a href="#" 
                class="relative text-sm font-medium text-gray-300 transition duration-200
                        hover:bg-gradient-to-r hover:from-purple-400 hover:to-purple-600 hover:bg-clip-text hover:text-transparent
                        after:content-[''] after:absolute after:bottom-0 after:left-0 after:w-0 after:h-[2px] after:bg-gradient-to-r after:from-purple-400 after:to-purple-600 hover:after:w-full after:transition-all after:duration-300">
                    Riwayat
                </a>
            </div>


            {{-- Auth Menu --}}
            <div class="flex items-center space-x-4">
                @auth
                    {{-- Notification --}}
                    <button class="relative bg-gray-800 p-1 rounded-full text-gray-400 hover:text-white hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-white">
                        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1" />
                        </svg>
                    </button>

                    {{-- Profile --}}
                    <div class="flex items-center space-x-2">
                        <img class="h-8 w-8 rounded-full border-2 border-purple-500" src="https://ui-avatars.com/api/?name={{ Auth::user()->name }}&background=7c3aed&color=fff" alt="Avatar">
                        <div class="text-sm text-white hidden lg:block">
                            <div class="font-medium">{{ Auth::user()->name }}</div>
                            <div class="text-gray-400 text-xs">{{ Auth::user()->email }}</div>
                        </div>
                    </div>
                @endauth
                @guest
                    <a href="{{ route('login') }}" 
                    class="px-4 py-1 border border-purple-500 rounded-md text-sm text-gray-300 
                            hover:text-white hover:bg-gradient-to-r hover:from-purple-500 hover:to-purple-700 
                            transition duration-200">
                        Login
                    </a>
                    <a href="{{ route('register') }}" 
                    class="px-4 py-1 border border-purple-500 rounded-md text-sm text-gray-300 
                            hover:text-white hover:bg-gradient-to-r hover:from-purple-500 hover:to-purple-700 
                            transition duration-200">
                        Register
                    </a>
                @endguest
            </div>
        </div>
    </div>
</nav>
