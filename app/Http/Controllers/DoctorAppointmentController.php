<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class DoctorAppointmentController extends Controller
{
    /**
     * Show all appointments booked with the authenticated doctor.
     */
    public function index(Request $request): View
    {
        $doctor = $request->user()->doctor;

        abort_unless($doctor, 403, 'No doctor profile is linked to this account.');

        $appointments = $doctor->appointments()
            ->with('user')
            ->orderBy('appointment_date')
            ->orderBy('appointment_time')
            ->get();

        return view('appointments.doctor-index', compact('doctor', 'appointments'));
    }

    /**
     * Update the status of an appointment (confirm, complete, or cancel).
     */
    public function updateStatus(Request $request, Appointment $appointment): RedirectResponse
    {
        $doctor = $request->user()->doctor;

        abort_unless($doctor && $appointment->doctor_id === $doctor->id, 403);

        $validated = $request->validate([
            'status' => ['required', Rule::in(['confirmed', 'completed', 'cancelled'])],
        ]);

        $appointment->update(['status' => $validated['status']]);

        return redirect()
            ->route('doctor.appointments.index')
            ->with('status', 'Appointment updated.');
    }
}
