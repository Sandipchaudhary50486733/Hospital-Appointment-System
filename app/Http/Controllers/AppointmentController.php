<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAppointmentRequest;
use App\Models\Appointment;
use App\Models\Doctor;

class AppointmentController extends Controller
{
    public function index(\Illuminate\Http\Request $request)
    {
        abort_if($request->user()->isDoctor() || $request->user()->isAdmin(), 403, 'This page is for patients.');

        $doctors = Doctor::where('is_active', true)->orderBy('name')->get();

        $appointments = $request->user()
            ->appointments()
            ->with(['doctor', 'rating'])
            ->orderBy('appointment_date')
            ->orderBy('appointment_time')
            ->get();

        return view('appointments.index', compact('doctors', 'appointments'));
    }

    public function store(StoreAppointmentRequest $request)
    {
        abort_if($request->user()->isDoctor() || $request->user()->isAdmin(), 403);

        $validated = $request->validated();

        $alreadyTaken = Appointment::where('doctor_id', $validated['doctor_id'])
            ->where('appointment_date', $validated['appointment_date'])
            ->where('appointment_time', $validated['appointment_time'])
            ->whereIn('status', ['pending', 'confirmed'])
            ->exists();

        if ($alreadyTaken) {
            return back()
                ->withInput()
                ->withErrors(['appointment_time' => 'That slot was just taken. Please pick another time.']);
        }

        $request->user()->appointments()->create($validated);

        return redirect()
            ->route('appointments.index')
            ->with('status', 'Appointment requested successfully. We will confirm it shortly.');
    }

    public function destroy(\Illuminate\Http\Request $request, Appointment $appointment)
    {
        abort_unless($appointment->user_id === $request->user()->id, 403);

        $appointment->update(['status' => 'cancelled']);

        return redirect()
            ->route('appointments.index')
            ->with('status', 'Appointment cancelled.');
    }
}
