<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\CurrikiGo\LMSIntegrationService;
use App\Services\CurrikiGo\LMSIntegrationServiceInterface;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(LMSIntegrationServiceInterface::class, LMSIntegrationService::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->environment('production')) {
            \URL::forceScheme('https');
        }
    }
}
