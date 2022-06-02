<?php

namespace App\Console;

use App\Console\Commands\SendDailyUsage;
use App\Console\Commands\PushNoovo;
use App\Console\Commands\StarterProjects;
use App\Console\Commands\DeleteExportedProjects;
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
        //$schedule->command(SendDailyUsage::class)->everyFourHours();
        //$schedule->command(PushNoovo::class)->everyMinute()->withoutOverlapping()->runInBackground(); // temporarily commented until final decision
        $schedule->command(DeleteExportedProjects::class)->daily()->runInBackground();
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
