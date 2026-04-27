<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

// 🔥 FACE VERIFY (TIAP 5 MENIT)
Schedule::command('face:verify-fallback')
    ->everyFiveMinutes()
    ->withoutOverlapping()
    ->appendOutputTo(storage_path('logs/fallback.log'));

// 🔥 CLEAN DATA PENGECUALIAN (HARIAN)
Schedule::command('app:clean-pengecualian')
    ->daily()
    ->withoutOverlapping()
    ->appendOutputTo(storage_path('logs/clean.log'));
