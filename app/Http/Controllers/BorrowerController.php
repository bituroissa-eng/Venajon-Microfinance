<?php

namespace App\Http\Controllers;

use App\Models\Borrower;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BorrowerController extends Controller
{
    public function index()
    {
        $borrowers = Borrower::with('createdBy')->latest()->paginate(15);
        return view('borrowers.index', compact('borrowers'));
    }

    public function create()
    {
        return view('borrowers.form');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'nida_no' => 'required|string|max:100',
            'picture' => 'nullable|image|max:2048',
            'sponsor_name' => 'required|string|max:255',
            'sponsor_phone' => 'required|string|max:20',
            'sponsor_nida' => 'required|string|max:100',
            'sponsor_picture' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('picture')) {
            $validated['picture_path'] = $request->file('picture')->store('borrowers', 'public');
        }

        if ($request->hasFile('sponsor_picture')) {
            $validated['sponsor_picture_path'] = $request->file('sponsor_picture')->store('sponsors', 'public');
        }

        $validated['created_by'] = auth()->id();

        Borrower::create($validated);
        return redirect()->route('borrowers.index')->with('success', 'Borrower registered successfully.');
    }

    public function show(Borrower $borrower)
    {
        return view('borrowers.show', compact('borrower'));
    }

    public function edit(Borrower $borrower)
    {
        return view('borrowers.form', compact('borrower'));
    }

    public function update(Request $request, Borrower $borrower)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'nida_no' => 'required|string|max:100',
            'picture' => 'nullable|image|max:2048',
            'sponsor_name' => 'required|string|max:255',
            'sponsor_phone' => 'required|string|max:20',
            'sponsor_nida' => 'required|string|max:100',
            'sponsor_picture' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('picture')) {
            if ($borrower->picture_path) Storage::disk('public')->delete($borrower->picture_path);
            $validated['picture_path'] = $request->file('picture')->store('borrowers', 'public');
        }

        if ($request->hasFile('sponsor_picture')) {
            if ($borrower->sponsor_picture_path) Storage::disk('public')->delete($borrower->sponsor_picture_path);
            $validated['sponsor_picture_path'] = $request->file('sponsor_picture')->store('sponsors', 'public');
        }

        $borrower->update($validated);
        return redirect()->route('borrowers.index')->with('success', 'Borrower updated successfully.');
    }

    public function destroy(Borrower $borrower)
    {
        // Only Admin or Manager should be able to delete, or enforce it in policy/middleware
        if (!auth()->user()->hasAnyRole(['Admin', 'Manager'])) {
            return back()->with('error', 'Unauthorized to delete borrowers.');
        }

        if ($borrower->picture_path) Storage::disk('public')->delete($borrower->picture_path);
        if ($borrower->sponsor_picture_path) Storage::disk('public')->delete($borrower->sponsor_picture_path);

        $borrower->delete();
        return redirect()->route('borrowers.index')->with('success', 'Borrower deleted successfully.');
    }
}
