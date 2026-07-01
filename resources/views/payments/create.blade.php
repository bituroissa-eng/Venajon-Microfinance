<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('payments.index') }}" class="p-2 rounded-xl text-slate-500 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"/></svg>
            </a>
            <div>
                <h2 class="font-bold text-2xl text-slate-800 dark:text-white tracking-tight">Record Payment</h2>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-0.5">Log a new payment for a borrower</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="page-wrap">

            {{-- ── Search Card ─────────────────────────────────────────── --}}
            <div class="card p-6 animate-slide-up">
                <form method="GET" action="{{ route('payments.create') }}" class="flex flex-col sm:flex-row gap-4 items-end">
                    <div class="flex-1 w-full max-w-md field">
                        <label for="borrower_id">Search Borrower <span class="text-red-500">*</span></label>
                        <select id="borrower_id" name="borrower_id" required onchange="this.form.submit()">
                            <option value="">Select Borrower...</option>
                            @foreach($borrowers as $borrower)
                                <option value="{{ $borrower->id }}" {{ $selectedBorrower == $borrower->id ? 'selected' : '' }}>
                                    {{ $borrower->first_name }} {{ $borrower->last_name }} ({{ $borrower->nida_no }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </form>
            </div>

            @if(session('error'))
            <div class="alert-error animate-slide-up">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0 mt-0.5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                {{ session('error') }}
            </div>
            @endif

            {{-- ── Pending Installments ────────────────────────────────── --}}
            @if($selectedBorrower && $installments->isEmpty())
                <div class="card p-12 text-center animate-slide-up">
                    <div class="mx-auto w-16 h-16 rounded-2xl bg-emerald-50 dark:bg-emerald-900/30 flex items-center justify-center mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <h3 class="text-lg font-bold text-slate-800 dark:text-white">All Caught Up!</h3>
                    <p class="text-slate-500 dark:text-slate-400 mt-1">This borrower has no pending installments.</p>
                </div>
            @endif

            @if($installments->isNotEmpty())
                <div class="card overflow-hidden animate-slide-up">
                    <div class="section-header">
                        <h3 class="section-title">Pending Installments</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table>
                            <thead>
                                <tr>
                                    <th>Due Date</th>
                                    <th>Expected</th>
                                    <th class="hidden sm:table-cell">Paid</th>
                                    <th>Remaining</th>
                                    <th class="w-48">Pay</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($installments as $installment)
                                    @php
                                        $remaining = $installment->expected_amount - $installment->amount_paid;
                                        $isOverdue = $installment->due_date->isPast();
                                    @endphp
                                    <tr class="{{ $isOverdue ? 'bg-red-50/30 dark:bg-red-900/10' : '' }}">
                                        <td class="{{ $isOverdue ? 'text-red-600 dark:text-red-400 font-semibold' : '' }}">
                                            {{ $installment->due_date->format('d M Y') }}
                                            @if($isOverdue) <span class="badge-defaulted text-[10px] ml-1">Overdue</span> @endif
                                        </td>
                                        <td class="font-medium text-slate-700 dark:text-slate-300">TZS {{ number_format($installment->expected_amount) }}</td>
                                        <td class="hidden sm:table-cell text-emerald-600 dark:text-emerald-400">TZS {{ number_format($installment->amount_paid) }}</td>
                                        <td class="font-bold text-slate-800 dark:text-slate-100">TZS {{ number_format($remaining) }}</td>
                                        <td>
                                            <form action="{{ route('payments.store') }}" method="POST" x-data="{ amount: {{ $remaining }} }" class="flex flex-col gap-2">
                                                @csrf
                                                <input type="hidden" name="installment_id" value="{{ $installment->id }}">
                                                
                                                <div class="flex gap-2">
                                                    <div class="relative flex-1">
                                                        <input type="number" step="0.01" name="amount" x-model="amount" max="{{ $remaining }}" 
                                                               class="!py-1.5 !px-2 !rounded-lg !text-sm" required>
                                                    </div>
                                                    <button type="submit" class="btn-success !py-1.5 !px-3 shrink-0 !text-sm">Pay</button>
                                                </div>
                                                
                                                <div x-show="amount < {{ $remaining }}" class="flex items-center gap-2" style="display: none;">
                                                    <span class="text-[10px] uppercase font-bold text-slate-400">Next:</span>
                                                    <input type="date" name="next_payment_date" class="!py-1 !px-2 !text-xs !rounded-md" :required="amount < {{ $remaining }}">
                                                </div>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
