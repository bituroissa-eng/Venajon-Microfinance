<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('loans.index') }}" class="p-2 rounded-xl text-slate-500 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"/></svg>
            </a>
            <div>
                <h2 class="font-bold text-2xl text-slate-800 dark:text-white tracking-tight">Loan #{{ $loan->id }}</h2>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-0.5">Processed {{ $loan->created_at->format('d M Y') }}</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="page-wrap">

            @if(session('success'))
            <div class="alert-success animate-slide-up">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                {{ session('success') }}
            </div>
            @endif
            @if(session('error'))
            <div class="alert-error animate-slide-up">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                {{ session('error') }}
            </div>
            @endif

            {{-- ── Status Banner ─────────────────────────────────────── --}}
            @php
                $bannerCls = match($loan->status) {
                    'Pending'   => 'from-amber-50 to-amber-100/50 dark:from-amber-900/20 dark:to-amber-900/10 border-amber-200 dark:border-amber-800/40',
                    'Active'    => 'from-blue-50 to-blue-100/50 dark:from-blue-900/20 dark:to-blue-900/10 border-blue-200 dark:border-blue-800/40',
                    'Completed','Closed' => 'from-emerald-50 to-emerald-100/50 dark:from-emerald-900/20 dark:to-emerald-900/10 border-emerald-200 dark:border-emerald-800/40',
                    'Defaulted' => 'from-red-50 to-red-100/50 dark:from-red-900/20 dark:to-red-900/10 border-red-200 dark:border-red-800/40',
                    default     => 'from-slate-50 to-slate-100/50 dark:from-slate-800 dark:to-slate-700 border-slate-200 dark:border-slate-700',
                };
                $badgeCls = match($loan->status) {
                    'Pending'   => 'badge-pending',
                    'Active'    => 'badge-active',
                    'Completed','Closed' => 'badge-paid',
                    'Defaulted' => 'badge-defaulted',
                    default     => 'badge-closed',
                };
            @endphp
            <div class="rounded-2xl border bg-gradient-to-r {{ $bannerCls }} p-5 flex items-center justify-between gap-4 animate-slide-up">
                <div class="flex items-center gap-3">
                    <span class="{{ $badgeCls }} text-sm px-3 py-1.5">{{ $loan->status }}</span>
                    <span class="text-sm text-slate-600 dark:text-slate-300 font-medium">
                        Loan for <strong>{{ $loan->borrower->first_name }} {{ $loan->borrower->last_name }}</strong>
                    </span>
                </div>
                @if($loan->status === 'Pending')
                    @hasanyrole('Admin|Manager')
                    <form action="{{ route('loans.approve', $loan) }}" method="POST">
                        @csrf
                        <button type="submit" onclick="return confirm('Approve this loan and generate all installments?')" class="btn-success">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                            Approve & Activate
                        </button>
                    </form>
                    @endhasanyrole
                @endif
            </div>

            {{-- ── Info Grid ─────────────────────────────────────────── --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">

                {{-- Borrower --}}
                <div class="card overflow-hidden animate-slide-up">
                    <div class="section-header">
                        <h3 class="section-title">Borrower</h3>
                        <a href="{{ route('borrowers.show', $loan->borrower) }}" class="btn btn-sm bg-primary-50 dark:bg-primary-900/30 text-primary-700 dark:text-primary-300 hover:bg-primary-100">Profile →</a>
                    </div>
                    <div class="p-6 grid grid-cols-2 gap-4">
                        <div class="detail-item col-span-2">
                            <p class="detail-label">Full Name</p>
                            <p class="detail-value text-base">{{ $loan->borrower->first_name }} {{ $loan->borrower->last_name }}</p>
                        </div>
                        <div class="detail-item">
                            <p class="detail-label">Phone</p>
                            <p class="detail-value">{{ $loan->borrower->phone }}</p>
                        </div>
                        <div class="detail-item">
                            <p class="detail-label">NIDA</p>
                            <p class="detail-value font-mono text-xs">{{ $loan->borrower->nida_no }}</p>
                        </div>
                        <div class="detail-item">
                            <p class="detail-label">Processed By</p>
                            <p class="detail-value">{{ $loan->processedBy->name ?? '—' }}</p>
                        </div>
                        <div class="detail-item">
                            <p class="detail-label">Approved By</p>
                            <p class="detail-value">{{ $loan->approvedBy->name ?? '—' }}</p>
                        </div>
                    </div>
                </div>

                {{-- Financial --}}
                <div class="card overflow-hidden animate-slide-up">
                    <div class="section-header">
                        <h3 class="section-title">Financial Details</h3>
                    </div>
                    <div class="p-6 grid grid-cols-2 gap-4">
                        <div class="detail-item">
                            <p class="detail-label">Category</p>
                            <p class="detail-value">{{ $loan->category->name }}</p>
                        </div>
                        <div class="detail-item">
                            <p class="detail-label">Duration</p>
                            <p class="detail-value">{{ $loan->category->plan->duration_months }} months</p>
                        </div>
                        <div class="detail-item">
                            <p class="detail-label">Principal</p>
                            <p class="detail-value text-base text-primary-700 dark:text-primary-300">TZS {{ number_format($loan->principal_amount) }}</p>
                        </div>
                        <div class="detail-item">
                            <p class="detail-label">Total Repayment</p>
                            <p class="detail-value text-base text-slate-800 dark:text-slate-100">TZS {{ number_format($loan->total_amount) }}</p>
                        </div>
                        <div class="detail-item">
                            <p class="detail-label">Monthly Payment</p>
                            <p class="detail-value text-emerald-600 dark:text-emerald-400">TZS {{ number_format($loan->monthly_payment) }}</p>
                        </div>
                        <div class="detail-item">
                            <p class="detail-label">Penalty / Month</p>
                            <p class="detail-value text-red-600 dark:text-red-400">{{ $loan->category->plan->penalty_percentage }}% (TZS {{ number_format($loan->penalty_amount_per_month) }})</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── Installment Schedule ──────────────────────────────── --}}
            @if($loan->status !== 'Pending')
            <div class="card overflow-hidden animate-slide-up">
                <div class="section-header">
                    <div>
                        <h3 class="section-title">Installment Schedule</h3>
                        <p class="text-xs text-slate-400 dark:text-slate-500 mt-0.5">
                            {{ $loan->installments->where('status','Paid')->count() }} of {{ $loan->installments->count() }} paid
                        </p>
                    </div>
                    @php
                        $paidCount = $loan->installments->where('status','Paid')->count();
                        $total = $loan->installments->count();
                        $pct = $total > 0 ? round($paidCount / $total * 100) : 0;
                    @endphp
                    <div class="hidden sm:flex items-center gap-3">
                        <div class="w-32 h-2 bg-slate-100 dark:bg-slate-700 rounded-full overflow-hidden">
                            <div class="h-full bg-gradient-to-r from-emerald-500 to-emerald-400 rounded-full transition-all duration-700" style="width: {{ $pct }}%"></div>
                        </div>
                        <span class="text-sm font-semibold text-emerald-600 dark:text-emerald-400">{{ $pct }}%</span>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table>
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Due Date</th>
                                <th>Expected</th>
                                <th>Paid</th>
                                <th class="hidden md:table-cell">Penalty</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($loan->installments as $idx => $inst)
                            @php
                                $isOverdue = $inst->status !== 'Paid' && $inst->due_date->isPast();
                            @endphp
                            <tr class="{{ $isOverdue ? 'bg-red-50/50 dark:bg-red-900/10' : '' }}">
                                <td class="text-slate-400 text-xs w-10">{{ $idx + 1 }}</td>
                                <td class="{{ $isOverdue ? 'text-red-600 dark:text-red-400 font-semibold' : '' }}">
                                    {{ $inst->due_date->format('d M Y') }}
                                    @if($isOverdue) <span class="badge-defaulted text-[10px] ml-1">Overdue</span> @endif
                                </td>
                                <td class="font-medium">TZS {{ number_format($inst->expected_amount) }}</td>
                                <td class="text-emerald-600 dark:text-emerald-400 font-medium">TZS {{ number_format($inst->amount_paid) }}</td>
                                <td class="hidden md:table-cell text-red-500 dark:text-red-400">
                                    {{ $inst->penalty_amount > 0 ? 'TZS '.number_format($inst->penalty_amount) : '—' }}
                                </td>
                                <td>
                                    @php
                                        $ic = match($inst->status) {
                                            'Paid'    => 'badge-paid',
                                            'Partial' => 'badge-partial',
                                            default   => 'badge-pending',
                                        };
                                    @endphp
                                    <span class="{{ $ic }}">{{ $inst->status }}</span>
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
