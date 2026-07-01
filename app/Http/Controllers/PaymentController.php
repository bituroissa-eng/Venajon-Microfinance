<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Installment;
use App\Models\Borrower;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $payments = Payment::with(['installment.loan.borrower', 'processedBy'])->latest()->paginate(15);
        return view('payments.index', compact('payments'));
    }

    public function create(Request $request)
    {
        $borrowers = Borrower::has('loans')->get();
        $selectedBorrower = $request->query('borrower_id');
        
        $installments = collect();
        if ($selectedBorrower) {
            $installments = Installment::whereHas('loan', function($q) use ($selectedBorrower) {
                $q->where('borrower_id', $selectedBorrower)->where('status', 'Active');
            })->whereIn('status', ['Pending', 'Partial'])->orderBy('due_date', 'asc')->get();
        }

        return view('payments.create', compact('borrowers', 'selectedBorrower', 'installments'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'installment_id' => 'required|exists:installments,id',
            'amount' => 'required|numeric|min:1',
            'next_payment_date' => 'nullable|date|after_or_equal:today',
        ]);

        $installment = Installment::findOrFail($validated['installment_id']);
        
        $amountToPay = $validated['amount'];
        $remainingBalance = $installment->expected_amount - $installment->amount_paid;

        if ($amountToPay > $remainingBalance) {
            return back()->with('error', 'Payment amount exceeds the remaining balance for this installment.');
        }

        // Store payment record
        Payment::create([
            'installment_id' => $installment->id,
            'amount' => $amountToPay,
            'payment_date' => now(),
            'processed_by_id' => auth()->id(),
        ]);

        // Update installment
        $installment->amount_paid += $amountToPay;
        
        if ($installment->amount_paid >= $installment->expected_amount) {
            $installment->status = 'Paid';
        } else {
            $installment->status = 'Partial';
            if ($request->filled('next_payment_date')) {
                $installment->due_date = $request->next_payment_date;
            } else {
                return back()->with('error', 'Next payment date is required for partial payments.');
            }
        }
        
        $installment->save();

        // Check if all installments are paid, to mark loan as completed
        $loan = $installment->loan;
        $unpaidInstallments = $loan->installments()->where('status', '!=', 'Paid')->count();
        if ($unpaidInstallments === 0) {
            $loan->status = 'Completed';
            $loan->save();
        }

        return redirect()->route('payments.index')->with('success', 'Payment processed successfully.');
    }
}
