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
         $schedule->command('avito:ads')->everyTwoHours();
         $schedule->command('avito:ads-stats')->everyTwoHours();
         $schedule->command('avito:ads-calls')->everyTwoHours();
         $schedule->command('avito:ads-service')->everyTwoHours();

         $schedule->command('sipout:calls')->everyTwoHours();

         $schedule->command('mс:timelines')->everyTenMinutes();
         $schedule->command('mc:orders')->everyTenMinutes();
         $schedule->command('mс:clients')->everyTenMinutes();
         $schedule->command('mс:skus-complects')->everyTenMinutes();
         $schedule->command('mс:skus-products')->everyTenMinutes();
         $schedule->command('mс:skus-variants')->everyTenMinutes();
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
