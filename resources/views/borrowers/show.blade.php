<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('borrowers.index') }}" class="p-2 rounded-xl text-slate-500 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"/></svg>
            </a>
            <div class="flex items-center gap-3">
                @if($borrower->picture_path)
                    <img src="{{ asset('storage/'.$borrower->picture_path) }}" class="w-10 h-10 rounded-xl object-cover ring-2 ring-primary-200 dark:ring-primary-800">
                @else
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-primary-400 to-primary-600 flex items-center justify-center text-white font-bold text-sm">
                        {{ strtoupper(substr($borrower->first_name, 0, 1)) }}
                    </div>
                @endif
                <div>
                    <h2 class="font-bold text-2xl text-slate-800 dark:text-white tracking-tight">{{ $borrower->first_name }} {{ $borrower->last_name }}</h2>
                    <p class="text-sm text-slate-500 dark:text-slate-400 mt-0.5">Borrower Profile</p>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="page-wrap">

            {{-- ── Top Info Cards ──────────────────────────────────────── --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-5 animate-slide-up">

                {{-- Borrower Card --}}
                <div class="card overflow-hidden">
                    <div class="section-header">
                        <h3 class="section-title">Borrower Information</h3>
                        <a href="{{ route('borrowers.edit', $borrower) }}" class="btn btn-sm bg-amber-50 dark:bg-amber-900/30 text-amber-700 dark:text-amber-300 hover:bg-amber-100">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor"><path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/></svg>
                            Edit
                        </a>
                    </div>
                    <div class="p-6 space-y-5">
                        <div class="flex items-center gap-4">
                            @if($borrower->picture_path)
                                <img src="{{ asset('storage/'.$borrower->picture_path) }}" class="w-20 h-20 rounded-2xl object-cover ring-4 ring-white dark:ring-slate-700 shadow-md">
                            @else
                                <div class="w-20 h-20 rounded-2xl bg-gradient-to-br from-primary-400 to-primary-600 flex items-center justify-center text-white text-2xl font-bold ring-4 ring-white dark:ring-slate-700 shadow-md">
                                    {{ strtoupper(substr($borrower->first_name, 0, 1)) }}
                                </div>
                            @endif
                            <div>
                                <p class="font-bold text-lg text-slate-800 dark:text-white leading-tight">{{ $borrower->first_name }} {{ $borrower->middle_name }} {{ $borrower->last_name }}</p>
                                <p class="text-sm text-slate-500 dark:text-slate-400 mt-0.5">{{ $borrower->phone }}</p>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="detail-item">
                                <p class="detail-label">Email</p>
                                <p class="detail-value">{{ $borrower->email ?? '—' }}</p>
                            </div>
                            <div class="detail-item">
                                <p class="detail-label">NIDA No.</p>
                                <p class="detail-value font-mono text-xs">{{ $borrower->nida_no }}</p>
                            </div>
                            <div class="detail-item col-span-2">
                                <p class="detail-label">Registered By</p>
                                <p class="detail-value">{{ $borrower->createdBy->name ?? 'Unknown' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Sponsor Card --}}
                <div class="card overflow-hidden">
                    <div class="section-header">
                        <h3 class="section-title">Sponsor / Guarantor</h3>
                    </div>
                    <div class="p-6 space-y-5">
                        <div class="flex items-center gap-4">
                            @if($borrower->sponsor_picture_path)
                                <img src="{{ asset('storage/'.$borrower->sponsor_picture_path) }}" class="w-20 h-20 rounded-2xl object-cover ring-4 ring-white dark:ring-slate-700 shadow-md">
                            @else
                                <div class="w-20 h-20 rounded-2xl bg-gradient-to-br from-violet-400 to-violet-600 flex items-center justify-center text-white text-2xl font-bold ring-4 ring-white dark:ring-slate-700 shadow-md">
                                    {{ strtoupper(substr($borrower->sponsor_name, 0, 1)) }}
                                </div>
                            @endif
                            <div>
                                <p class="font-bold text-lg text-slate-800 dark:text-white leading-tight">{{ $borrower->sponsor_name }}</p>
                                <p class="text-sm text-slate-500 dark:text-slate-400 mt-0.5">{{ $borrower->sponsor_phone }}</p>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 gap-4">
                            <div class="detail-item">
                                <p class="detail-label">NIDA No.</p>
                                <p class="detail-value font-mono text-xs">{{ $borrower->sponsor_nida }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── Loan History ────────────────────────────────────────── --}}
            <div class="card overflow-hidden animate-slide-up">
                <div class="section-header">
                    <div>
                        <h3 class="section-title">Loan History</h3>
                        <p class="text-xs text-slate-400 dark:text-slate-500 mt-0.5">{{ $borrower->loans->count() }} loan(s) on record</p>
                    </div>
                    <a href="{{ route('loans.create', ['borrower_id' => $borrower->id]) }}" class="btn-success">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/></svg>
                        New Loan
                    </a>
                </div>
                <div class="overflow-x-auto">
                    <table>
                        <thead>
                            <tr>
                                <th>Category</th>
                                <th>Principal</th>
                                <th>Total Repayment</th>
                                <th>Status</th>
                                <th class="text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($borrower->loans as $loan)
                            <tr>
                                <td class="font-medium text-slate-700 dark:text-slate-200">{{ $loan->category->name ?? '—' }}</td>
                                <td class="font-semibold text-slate-800 dark:text-slate-100">TZS {{ number_format($loan->principal_amount) }}</td>
                                <td class="text-slate-600 dark:text-slate-300">TZS {{ number_format($loan->total_amount) }}</td>
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
                                <td class="text-right">
                                    <a href="{{ route('loans.show', $loan) }}" class="btn btn-sm bg-primary-50 dark:bg-primary-900/30 text-primary-700 dark:text-primary-300 hover:bg-primary-100">View Details</a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="border-0">
                                    <div class="empty-state">
                                        <div class="empty-icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                        </div>
                                        <p class="text-slate-600 dark:text-slate-400 font-semibold">No loans yet</p>
                                        <a href="{{ route('loans.create', ['borrower_id' => $borrower->id]) }}" class="btn-success mt-2">Process First Loan</a>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
