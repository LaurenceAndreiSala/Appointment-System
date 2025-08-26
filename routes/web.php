<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\IntroPageController;
use App\Http\Controllers\Admin\DoctorController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\DoctorDashboardController;
use App\Http\Controllers\PatientDashboardController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/welcome', [IntroPageController::class, 'intropage']);

// Login routes
Route::get('/login', [LoginController::class, 'showlogin'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.perform'); // ✅ Added this
Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// Register routes
Route::get('/register', [RegisterController::class, 'showregister'])->name('register');  
Route::post('/register', [RegisterController::class, 'store'])->name('register.store');

// Admin Dashboard (only accessible if logged in + has admin role, you can add middleware later)
Route::middleware(['auth'])->group(function () {
    Route::get('/admin/admin-dashboard', [AdminDashboardController::class, 'index'])->name('admin.admin-dashboard');
    Route::get('/admin/create-doctors', [DoctorController::class, 'create'])->name('admin.create-doctors');
    Route::post('/admin/doctors/store', [DoctorController::class, 'store'])->name('doctors.store');
    Route::get('/admin/set-appointment', [AdminDashboardController::class, 'setappointment'])->name('admin.set-appointment');  
    Route::get('/admin/view-appointment', [AdminDashboardController::class, 'viewappointment'])->name('admin.view-appointment');  
    Route::get('/admin/settings', [AdminDashboardController::class, 'settings'])->name('admin.settings');  
});

// Patient Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/patient/patient-dashboard', [PatientDashboardController::class, 'index'])->name('patient.patient-dashboard');
    Route::get('/patient/book-appointment', [PatientDashboardController::class, 'showbook'])->name('patient.book-appointment');  
    Route::get('/patient/view-appointment', [PatientDashboardController::class, 'viewappointment'])->name('patient.view-appointment');  
    Route::get('/patient/video-call', [PatientDashboardController::class, 'chatcall'])->name('patient.video-call');  
    Route::get('/patient/give-feedback', [PatientDashboardController::class, 'feedback'])->name('patient.give-feedback');  
    Route::get('/patient/view-precription', [PatientDashboardController::class, 'precription'])->name('patient.view-precription');  
});


Route::middleware(['auth'])->group(function () {
    Route::get('/doctor/doctor-dashboard', [DoctorDashboardController::class, 'index'])->name('doctor.doctor-dashboard');
});

Route::post('/patients/{id}/approve', [PatientDashboardController::class, 'approve'])->name('patients.approve');
Route::post('/patients/{id}/deny', [PatientDashboardController::class, 'deny'])->name('patients.deny');
