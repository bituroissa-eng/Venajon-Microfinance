<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoanCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'starting_amount',
        'ending_amount',
        'loan_plan_id',
    ];

    public function plan()
    {
        return $this->belongsTo(LoanPlan::class, 'loan_plan_id');
    }

    public function loans()
    {
        return $this->hasMany(Loan::class);
    }
}
