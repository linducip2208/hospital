<?php

namespace App\Notifications;

use App\Models\LabTest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class CriticalLabResultNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(private readonly LabTest $labTest)
    {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'category' => 'critical_lab_result',
            'lab_test_id' => $this->labTest->id,
            'patient_id' => $this->labTest->patient_id,
            'message' => 'Hasil laboratorium kritis memerlukan tindak lanjut.',
        ];
    }
}
