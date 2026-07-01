<?php

namespace App\Http\Controllers;

use App\Models\LoanPlan;
use Illuminate\Http\Request;

class LoanPlanController extends Controller
{
    public function index()
    {
        $plans = LoanPlan::all();
        return view('loan-plans.index', compact('plans'));
    }

    public function create()
    {
        return view('loan-plans.form');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'duration_months' => 'required|integer|min:1',
            'interest_percentage' => 'required|numeric|min:0',
            'penalty_percentage' => 'required|numeric|min:0',
        ]);

        LoanPlan::create($validated);

        return redirect()->route('loan-plans.index')->with('success', 'Loan Plan created successfully.');
    }

    public function edit(LoanPlan $loanPlan)
    {
        return view('loan-plans.form', compact('loanPlan'));
    }

    public function update(Request $request, LoanPlan $loanPlan)
    {
        $validated = $request->validate([
            'duration_months' => 'required|integer|min:1',
            'interest_percentage' => 'required|numeric|min:0',
            'penalty_percentage' => 'required|numeric|min:0',
        ]);

        $loanPlan->update($validated);

        return redirect()->route('loan-plans.index')->with('success', 'Loan Plan updated successfully.');
    }

    public function destroy(LoanPlan $loanPlan)
    {
        $loanPlan->delete();
        return redirect()->route('loan-plans.index')->with('success', 'Loan Plan deleted successfully.');
    }
}
