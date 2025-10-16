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
        // Register HRM module views
        $this->loadViewsFrom(base_path('Modules/HRM/Views'), 'hrm');
        
        // Register SUPPORT module views
        $this->loadViewsFrom(base_path('Modules/SUPPORT/Views'), 'support');
        
        // Register DashboardHelper globally
        if (file_exists(app_path('Helpers/DashboardHelper.php'))) {
            require_once app_path('Helpers/DashboardHelper.php');
        }
    }
}