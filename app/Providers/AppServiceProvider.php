<?php

namespace App\Providers;

use App\Models\Appointment;
use App\Models\Drug;
use App\Models\Emergency;
use App\Models\Employee;
use App\Models\InsuranceClaim;
use App\Models\JournalEntry;
use App\Models\LabTest;
use App\Models\Leave;
use App\Models\MedicalRecord;
use App\Models\Patient;
use App\Models\Payment;
use App\Models\Prescription;
use App\Models\Radiology;
use App\Models\Referral;
use App\Models\Salary;
use App\Models\Surgery;
use App\Models\User;
use App\Models\Encounter;
use App\Models\Dispensing;
use App\Models\Charge;
use App\Models\Bill;
use App\Models\Refund;
use App\Observers\ActivityObserver;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /** Model yang otomatis dicatat ke activity log. */
    protected array $auditedModels = [
        Patient::class,
        Appointment::class,
        MedicalRecord::class,
        Prescription::class,
        Emergency::class,
        LabTest::class,
        Radiology::class,
        Surgery::class,
        Payment::class,
        InsuranceClaim::class,
        JournalEntry::class,
        Drug::class,
        Employee::class,
        Salary::class,
        Leave::class,
        User::class,
        Encounter::class,
        Dispensing::class,
        Charge::class,
        Bill::class,
        Refund::class,
    ];

    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute((int) env('API_RATE_LIMIT', 60))
                ->by($request->user()?->id ?: $request->ip());
        });

        RateLimiter::for('login', function (Request $request) {
            return Limit::perMinute((int) env('LOGIN_RATE_LIMIT', 5))
                ->by(strtolower((string) $request->input('email')).'|'.$request->ip());
        });

        Builder::macro('whereAny', function (array $columns, string $operator, mixed $value) {
            return $this->where(function (Builder $query) use ($columns, $operator, $value) {
                foreach ($columns as $column) {
                    $query->orWhere($column, $operator, $value);
                }
            });
        });

        // Force HTTPS di production (atau ketika FORCE_HTTPS=true di .env)
        if ($this->app->environment('production') || env('FORCE_HTTPS', false)) {
            URL::forceScheme('https');
        }

        // Auto-audit trail untuk model penting
        foreach ($this->auditedModels as $model) {
            if (class_exists($model)) {
                $model::observe(ActivityObserver::class);
            }
        }

        // Notifikasi terkategori untuk topbar admin (semua view layout admin)
        View::composer('layouts.admin', function ($view) {
            $notif = [
                'stok_obat' => Drug::where('stock', '<', 50)->count(),
                'igd_kritis' => Emergency::where('triage', 'red')->whereIn('status', ['waiting', 'in_treatment', 'observation'])->count(),
                'klaim' => InsuranceClaim::whereIn('status', ['draft', 'submitted'])->count(),
                'lab' => LabTest::whereIn('status', ['requested', 'sample_collected', 'in_progress'])->count(),
                'rujukan' => Referral::where('status', 'pending')->count(),
            ];
            $notif['total'] = $notif['stok_obat'] + $notif['igd_kritis'] + $notif['klaim'] + $notif['rujukan'];
            $view->with('adminNotif', $notif);
        });
    }
}
