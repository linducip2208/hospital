<?php

namespace App\Console\Commands;

use App\Models\Appointment;
use Illuminate\Console\Command;

class SendAppointmentReminders extends Command
{
    protected $signature = 'appointments:remind {--days=1 : Kirim reminder untuk janji temu H-N}';

    protected $description = 'Kirim pengingat janji temu pasien (H-N sebelum jadwal)';

    public function handle(): int
    {
        $days = (int) $this->option('days');
        $targetDate = now()->addDays($days)->toDateString();

        $appointments = Appointment::with('patient', 'doctor', 'polyclinic')
            ->whereDate('appointment_date', $targetDate)
            ->whereIn('status', ['scheduled', 'confirmed'])
            ->get();

        $sent = 0;
        foreach ($appointments as $appt) {
            $reminders = $appt->reminders ?? [];
            $key = "h_minus_{$days}";

            if (! empty($reminders[$key])) {
                continue;
            }

            $reminders[$key] = now()->toDateTimeString();
            $appt->reminders = $reminders;
            $appt->saveQuietly();

            $this->line(sprintf(
                'Reminder: %s → dr. %s (%s) pada %s %s',
                $appt->patient?->name ?? '-',
                $appt->doctor?->name ?? '-',
                $appt->polyclinic?->name ?? '-',
                $appt->appointment_date?->format('d M Y'),
                $appt->start_time
            ));
            $sent++;
        }

        $this->info("Selesai. {$sent} reminder ditandai terkirim untuk H-{$days} ({$targetDate}).");

        return self::SUCCESS;
    }
}
