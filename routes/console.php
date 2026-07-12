<?php

use App\Console\Commands\AutoReorderDrugs;
use App\Console\Commands\BackupDatabase;
use App\Console\Commands\IndexNowSubmit;
use App\Console\Commands\SendAppointmentReminders;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// ── Scheduler ──

// Pengingat janji temu H-1 setiap pagi
Schedule::command(SendAppointmentReminders::class, ['--days=1'])
    ->dailyAt('07:00')
    ->withoutOverlapping();

// Auto-reorder obat di bawah reorder level (draft PO)
Schedule::command(AutoReorderDrugs::class)
    ->dailyAt('06:00')
    ->withoutOverlapping();

// Backup database harian dini hari
Schedule::command(BackupDatabase::class)
    ->dailyAt('02:00')
    ->withoutOverlapping();

// Submit URL baru ke IndexNow (Bing, Yandex, dll) tiap hari
Schedule::command(IndexNowSubmit::class, ['--new'])
    ->dailyAt('02:45')
    ->withoutOverlapping();
