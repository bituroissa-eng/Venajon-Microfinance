<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-slate-800 dark:text-white leading-tight tracking-tight">
            Dashboard
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="page-wrap !pt-0">

            {{-- ── Welcome Banner ─────────────────────────────────────── --}}
            <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-primary-600 to-primary-800 p-8 text-white shadow-xl animate-slide-up">
                <div class="absolute -top-10 -right-10 w-64 h-64 bg-white/5 rounded-full blur-3xl"></div>
                <div class="absolute -bottom-10 -left-10 w-48 h-48 bg-white/5 rounded-full blur-3xl"></div>
                <div class="relative z-10">
                    <p class="text-primary-200 text-sm font-medium mb-1">Welcome,</p>
                    <h1 class="text-3xl font-bold mb-2">{{ Auth::user()->name }}</h1>
                    <p class="text-primary-200 text-sm">Here's what's happening with your loan portfolio today.</p>
                </div>
            </div>

            {{-- ── Stats Cards ────────────────────────────────────────── --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 animate-slide-up" style="animation-delay: 50ms;">
                @php
                    $totalBorrowers = \App\Models\Borrower::count();
                    $totalLoans = \App\Models\Loan::count();
                    $activeLoans = \App\Models\Loan::where('status','Active')->count();
                    $totalPayments = \App\Models\Payment::sum('amount') ?? 0;
                @endphp

                <div class="stat-card">
                    <div class="flex items-center justify-between mb-2">
                        <div class="stat-icon bg-blue-100 dark:bg-blue-900/40 text-blue-600 dark:text-blue-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z" /></svg>
                        </div>
                        <span class="badge bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-300">Total</span>
                    </div>
                    <div>
                        <p class="text-3xl font-bold text-slate-800 dark:text-white leading-none">{{ $totalBorrowers }}</p>
                        <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 mt-1 uppercase tracking-wide">Borrowers</p>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="flex items-center justify-between mb-2">
                        <div class="stat-icon bg-violet-100 dark:bg-violet-900/40 text-violet-600 dark:text-violet-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" /></svg>
                        </div>
                        <span class="badge bg-violet-50 dark:bg-violet-900/30 text-violet-600 dark:text-violet-300">All</span>
                    </div>
                    <div>
                        <p class="text-3xl font-bold text-slate-800 dark:text-white leading-none">{{ $totalLoans }}</p>
                        <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 mt-1 uppercase tracking-wide">Issued Loans</p>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="flex items-center justify-between mb-2">
                        <div class="stat-icon bg-emerald-100 dark:bg-emerald-900/40 text-emerald-600 dark:text-emerald-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
                        </div>
                        <span class="badge bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-300">Active</span>
                    </div>
                    <div>
                        <p class="text-3xl font-bold text-slate-800 dark:text-white leading-none">{{ $activeLoans }}</p>
                        <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 mt-1 uppercase tracking-wide">Active Loans</p>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="flex items-center justify-between mb-2">
                        <div class="stat-icon bg-amber-100 dark:bg-amber-900/40 text-amber-600 dark:text-amber-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z" /><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z" clip-rule="evenodd" /></svg>
                        </div>
                        <span class="badge bg-amber-50 dark:bg-amber-900/30 text-amber-600 dark:text-amber-300">Collected</span>
                    </div>
                    <div>
                        <p class="text-xl sm:text-2xl font-bold text-slate-800 dark:text-white leading-none truncate" title="TZS {{ number_format($totalPayments) }}">TZS {{ number_format($totalPayments) }}</p>
                        <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 mt-1 uppercase tracking-wide">Total Repaid</p>
                    </div>
                </div>
            </div>

            {{-- ── Quick Actions ───────────────────────────────────────── --}}
            <div class="card p-6 animate-slide-up" style="animation-delay: 100ms;">
                <h3 class="text-sm font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 mb-4 pb-2 border-b border-slate-100 dark:border-slate-700">Quick Actions</h3>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                    <a href="{{ route('borrowers.create') }}" class="flex flex-col items-center gap-3 p-4 rounded-xl border border-slate-100 dark:border-slate-700 hover:border-primary-300 dark:hover:border-primary-600 hover:bg-primary-50 dark:hover:bg-primary-900/20 transition-all duration-200 group">
                        <div class="w-12 h-12 rounded-2xl bg-blue-100 dark:bg-blue-900/40 flex items-center justify-center group-hover:bg-blue-200 dark:group-hover:bg-blue-800/50 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600 dark:text-blue-400" viewBox="0 0 20 20" fill="currentColor"><path d="M8 9a3 3 0 100-6 3 3 0 000 6zM8 11a6 6 0 016 6H2a6 6 0 016-6zM16 7a1 1 0 10-2 0v1h-1a1 1 0 100 2h1v1a1 1 0 102 0v-1h1a1 1 0 100-2h-1V7z" /></svg>
                        </div>
                        <span class="text-sm font-semibold text-slate-700 dark:text-slate-200 group-hover:text-primary-700 dark:group-hover:text-primary-300 text-center">New Borrower</span>
                    </a>
                    <a href="{{ route('loans.create') }}" class="flex flex-col items-center gap-3 p-4 rounded-xl border border-slate-100 dark:border-slate-700 hover:border-primary-300 dark:hover:border-primary-600 hover:bg-primary-50 dark:hover:bg-primary-900/20 transition-all duration-200 group">
                        <div class="w-12 h-12 rounded-2xl bg-violet-100 dark:bg-violet-900/40 flex items-center justify-center group-hover:bg-violet-200 dark:group-hover:bg-violet-800/50 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-violet-600 dark:text-violet-400" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" /></svg>
                        </div>
                        <span class="text-sm font-semibold text-slate-700 dark:text-slate-200 group-hover:text-primary-700 dark:group-hover:text-primary-300 text-center">Process Loan</span>
                    </a>
                    <a href="{{ route('payments.create') }}" class="flex flex-col items-center gap-3 p-4 rounded-xl border border-slate-100 dark:border-slate-700 hover:border-primary-300 dark:hover:border-primary-600 hover:bg-primary-50 dark:hover:bg-primary-900/20 transition-all duration-200 group">
                        <div class="w-12 h-12 rounded-2xl bg-emerald-100 dark:bg-emerald-900/40 flex items-center justify-center group-hover:bg-emerald-200 dark:group-hover:bg-emerald-800/50 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-emerald-600 dark:text-emerald-400" viewBox="0 0 20 20" fill="currentColor"><path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4zM18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" /></svg>
                        </div>
                        <span class="text-sm font-semibold text-slate-700 dark:text-slate-200 group-hover:text-primary-700 dark:group-hover:text-primary-300 text-center">Record Payment</span>
                    </a>
                    <a href="{{ route('borrowers.index') }}" class="flex flex-col items-center gap-3 p-4 rounded-xl border border-slate-100 dark:border-slate-700 hover:border-primary-300 dark:hover:border-primary-600 hover:bg-primary-50 dark:hover:bg-primary-900/20 transition-all duration-200 group">
                        <div class="w-12 h-12 rounded-2xl bg-amber-100 dark:bg-amber-900/40 flex items-center justify-center group-hover:bg-amber-200 dark:group-hover:bg-amber-800/50 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-amber-600 dark:text-amber-400" viewBox="0 0 20 20" fill="currentColor"><path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/><path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/></svg>
                        </div>
                        <span class="text-sm font-semibold text-slate-700 dark:text-slate-200 group-hover:text-primary-700 dark:group-hover:text-primary-300 text-center">View All</span>
                    </a>
                </div>
            </div>

            {{-- ── Recent Loans Table ────────────────────────────────── --}}
            <div class="card overflow-hidden animate-slide-up" style="animation-delay: 150ms;">
                <div class="section-header">
                    <h3 class="section-title">Recent Loans</h3>
                    <a href="{{ route('loans.index') }}" class="btn btn-sm btn-ghost">View all →</a>
                </div>
                <div class="overflow-x-auto">
                    <table>
                        <thead>
                            <tr>
                                <th>Borrower</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th class="hidden sm:table-cell">Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse(\App\Models\Loan::with('borrower')->latest()->take(5)->get() as $loan)
                            <tr>
                                <td>
                                    <div class="flex items-center gap-2">
                                        <div class="w-6 h-6 rounded-full bg-slate-100 dark:bg-slate-700 flex items-center justify-center text-[10px] font-bold text-slate-600 dark:text-slate-300 shrink-0">
                                            {{ strtoupper(substr($loan->borrower->first_name ?? '?', 0, 1)) }}
                                        </div>
                                        <span class="font-medium text-slate-800 dark:text-slate-100">
                                            {{ $loan->borrower->first_name ?? '-' }} {{ $loan->borrower->last_name ?? '' }}
                                        </span>
                                    </div>
                                </td>
                                <td class="font-semibold text-slate-800 dark:text-slate-100">TZS {{ number_format($loan->principal_amount) }}</td>
                                <td>
                                    @php
                                        $cls = match($loan->status) {
                                            'Pending'   => 'badge-pending',
                                            'Active'    => 'badge-active',
                                            'Completed','Closed' => 'badge-paid',
                                            'Defaulted' => 'badge-defaulted',
                                            default     => 'badge-closed',
                                        };
                                    @endphp
                                    <span class="{{ $cls }}">{{ $loan->status }}</span>
                                </td>
                                <td class="hidden sm:table-cell text-slate-500 dark:text-slate-400">{{ $loan->created_at->format('d M Y') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center text-slate-400 dark:text-slate-500 py-8">No loans found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
