<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\DoctorController as AdminDoctorController;
use App\Http\Controllers\Admin\PatientController as AdminPatientController;
use App\Http\Controllers\Admin\ScheduleController as AdminScheduleController;
use App\Http\Controllers\Admin\ReservationController as AdminReservationController;
use App\Http\Controllers\Admin\MedicalRecordController as AdminMedicalRecordController;
use App\Http\Controllers\Doctor\DashboardController as DoctorDashboardController;
use App\Http\Controllers\Doctor\PatientController as DoctorPatientController;
use App\Http\Controllers\Doctor\ReservationController as DoctorReservationController;
use App\Http\Controllers\Doctor\MedicalRecordController as DoctorMedicalRecordController;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');

// Admin Routes
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::resource('doctors', AdminDoctorController::class);
    Route::resource('patients', AdminPatientController::class);
    Route::resource('schedules', AdminScheduleController::class);
    Route::resource('reservations', AdminReservationController::class);
    Route::resource('medical-records', AdminMedicalRecordController::class);
});

// Doctor Routes
Route::prefix('doctor')->name('doctor.')->middleware(['auth', 'doctor'])->group(function () {
    Route::get('/dashboard', [DoctorDashboardController::class, 'index'])->name('dashboard');
    Route::resource('patients', DoctorPatientController::class);
    Route::resource('reservations', DoctorReservationController::class);
    Route::patch('/reservations/{reservation}/status', [DoctorReservationController::class, 'updateStatus'])->name('reservations.updateStatus');
    Route::resource('medical-records', DoctorMedicalRecordController::class);
    
    // Route khusus untuk print medical records
    Route::get('/medical-records/{medicalRecord}/print', [DoctorMedicalRecordController::class, 'print'])
        ->name('medical-records.print');
});
