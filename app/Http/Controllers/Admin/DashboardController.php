<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Rating;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'patients'          => User::where('role', 'patient')->count(),
            'doctors'           => Doctor::count(),
            'pending'           => Appointment::where('status', 'pending')->count(),
            'todayAppointments' => Appointment::whereDate('appointment_date', now()->toDateString())->count(),
        ];

        // Average rating across every doctor on the platform, rounded to
        // 1 decimal place. Null (shown as "—") if no ratings exist yet.
        $averageRating = Rating::avg('rating');
        $averageRating = $averageRating ? round($averageRating, 1) : null;

       $recentAppointments = Appointment::with(['user', 'doctor.hospital'])
            ->latest()
            ->take(8)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentAppointments', 'averageRating'));
    }
}
