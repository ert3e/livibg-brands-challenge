<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Console\Commands\MqttListen;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        MqttListen::class,
    ];

    protected function schedule(Schedule $schedule)
    {
        // Schedule commands here
    }

    protected function commands()
    {
        $this->load(__DIR__.'/Commands');
        require base_path('routes/console.php');
    }
}
