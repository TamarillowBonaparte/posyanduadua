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
        $this->app->singleton('App\Services\StuntingCalculator', function ($app) {
            // Fix error "Class App\Services\StuntingCalculator not found"
            $calculatorPath = app_path('Services/StuntingCalculator.php');
            if (file_exists($calculatorPath)) {
                require_once $calculatorPath;
            }
            
            return new \App\Services\StuntingCalculator();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
