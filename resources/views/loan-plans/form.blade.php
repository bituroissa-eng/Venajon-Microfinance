<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-slate-800 dark:text-slate-200 leading-tight tracking-tight">
            {{ isset($loanPlan) ? 'Edit Loan Plan' : 'Create Loan Plan' }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-slate-800 overflow-hidden shadow-xl sm:rounded-2xl border border-slate-100">
                <div class="p-6 text-slate-900 dark:text-slate-100 max-w-xl">
                    <form action="{{ isset($loanPlan) ? route('loan-plans.update', $loanPlan) : route('loan-plans.store') }}" method="POST">
                        @csrf
                        @if(isset($loanPlan))
                            @method('PUT')
                        @endif

                        <div class="mb-4">
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Duration (Months)</label>
                            <input type="number" name="duration_months" value="{{ old('duration_months', $loanPlan->duration_months ?? '') }}" class="mt-1 block w-full rounded-md border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900/50 shadow-inner focus:border-blue-500 focus:ring-blue-500 focus:bg-white dark:bg-slate-800 transition-colors duration-200" required>
                            @error('duration_months') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Interest Percentage (%)</label>
                            <input type="number" step="0.01" name="interest_percentage" value="{{ old('interest_percentage', $loanPlan->interest_percentage ?? '') }}" class="mt-1 block w-full rounded-md border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900/50 shadow-inner focus:border-blue-500 focus:ring-blue-500 focus:bg-white dark:bg-slate-800 transition-colors duration-200" required>
                            @error('interest_percentage') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Penalty Percentage (%)</label>
                            <input type="number" step="0.01" name="penalty_percentage" value="{{ old('penalty_percentage', $loanPlan->penalty_percentage ?? '') }}" class="mt-1 block w-full rounded-md border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900/50 shadow-inner focus:border-blue-500 focus:ring-blue-500 focus:bg-white dark:bg-slate-800 transition-colors duration-200" required>
                            @error('penalty_percentage') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div class="flex items-center gap-4 mt-6">
                            <button type="submit" class="px-4 py-2 bg-gradient-to-r from-blue-600 to-blue-500 text-white rounded-lg hover:from-blue-700 hover:to-blue-600 shadow-md hover:shadow-lg transition-all duration-200 font-medium">Save</button>
                            <a href="{{ route('loan-plans.index') }}" class="text-gray-600 hover:underline">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
