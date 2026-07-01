<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (\Illuminate\Support\Facades\Schema::hasTable('system_settings')) {
            $systemSetting = \App\Models\SystemSetting::first();
            \Illuminate\Support\Facades\View::share('systemSetting', $systemSetting);
        }

        \Illuminate\Support\Facades\View::composer('layouts.navigation', function ($view) {
            if (\Illuminate\Support\Facades\Schema::hasTable('installments')) {
                $fiveDaysFromNow = \Carbon\Carbon::now()->addDays(5)->toDateString();
                $dueInstallments = \App\Models\Installment::with('loan.borrower')
                    ->where('status', 'Pending')
                    ->where('due_date', '<=', $fiveDaysFromNow)
                    ->orderBy('due_date', 'asc')
                    ->get();
                $view->with('dueInstallments', $dueInstallments);
            } else {
                $view->with('dueInstallments', collect());
            }
        });
    }
}
