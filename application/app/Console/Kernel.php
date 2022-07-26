<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
         $schedule->command('avito:ads')->everyTwoMinutes();
         $schedule->command('avito:ads-stats')->everyTwoMinutes();
         $schedule->command('avito:ads-calls')->everyTwoMinutes();
         $schedule->command('avito:ads-service')->everyTwoMinutes();

         $schedule->command('sipout:calls')->everyTwoMinutes();

         $schedule->command('mc:timelines')->everyTenMinutes();
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
