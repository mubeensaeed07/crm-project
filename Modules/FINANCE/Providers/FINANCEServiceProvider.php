<?php

namespace Modules\FINANCE\Providers;

use Illuminate\Support\ServiceProvider;

class FINANCEServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->registerViews();
        // $this->loadRoutes(); // Disabled - using main routes file instead
    }

    /**
     * Register views.
     */
    protected function registerViews(): void
    {
        $viewPath = base_path('Modules/FINANCE/Views');
        
        // Debug: Check if path exists
        if (!is_dir($viewPath)) {
            throw new \Exception("View path does not exist: {$viewPath}");
        }
        
        $this->loadViewsFrom($viewPath, 'finance');
        
        // Debug: Log that views are registered
        \Log::info("FINANCE views registered from: {$viewPath}");
    }

    /**
     * Load routes.
     */
    protected function loadRoutes(): void
    {
        \Log::info('Loading Finance routes from: ' . __DIR__ . '/../Routes/web.php');
        $this->loadRoutesFrom(__DIR__ . '/../Routes/web.php');
        \Log::info('Finance routes loaded successfully');
    }
}
