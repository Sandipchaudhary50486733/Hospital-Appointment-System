<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\DoctorAvailability;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class DoctorAppointmentController extends Controller
{
    /**
     * Show all appointments booked with the authenticated doctor.
     */
    public function index(Request $request): View
    {
        $doctor = $request->user()
            ->doctor()
            ->with('hospital')
            ->first();

        abort_unless($doctor, 403, 'No doctor profile is linked to this account.');

        $appointments = $doctor->appointments()
            ->with(['user', 'rating'])
            ->orderBy('appointment_date')
            ->orderBy('appointment_time')
            ->get();

        return view('appointments.doctor-index', compact('doctor', 'appointments'));
    }


    /**
     * Show the doctor's appointment availability page.
     */
    public function availability(Request $request): View
    {
        $doctor = $request->user()
            ->doctor()
            ->with('hospital')
            ->first();

        abort_unless($doctor, 403, 'No doctor profile is linked to this account.');

        $availabilities = $doctor->availabilities()
            ->whereDate('available_date', '>=', today())
            ->orderBy('available_date')
            ->orderBy('available_time')
            ->get();

        return view('doctor.availability.index', compact('doctor', 'availabilities'));
    }


    /**
     * Doctor publishes an appointment date/time that patients can book.
     */
    public function storeAvailability(Request $request): RedirectResponse
    {
        $doctor = $request->user()->doctor;

        abort_unless($doctor, 403, 'No doctor profile is linked to this account.');

        $validated = $request->validate([
            'available_date' => ['required', 'date', 'after_or_equal:today'],
            'available_time' => ['required', 'date_format:H:i'],
        ]);

        $alreadyExists = DoctorAvailability::where('doctor_id', $doctor->id)
            ->whereDate('available_date', $validated['available_date'])
            ->where('available_time', $validated['available_time'])
            ->exists();

        if ($alreadyExists) {
            return back()
                ->withErrors(['available_time' => 'This appointment time has already been added.'])
                ->withInput();
        }

        DoctorAvailability::create([
            'doctor_id'      => $doctor->id,
            'available_date' => $validated['available_date'],
            'available_time' => $validated['available_time'],
            'is_booked'      => false,
        ]);

        return back()->with('status', 'Appointment time made available to patients.');
    }


    /**
     * Remove an available slot that has not already been booked.
     */
    public function destroyAvailability(
        Request $request,
        DoctorAvailability $availability
    ): RedirectResponse {
        $doctor = $request->user()->doctor;

        abort_unless($doctor && $availability->doctor_id === $doctor->id, 403);

        $booked = Appointment::where('doctor_id', $doctor->id)
            ->whereDate('appointment_date', $availability->available_date)
            ->where('appointment_time', $availability->available_time)
            ->whereIn('status', ['pending', 'confirmed'])
            ->exists();

        if ($booked) {
            return back()->withErrors([
                'availability' => 'This time cannot be removed because a patient has already booked it.',
            ]);
        }

        $availability->delete();

        return back()->with('status', 'Available appointment time removed.');
    }


    /**
     * Update the status of an appointment.
     */
    public function updateStatus(Request $request, Appointment $appointment): RedirectResponse
    {
        $doctor = $request->user()->doctor;

        abort_unless($doctor && $appointment->doctor_id === $doctor->id, 403);

        $validated = $request->validate([
            'status' => ['required', Rule::in(['confirmed', 'completed', 'cancelled'])],
        ]);

        // Completed and cancelled are final. Without this guard a cancelled
        // appointment could be "confirmed" again after its slot was released
        // and rebooked, which would double-book the doctor.
        if (! in_array($appointment->status, ['pending', 'confirmed'], true)) {
            return redirect()
                ->route('doctor.appointments.index')
                ->with('error', 'This appointment is already ' . $appointment->status . ' and cannot be changed.');
        }

        DB::transaction(function () use ($appointment, $validated) {
            $appointment->update(['status' => $validated['status']]);

            // A cancelled appointment must give its slot back to patients.
            if (
                $validated['status'] === 'cancelled' &&
                $appointment->doctor_availability_id
            ) {
                DoctorAvailability::whereKey($appointment->doctor_availability_id)
                    ->update(['is_booked' => false]);
            }
        });

        return redirect()
            ->route('doctor.appointments.index')
            ->with('status', 'Appointment updated.');
    }
}
