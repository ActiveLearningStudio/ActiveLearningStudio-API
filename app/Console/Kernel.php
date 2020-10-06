<?php

namespace App\Console;

use App\Console\Commands\SendDailyUsage;
use App\Console\Commands\StarterProjects;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command(SendDailyUsage::class)->dailyAt('0:00');
        $schedule->command(SendDailyUsage::class)->everyFourHours();
        $schedule->command(StarterProjects::class)
            ->everyFiveMinutes()
            ->name('starter_projects')
            ->when(function (){
                return \Cache::store('database')->get('starter_projects_cron_count') <= 5;
            })->before(function () {
                $count = \Cache::store('database')->get('starter_projects_cron_count');
                \Cache::store('database')->put('starter_projects_cron_count', $count + 1);
            })->after(function () {
                $count = \Cache::store('database')->get('starter_projects_cron_count');
                \Cache::store('database')->put('starter_projects_cron_count', $count - 1);
            })->runInBackground();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
