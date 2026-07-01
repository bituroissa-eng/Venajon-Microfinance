{{-- =====================================================================
     SIDEBAR (desktop) + BOTTOM TAB BAR (mobile)
     ===================================================================== --}}

{{-- ── Desktop Sidebar ─────────────────────────────────────────────────── --}}
<aside id="sidebar"
    class="fixed inset-y-0 left-0 z-40 w-56 flex flex-col
           bg-white dark:bg-slate-900
           border-r border-slate-200 dark:border-slate-700/60
           shadow-xl dark:shadow-slate-900/60
           transition-transform duration-300 ease-in-out
           -translate-x-full lg:translate-x-0">

    {{-- Logo --}}
    <div class="flex items-center gap-3 px-5 py-5 border-b border-slate-100 dark:border-slate-800">
        @if(isset($systemSetting) && $systemSetting->logo_path)
            <div class="w-9 h-9 rounded-xl overflow-hidden shadow-md shrink-0">
                <img src="{{ asset('storage/' . $systemSetting->logo_path) }}" alt="Logo" class="w-full h-full object-cover">
            </div>
        @else
            <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-primary-500 to-primary-700 flex items-center justify-center shadow-md shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"/>
                    <path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd"/>
                </svg>
            </div>
        @endif
        <div>
            <span class="font-bold text-base tracking-tight text-slate-800 dark:text-white truncate max-w-[140px] block">
                {{ isset($systemSetting) ? $systemSetting->name : 'Venajon MFI' }}
            </span>
            <p class="text-[10px] text-slate-400 dark:text-slate-500 leading-none mt-0.5">Microfinance Portal</p>
        </div>
    </div>

    {{-- Nav Links --}}
    <nav class="flex-1 overflow-y-auto py-4 px-3 space-y-0.5">

        @php
            function navLink($route, $label, $icon, $routes = null) {
                $active = $routes ? request()->routeIs($routes) : request()->routeIs($route);
                $base   = 'flex items-center gap-2.5 px-3 py-2 rounded-xl text-[13px] font-semibold transition-all duration-200 group';
                $on     = 'bg-primary-50 dark:bg-primary-900/40 text-primary-700 dark:text-primary-300 shadow-sm';
                $off    = 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white';
                return "<a href=\"" . route($route) . "\" class=\"$base " . ($active ? $on : $off) . "\">$icon <span>$label</span></a>";
            }
        @endphp

        <p class="px-3 pt-1 pb-2 text-[10px] font-semibold uppercase tracking-widest text-slate-400 dark:text-slate-600">Main</p>

        {!! navLink('dashboard','Dashboard','<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0" viewBox="0 0 20 20" fill="currentColor"><path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/></svg>') !!}

        {!! navLink('borrowers.index','Borrowers','<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0" viewBox="0 0 20 20" fill="currentColor"><path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/></svg>','borrowers.*') !!}

        {!! navLink('loans.index','Loans','<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/></svg>','loans.*') !!}

        {!! navLink('payments.index','Payments','<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0" viewBox="0 0 20 20" fill="currentColor"><path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4zM18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z"/></svg>','payments.*') !!}

        {!! navLink('loan-calculator','Calculator','<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V4a2 2 0 00-2-2H6zm1 2a1 1 0 000 2h6a1 1 0 100-2H7zm6 7a1 1 0 011 1v3a1 1 0 11-2 0v-3a1 1 0 011-1zm-3 3a1 1 0 100 2h.01a1 1 0 100-2H10zm-4 1a1 1 0 011-1h.01a1 1 0 110 2H7a1 1 0 01-1-1zm1-4a1 1 0 100 2h.01a1 1 0 100-2H7zm2 1a1 1 0 011-1h.01a1 1 0 110 2H10a1 1 0 01-1-1zm4-4a1 1 0 100 2h.01a1 1 0 100-2H13zM9 9a1 1 0 011-1h.01a1 1 0 110 2H10a1 1 0 01-1-1zm-4 0a1 1 0 011-1h.01a1 1 0 110 2H7a1 1 0 01-1-1z" clip-rule="evenodd"/></svg>') !!}

        @hasanyrole('Admin|Manager')
        <p class="px-3 pt-4 pb-2 text-[10px] font-semibold uppercase tracking-widest text-slate-400 dark:text-slate-600">Management</p>

        {!! navLink('loan-plans.index','Loan Plans','<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0" viewBox="0 0 20 20" fill="currentColor"><path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/><path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/></svg>','loan-plans.*') !!}

        {!! navLink('loan-categories.index','Categories','<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0" viewBox="0 0 20 20" fill="currentColor"><path d="M7 3a1 1 0 000 2h6a1 1 0 100-2H7zM4 7a1 1 0 011-1h10a1 1 0 110 2H5a1 1 0 01-1-1zM2 11a2 2 0 012-2h12a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4z"/></svg>','loan-categories.*') !!}

        {!! navLink('users.index','Users','<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/></svg>','users.*') !!}
        @endhasanyrole

        @role('Admin')
        {!! navLink('system-settings.index','Settings','<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"/></svg>','system-settings.*') !!}
        @endrole
    </nav>

    {{-- User Panel --}}
    <div class="border-t border-slate-100 dark:border-slate-800 p-3">
        {{-- Dark Mode Toggle --}}
        <button onclick="toggleTheme()"
            class="w-full flex items-center gap-2.5 px-3 py-2 rounded-xl text-[13px] font-semibold text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors duration-200 mb-1">
            <svg id="sb-icon-sun" class="w-4 h-4 hidden" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
            </svg>
            <svg id="sb-icon-moon" class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
            </svg>
            <span id="sb-theme-label">Dark Mode</span>
        </button>

        {{-- User Info + Dropdown --}}
        <div x-data="{ userOpen: false }" class="relative">
            <button @click="userOpen = !userOpen"
                class="w-full flex items-center gap-2.5 px-3 py-2 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors duration-200">
                <div class="w-7 h-7 rounded-full bg-gradient-to-br from-primary-400 to-primary-600 flex items-center justify-center text-white text-[10px] font-bold shrink-0">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
                <div class="flex-1 text-left overflow-hidden">
                    <div class="text-[13px] font-semibold text-slate-800 dark:text-white truncate">{{ Auth::user()->name }}</div>
                    <div class="text-[11px] text-slate-400 dark:text-slate-500 truncate -mt-0.5">{{ Auth::user()->email }}</div>
                </div>
                <svg class="h-4 w-4 text-slate-400 shrink-0 transition-transform duration-200" :class="{'rotate-180': userOpen}" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                </svg>
            </button>
            <div x-show="userOpen" x-transition @click.outside="userOpen = false"
                class="absolute bottom-full left-0 right-0 mb-1 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl shadow-lg overflow-hidden z-50">
                <a href="{{ route('profile.edit') }}" class="flex items-center gap-2 px-4 py-3 text-sm text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-400" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/></svg>
                    Profile
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-2 px-4 py-3 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M3 3a1 1 0 00-1 1v12a1 1 0 102 0V4a1 1 0 00-1-1zm10.293 9.293a1 1 0 001.414 1.414l3-3a1 1 0 000-1.414l-3-3a1 1 0 10-1.414 1.414L14.586 9H7a1 1 0 100 2h7.586l-1.293 1.293z" clip-rule="evenodd"/></svg>
                        Log Out
                    </button>
                </form>
            </div>
        </div>
    </div>
</aside>

{{-- ── Mobile Top Bar ──────────────────────────────────────────────────── --}}
<header class="lg:hidden fixed top-0 inset-x-0 z-40 h-14 flex items-center justify-between px-4
               bg-white/95 dark:bg-slate-900/95 backdrop-blur-md
               border-b border-slate-200 dark:border-slate-700/60 shadow-sm">
    <button id="sidebar-toggle" onclick="toggleSidebar()"
        class="p-2 rounded-lg text-slate-500 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
        <svg class="h-5 w-5" stroke="currentColor" fill="none" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
        </svg>
    </button>
    <a href="{{ route('dashboard') }}" class="flex items-center gap-2">
        @if(isset($systemSetting) && $systemSetting->logo_path)
            <div class="w-7 h-7 rounded-lg overflow-hidden shrink-0">
                <img src="{{ asset('storage/' . $systemSetting->logo_path) }}" alt="Logo" class="w-full h-full object-cover">
            </div>
        @else
            <div class="w-7 h-7 rounded-lg bg-gradient-to-br from-primary-500 to-primary-700 flex items-center justify-center shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-white" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"/>
                    <path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd"/>
                </svg>
            </div>
        @endif
        <span class="font-bold text-sm tracking-tight text-slate-800 dark:text-white truncate max-w-[150px]">
            {{ isset($systemSetting) ? $systemSetting->name : 'Venajon MFI' }}
        </span>
    </a>
    <div class="flex items-center gap-1">
    <button onclick="toggleTheme()" class="p-2 rounded-lg text-slate-500 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
        <svg id="top-icon-sun" class="w-5 h-5 hidden" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
        </svg>
        <svg id="top-icon-moon" class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
        </svg>
    </button>
    </div>
</header>

{{-- ── Mobile Sidebar Overlay ──────────────────────────────────────────── --}}
<div id="sidebar-overlay" onclick="toggleSidebar()"
    class="fixed inset-0 z-30 bg-black/40 backdrop-blur-sm hidden lg:hidden transition-opacity duration-300"></div>

{{-- ── Mobile Bottom Tab Bar ───────────────────────────────────────────── --}}
<nav class="lg:hidden fixed bottom-0 inset-x-0 z-40
            bg-white/95 dark:bg-slate-900/95 backdrop-blur-md
            border-t border-slate-200 dark:border-slate-700/60
            safe-area-inset-bottom shadow-[0_-4px_24px_-4px_rgba(0,0,0,0.1)]">
    <div class="flex items-center justify-around px-2 py-1">

        <a href="{{ route('dashboard') }}"
            class="flex flex-col items-center gap-0.5 px-3 py-2 rounded-xl transition-colors duration-200 min-w-0
                   {{ request()->routeIs('dashboard') ? 'text-primary-600 dark:text-primary-400' : 'text-slate-500 dark:text-slate-400' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/>
            </svg>
            <span class="text-[10px] font-semibold">Home</span>
        </a>

        <a href="{{ route('borrowers.index') }}"
            class="flex flex-col items-center gap-0.5 px-3 py-2 rounded-xl transition-colors duration-200 min-w-0
                   {{ request()->routeIs('borrowers.*') ? 'text-primary-600 dark:text-primary-400' : 'text-slate-500 dark:text-slate-400' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
            </svg>
            <span class="text-[10px] font-semibold">Borrowers</span>
        </a>

        <a href="{{ route('loans.index') }}"
            class="flex flex-col items-center gap-0.5 px-3 py-2 rounded-xl transition-colors duration-200 min-w-0
                   {{ request()->routeIs('loans.*') ? 'text-primary-600 dark:text-primary-400' : 'text-slate-500 dark:text-slate-400' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
            </svg>
            <span class="text-[10px] font-semibold">Loans</span>
        </a>

        <a href="{{ route('payments.index') }}"
            class="flex flex-col items-center gap-0.5 px-3 py-2 rounded-xl transition-colors duration-200 min-w-0
                   {{ request()->routeIs('payments.*') ? 'text-primary-600 dark:text-primary-400' : 'text-slate-500 dark:text-slate-400' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4zM18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z"/>
            </svg>
            <span class="text-[10px] font-semibold">Payments</span>
        </a>

        <a href="{{ route('loan-calculator') }}"
            class="flex flex-col items-center gap-0.5 px-3 py-2 rounded-xl transition-colors duration-200 min-w-0
                   {{ request()->routeIs('loan-calculator') ? 'text-primary-600 dark:text-primary-400' : 'text-slate-500 dark:text-slate-400' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V4a2 2 0 00-2-2H6zm1 2a1 1 0 000 2h6a1 1 0 100-2H7zm6 7a1 1 0 011 1v3a1 1 0 11-2 0v-3a1 1 0 011-1zm-3 3a1 1 0 100 2h.01a1 1 0 100-2H10zm-4 1a1 1 0 011-1h.01a1 1 0 110 2H7a1 1 0 01-1-1zm1-4a1 1 0 100 2h.01a1 1 0 100-2H7zm2 1a1 1 0 011-1h.01a1 1 0 110 2H10a1 1 0 01-1-1zm4-4a1 1 0 100 2h.01a1 1 0 100-2H13zM9 9a1 1 0 011-1h.01a1 1 0 110 2H10a1 1 0 01-1-1zm-4 0a1 1 0 011-1h.01a1 1 0 110 2H7a1 1 0 01-1-1z" clip-rule="evenodd"/>
            </svg>
            <span class="text-[10px] font-semibold">Calc</span>
        </a>

    </div>
</nav>

<script>
// ── Sidebar toggle (mobile) ───────────────────────────────────────────────
function toggleSidebar() {
    const sidebar  = document.getElementById('sidebar');
    const overlay  = document.getElementById('sidebar-overlay');
    const open     = !sidebar.classList.contains('-translate-x-full');
    sidebar.classList.toggle('-translate-x-full', open);
    overlay.classList.toggle('hidden', open);
}

// ── Dark-mode toggle ──────────────────────────────────────────────────────
function toggleTheme() {
    const isDark = document.documentElement.classList.toggle('dark');
    localStorage.theme = isDark ? 'dark' : 'light';
    syncThemeIcons(isDark);
}

function syncThemeIcons(isDark) {
    ['sb-icon-sun','top-icon-sun'].forEach(id => {
        const el = document.getElementById(id);
        if (el) el.classList.toggle('hidden', !isDark);
    });
    ['sb-icon-moon','top-icon-moon'].forEach(id => {
        const el = document.getElementById(id);
        if (el) el.classList.toggle('hidden', isDark);
    });
    const label = document.getElementById('sb-theme-label');
    if (label) label.textContent = isDark ? 'Light Mode' : 'Dark Mode';
}

document.addEventListener('DOMContentLoaded', function () {
    syncThemeIcons(document.documentElement.classList.contains('dark'));
});
</script>
