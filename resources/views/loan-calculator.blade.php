<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-violet-500 to-violet-700 flex items-center justify-center shadow-md">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V4a2 2 0 00-2-2H6zm1 2a1 1 0 000 2h6a1 1 0 100-2H7zm6 7a1 1 0 011 1v3a1 1 0 11-2 0v-3a1 1 0 011-1zm-3 3a1 1 0 100 2h.01a1 1 0 100-2H10zm-4 1a1 1 0 011-1h.01a1 1 0 110 2H7a1 1 0 01-1-1zm1-4a1 1 0 100 2h.01a1 1 0 100-2H7zm2 1a1 1 0 011-1h.01a1 1 0 110 2H10a1 1 0 01-1-1zm4-4a1 1 0 100 2h.01a1 1 0 100-2H13zM9 9a1 1 0 011-1h.01a1 1 0 110 2H10a1 1 0 01-1-1zm-4 0a1 1 0 011-1h.01a1 1 0 110 2H7a1 1 0 01-1-1z" clip-rule="evenodd"/>
                </svg>
            </div>
            <div>
                <h2 class="font-bold text-2xl text-slate-800 dark:text-white leading-tight tracking-tight">Loan Calculator</h2>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-0.5">Estimate repayments before processing a loan</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- ── Input Card ──────────────────────────────────────────────── --}}
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 overflow-hidden">
                <div class="px-6 pt-6 pb-2">
                    <h3 class="text-base font-semibold text-slate-800 dark:text-white mb-1">Loan Parameters</h3>
                    <p class="text-sm text-slate-500 dark:text-slate-400">Fill in the details below to instantly see the full repayment breakdown.</p>
                </div>

                <div class="px-6 pt-6 pb-2">
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-3">
                        Calculation Method
                    </label>
                    <div class="flex flex-wrap items-center gap-5">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="calc_mode" value="predefined" class="w-4 h-4 text-violet-600 bg-slate-100 border-slate-300 focus:ring-violet-500 dark:focus:ring-violet-600 dark:ring-offset-slate-800 focus:ring-2 dark:bg-slate-700 dark:border-slate-600" checked>
                            <span class="text-sm text-slate-700 dark:text-slate-300">Use Pre-defined Plan</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="calc_mode" value="manual" class="w-4 h-4 text-violet-600 bg-slate-100 border-slate-300 focus:ring-violet-500 dark:focus:ring-violet-600 dark:ring-offset-slate-800 focus:ring-2 dark:bg-slate-700 dark:border-slate-600">
                            <span class="text-sm text-slate-700 dark:text-slate-300">Manual Input</span>
                        </label>
                    </div>
                </div>

                <div id="predefined-wrapper" class="px-6 pt-2 pb-2">
                    <label for="lc-category" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">
                        Select Plan
                    </label>
                    <select id="lc-category" class="w-full pl-4 pr-10 py-3 rounded-xl border border-slate-200 dark:border-slate-600 bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-transparent transition">
                        <option value="">-- Select a Plan --</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" data-rate="{{ $category->plan->interest_percentage }}" data-months="{{ $category->plan->duration_months }}">
                                {{ $category->name }} ({{ $category->plan->interest_percentage }}% per month, {{ $category->plan->duration_months }} months)
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="p-6 grid grid-cols-1 sm:grid-cols-3 gap-5">
                    {{-- Loan Amount --}}
                    <div class="space-y-1.5">
                        <label for="lc-amount" class="block text-sm font-semibold text-slate-700 dark:text-slate-300">
                            Loan Amount <span class="text-slate-400 font-normal"></span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                <span class="text-slate-400 dark:text-slate-500 font-medium text-sm"></span>
                            </div>
                            <input id="lc-amount" type="number" min="1" placeholder="e.g. 500,000"
                                class="w-full pl-14 pr-4 py-3 rounded-xl border border-slate-200 dark:border-slate-600 bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-transparent transition">
                        </div>
                    </div>

                    {{-- Interest Rate --}}
                    <div id="manual-rate-wrapper" class="space-y-1.5 hidden">
                        <label for="lc-rate" class="block text-sm font-semibold text-slate-700 dark:text-slate-300">
                            Interest Rate <span class="text-slate-400 font-normal">(% per month)</span>
                        </label>
                        <div class="relative">
                            <input id="lc-rate" type="number" min="0" step="0.01" placeholder="e.g. 5"
                                class="w-full pl-4 pr-10 py-3 rounded-xl border border-slate-200 dark:border-slate-600 bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-transparent transition">
                            <div class="absolute inset-y-0 right-0 pr-3.5 flex items-center pointer-events-none">
                                <span class="text-slate-400 dark:text-slate-500 font-medium text-sm">%</span>
                            </div>
                        </div>
                    </div>

                    {{-- Duration --}}
                    <div id="manual-months-wrapper" class="space-y-1.5 hidden">
                        <label for="lc-months" class="block text-sm font-semibold text-slate-700 dark:text-slate-300">
                            Loan Duration <span class="text-slate-400 font-normal">(months)</span>
                        </label>
                        <div class="relative">
                            <input id="lc-months" type="number" min="1" max="360" placeholder="e.g. 12"
                                class="w-full pl-4 pr-20 py-3 rounded-xl border border-slate-200 dark:border-slate-600 bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-transparent transition">
                            <div class="absolute inset-y-0 right-0 pr-3.5 flex items-center pointer-events-none">
                                <span class="text-slate-400 dark:text-slate-500 font-medium text-sm">months</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Calculate Button --}}
                <div class="px-6 pb-6">
                    <button id="lc-calculate"
                        class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-8 py-3 bg-gradient-to-r from-violet-600 to-violet-500 text-white rounded-xl font-semibold text-sm shadow-md hover:from-violet-700 hover:to-violet-600 hover:shadow-violet-500/30 active:scale-95 transition-all duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V4a2 2 0 00-2-2H6zm1 2a1 1 0 000 2h6a1 1 0 100-2H7zm6 7a1 1 0 011 1v3a1 1 0 11-2 0v-3a1 1 0 011-1zm-3 3a1 1 0 100 2h.01a1 1 0 100-2H10zm-4 1a1 1 0 011-1h.01a1 1 0 110 2H7a1 1 0 01-1-1zm1-4a1 1 0 100 2h.01a1 1 0 100-2H7zm2 1a1 1 0 011-1h.01a1 1 0 110 2H10a1 1 0 01-1-1zm4-4a1 1 0 100 2h.01a1 1 0 100-2H13zM9 9a1 1 0 011-1h.01a1 1 0 110 2H10a1 1 0 01-1-1zm-4 0a1 1 0 011-1h.01a1 1 0 110 2H7a1 1 0 01-1-1z" clip-rule="evenodd"/>
                        </svg>
                        Calculate Repayment
                    </button>
                </div>

                {{-- Validation error --}}
                <div id="lc-error" class="hidden mx-6 mb-6 flex items-center gap-2 p-3.5 bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-700 rounded-xl text-red-700 dark:text-red-300 text-sm font-medium">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                    <span id="lc-error-text"></span>
                </div>
            </div>

            {{-- ── Results Section ─────────────────────────────────────────── --}}
            <div id="lc-results" class="hidden space-y-6 animate-fade-in">

                {{-- Summary Cards --}}
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                    <div class="bg-white dark:bg-slate-800 rounded-2xl p-5 border border-slate-100 dark:border-slate-700 shadow-sm">
                        <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wide mb-1">Principal</p>
                        <p id="res-principal" class="text-xl font-bold text-slate-800 dark:text-white"></p>
                    </div>
                    <div class="bg-white dark:bg-slate-800 rounded-2xl p-5 border border-violet-200 dark:border-violet-800/60 shadow-sm bg-gradient-to-br from-white to-violet-50 dark:from-slate-800 dark:to-violet-900/20">
                        <p class="text-xs font-semibold text-violet-500 uppercase tracking-wide mb-1">Monthly Payment</p>
                        <p id="res-monthly" class="text-xl font-bold text-violet-700 dark:text-violet-300"></p>
                    </div>
                    <div class="bg-white dark:bg-slate-800 rounded-2xl p-5 border border-slate-100 dark:border-slate-700 shadow-sm">
                        <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wide mb-1">Total Interest</p>
                        <p id="res-interest" class="text-xl font-bold text-amber-600 dark:text-amber-400"></p>
                    </div>
                    <div class="bg-white dark:bg-slate-800 rounded-2xl p-5 border border-slate-100 dark:border-slate-700 shadow-sm">
                        <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wide mb-1">Total Repayment</p>
                        <p id="res-total" class="text-xl font-bold text-emerald-600 dark:text-emerald-400"></p>
                    </div>
                </div>

                {{-- Cost Bar --}}
                <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 border border-slate-100 dark:border-slate-700 shadow-sm">
                    <div class="flex items-center justify-between mb-3">
                        <p class="text-sm font-semibold text-slate-700 dark:text-slate-300">Repayment Breakdown</p>
                        <div class="flex items-center gap-4 text-xs text-slate-500 dark:text-slate-400">
                            <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-sm bg-violet-500 inline-block"></span> Principal</span>
                            <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-sm bg-amber-400 inline-block"></span> Interest</span>
                        </div>
                    </div>
                    <div class="h-5 rounded-full overflow-hidden bg-slate-100 dark:bg-slate-700 flex">
                        <div id="bar-principal" class="bg-gradient-to-r from-violet-500 to-violet-600 h-full transition-all duration-700 rounded-l-full"></div>
                        <div id="bar-interest" class="bg-gradient-to-r from-amber-400 to-amber-500 h-full transition-all duration-700 rounded-r-full"></div>
                    </div>
                    <div class="flex justify-between mt-2 text-xs text-slate-500 dark:text-slate-400">
                        <span id="bar-pct-principal"></span>
                        <span id="bar-pct-interest"></span>
                    </div>
                </div>

                {{-- Amortization Table --}}
                <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 overflow-hidden">
                    <div class="px-6 py-5 border-b border-slate-100 dark:border-slate-700 flex items-center justify-between">
                        <h3 class="text-base font-semibold text-slate-800 dark:text-white">Repayment Schedule</h3>
                        <span id="sched-count" class="text-xs font-semibold px-2.5 py-1 bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 rounded-full"></span>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full rounded-none shadow-none">
                            <thead>
                                <tr>
                                    <th class="text-left px-4 py-3 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wide bg-slate-50 dark:bg-slate-800/80 border-b border-slate-100 dark:border-slate-700">Month</th>
                                    <th class="text-right px-4 py-3 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wide bg-slate-50 dark:bg-slate-800/80 border-b border-slate-100 dark:border-slate-700">Payment</th>
                                    <th class="text-right px-4 py-3 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wide bg-slate-50 dark:bg-slate-800/80 border-b border-slate-100 dark:border-slate-700">Principal</th>
                                    <th class="text-right px-4 py-3 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wide bg-slate-50 dark:bg-slate-800/80 border-b border-slate-100 dark:border-slate-700">Interest</th>
                                    <th class="text-right px-4 py-3 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wide bg-slate-50 dark:bg-slate-800/80 border-b border-slate-100 dark:border-slate-700">Balance</th>
                                </tr>
                            </thead>
                            <tbody id="lc-table-body" class="divide-y divide-slate-50 dark:divide-slate-700/50"></tbody>
                        </table>
                    </div>
                </div>

                {{-- CTA --}}
                <div class="flex flex-col sm:flex-row gap-3">
                    <a href="{{ route('loans.create') }}"
                        class="flex-1 inline-flex items-center justify-center gap-2 px-6 py-3.5 bg-gradient-to-r from-primary-600 to-primary-500 text-white rounded-xl font-semibold text-sm shadow-md hover:from-primary-700 hover:to-primary-600 hover:shadow-primary-500/30 transition-all duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/></svg>
                        Process a Real Loan
                    </a>
                    <button onclick="resetCalculator()"
                        class="flex-1 sm:flex-none inline-flex items-center justify-center gap-2 px-6 py-3.5 bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-200 rounded-xl font-semibold text-sm hover:bg-slate-200 dark:hover:bg-slate-600 transition-all duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd"/></svg>
                        Reset
                    </button>
                </div>
            </div>

        </div>
    </div>

    <style>
        @keyframes fadeIn { from { opacity:0; transform: translateY(12px); } to { opacity:1; transform: translateY(0); } }
        .animate-fade-in { animation: fadeIn 0.4s ease both; }
    </style>

    <script>
    const fmt = n => 'TZS ' + Number(n).toLocaleString('en-US', {minimumFractionDigits:2, maximumFractionDigits:2});

    function resetCalculator() {
        document.getElementById('lc-category').value = '';
        document.getElementById('lc-amount').value = '';
        document.getElementById('lc-rate').value = '';
        document.getElementById('lc-months').value = '';
        document.getElementById('lc-results').classList.add('hidden');
        document.getElementById('lc-error').classList.add('hidden');
    }

    document.getElementById('lc-calculate').addEventListener('click', function () {
        const amount  = parseFloat(document.getElementById('lc-amount').value);
        const rate    = parseFloat(document.getElementById('lc-rate').value);    // % per month
        const months  = parseInt(document.getElementById('lc-months').value);

        const errDiv  = document.getElementById('lc-error');
        const errText = document.getElementById('lc-error-text');

        // Validate
        if (!amount || amount <= 0 || !months || months <= 0 || isNaN(rate) || rate < 0) {
            errText.textContent = 'Please enter a valid loan amount, interest rate (≥ 0), and duration (≥ 1 month).';
            errDiv.classList.remove('hidden');
            document.getElementById('lc-results').classList.add('hidden');
            return;
        }
        errDiv.classList.add('hidden');

        // ── Calculation ────────────────────────────────────────────────────
        // Total = Principal + (Principal × rate × months)  [flat-rate method]
        // Monthly = Total / months
        const r = rate / 100;
        let monthlyPayment, totalRepayment, totalInterest;

        if (r === 0) {
            // Zero-interest
            monthlyPayment = amount / months;
            totalRepayment = amount;
            totalInterest  = 0;
        } else {
            // Flat-rate interest (common in microfinance)
            totalInterest  = amount * r * months;
            totalRepayment = amount + totalInterest;
            monthlyPayment = totalRepayment / months;
        }

        // ── Summary Cards ──────────────────────────────────────────────────
        document.getElementById('res-principal').textContent = fmt(amount);
        document.getElementById('res-monthly').textContent   = fmt(monthlyPayment);
        document.getElementById('res-interest').textContent  = fmt(totalInterest);
        document.getElementById('res-total').textContent     = fmt(totalRepayment);

        // ── Breakdown Bar ──────────────────────────────────────────────────
        const pPct = totalRepayment > 0 ? (amount / totalRepayment * 100).toFixed(1) : 100;
        const iPct = (100 - parseFloat(pPct)).toFixed(1);
        document.getElementById('bar-principal').style.width = pPct + '%';
        document.getElementById('bar-interest').style.width  = iPct + '%';
        document.getElementById('bar-pct-principal').textContent = 'Principal ' + pPct + '%';
        document.getElementById('bar-pct-interest').textContent  = 'Interest ' + iPct + '%';

        // ── Amortization Table (flat-rate) ─────────────────────────────────
        const tbody = document.getElementById('lc-table-body');
        tbody.innerHTML = '';
        let balance = amount;
        const principalPerMonth = amount / months;
        const interestPerMonth  = amount * r;

        for (let i = 1; i <= months; i++) {
            const pay  = r === 0 ? monthlyPayment : principalPerMonth + interestPerMonth;
            const prin = principalPerMonth;
            const int  = interestPerMonth;
            balance   -= prin;

            const tr = document.createElement('tr');
            tr.className = 'hover:bg-slate-50 dark:hover:bg-slate-700/40 transition-colors';
            tr.innerHTML = `
                <td class="px-4 py-3 text-sm text-slate-800 dark:text-slate-200 font-medium">Month ${i}</td>
                <td class="px-4 py-3 text-sm text-slate-700 dark:text-slate-300 text-right font-semibold">${fmt(pay)}</td>
                <td class="px-4 py-3 text-sm text-violet-600 dark:text-violet-400 text-right">${fmt(prin)}</td>
                <td class="px-4 py-3 text-sm text-amber-600 dark:text-amber-400 text-right">${fmt(int)}</td>
                <td class="px-4 py-3 text-sm text-slate-600 dark:text-slate-400 text-right">${fmt(Math.max(0, balance))}</td>`;
            tbody.appendChild(tr);
        }

        document.getElementById('sched-count').textContent = months + ' installments';
        const results = document.getElementById('lc-results');
        results.classList.remove('hidden');
        results.classList.add('animate-fade-in');
        setTimeout(() => results.scrollIntoView({ behavior: 'smooth', block: 'start' }), 50);
    });

    // Allow Enter key to trigger calculate
    ['lc-amount','lc-rate','lc-months'].forEach(id => {
        document.getElementById(id).addEventListener('keydown', e => {
            if (e.key === 'Enter') document.getElementById('lc-calculate').click();
        });
    });
    
    // Toggle calculation mode
    const modeRadios = document.querySelectorAll('input[name="calc_mode"]');
    const predefinedWrapper = document.getElementById('predefined-wrapper');
    const manualRateWrapper = document.getElementById('manual-rate-wrapper');
    const manualMonthsWrapper = document.getElementById('manual-months-wrapper');
    const categorySelect = document.getElementById('lc-category');
    
    function updateCalcMode() {
        const mode = document.querySelector('input[name="calc_mode"]:checked').value;
        if (mode === 'manual') {
            predefinedWrapper.classList.add('hidden');
            manualRateWrapper.classList.remove('hidden');
            manualMonthsWrapper.classList.remove('hidden');
            categorySelect.value = ''; // clear dropdown selection
        } else {
            predefinedWrapper.classList.remove('hidden');
            manualRateWrapper.classList.add('hidden');
            manualMonthsWrapper.classList.add('hidden');
            // Trigger change if something is selected to update hidden inputs
            if (categorySelect.value) {
                categorySelect.dispatchEvent(new Event('change'));
            }
        }
    }

    modeRadios.forEach(r => r.addEventListener('change', updateCalcMode));

    // Auto-fill from selected category
    categorySelect.addEventListener('change', function() {
        const option = this.options[this.selectedIndex];
        if (option && option.value) {
            document.getElementById('lc-rate').value = option.dataset.rate;
            document.getElementById('lc-months').value = option.dataset.months;
        } else {
            document.getElementById('lc-rate').value = '';
            document.getElementById('lc-months').value = '';
        }
    });

    // Initialize mode
    updateCalcMode();
    </script>
</x-app-layout>
