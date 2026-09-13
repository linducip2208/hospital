<?php

use App\Http\Controllers\Api\AppointmentController;
use App\Http\Controllers\Api\DoctorController;
use App\Http\Controllers\Api\MedicalRecordController;
use App\Http\Controllers\Api\PatientController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\SatuSehatWebhookController;
use App\Http\Controllers\Api\TreatmentController;
use Illuminate\Support\Facades\Route;

Route::post('/satusehat/webhook', [SatuSehatWebhookController::class, 'receive'])->name('satusehat.webhook');
Route::post('/auth/tokens', [\App\Http\Controllers\Api\TokenController::class, 'store'])
    ->middleware('throttle:10,1')->name('api.tokens.store');

Route::prefix('v1')->middleware('api.auth')->name('api.')->group(function () {
    Route::apiResource('patients', PatientController::class);
    Route::apiResource('doctors', DoctorController::class);
    Route::apiResource('treatments', TreatmentController::class);

    Route::get('appointments/calendar', [AppointmentController::class, 'calendar']);
    Route::apiResource('appointments', AppointmentController::class);

    Route::apiResource('medical-records', MedicalRecordController::class);
    Route::apiResource('payments', PaymentController::class);

    Route::delete('auth/tokens/{token}', [\App\Http\Controllers\Api\TokenController::class, 'destroy'])
        ->name('tokens.destroy');
});
