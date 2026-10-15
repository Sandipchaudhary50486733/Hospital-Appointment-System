<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAppointmentRequest;
use App\Models\Appointment;
use App\Models\District;
use App\Models\Doctor;
use App\Models\DoctorAvailability;
use App\Models\Hospital;
use App\Models\Province;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AppointmentController extends Controller
{
    /**
     * Display the patient appointment page.
     */
    public function index(Request $request)
    {
        abort_if(
            $request->user()->isDoctor() || $request->user()->isAdmin(),
            403,
            'This page is for patients.'
        );

        $doctors = Doctor::with('hospital')
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        $hospitals = Hospital::where('is_active', true)
            ->with(['province', 'district'])
            ->orderBy('name')
            ->get();

        $appointments = $request->user()
            ->appointments()
            ->with(['doctor.hospital', 'rating'])
            ->orderBy('appointment_date')
            ->orderBy('appointment_time')
            ->get();

        $provinces = Province::orderBy('name')->get();

        $districts = District::orderBy('name')->get();

        return view('appointments.index', compact(
            'doctors',
            'hospitals',
            'appointments',
            'provinces',
            'districts'
        ));
    }


    /**
     * Get available appointment times for a doctor on a selected date.
     */
    public function availableTimes(Request $request)
    {
        abort_if(
            $request->user()->isDoctor() || $request->user()->isAdmin(),
            403,
            'This page is for patients.'
        );

        $validated = $request->validate([
            'doctor_id' => ['required', 'exists:doctors,id'],
            'date'      => ['required', 'date', 'after_or_equal:today'],
        ]);

        $times = DoctorAvailability::where('doctor_id', $validated['doctor_id'])
            ->whereDate('available_date', $validated['date'])
            ->where('is_booked', false)
            ->whereHas('doctor', fn ($q) => $q->where('is_active', true))
            ->orderBy('available_time')
            ->get(['id', 'available_time']);

        return response()->json($times);
    }


    /**
     * Store a new patient appointment.
     */
    public function store(StoreAppointmentRequest $request): RedirectResponse
    {
        abort_if(
            $request->user()->isDoctor() || $request->user()->isAdmin(),
            403,
            'Only patients can book appointments.'
        );

        $validated = $request->validated();

        $doctor = Doctor::findOrFail($validated['doctor_id']);

        if (! $doctor->is_active) {
            return back()->withInput()->withErrors([
                'doctor_id' => 'This doctor is not accepting appointments right now.',
            ]);
        }

        // The selected doctor must belong to the selected hospital.
        if (
            ! empty($validated['hospital_id']) &&
            (int) $doctor->hospital_id !== (int) $validated['hospital_id']
        ) {
            return back()->withInput()->withErrors([
                'hospital_id' => 'The selected doctor does not belong to the selected hospital.',
            ]);
        }

        // hospital_id is only used for the check above; it is not an
        // appointments column.
        unset($validated['hospital_id']);

        /*
        | Everything below runs in ONE transaction and the availability row is
        | locked (SELECT ... FOR UPDATE) so two patients clicking "Book" for the
        | same slot at the same moment cannot both succeed.
        */
        $error = DB::transaction(function () use ($validated, $request) {

            $availability = DoctorAvailability::where('doctor_id', $validated['doctor_id'])
                ->whereDate('available_date', $validated['appointment_date'])
                ->where('available_time', $validated['appointment_time'])
                ->where('is_booked', false)
                ->lockForUpdate()
                ->first();

            if (! $availability) {
                return 'The doctor is not available at the selected time. Please choose one of the available times.';
            }

            // Safety net: only pending/confirmed bookings occupy a slot.
            $alreadyTaken = Appointment::where('doctor_id', $validated['doctor_id'])
                ->whereDate('appointment_date', $validated['appointment_date'])
                ->where('appointment_time', $validated['appointment_time'])
                ->whereIn('status', ['pending', 'confirmed'])
                ->exists();

            if ($alreadyTaken) {
                return 'That appointment time was just taken. Please choose another available time.';
            }

            $validated['doctor_availability_id'] = $availability->id;

            $request->user()->appointments()->create($validated);

            $availability->update(['is_booked' => true]);

            return null;
        });

        if ($error) {
            return back()->withInput()->withErrors(['appointment_time' => $error]);
        }

        return redirect()
            ->route('appointments.index')
            ->with(
                'status',
                'Appointment requested successfully. The doctor will confirm your appointment shortly.'
            );
    }


    /**
     * Cancel patient appointment.
     */
    public function destroy(Request $request, Appointment $appointment): RedirectResponse
    {
        abort_unless($appointment->user_id === $request->user()->id, 403);

        // Completed / already-cancelled appointments cannot be cancelled again
        // (that would re-open a slot that was already used).
        if (! in_array($appointment->status, ['pending', 'confirmed'], true)) {
            return redirect()
                ->route('appointments.index')
                ->with('error', 'Only pending or confirmed appointments can be cancelled.');
        }

        DB::transaction(function () use ($appointment) {
            // Release the doctor's availability slot so it can be booked again.
            if ($appointment->doctor_availability_id) {
                DoctorAvailability::whereKey($appointment->doctor_availability_id)
                    ->update(['is_booked' => false]);
            }

            $appointment->update(['status' => 'cancelled']);
        });

        return redirect()
            ->route('appointments.index')
            ->with('status', 'Appointment cancelled.');
    }
}
