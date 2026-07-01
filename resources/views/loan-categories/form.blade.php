<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-slate-800 dark:text-slate-200 leading-tight tracking-tight">
            {{ isset($loanCategory) ? 'Edit Category' : 'Create Category' }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-slate-800 overflow-hidden shadow-xl sm:rounded-2xl border border-slate-100">
                <div class="p-6 text-slate-900 dark:text-slate-100 max-w-xl">
                    <form action="{{ isset($loanCategory) ? route('loan-categories.update', $loanCategory) : route('loan-categories.store') }}" method="POST">
                        @csrf
                        @if(isset($loanCategory))
                            @method('PUT')
                        @endif

                        <div class="mb-4">
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Category Name</label>
                            <input type="text" name="name" value="{{ old('name', $loanCategory->name ?? '') }}" class="mt-1 block w-full rounded-md border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900/50 shadow-inner focus:border-blue-500 focus:ring-blue-500 focus:bg-white dark:bg-slate-800 transition-colors duration-200" required>
                            @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Starting Amount</label>
                            <input type="number" step="0.01" name="starting_amount" value="{{ old('starting_amount', $loanCategory->starting_amount ?? '') }}" class="mt-1 block w-full rounded-md border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900/50 shadow-inner focus:border-blue-500 focus:ring-blue-500 focus:bg-white dark:bg-slate-800 transition-colors duration-200" required>
                            @error('starting_amount') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Ending Amount</label>
                            <input type="number" step="0.01" name="ending_amount" value="{{ old('ending_amount', $loanCategory->ending_amount ?? '') }}" class="mt-1 block w-full rounded-md border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900/50 shadow-inner focus:border-blue-500 focus:ring-blue-500 focus:bg-white dark:bg-slate-800 transition-colors duration-200" required>
                            @error('ending_amount') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        
                        <div class="mb-4">
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Mapped Loan Plan</label>
                            <select name="loan_plan_id" class="mt-1 block w-full rounded-md border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900/50 shadow-inner focus:border-blue-500 focus:ring-blue-500 focus:bg-white dark:bg-slate-800 transition-colors duration-200" required>
                                <option value="">Select Plan...</option>
                                @foreach($plans as $plan)
                                    <option value="{{ $plan->id }}" {{ (old('loan_plan_id', $loanCategory->loan_plan_id ?? '') == $plan->id) ? 'selected' : '' }}>
                                        {{ $plan->duration_months }} Months ({{ $plan->interest_percentage }}% Int)
                                    </option>
                                @endforeach
                            </select>
                            @error('loan_plan_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div class="flex items-center gap-4 mt-6">
                            <button type="submit" class="px-4 py-2 bg-gradient-to-r from-blue-600 to-blue-500 text-white rounded-lg hover:from-blue-700 hover:to-blue-600 shadow-md hover:shadow-lg transition-all duration-200 font-medium">Save</button>
                            <a href="{{ route('loan-categories.index') }}" class="text-gray-600 hover:underline">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
