<div x-data="{ notifOpen: false }" class="relative">
    <button @click="notifOpen = !notifOpen" class="relative p-3 rounded-lg text-slate-500 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
        </svg>
        @if(isset($dueInstallments) && $dueInstallments->count() > 0)
        <span class="absolute top-2 right-2 flex h-3.5 w-3.5">
            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
            <span class="relative inline-flex rounded-full h-3.5 w-3.5 bg-red-500"></span>
        </span>
        @endif
    </button>
    
    <div x-show="notifOpen" x-transition @click.outside="notifOpen = false"
        class="absolute {{ $positionClass ?? 'left-0' }} mt-2 w-96 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl shadow-lg overflow-hidden z-50">
        <div class="px-5 py-4 border-b border-slate-100 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/80 flex items-center justify-between">
            <h3 class="text-sm font-semibold text-slate-800 dark:text-white">Due in 5 Days</h3>
            <span class="px-2 py-0.5 rounded-md bg-red-100 text-red-600 text-[10px] font-bold">{{ isset($dueInstallments) ? $dueInstallments->count() : 0 }}</span>
        </div>
        <div class="max-h-64 overflow-y-auto">
            @if(isset($dueInstallments) && $dueInstallments->count() > 0)
                @foreach($dueInstallments as $inst)
                <div class="px-4 py-3 border-b border-slate-50 dark:border-slate-700/50 hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">
                    <p class="text-xs font-semibold text-slate-800 dark:text-white">
                        {{ $inst->loan->borrower->first_name ?? 'Unknown' }} {{ $inst->loan->borrower->last_name ?? '' }}
                    </p>
                    <div class="flex items-center justify-between mt-1">
                        <span class="text-xs text-slate-500 dark:text-slate-400">Due: {{ \Carbon\Carbon::parse($inst->due_date)->format('M d, Y') }}</span>
                        <span class="text-xs font-bold text-red-600 dark:text-red-400">TZS {{ number_format($inst->expected_amount) }}</span>
                    </div>
                </div>
                @endforeach
            @else
                <div class="px-4 py-6 text-center text-sm text-slate-500 dark:text-slate-400">
                    No upcoming payments in 5 days.
                </div>
            @endif
        </div>
    </div>
</div>
