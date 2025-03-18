<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class RunTaskEvery10Seconds extends Command
{
    protected $signature = 'task:run-every-10-seconds';
    protected $description = 'Run a specific task every 10 seconds for one minute';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        // Run the task indefinitely, every 10 seconds
        while (true) {
            // Perform the task
            $this->performTask();

            // Wait for 10 seconds before the next execution
            sleep(10);
        }

        return Command::SUCCESS; // This line will never be reached since the loop is infinite
    }

    private function performTask()
    {
        // Example task
        \Log::info("Task ran at " . now());
        // Or call a service or another method to perform the desired actions
    }
}
