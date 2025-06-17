<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-900 antialiased">
        <div class="relative grid h-dvh lg:grid-cols-2">
            <!-- Left Panel - Form -->
            <div class="flex items-center justify-center bg-white dark:bg-zinc-800 px-8 py-12">
                <div class="w-full max-w-sm space-y-8">
                    <!-- Logo -->
                    <div class="flex items-center space-x-2">
                        <svg class="h-8 w-8 text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                        <span class="ml-2 text-2xl font-extrabold tracking-wide bg-gradient-to-r from-purple-400 via-black dark:via-white to-purple-500 text-transparent bg-clip-text">
                            Fan3Cinema
                        </span>
                    </div>

                    <!-- Form Container -->
                    <div class="space-y-6 text-zinc-900 dark:text-zinc-100">
                        {{ $slot }}
                    </div>
                </div>
            </div>

            <!-- Right Panel - Abstract Background -->
            <div class="hidden lg:block relative overflow-hidden">
                <!-- Beautiful abstract gradient background -->
                <div class="absolute inset-0 bg-gradient-to-br from-purple-400 via-purple-500 to-purple-600 dark:from-purple-600 dark:via-purple-700 dark:to-purple-800"></div>

                <!-- Organic shapes -->
                <div class="absolute inset-0">
                    <!-- Large circle top right -->
                    <div class="absolute -top-20 -right-20 w-80 h-80 bg-gradient-to-br from-purple-300 to-purple-500 rounded-full opacity-80 blur-3xl dark:from-purple-500 dark:to-purple-700"></div>

                    <!-- Medium circle middle left -->
                    <div class="absolute top-1/3 -left-16 w-64 h-64 bg-gradient-to-br from-blue-400 to-purple-400 rounded-full opacity-70 blur-2xl dark:from-blue-500 dark:to-purple-600"></div>

                    <!-- Small circle bottom center -->
                    <div class="absolute bottom-20 left-1/3 w-48 h-48 bg-gradient-to-br from-pink-400 to-purple-500 rounded-full opacity-60 blur-xl dark:from-pink-500 dark:to-purple-600"></div>

                    <!-- Additional organic shape -->
                    <div class="absolute top-1/2 right-1/4 w-56 h-56 bg-gradient-to-br from-purple-200 to-purple-400 rounded-full opacity-50 blur-2xl transform rotate-45 dark:from-purple-400 dark:to-purple-600"></div>
                </div>

                <!-- Subtle overlay for depth -->
                <div class="absolute inset-0 bg-gradient-to-t from-purple-600/20 to-transparent dark:from-purple-900/20"></div>
            </div>
        </div>
        @fluxScripts
    </body>
</html>
