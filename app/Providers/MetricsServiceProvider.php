<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Observers\ActivityObserver;
use App\Models\Activity;

class MetricsServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        Activity::observe(ActivityObserver::class);
    }
}
