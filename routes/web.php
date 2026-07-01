<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LoanPlanController;
use App\Http\Controllers\LoanCategoryController;
use App\Http\Controllers\BorrowerController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\SystemSettingController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Loan Calculator
    Route::get('/loan-calculator', function () {
        $categories = \App\Models\LoanCategory::with('plan')->get();
        return view('loan-calculator', compact('categories'));
    })->name('loan-calculator');

    // Common routes for Admin, Manager, Loan Officer
    Route::resource('borrowers', BorrowerController::class);
    Route::resource('loans', LoanController::class);
    Route::post('loans/{loan}/approve', [LoanController::class, 'approve'])->name('loans.approve');
    Route::resource('payments', PaymentController::class);

    // Routes for Admin and Manager
    Route::middleware('role:Admin|Manager')->group(function () {
        Route::resource('loan-plans', LoanPlanController::class);
        Route::resource('loan-categories', LoanCategoryController::class);
        Route::resource('users', UserController::class);
    });

    // Routes for Admin only
    Route::middleware('role:Admin')->group(function () {
        Route::resource('system-settings', SystemSettingController::class);
    });
});

require __DIR__.'/auth.php';
