<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h2 class="font-bold text-2xl text-slate-800 dark:text-white tracking-tight">Payments</h2>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-0.5">Payment transaction history</p>
            </div>
            <a href="{{ route('payments.create') }}" class="btn-success">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/></svg>
                Record Payment
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="page-wrap">

            @if(session('success'))
            <div class="alert-success animate-slide-up">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0 mt-0.5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                {{ session('success') }}
            </div>
            @endif

            <div class="card overflow-hidden animate-slide-up">
                <div class="section-header">
                    <div>
                        <h3 class="section-title">All Payments</h3>
                        <p class="text-xs text-slate-400 dark:text-slate-500 mt-0.5">{{ $payments->total() }} total records</p>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table>
                        <thead>
                            <tr>
                                <th>Date & Time</th>
                                <th>Borrower</th>
                                <th class="hidden md:table-cell">Installment</th>
                                <th>Amount Paid</th>
                                <th class="hidden sm:table-cell">Penalty</th>
                                <th class="hidden lg:table-cell">Processed By</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($payments as $payment)
                            <tr>
                                <td>
                                    <p class="font-medium text-slate-800 dark:text-slate-100 text-sm">
                                        {{ optional($payment->payment_date)->format('d M Y') ?? '-' }}
                                    </p>
                                    <p class="text-xs text-slate-400 dark:text-slate-500">
                                        {{ optional($payment->payment_date)->format('H:i') ?? '' }}
                                    </p>
                                </td>
                                <td>
                                    <div class="flex items-center gap-2.5">
                                        <div class="w-8 h-8 rounded-full bg-gradient-to-br from-emerald-400 to-emerald-600 flex items-center justify-center text-white text-xs font-bold shrink-0">
                                            {{ strtoupper(substr($payment->installment->loan->borrower->first_name ?? '?', 0, 1)) }}
                                        </div>
                                        <div>
                                            <p class="font-semibold text-slate-800 dark:text-slate-100 text-sm leading-tight">
                                                {{ $payment->installment->loan->borrower->first_name ?? '-' }}
                                                {{ $payment->installment->loan->borrower->last_name ?? '' }}
                                            </p>
                                            <p class="text-xs text-slate-400 dark:text-slate-500 md:hidden">M. {{ $payment->installment->month_number ?? '-' }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="hidden md:table-cell text-slate-600 dark:text-slate-300">
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-medium bg-slate-100 dark:bg-slate-700/50 text-slate-600 dark:text-slate-300">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-slate-400" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/></svg>
                                        Month {{ $payment->installment->month_number ?? '-' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="font-bold text-emerald-600 dark:text-emerald-400">
                                        TZS {{ number_format($payment->amount_paid ?? $payment->amount ?? 0) }}
                                    </span>
                                </td>
                                <td class="hidden sm:table-cell">
                                    @if(($payment->penalty_paid ?? 0) > 0)
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-xs font-medium bg-red-50 text-red-600 dark:bg-red-900/30 dark:text-red-400">
                                            + TZS {{ number_format($payment->penalty_paid) }}
                                        </span>
                                    @else
                                        <span class="text-slate-300 dark:text-slate-600">—</span>
                                    @endif
                                </td>
                                <td class="hidden lg:table-cell text-slate-500 dark:text-slate-400 text-sm">
                                    {{ $payment->processedBy->name ?? $payment->processor->name ?? '-' }}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="border-0">
                                    <div class="empty-state">
                                        <div class="empty-icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                                        </div>
                                        <p class="text-slate-600 dark:text-slate-400 font-semibold">No payment records yet</p>
                                        <p class="text-sm text-slate-400 dark:text-slate-500">Payments will appear here once recorded.</p>
                                        <a href="{{ route('payments.create') }}" class="btn-success mt-2">Record Payment</a>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($payments->hasPages())
                <div class="px-6 py-4 border-t border-slate-100 dark:border-slate-700">
                    {{ $payments->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
