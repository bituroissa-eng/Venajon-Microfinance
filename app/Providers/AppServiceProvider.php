<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
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
        if (getenv('VERCEL') || getenv('VERCEL_URL') || str_starts_with(config('app.url', ''), 'https')) {
            $appUrl = config('app.url') ?: env('APP_URL');
            if (! empty($appUrl)) {
                URL::forceRootUrl(rtrim($appUrl, '/'));
            }
            URL::forceScheme('https');
        }

        try {
            if (Schema::hasTable('system_settings')) {
                $systemSetting = \App\Models\SystemSetting::first();
                View::share('systemSetting', $systemSetting);
            } else {
                View::share('systemSetting', null);
            }
        } catch (\Exception $e) {
            View::share('systemSetting', null);
        }

        View::composer('layouts.navigation', function ($view) {
            try {
                if (Schema::hasTable('installments')) {
                    $fiveDaysFromNow = \Carbon\Carbon::now()->addDays(5)->toDateString();
                    $dueInstallments = \App\Models\Installment::with('loan.borrower')
                        ->where('status', 'Pending')
                        ->where('due_date', '<=', $fiveDaysFromNow)
                        ->orderBy('due_date', 'asc')
                        ->get();

                    $view->with('dueInstallments', $dueInstallments);
                    return;
                }
            } catch (\Exception $e) {
                // Fall through to default empty collection if the database is unavailable.
            }

            $view->with('dueInstallments', collect());
        });
    }
}
