<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <script>
            if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark')
            } else {
                document.documentElement.classList.remove('dark')
            }
        </script>
    </head>
    <body class="font-sans text-slate-900 dark:text-slate-100 antialiased bg-slate-100 dark:bg-slate-900 transition-colors duration-300">
        
        <div class="min-h-screen flex">
            <!-- Left Side / Branding -->
            <div class="hidden lg:flex lg:w-1/2 bg-slate-900 relative items-center justify-center overflow-hidden">
                <!-- Image Background -->
                <div class="absolute inset-0">
                    <img src="{{ asset('images/finance_bg.png') }}" alt="Microfinance Background" class="w-full h-full object-cover opacity-90">
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-900/90 via-slate-900/40 to-transparent mix-blend-multiply"></div>
                </div>
                
                <div class="relative z-10 text-center px-12">
                    <div class="flex justify-center mb-8">
                        @if(isset($systemSetting) && $systemSetting->logo_path)
                            <div class="w-20 h-20 rounded-2xl overflow-hidden shadow-lg border-2 border-white/20 bg-white">
                                <img src="{{ asset('storage/' . $systemSetting->logo_path) }}" alt="Logo" class="w-full h-full object-cover">
                            </div>
                        @else
                            <div class="w-20 h-20 rounded-2xl bg-white/10 backdrop-blur-sm flex items-center justify-center shadow-lg border border-white/20">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-white" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z" />
                                    <path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        @endif
                    </div>
                    <h1 class="text-4xl md:text-5xl font-bold text-white mb-6 tracking-tight">
                        {{ isset($systemSetting) ? $systemSetting->name : 'Venajon Microfinance' }}
                    </h1>
                    <p class="text-slate-200 text-lg max-w-md mx-auto leading-relaxed">
                        Empowering communities through accessible and transparent financial services. Log in to manage your portfolio and process loans.
                    </p>
                </div>
            </div>

            <!-- Right Side / Form -->
            <div class="w-full lg:w-1/2 flex items-center justify-center p-8 sm:p-12 lg:p-24 bg-white dark:bg-slate-900 relative">
                
                <!-- Dark Mode Toggle (Top Right) -->
                <div class="absolute top-8 right-8">
                    <button onclick="localStorage.theme = document.documentElement.classList.contains('dark') ? 'light' : 'dark'; document.documentElement.classList.toggle('dark');" class="p-2 rounded-full text-slate-400 hover:text-primary-500 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors duration-200">
                        <svg x-data x-show="!document.documentElement.classList.contains('dark')" class="w-6 h-6 hidden dark:block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        <svg x-data x-show="document.documentElement.classList.contains('dark')" class="w-6 h-6 block dark:hidden" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                        </svg>
                    </button>
                </div>

                <div class="w-full max-w-md z-10">
                    <!-- Mobile Logo -->
                    <div class="lg:hidden flex items-center justify-center gap-4 mb-10">
                        @if(isset($systemSetting) && $systemSetting->logo_path)
                            <div class="w-14 h-14 rounded-2xl overflow-hidden shadow-xl border border-slate-200 dark:border-slate-700">
                                <img src="{{ asset('storage/' . $systemSetting->logo_path) }}" alt="Logo" class="w-full h-full object-cover">
                            </div>
                        @else
                            <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-primary-500 to-primary-700 flex items-center justify-center shadow-xl">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-white" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z" />
                                    <path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        @endif
                        <span class="font-extrabold text-2xl tracking-tight text-slate-800 dark:text-white truncate max-w-[200px]">
                            {{ isset($systemSetting) ? $systemSetting->name : 'Venajon' }}
                        </span>
                    </div>

                    <div class="mb-10 text-center lg:text-left">
                        <h2 class="text-3xl font-bold text-slate-900 dark:text-white tracking-tight">Welcome Back</h2>
                        <p class="text-slate-500 dark:text-slate-400 mt-2 text-sm">Please sign in to your account to continue</p>
                    </div>

                    <div class="w-full">
                        {{ $slot }}
                    </div>
                </div>
            </div>
        </div>

    </body>
</html>
