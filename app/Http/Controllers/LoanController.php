<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Models\Borrower;
use App\Models\LoanCategory;
use App\Models\Installment;
use Illuminate\Http\Request;
use Carbon\Carbon;

class LoanController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status');
        $query = Loan::with(['borrower', 'category.plan'])->latest();
        
        if ($status && $status !== 'All') {
            $query->where('status', $status);
        }
        
        $loans = $query->paginate(15)->appends(['status' => $status]);
        return view('loans.index', compact('loans', 'status'));
    }

    public function create(Request $request)
    {
        $borrowers = Borrower::all();
        $categories = LoanCategory::with('plan')->get();
        $selectedBorrower = $request->query('borrower_id');
        return view('loans.create', compact('borrowers', 'categories', 'selectedBorrower'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'borrower_id' => 'required|exists:borrowers,id',
            'principal_amount' => 'required|numeric|min:1',
        ]);

        $principal = $validated['principal_amount'];

        // Find the category it falls into
        $category = LoanCategory::where('starting_amount', '<=', $principal)
            ->where('ending_amount', '>=', $principal)
            ->first();

        if (!$category) {
            return back()->with('error', 'No loan category found for the specified amount. Please adjust the amount or create a suitable category.');
        }

        $plan = $category->plan;

        // Calculations
        // Total amount (T) = loan amount + (loan amount * (rate/100) * duration)
        $totalAmount = $principal + ($principal * ($plan->interest_percentage / 100) * $plan->duration_months);
        
        // Monthly payment = Total amount to be payed / duration
        $monthlyPayment = $totalAmount / $plan->duration_months;

        // Penalty = loan amount * (penalty/100)
        $penaltyPerMonth = $principal * ($plan->penalty_percentage / 100);

        $loan = Loan::create([
            'borrower_id' => $validated['borrower_id'],
            'loan_category_id' => $category->id,
            'principal_amount' => $principal,
            'total_amount' => $totalAmount,
            'monthly_payment' => $monthlyPayment,
            'penalty_amount_per_month' => $penaltyPerMonth,
            'status' => 'Pending',
            'processed_by_id' => auth()->id(),
        ]);

        return redirect()->route('loans.index')->with('success', 'Loan processed and sent for approval.');
    }

    public function show(Loan $loan)
    {
        $loan->load(['borrower', 'category.plan', 'installments.payments', 'processedBy', 'approvedBy']);
        return view('loans.show', compact('loan'));
    }

    public function approve(Loan $loan)
    {
        // Only managers or admins can approve
        if (!auth()->user()->hasAnyRole(['Admin', 'Manager'])) {
            return back()->with('error', 'Unauthorized to approve loans.');
        }

        if ($loan->status !== 'Pending') {
            return back()->with('error', 'Loan is not in pending status.');
        }

        $loan->update([
            'status' => 'Active',
            'approved_by_id' => auth()->id(),
        ]);

        // Generate installments
        $duration = $loan->category->plan->duration_months;
        $dueDate = Carbon::now()->addMonth();

        for ($i = 1; $i <= $duration; $i++) {
            Installment::create([
                'loan_id' => $loan->id,
                'expected_amount' => $loan->monthly_payment,
                'due_date' => $dueDate->copy(),
                'status' => 'Pending',
            ]);
            $dueDate->addMonth();
        }

        return back()->with('success', 'Loan approved and installments generated.');
    }

    public function destroy(Loan $loan)
    {
        if (!auth()->user()->hasAnyRole(['Admin', 'Manager'])) {
            return back()->with('error', 'Unauthorized to delete loans.');
        }

        $loan->delete();
        return redirect()->route('loans.index')->with('success', 'Loan deleted successfully.');
    }
}
