<?php

namespace App\Http\Controllers;

class HomeController extends Controller
{
    public function index(\Illuminate\Http\Request $request)
    {
        $user = $request->user();

        $nextAppointment = $user->appointments()
            ->with('doctor')
            ->whereIn('status', ['pending', 'confirmed'])
            ->where('appointment_date', '>=', now()->toDateString())
            ->orderBy('appointment_date')
            ->orderBy('appointment_time')
            ->first();

        $upcomingCount = $user->appointments()
            ->whereIn('status', ['pending', 'confirmed'])
            ->where('appointment_date', '>=', now()->toDateString())
            ->count();

        $pastCount = $user->appointments()
            ->where('status', 'completed')
            ->count();

        // Find the most recent completed appointment that hasn't been
        // rated yet, so the home page can prompt the patient to leave one.
        $pendingRating = $user->appointments()
            ->with('doctor')
            ->where('status', 'completed')
            ->whereDoesntHave('rating')
            ->latest('appointment_date')
            ->first();

        return view('home', compact('nextAppointment', 'upcomingCount', 'pastCount', 'pendingRating'));
    }
}
