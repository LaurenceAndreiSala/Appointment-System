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

Route::get('/', [IntroPageController::class, 'intropage']);

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
});

Route::middleware(['auth'])->group(function () {
    Route::get('/patient/patient-dashboard', [PatientDashboardController::class, 'index'])->name('patient.patient-dashboard');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/doctor/doctor-dashboard', [DoctorDashboardController::class, 'index'])->name('doctor.doctor-dashboard');
});