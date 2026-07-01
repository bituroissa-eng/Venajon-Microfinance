<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h2 class="font-bold text-2xl text-slate-800 dark:text-white tracking-tight">Loans</h2>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-0.5">All loan records and statuses</p>
            </div>
            <a href="{{ route('loans.create') }}" class="btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/></svg>
                Process New Loan
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
            @if(session('error'))
            <div class="alert-error animate-slide-up">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0 mt-0.5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                {{ session('error') }}
            </div>
            @endif

            <div class="card overflow-hidden animate-slide-up">
                <div class="section-header flex-col items-start gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h3 class="section-title">All Loans</h3>
                        <p class="text-xs text-slate-400 dark:text-slate-500 mt-0.5">{{ $loans->total() }} total loans</p>
                    </div>
                    
                    {{-- Status Filters --}}
                    @php
                        $currentStatus = request()->query('status', 'All');
                        $statuses = ['All', 'Pending', 'Active', 'Completed', 'Closed'];
                        // Note: Declined doesn't exist as a state yet, but we'll include Closed which represents denied or finished in some systems, or we can just stick to these. Let's add 'Declined' as requested by the user, if they add it later.
                        $statuses = ['All', 'Pending', 'Active', 'Completed', 'Declined'];
                    @endphp
                    <div class="flex flex-wrap items-center gap-2">
                        @foreach($statuses as $st)
                            <a href="{{ route('loans.index', ['status' => $st]) }}"
                               class="px-3 py-1.5 text-xs font-semibold rounded-lg transition-colors border
                               {{ $currentStatus === $st 
                                    ? 'bg-primary-50 dark:bg-primary-900/40 text-primary-700 dark:text-primary-300 border-primary-200 dark:border-primary-800' 
                                    : 'bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-400 border-slate-200 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-700' }}">
                                {{ $st }}
                            </a>
                        @endforeach
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table>
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Borrower</th>
                                <th class="hidden md:table-cell">Category</th>
                                <th>Principal</th>
                                <th class="hidden lg:table-cell">Monthly</th>
                                <th>Status</th>
                                <th class="text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($loans as $loan)
                            <tr>
                                <td class="text-slate-400 dark:text-slate-500 font-mono text-xs w-12">{{ $loan->id }}</td>
                                <td>
                                    <div class="flex items-center gap-2.5">
                                        <div class="w-8 h-8 rounded-full bg-gradient-to-br from-primary-400 to-primary-600 flex items-center justify-center text-white text-xs font-bold shrink-0">
                                            {{ strtoupper(substr($loan->borrower->first_name ?? '?', 0, 1)) }}
                                        </div>
                                        <div>
                                            <p class="font-semibold text-slate-800 dark:text-slate-100 text-sm leading-tight">
                                                {{ $loan->borrower->first_name }} {{ $loan->borrower->last_name }}
                                            </p>
                                            <p class="text-xs text-slate-400 dark:text-slate-500 md:hidden">{{ $loan->category->name ?? '—' }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="hidden md:table-cell text-slate-600 dark:text-slate-300">{{ $loan->category->name ?? '—' }}</td>
                                <td class="font-bold text-slate-800 dark:text-slate-100">TZS {{ number_format($loan->principal_amount) }}</td>
                                <td class="hidden lg:table-cell text-slate-600 dark:text-slate-300">TZS {{ number_format($loan->monthly_payment ?? 0) }}</td>
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
                                    <div class="flex items-center justify-end gap-1.5 flex-wrap">
                                        <a href="{{ route('loans.show', $loan) }}" class="btn btn-sm bg-primary-50 dark:bg-primary-900/30 text-primary-700 dark:text-primary-300 hover:bg-primary-100">
                                            View
                                        </a>
                                        @if($loan->status === 'Pending')
                                            @hasanyrole('Admin|Manager')
                                            <form action="{{ route('loans.approve', $loan) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" onclick="return confirm('Approve this loan and generate installments?')"
                                                    class="btn btn-sm bg-emerald-50 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-300 hover:bg-emerald-100">
                                                    Approve
                                                </button>
                                            </form>
                                            @endhasanyrole
                                        @endif
                                        @hasanyrole('Admin|Manager')
                                        <form action="{{ route('loans.destroy', $loan) }}" method="POST" class="inline">
                                            @csrf @method('DELETE')
                                            <button type="submit" onclick="return confirm('Delete this loan permanently?')"
                                                class="btn btn-sm bg-red-50 dark:bg-red-900/30 text-red-700 dark:text-red-300 hover:bg-red-100">
                                                Delete
                                            </button>
                                        </form>
                                        @endhasanyrole
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="border-0">
                                    <div class="empty-state">
                                        <div class="empty-icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                        </div>
                                        <p class="text-slate-600 dark:text-slate-400 font-semibold">No loans found</p>
                                        <p class="text-sm text-slate-400 dark:text-slate-500">Process the first loan to get started.</p>
                                        <a href="{{ route('loans.create') }}" class="btn-primary mt-2">Process Loan</a>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($loans->hasPages())
                <div class="px-6 py-4 border-t border-slate-100 dark:border-slate-700">
                    {{ $loans->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
