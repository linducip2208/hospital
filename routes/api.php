<?php

use App\Http\Controllers\Api\AppointmentController;
use App\Http\Controllers\Api\DoctorController;
use App\Http\Controllers\Api\GenericResourceApiController;
use App\Http\Controllers\Api\MedicalRecordController;
use App\Http\Controllers\Api\PatientController;
use App\Http\Controllers\Api\PatientSelfController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\SatuSehatWebhookController;
use App\Http\Controllers\Api\TokenController;
use App\Http\Controllers\Api\TreatmentController;
use Illuminate\Support\Facades\Route;

Route::post('/satusehat/webhook', [SatuSehatWebhookController::class, 'receive'])
    ->middleware('throttle:30,1')->name('satusehat.webhook');

// Auth staf
Route::post('/auth/tokens', [TokenController::class, 'store'])
    ->middleware('throttle:10,1')->name('api.tokens.store');
Route::post('/auth/tokens/refresh', [TokenController::class, 'refresh'])
    ->middleware('throttle:10,1')->name('api.tokens.refresh');

// Auth pasien (portal)
Route::post('/auth/patients/tokens', [TokenController::class, 'storePatient'])
    ->middleware('throttle:10,1')->name('api.patients.tokens.store');
Route::post('/auth/patients/register', [TokenController::class, 'registerPatient'])
    ->middleware('throttle:10,1')->name('api.patients.register');

Route::prefix('v1')->middleware(['api.auth', 'throttle:api'])->name('api.')->group(function () {
    Route::apiResource('patients', PatientController::class);
    Route::apiResource('doctors', DoctorController::class);
    Route::apiResource('treatments', TreatmentController::class);

    Route::get('appointments/calendar', [AppointmentController::class, 'calendar']);
    Route::apiResource('appointments', AppointmentController::class);

    Route::apiResource('medical-records', MedicalRecordController::class);
    Route::apiResource('payments', PaymentController::class);

    Route::delete('auth/tokens/{token}', [TokenController::class, 'destroy'])
        ->name('tokens.destroy');
    Route::delete('auth/patients/tokens/current', [TokenController::class, 'destroyPatientCurrent'])
        ->name('patients.tokens.destroy-current');

    // Layanan mandiri pasien (guard patient, hanya data milik sendiri)
    Route::prefix('me')->name('me.')->group(function () {
        Route::get('/', [PatientSelfController::class, 'me'])->name('profile');
        Route::get('appointments', [PatientSelfController::class, 'appointments'])->name('appointments.index');
        Route::post('appointments', [PatientSelfController::class, 'bookAppointment'])->name('appointments.store');
        Route::get('appointments/{appointment}', [PatientSelfController::class, 'showAppointment'])->name('appointments.show');
        Route::get('medical-records', [PatientSelfController::class, 'medicalRecords'])->name('medical-records.index');
        Route::get('medical-records/{medicalRecord}', [PatientSelfController::class, 'showMedicalRecord'])->name('medical-records.show');
        Route::get('invoices', [PatientSelfController::class, 'invoices'])->name('invoices.index');
        Route::get('invoices/{invoice}', [PatientSelfController::class, 'showInvoice'])->name('invoices.show');
    });

    // CRUD generik semua modul staf (lihat config/api_resources.php).
    // {resource} dibatasi regex ke slug generik agar tidak menelan route khusus.
    $genericSlugs = implode('|', array_map('preg_quote', array_keys(config('api_resources', []))));
    Route::get('{resource}', [GenericResourceApiController::class, 'index'])
        ->where('resource', $genericSlugs)->name('generic.index');
    Route::post('{resource}', [GenericResourceApiController::class, 'store'])
        ->where('resource', $genericSlugs)->name('generic.store');
    Route::get('{resource}/{id}', [GenericResourceApiController::class, 'show'])
        ->where(['resource' => $genericSlugs, 'id' => '[0-9]+'])->name('generic.show');
    Route::match(['put', 'patch'], '{resource}/{id}', [GenericResourceApiController::class, 'update'])
        ->where(['resource' => $genericSlugs, 'id' => '[0-9]+'])->name('generic.update');
    Route::delete('{resource}/{id}', [GenericResourceApiController::class, 'destroy'])
        ->where(['resource' => $genericSlugs, 'id' => '[0-9]+'])->name('generic.destroy');
});
