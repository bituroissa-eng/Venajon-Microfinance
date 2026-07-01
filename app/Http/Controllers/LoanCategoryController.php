<?php

namespace App\Http\Controllers;

use App\Models\LoanCategory;
use App\Models\LoanPlan;
use Illuminate\Http\Request;

class LoanCategoryController extends Controller
{
    public function index()
    {
        $categories = LoanCategory::with('plan')->get();
        return view('loan-categories.index', compact('categories'));
    }

    public function create()
    {
        $plans = LoanPlan::all();
        return view('loan-categories.form', compact('plans'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'starting_amount' => 'required|numeric|min:0',
            'ending_amount' => 'required|numeric|min:0',
            'loan_plan_id' => 'required|exists:loan_plans,id',
        ]);

        LoanCategory::create($validated);
        return redirect()->route('loan-categories.index')->with('success', 'Loan Category created successfully.');
    }

    public function edit(LoanCategory $loanCategory)
    {
        $plans = LoanPlan::all();
        return view('loan-categories.form', compact('loanCategory', 'plans'));
    }

    public function update(Request $request, LoanCategory $loanCategory)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'starting_amount' => 'required|numeric|min:0',
            'ending_amount' => 'required|numeric|min:0',
            'loan_plan_id' => 'required|exists:loan_plans,id',
        ]);

        $loanCategory->update($validated);
        return redirect()->route('loan-categories.index')->with('success', 'Loan Category updated successfully.');
    }

    public function destroy(LoanCategory $loanCategory)
    {
        $loanCategory->delete();
        return redirect()->route('loan-categories.index')->with('success', 'Loan Category deleted successfully.');
    }
}
