<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Log;

use App\Console\Commands\RunTaskEvery10Seconds;
use App\Console\Commands\RunChangeStatusPelatihan;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        RunTaskEvery10Seconds::class,
        RunChangeStatusPelatihan::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        Log::info('Mulai memproses');
        // OLD
        //@ms198 - CONTOH kalo yg 10 detik ... 
        // $schedule->command('task:run-every-10-seconds')->everyMinute();

        // Schedule everyMinute
        $schedule->command('task:run-change-status-pelatihan')->everyMinute();
        
        
        // // Menjalankan job setiap hari pada tengah malam
        // $schedule->job(new \App\Jobs\UpdatePelatihanStatusJob)->daily();
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
