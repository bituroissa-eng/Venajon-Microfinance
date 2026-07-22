<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="w-full bg-white dark:bg-slate-900 rounded-2xl p-2">
        <form method="POST" action="{{ route('login') }}" class="space-y-6">
            @csrf

            <!-- Email Address -->
            <div>
                <label for="email" class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-300">Email Address</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-slate-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                            <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                        </svg>
                    </div>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                        class="block w-full rounded-xl border border-slate-200 bg-slate-50/50 pl-11 pr-4 py-3 text-sm text-slate-800 transition focus:border-primary-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary-500/20 dark:border-slate-700 dark:bg-slate-800 dark:text-white"
                        placeholder="admin@venajon.co.tz">
                </div>
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-300">Password</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-slate-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <input id="password" type="password" name="password" required autocomplete="current-password"
                        class="block w-full rounded-xl border border-slate-200 bg-slate-50/50 pl-11 pr-4 py-3 text-sm text-slate-800 transition focus:border-primary-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary-500/20 dark:border-slate-700 dark:bg-slate-800 dark:text-white"
                        placeholder="••••••••">
                </div>
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- Remember Me + Forgot -->
            <div class="flex items-center justify-between">
                <label class="flex items-center gap-2 cursor-pointer group">
                    <input id="remember_me" type="checkbox" name="remember" class="h-4 w-4 rounded border-slate-300 text-primary-600 focus:ring-primary-500 dark:border-slate-600 dark:bg-slate-800 transition">
                    <span class="select-none text-sm text-slate-600 dark:text-slate-400 group-hover:text-slate-800 dark:group-hover:text-slate-200 transition">Remember me</span>
                </label>
                @if (Route::has('password.request'))
                    <a class="text-sm font-medium text-primary-600 transition hover:text-primary-700 dark:text-primary-400 dark:hover:text-primary-300" href="{{ route('password.request') }}">
                        Forgot password?
                    </a>
                @endif
            </div>

            <!-- Submit -->
            <button type="submit" class="mt-2 flex w-full items-center justify-center rounded-xl bg-primary-600 px-6 py-3.5 text-sm font-semibold text-white shadow-sm transition-all hover:bg-primary-700 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 dark:focus:ring-offset-slate-900">
                Sign In
            </button>
        </form>
    </div>
</x-guest-layout>
