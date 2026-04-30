<?php

use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\MedicalRecordController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\TreatmentController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// License pairing wizard v3
require base_path('routes/pair.php');

// Authentication
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);
Route::post('logout', [LoginController::class, 'logout'])->name('logout');
Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('register', [RegisterController::class, 'register']);

// Authenticated routes
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Patients
    Route::resource('patients', PatientController::class);

    // Doctors
    Route::resource('doctors', DoctorController::class);

    // Treatments
    Route::resource('treatments', TreatmentController::class);

    // Appointments
    Route::post('/appointments/{appointment}/status/{status}', [AppointmentController::class, 'status'])->name('appointments.status');
    Route::resource('appointments', AppointmentController::class);

    // Medical Records
    Route::resource('medical-records', MedicalRecordController::class);

    // Payments
    Route::resource('payments', PaymentController::class);
});