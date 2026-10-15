<?php

use App\Http\Controllers\Admin\ContactMessageController as AdminContactMessageController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\Admin\AppointmentController as AdminAppointmentController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\DoctorController as AdminDoctorController;
use App\Http\Controllers\Admin\SettingsController as AdminSettingsController;
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
use App\Http\Controllers\Admin\HospitalController;
use App\Http\Controllers\ProfileController;

/*
|--------------------------------------------------------------------------
| Uploaded photos (works without `php artisan storage:link`)
|--------------------------------------------------------------------------
*/
Route::get('/media/{path}', [MediaController::class, 'show'])
    ->where('path', '(doctors|profiles)/[A-Za-z0-9._-]+')
    ->name('media');


// --------------------------------------------------------------------------
// Public site
// --------------------------------------------------------------------------

Route::get('/', [PublicPageController::class, 'home'])
    ->name('public.home');

Route::get('/about', [PublicPageController::class, 'about'])
    ->name('public.about');

Route::get('/services', [PublicPageController::class, 'services'])
    ->name('public.services');

Route::get('/doctors', [PublicPageController::class, 'doctors'])
    ->name('public.doctors');

Route::get('/doctors/{doctor}', [PublicPageController::class, 'doctor'])
    ->name('public.doctor');

Route::get('/contact', [PublicPageController::class, 'contact'])
    ->name('public.contact');

Route::post('/contact', [ContactController::class, 'store'])
    ->name('contact.store');


// --------------------------------------------------------------------------
// Guest-only authentication routes
// --------------------------------------------------------------------------

Route::middleware('guest')->group(function () {

    // Patient registration
    Route::get('/register', [RegisteredUserController::class, 'create'])
        ->name('register');

    Route::post('/register', [RegisteredUserController::class, 'store']);


    // Doctor registration
    Route::get('/register/doctor', [DoctorRegisteredUserController::class, 'create'])
        ->name('register.doctor');

    Route::post('/register/doctor', [DoctorRegisteredUserController::class, 'store']);


    // Login
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');

    Route::post('/login', [AuthenticatedSessionController::class, 'store']);
});


// --------------------------------------------------------------------------
// Any authenticated user
// --------------------------------------------------------------------------

Route::middleware('auth')->group(function () {

    Route::post(
        '/logout',
        [AuthenticatedSessionController::class, 'destroy']
    )->name('logout');
});


// --------------------------------------------------------------------------
// Patient area
// --------------------------------------------------------------------------

Route::middleware(['auth', 'role:patient'])->group(function () {

    // Patient dashboard
    Route::get(
        '/home',
        [HomeController::class, 'index']
    )->name('home');


    // ----------------------------------------------------------------------
    // Appointments
    // ----------------------------------------------------------------------

    Route::get(
        '/appointments',
        [AppointmentController::class, 'index']
    )->name('appointments.index');

    Route::post(
        '/appointments',
        [AppointmentController::class, 'store']
    )->name('appointments.store');

    Route::delete(
        '/appointments/{appointment}',
        [AppointmentController::class, 'destroy']
    )->name('appointments.destroy');


    // ----------------------------------------------------------------------
    // Doctor available appointment times
    // ----------------------------------------------------------------------

    Route::get(
        '/appointments/available-times',
        [AppointmentController::class, 'availableTimes']
    )->name('appointments.available-times');


    // ----------------------------------------------------------------------
    // Appointment rating
    // ----------------------------------------------------------------------

    Route::post(
        '/appointments/{appointment}/rating',
        [RatingController::class, 'store']
    )->name('appointments.rating.store');


    // ----------------------------------------------------------------------
    // Patient Profile
    // ----------------------------------------------------------------------

    Route::get(
        '/patient/profile',
        [ProfileController::class, 'patient']
    )->name('patient.profile');

    Route::get(
        '/patient/profile/edit',
        [ProfileController::class, 'editPatient']
    )->name('patient.profile.edit');

    Route::put(
        '/patient/profile',
        [ProfileController::class, 'updatePatient']
    )->name('patient.profile.update');

    Route::get(
        '/patient/profile/password',
        [ProfileController::class, 'editPatientPassword']
    )->name('patient.profile.password');

    Route::put(
        '/patient/profile/password',
        [ProfileController::class, 'updatePatientPassword']
    )->name('patient.profile.password.update');
});


// --------------------------------------------------------------------------
// Doctor area
// --------------------------------------------------------------------------

Route::middleware(['auth', 'role:doctor'])->group(function () {

    // ----------------------------------------------------------------------
    // Doctor appointments
    // ----------------------------------------------------------------------

    Route::get(
        '/doctor/appointments',
        [DoctorAppointmentController::class, 'index']
    )->name('doctor.appointments.index');

    Route::patch(
        '/doctor/appointments/{appointment}',
        [DoctorAppointmentController::class, 'updateStatus']
    )->name('doctor.appointments.updateStatus');


    // ----------------------------------------------------------------------
    // Doctor Availability
    // ----------------------------------------------------------------------

    Route::get(
    '/doctor/availability',
    [DoctorAppointmentController::class, 'availability']
)->name('doctor.availability.index');

    Route::post(
        '/doctor/availability',
        [DoctorAppointmentController::class, 'storeAvailability']
    )->name('doctor.availability.store');

    Route::delete(
        '/doctor/availability/{availability}',
        [DoctorAppointmentController::class, 'destroyAvailability']
    )->name('doctor.availability.destroy');


    // ----------------------------------------------------------------------
    // Doctor Profile
    // ----------------------------------------------------------------------

    Route::get(
        '/doctor/profile',
        [ProfileController::class, 'doctor']
    )->name('doctor.profile');

    Route::get(
        '/doctor/profile/edit',
        [ProfileController::class, 'editDoctor']
    )->name('doctor.profile.edit');

    Route::put(
        '/doctor/profile',
        [ProfileController::class, 'updateDoctor']
    )->name('doctor.profile.update');

    Route::get(
        '/doctor/profile/password',
        [ProfileController::class, 'editDoctorPassword']
    )->name('doctor.profile.password');

    Route::put(
        '/doctor/profile/password',
        [ProfileController::class, 'updateDoctorPassword']
    )->name('doctor.profile.password.update');
});


// --------------------------------------------------------------------------
// Admin area
// --------------------------------------------------------------------------

Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // ------------------------------------------------------------------
        // Hospitals
        // ------------------------------------------------------------------

        Route::resource('hospitals', HospitalController::class)
            ->except(['show']);


        // ------------------------------------------------------------------
        // Contact messages (inbox)
        // ------------------------------------------------------------------

        Route::get('/messages', [AdminContactMessageController::class, 'index'])
            ->name('messages.index');
        Route::get('/messages/{message}', [AdminContactMessageController::class, 'show'])
            ->name('messages.show');
        Route::patch('/messages/{message}', [AdminContactMessageController::class, 'update'])
            ->name('messages.update');
        Route::delete('/messages/{message}', [AdminContactMessageController::class, 'destroy'])
            ->name('messages.destroy');


        // ------------------------------------------------------------------
        // Admin Dashboard
        // ------------------------------------------------------------------

        Route::get(
            '/',
            [AdminDashboardController::class, 'index']
        )->name('dashboard');


        // ------------------------------------------------------------------
        // Doctors
        // ------------------------------------------------------------------

        Route::get(
            '/doctors',
            [AdminDoctorController::class, 'index']
        )->name('doctors.index');

        Route::get(
            '/doctors/create',
            [AdminDoctorController::class, 'create']
        )->name('doctors.create');

        Route::post(
            '/doctors',
            [AdminDoctorController::class, 'store']
        )->name('doctors.store');

        Route::get(
            '/doctors/{doctor}/edit',
            [AdminDoctorController::class, 'edit']
        )->name('doctors.edit');

        Route::put(
            '/doctors/{doctor}',
            [AdminDoctorController::class, 'update']
        )->name('doctors.update');

        Route::delete(
            '/doctors/{doctor}',
            [AdminDoctorController::class, 'destroy']
        )->name('doctors.destroy');


        // ------------------------------------------------------------------
        // Appointments
        // ------------------------------------------------------------------

        Route::get(
            '/appointments',
            [AdminAppointmentController::class, 'index']
        )->name('appointments.index');


        // ------------------------------------------------------------------
        // Users
        // ------------------------------------------------------------------

        Route::get(
            '/users',
            [AdminUserController::class, 'index']
        )->name('users.index');


        // ------------------------------------------------------------------
        // Settings
        // ------------------------------------------------------------------

        Route::get(
            '/settings',
            [AdminSettingsController::class, 'edit']
        )->name('settings.edit');

        Route::put(
            '/settings',
            [AdminSettingsController::class, 'update']
        )->name('settings.update');


        // ------------------------------------------------------------------
        // Hospital doctors
        // ------------------------------------------------------------------

        Route::get(
            '/hospitals/{hospital}/doctors',
            [HospitalController::class, 'doctors']
        )->name('hospitals.doctors');
    });
