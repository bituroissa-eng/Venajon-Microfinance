<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Installment;
use Carbon\Carbon;

class ApplyPenalties extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'loans:apply-penalties';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Apply penalties to overdue installments that have not yet been penalized';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $today = Carbon::today()->toDateString();

        // Find pending installments where due date has passed and no penalty has been applied yet
        $overdueInstallments = Installment::with('loan')
            ->where('status', 'Pending')
            ->where('due_date', '<', $today)
            ->where('penalty_amount', 0) // assuming we only apply it once
            ->get();

        $count = 0;

        foreach ($overdueInstallments as $installment) {
            $loan = $installment->loan;
            
            if ($loan && $loan->penalty_amount_per_month > 0) {
                // Add the penalty percent amount to the total monthly payment expected
                $penalty = $loan->penalty_amount_per_month;
                
                $installment->penalty_amount = $penalty;
                $installment->expected_amount = $installment->expected_amount + $penalty;
                $installment->save();
                
                $count++;
            }
        }

        $this->info("Successfully applied penalties to {$count} overdue installments.");
    }
}
