<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoanPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'duration_months',
        'interest_percentage',
        'penalty_percentage',
    ];

    public function categories()
    {
        return $this->hasMany(LoanCategory::class);
    }
}
