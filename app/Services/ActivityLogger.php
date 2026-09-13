<?php

namespace App\Services;

use App\Models\ActivityLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ActivityLogger
{
    /** Peta model → kategori & label Indonesia. */
    protected static array $map = [
        'Patient' => ['klinis', 'Pasien'],
        'Appointment' => ['klinis', 'Appointment'],
        'MedicalRecord' => ['klinis', 'Rekam Medis'],
        'Prescription' => ['farmasi', 'Resep'],
        'Emergency' => ['klinis', 'IGD'],
        'LabTest' => ['klinis', 'Lab'],
        'Radiology' => ['klinis', 'Radiologi'],
        'Surgery' => ['klinis', 'Operasi'],
        'Payment' => ['keuangan', 'Pembayaran'],
        'InsuranceClaim' => ['keuangan', 'Klaim'],
        'JournalEntry' => ['keuangan', 'Jurnal'],
        'Drug' => ['farmasi', 'Obat'],
        'DrugSupplyOrder' => ['farmasi', 'Pesanan Obat'],
        'Employee' => ['sdm', 'Karyawan'],
        'Salary' => ['sdm', 'Gaji'],
        'Leave' => ['sdm', 'Cuti'],
        'User' => ['sistem', 'Pengguna'],
        'BlogPost' => ['sistem', 'Artikel'],
        'EquipmentCalibration' => ['operasional', 'Kalibrasi Alat'],
        'MedicalWaste' => ['operasional', 'Limbah Medis'],
        'Vendor' => ['operasional', 'Vendor'],
    ];

    public static function log(string $event, ?Model $subject = null, ?string $description = null, array $properties = []): ActivityLog
    {
        $user = Auth::user();
        [$category, $label] = self::resolve($subject);

        return ActivityLog::create([
            'user_id' => $user?->id,
            'user_name' => $user?->name ?? 'Sistem',
            'event' => $event,
            'subject_type' => $subject ? get_class($subject) : null,
            'subject_id' => $subject?->getKey(),
            'description' => $description ?? self::defaultDescription($event, $label, $subject),
            'category' => $category,
            'properties' => $properties,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }

    protected static function resolve(?Model $subject): array
    {
        if (! $subject) {
            return ['sistem', 'Sistem'];
        }
        $base = class_basename($subject);

        return self::$map[$base] ?? ['umum', $base];
    }

    protected static function defaultDescription(string $event, string $label, ?Model $subject): string
    {
        $verb = match ($event) {
            'created' => 'menambah',
            'updated' => 'memperbarui',
            'deleted' => 'menghapus',
            'login' => 'masuk ke sistem',
            'logout' => 'keluar dari sistem',
            default => $event,
        };

        if (in_array($event, ['login', 'logout'], true)) {
            return ucfirst($verb);
        }

        $name = $subject?->name ?? $subject?->invoice_number ?? $subject?->claim_no ?? ('#'.$subject?->getKey());

        return ucfirst($verb)." {$label} {$name}";
    }
}
