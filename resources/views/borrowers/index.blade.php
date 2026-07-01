<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h2 class="font-bold text-2xl text-slate-800 dark:text-white tracking-tight">Borrowers</h2>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-0.5">Manage all registered borrowers</p>
            </div>
            <a href="{{ route('borrowers.create') }}" class="btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path d="M8 9a3 3 0 100-6 3 3 0 000 6zM8 11a6 6 0 016 6H2a6 6 0 016-6zM16 7a1 1 0 10-2 0v1h-1a1 1 0 100 2h1v1a1 1 0 102 0v-1h1a1 1 0 100-2h-1V7z"/></svg>
                Register Borrower
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
                <div class="section-header">
                    <div>
                        <h3 class="section-title">All Borrowers</h3>
                        <p class="text-xs text-slate-400 dark:text-slate-500 mt-0.5">{{ $borrowers->total() }} total registrations</p>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table>
                        <thead>
                            <tr>
                                <th>Borrower</th>
                                <th>Phone</th>
                                <th class="hidden sm:table-cell">Email</th>
                                <th class="hidden md:table-cell">NIDA No.</th>
                                <th class="hidden lg:table-cell">Registered By</th>
                                <th class="text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($borrowers as $borrower)
                            <tr>
                                <td>
                                    <div class="flex items-center gap-3">
                                        @if($borrower->photo ?? $borrower->picture_path)
                                            <img src="{{ asset('storage/'.($borrower->photo ?? $borrower->picture_path)) }}"
                                                 alt="Photo" class="w-9 h-9 rounded-full object-cover ring-2 ring-white dark:ring-slate-700 shadow-sm">
                                        @else
                                            <div class="w-9 h-9 rounded-full bg-gradient-to-br from-primary-400 to-primary-600 flex items-center justify-center text-white text-sm font-bold ring-2 ring-white dark:ring-slate-700 shadow-sm">
                                                {{ strtoupper(substr($borrower->first_name, 0, 1)) }}
                                            </div>
                                        @endif
                                        <div>
                                            <p class="font-semibold text-slate-800 dark:text-slate-100 text-sm leading-tight">
                                                {{ $borrower->first_name }} {{ $borrower->middle_name }} {{ $borrower->last_name }}
                                            </p>
                                            <p class="text-xs text-slate-400 dark:text-slate-500 sm:hidden">{{ $borrower->phone }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="hidden sm:table-cell text-slate-600 dark:text-slate-300">{{ $borrower->phone }}</td>
                                <td class="hidden sm:table-cell text-slate-500 dark:text-slate-400">{{ $borrower->email ?? '—' }}</td>
                                <td class="hidden md:table-cell font-mono text-xs text-slate-500 dark:text-slate-400">{{ $borrower->nida_no }}</td>
                                <td class="hidden lg:table-cell text-slate-500 dark:text-slate-400 text-xs">{{ $borrower->createdBy->name ?? '—' }}</td>
                                <td class="text-right">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <a href="{{ route('borrowers.show', $borrower) }}" class="btn btn-sm bg-primary-50 dark:bg-primary-900/30 text-primary-700 dark:text-primary-300 hover:bg-primary-100 dark:hover:bg-primary-900/50">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor"><path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/><path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/></svg>
                                            View
                                        </a>
                                        <a href="{{ route('borrowers.edit', $borrower) }}" class="btn btn-sm bg-amber-50 dark:bg-amber-900/30 text-amber-700 dark:text-amber-300 hover:bg-amber-100 dark:hover:bg-amber-900/50">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor"><path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/></svg>
                                            Edit
                                        </a>
                                        @hasanyrole('Admin|Manager')
                                        <form action="{{ route('borrowers.destroy', $borrower) }}" method="POST" class="inline">
                                            @csrf @method('DELETE')
                                            <button type="submit" onclick="return confirm('Delete this borrower?')" class="btn btn-sm bg-red-50 dark:bg-red-900/30 text-red-700 dark:text-red-300 hover:bg-red-100 dark:hover:bg-red-900/50">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                                Delete
                                            </button>
                                        </form>
                                        @endhasanyrole
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="border-0">
                                    <div class="empty-state">
                                        <div class="empty-icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                        </div>
                                        <p class="text-slate-600 dark:text-slate-400 font-semibold">No borrowers registered yet</p>
                                        <p class="text-sm text-slate-400 dark:text-slate-500">Get started by registering your first borrower.</p>
                                        <a href="{{ route('borrowers.create') }}" class="btn-primary mt-2">Register Borrower</a>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($borrowers->hasPages())
                <div class="px-6 py-4 border-t border-slate-100 dark:border-slate-700">
                    {{ $borrowers->links() }}
                </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
