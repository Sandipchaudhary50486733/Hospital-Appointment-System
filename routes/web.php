<?php

use App\Http\Controllers\Admin\AppointmentController as AdminAppointmentController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\DoctorController as AdminDoctorController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\DoctorRegisteredUserController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\DoctorAppointmentController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PublicPageController;
use App\Http\Controllers\RatingController;
use Illuminate\Support\Facades\Route;

// ---- Public site ----
Route::get('/', [PublicPageController::class, 'home'])->name('public.home');
Route::get('/about', [PublicPageController::class, 'about'])->name('public.about');
Route::get('/services', [PublicPageController::class, 'services'])->name('public.services');
Route::get('/doctors', [PublicPageController::class, 'doctors'])->name('public.doctors');
Route::get('/contact', [PublicPageController::class, 'contact'])->name('public.contact');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

// ---- Guest-only auth routes ----
Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/register', [RegisteredUserController::class, 'store']);

    Route::get('/register/doctor', [DoctorRegisteredUserController::class, 'create'])->name('register.doctor');
    Route::post('/register/doctor', [DoctorRegisteredUserController::class, 'store']);

    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store']);
});

// ---- Any authenticated user ----
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});

// ---- Patient area ----
Route::middleware(['auth', 'role:patient'])->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');

    Route::get('/appointments', [AppointmentController::class, 'index'])->name('appointments.index');
    Route::post('/appointments', [AppointmentController::class, 'store'])->name('appointments.store');
    Route::delete('/appointments/{appointment}', [AppointmentController::class, 'destroy'])->name('appointments.destroy');

    // Submit a rating for a completed appointment.
    Route::post('/appointments/{appointment}/rating', [RatingController::class, 'store'])->name('appointments.rating.store');
});

// ---- Doctor area ----
Route::middleware(['auth', 'role:doctor'])->group(function () {
    Route::get('/doctor/appointments', [DoctorAppointmentController::class, 'index'])->name('doctor.appointments.index');
    Route::patch('/doctor/appointments/{appointment}', [DoctorAppointmentController::class, 'updateStatus'])->name('doctor.appointments.updateStatus');
});

// ---- Admin area ----
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

    Route::get('/doctors', [AdminDoctorController::class, 'index'])->name('doctors.index');
    Route::get('/doctors/create', [AdminDoctorController::class, 'create'])->name('doctors.create');
    Route::post('/doctors', [AdminDoctorController::class, 'store'])->name('doctors.store');
    Route::get('/doctors/{doctor}/edit', [AdminDoctorController::class, 'edit'])->name('doctors.edit');
    Route::put('/doctors/{doctor}', [AdminDoctorController::class, 'update'])->name('doctors.update');
    Route::delete('/doctors/{doctor}', [AdminDoctorController::class, 'destroy'])->name('doctors.destroy');

    Route::get('/appointments', [AdminAppointmentController::class, 'index'])->name('appointments.index');
    Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
});
