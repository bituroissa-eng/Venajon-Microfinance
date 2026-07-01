<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Venajon MFI') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

        <!-- Scripts / Styles -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Dark Mode Init (must run before paint) -->
        <script>
            if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        </script>

        <style>
            /* Push content beside sidebar on desktop */
            .main-content { transition: margin-left 0.3s ease; }
            /* bottom-safe-area for iOS/Android notch */
            .pb-safe { padding-bottom: env(safe-area-inset-bottom, 0px); }
        </style>
    </head>
    <body class="font-sans antialiased bg-slate-100 dark:bg-slate-900 text-slate-900 dark:text-slate-100 transition-colors duration-300 relative">
        
        {{-- System Background Image --}}
        <div class="fixed inset-0 z-[-1] pointer-events-none bg-cover bg-center bg-no-repeat opacity-40 dark:opacity-5 mix-blend-multiply dark:mix-blend-screen" style="background-image: url('{{ asset('images/system-bg.png') }}');"></div>

        {{-- Navigation (sidebar + mobile bars) --}}
        @include('layouts.navigation')

        {{-- Wrapper: offset left for sidebar on lg, top+bottom for mobile bars --}}
        <div class="main-content lg:ml-56 pt-14 lg:pt-0 pb-20 lg:pb-0 min-h-screen flex flex-col">

            {{-- Page Heading --}}
            @isset($header)
                <header class="bg-white dark:bg-slate-800 border-b border-slate-200 dark:border-slate-700 transition-colors duration-300">
                    <div class="max-w-7xl mx-auto py-5 px-4 sm:px-6 lg:px-8 flex items-center gap-4">
                        <div class="shrink-0">
                            @include('components.due-notification', ['positionClass' => 'left-0 mt-2'])
                        </div>
                        <div class="flex-1">
                            {{ $header }}
                        </div>
                    </div>
                </header>
            @endisset

            {{-- Page Content --}}
            <main class="flex-1">
                {{ $slot }}
            </main>
        </div>

    </body>
</html>
