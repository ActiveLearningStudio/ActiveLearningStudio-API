<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\CurrikiGo\LMSIntegrationService;
use App\Services\CurrikiGo\LMSIntegrationServiceInterface;
use App\Services\C2E\Publisher\PublisherService;
use App\Services\C2E\Publisher\PublisherServiceInterface;
use Illuminate\Support\Facades\Response;

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
        $this->app->bind(PublisherServiceInterface::class, PublisherService::class);
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

        Response::macro('error', function ($value) {
            return Response::make([
                'errors' => (object) [
                    'error' => [$value[0]]
                ]
            ], $value[1]);
        });
    }
}
