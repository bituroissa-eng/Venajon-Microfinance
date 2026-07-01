<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    use HasFactory;

    protected $fillable = [
        'borrower_id',
        'loan_category_id',
        'principal_amount',
        'total_amount',
        'monthly_payment',
        'penalty_amount_per_month',
        'status',
        'processed_by_id',
        'approved_by_id',
    ];

    public function borrower()
    {
        return $this->belongsTo(Borrower::class);
    }

    public function category()
    {
        return $this->belongsTo(LoanCategory::class, 'loan_category_id');
    }

    public function processedBy()
    {
        return $this->belongsTo(User::class, 'processed_by_id');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by_id');
    }

    public function installments()
    {
        return $this->hasMany(Installment::class);
    }
}
