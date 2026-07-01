<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('loans.index') }}" class="p-2 rounded-xl text-slate-500 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"/></svg>
            </a>
            <div>
                <h2 class="font-bold text-2xl text-slate-800 dark:text-white tracking-tight">Process New Loan</h2>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-0.5">Issue a new loan to a registered borrower</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            @if(session('error'))
            <div class="alert-error animate-slide-up">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0 mt-0.5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                {{ session('error') }}
            </div>
            @endif

            <form action="{{ route('loans.store') }}" method="POST" class="space-y-5">
                @csrf
                <div class="card p-6 space-y-6 animate-slide-up">
                    <p class="form-section-title">
                        <span class="inline-flex items-center gap-2">
                            <span class="w-5 h-5 rounded-md bg-blue-100 dark:bg-blue-900/40 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 text-blue-600 dark:text-blue-400" viewBox="0 0 20 20" fill="currentColor"><path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"/><path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd"/></svg>
                            </span>
                            Loan Application
                        </span>
                    </p>

                    <div class="field">
                        <label for="borrower_id">Select Borrower <span class="text-red-500">*</span></label>
                        <select id="borrower_id" name="borrower_id" required>
                            <option value="">Choose a borrower...</option>
                            @foreach($borrowers as $borrower)
                                <option value="{{ $borrower->id }}" {{ (old('borrower_id', $selectedBorrower) == $borrower->id) ? 'selected' : '' }}>
                                    {{ $borrower->first_name }} {{ $borrower->last_name }} ({{ $borrower->phone }})
                                </option>
                            @endforeach
                        </select>
                        @error('borrower_id')<p class="field-error">{{ $message }}</p>@enderror
                    </div>

                    <div class="field">
                        <label for="principal_amount">Principal Amount (TZS) <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                <span class="text-slate-400 dark:text-slate-500 font-semibold sm:text-sm"></span>
                            </div>
                            <input type="number" step="1000" id="principal_amount" name="principal_amount" 
                                   value="{{ old('principal_amount') }}" placeholder="0.00" class="pl-14" required>
                        </div>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1.5 flex items-start gap-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0 text-blue-500" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>
                            The system will automatically categorize the loan based on the amount and calculate the correct interest and installments.
                        </p>
                        @error('principal_amount')<p class="field-error">{{ $message }}</p>@enderror
                    </div>

                </div>

                {{-- Preview Section --}}
                <div id="loan-preview" class="hidden card p-6 space-y-4 animate-slide-up border-l-4 border-l-violet-500">
                    <p class="font-semibold text-slate-800 dark:text-white flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-violet-500" viewBox="0 0 20 20" fill="currentColor"><path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/><path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/></svg>
                        Loan Summary & Schedule
                    </p>
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 text-sm">
                        <div>
                            <p class="text-slate-500 dark:text-slate-400">Category</p>
                            <p id="prev-category" class="font-semibold text-slate-800 dark:text-white"></p>
                        </div>
                        <div>
                            <p class="text-slate-500 dark:text-slate-400">Total Interest</p>
                            <p id="prev-interest" class="font-semibold text-amber-600 dark:text-amber-400"></p>
                        </div>
                        <div>
                            <p class="text-slate-500 dark:text-slate-400">Total Repayment</p>
                            <p id="prev-total" class="font-semibold text-emerald-600 dark:text-emerald-400"></p>
                        </div>
                        <div>
                            <p class="text-slate-500 dark:text-slate-400">Monthly Installment</p>
                            <p id="prev-monthly" class="font-semibold text-violet-600 dark:text-violet-400"></p>
                        </div>
                    </div>
                    <p class="text-xs text-slate-500 mt-2">Please confirm the details above before submitting the application.</p>
                </div>

                <div class="flex items-center gap-3 animate-slide-up">
                    <button type="button" id="preview-btn" class="btn-primary bg-slate-800 hover:bg-slate-700 text-white border-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/></svg>
                        Calculate & Preview
                    </button>
                    <button type="submit" id="submit-btn" class="btn-primary hidden">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        Confirm & Submit
                    </button>
                    <a href="{{ route('loans.index') }}" class="btn-ghost">Cancel</a>
                </div>
            </form>
        </div>
    </div>

    <script>
        const categories = @json($categories);
        const fmt = n => 'TZS ' + Number(n).toLocaleString('en-US', {minimumFractionDigits:2, maximumFractionDigits:2});

        document.getElementById('preview-btn').addEventListener('click', function() {
            const amountInput = document.getElementById('principal_amount');
            const amount = parseFloat(amountInput.value);

            if (!amount || amount <= 0) {
                alert('Please enter a valid principal amount.');
                return;
            }

            // Find matching category
            const category = categories.find(c => amount >= c.starting_amount && amount <= c.ending_amount);

            if (!category) {
                alert('No loan category found for this amount. Please adjust the amount.');
                return;
            }

            const plan = category.plan;
            const r = plan.interest_percentage / 100;
            const months = plan.duration_months;

            const totalInterest = amount * r * months;
            const totalRepayment = amount + totalInterest;
            const monthlyPayment = totalRepayment / months;

            // Fill preview
            document.getElementById('prev-category').textContent = category.name + ' (' + plan.duration_months + ' mos)';
            document.getElementById('prev-interest').textContent = fmt(totalInterest);
            document.getElementById('prev-total').textContent = fmt(totalRepayment);
            document.getElementById('prev-monthly').textContent = fmt(monthlyPayment);

            // Show preview and submit btn
            document.getElementById('loan-preview').classList.remove('hidden');
            document.getElementById('submit-btn').classList.remove('hidden');
            this.classList.add('hidden');
        });

        // Hide preview if amount changes
        document.getElementById('principal_amount').addEventListener('input', function() {
            document.getElementById('loan-preview').classList.add('hidden');
            document.getElementById('submit-btn').classList.add('hidden');
            document.getElementById('preview-btn').classList.remove('hidden');
        });
    </script>
</x-app-layout>
