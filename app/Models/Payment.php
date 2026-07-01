<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'installment_id',
        'amount',
        'payment_date',
        'processed_by_id',
    ];

    protected $casts = [
        'payment_date' => 'date',
    ];

    public function installment()
    {
        return $this->belongsTo(Installment::class);
    }

    public function processedBy()
    {
        return $this->belongsTo(User::class, 'processed_by_id');
    }
}
