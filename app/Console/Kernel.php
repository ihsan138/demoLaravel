<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Log;

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
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        //perhaps this does not works in windows enviroment, need to verify
        //$schedule->command('inspire')->hourly();
        $schedule->command('inspire')->everyMinute();  

        //$schedule->command('backup:run')->daily()->at('01:35');
        $schedule
        ->command('backup:run')->daily()->at('11:30')
        ->onFailure(function () {
            Log::info('backup failed');
        })
        ->onSuccess(function () {
           Log::info('backup success');
        });
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
