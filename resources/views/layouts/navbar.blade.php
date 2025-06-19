{{-- layouts/navbar.blade.php --}}
<nav class="fixed top-0 left-0 w-full z-50 bg-gray-950 border-b border-purple-700/50 shadow-2xl">

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
                <a href="#studio" 
                class="relative text-sm font-medium text-gray-300 transition duration-200
                        hover:bg-gradient-to-r hover:from-purple-400 hover:to-purple-600 hover:bg-clip-text hover:text-transparent
                        after:content-[''] after:absolute after:bottom-0 after:left-0 after:w-0 after:h-[2px] after:bg-gradient-to-r after:from-purple-400 after:to-purple-600 hover:after:w-full after:transition-all after:duration-300">
                    Studio
                </a>
                <a href="#film" 
                class="relative text-sm font-medium text-gray-300 transition duration-200
                        hover:bg-gradient-to-r hover:from-purple-400 hover:to-purple-600 hover:bg-clip-text hover:text-transparent
                        after:content-[''] after:absolute after:bottom-0 after:left-0 after:w-0 after:h-[2px] after:bg-gradient-to-r after:from-purple-400 after:to-purple-600 hover:after:w-full after:transition-all after:duration-300">
                    Film
                </a>
                <a href="#film" 
                class="relative text-sm font-medium text-gray-300 transition duration-200
                        hover:bg-gradient-to-r hover:from-purple-400 hover:to-purple-600 hover:bg-clip-text hover:text-transparent
                        after:content-[''] after:absolute after:bottom-0 after:left-0 after:w-0 after:h-[2px] after:bg-gradient-to-r after:from-purple-400 after:to-purple-600 hover:after:w-full after:transition-all after:duration-300">
                    Jadwal
                </a>
                @auth
                    <a href="#pesan" 
                    class="relative text-sm font-medium text-gray-300 transition duration-200
                            hover:bg-gradient-to-r hover:from-purple-400 hover:to-purple-600 hover:bg-clip-text hover:text-transparent
                            after:content-[''] after:absolute after:bottom-0 after:left-0 after:w-0 after:h-[2px] after:bg-gradient-to-r after:from-purple-400 after:to-purple-600 hover:after:w-full after:transition-all after:duration-300">
                        Pesan
                    </a>
                @endauth

                @guest
                    <a href="{{ route('login') }}" 
                    class="relative text-sm font-medium text-gray-300 transition duration-200
                            hover:bg-gradient-to-r hover:from-purple-400 hover:to-purple-600 hover:bg-clip-text hover:text-transparent
                            after:content-[''] after:absolute after:bottom-0 after:left-0 after:w-0 after:h-[2px] after:bg-gradient-to-r after:from-purple-400 after:to-purple-600 hover:after:w-full after:transition-all after:duration-300">
                        Pesan
                    </a>
                @endguest
            </div>


            {{-- Auth Menu --}}
            <div class="flex items-center space-x-4">
                @auth

                    {{-- Profile & Dropdown --}}
                    <div class="relative" x-data="{ open: false }" @click.away="open = false">
                        <div class="flex items-center space-x-2 cursor-pointer" @click="open = !open">
                            <img class="h-8 w-8 rounded-full border-2 border-purple-500"
                                src="https://ui-avatars.com/api/?name={{ Auth::user()->name }}&background=7c3aed&color=fff"
                                alt="Avatar">
                            <div class="text-sm text-white hidden lg:block">
                                <div class="font-medium">{{ Auth::user()->name }}</div>
                                <div class="text-gray-400 text-xs">{{ Auth::user()->email }}</div>
                            </div>
                            {{-- Dropdown Arrow --}}
                            <svg class="h-4 w-4 text-gray-400 transition-transform duration-200"
                                :class="{ 'rotate-180': open }"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </div>

                        {{-- Dropdown Menu dengan tema gelap --}}
                        <div x-show="open" 
                            x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 scale-95"
                            x-transition:enter-end="opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-150"
                            x-transition:leave-start="opacity-100 scale-100"
                            x-transition:leave-end="opacity-0 scale-95"
                            class="absolute right-0 mt-2 w-56 bg-gray-800 rounded-lg shadow-2xl z-50 border border-gray-700"
                            style="display: none;">
                            
                            {{-- Header Dropdown --}}
                            <div class="px-4 py-3 border-b border-gray-700">
                                <p class="text-sm text-white font-medium">{{ Auth::user()->name }}</p>
                                <p class="text-xs text-gray-400 truncate">{{ Auth::user()->email }}</p>
                            </div>

                            {{-- Menu Items --}}
                            <div class="py-2">
                                <a href="{{ route('settings.profile') }}" 
                                class="flex items-center px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 hover:text-white transition duration-150">
                                    <svg class="mr-3 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                    Lihat Profil
                                </a>

                                @if (Auth::user()->email === 'admin@gmail.com')
                                    <a href="{{route('user-manager')  }}" 
                                    class="flex items-center px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 hover:text-white transition duration-150">
                                        <svg class="mr-3 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                        Kelola Web
                                    </a>
                                @endif
                            </div>

                            {{-- Divider --}}
                            <div class="border-t border-gray-700"></div>

                            {{-- Logout --}}
                            <div class="py-2">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit"
                                            class="flex items-center w-full text-left px-4 py-2 text-sm text-red-400 hover:bg-gray-700 hover:text-red-300 transition duration-150">
                                        <svg class="mr-3 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                        </svg>
                                        Logout
                                    </button>
                                </form>
                            </div>
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
                @endauth
            </div>
        </div>
    </div>
</nav>
