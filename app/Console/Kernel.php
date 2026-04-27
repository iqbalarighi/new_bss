<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule)
    {
        // 🔥 FACE VERIFY (TIAP 5 MENIT)
        $schedule->command('face:verify-fallback')
            ->everyFiveMinutes()
            ->withoutOverlapping() // ⛔ cegah double jalan
            ->appendOutputTo(storage_path('logs/fallback.log')); // 📝 simpan log

        // 🔥 CLEAN DATA PENGECUALIAN (HARIAN)
        $schedule->command('app:clean-pengecualian')
            ->daily()
            ->withoutOverlapping()
            ->appendOutputTo(storage_path('logs/clean.log'));
    }

    protected function commands()
    {
        $this->load(__DIR__.'/Commands');
    }
}