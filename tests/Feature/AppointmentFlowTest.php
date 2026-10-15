<?php

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\DoctorAvailability;
use App\Models\Hospital;
use App\Models\User;

/*
|--------------------------------------------------------------------------
| Helpers
|--------------------------------------------------------------------------
*/

function makeHospital(): Hospital
{
    return Hospital::create(['name' => 'Itahari Hospital', 'is_active' => true]);
}

function makeDoctor(bool $active = true): array
{
    $user = User::create([
        'name' => 'Dr Test', 'email' => 'doc' . uniqid() . '@test.test',
        'password' => 'password', 'role' => 'doctor',
    ]);

    $doctor = Doctor::create([
        'user_id' => $user->id,
        'hospital_id' => makeHospital()->id,
        'name' => 'Dr Test',
        'specialization' => 'General',
        'license_number' => 'L-' . uniqid(),
        'is_active' => $active,
    ]);

    return [$user, $doctor];
}

function makePatient(): User
{
    return User::create([
        'name' => 'Pat', 'email' => 'pat' . uniqid() . '@test.test',
        'password' => 'password', 'role' => 'patient',
    ]);
}

function makeSlot(Doctor $doctor, string $time = '10:00'): DoctorAvailability
{
    return DoctorAvailability::create([
        'doctor_id' => $doctor->id,
        'available_date' => now()->addDays(2)->toDateString(),
        'available_time' => $time,
        'is_booked' => false,
    ]);
}

function bookingPayload(Doctor $doctor, string $time = '10:00'): array
{
    return [
        'hospital_id' => $doctor->hospital_id,
        'doctor_id' => $doctor->id,
        'appointment_date' => now()->addDays(2)->toDateString(),
        'appointment_time' => $time,
        'contact_number' => '9800000000',
        'type' => 'new_patient',
        'reason' => 'Checkup',
    ];
}

/*
|--------------------------------------------------------------------------
| Booking
|--------------------------------------------------------------------------
*/

it('lets a patient book an available slot and marks it booked', function () {
    [, $doctor] = makeDoctor();
    $slot = makeSlot($doctor);
    $patient = makePatient();

    $this->actingAs($patient)
        ->post(route('appointments.store'), bookingPayload($doctor))
        ->assertRedirect(route('appointments.index'))
        ->assertSessionHasNoErrors();

    expect(Appointment::count())->toBe(1);
    expect($slot->fresh()->is_booked)->toBeTrue();
});

it('does not let two patients book the same slot', function () {
    [, $doctor] = makeDoctor();
    makeSlot($doctor);

    $this->actingAs(makePatient())->post(route('appointments.store'), bookingPayload($doctor));
    $this->actingAs(makePatient())
        ->post(route('appointments.store'), bookingPayload($doctor))
        ->assertSessionHasErrors('appointment_time');

    expect(Appointment::count())->toBe(1);
});

it('rejects a time the doctor never published', function () {
    [, $doctor] = makeDoctor();

    $this->actingAs(makePatient())
        ->post(route('appointments.store'), bookingPayload($doctor, '11:00'))
        ->assertSessionHasErrors('appointment_time');
});

it('rejects booking an inactive (unapproved) doctor', function () {
    [, $doctor] = makeDoctor(active: false);
    makeSlot($doctor);

    $this->actingAs(makePatient())
        ->post(route('appointments.store'), bookingPayload($doctor))
        ->assertSessionHasErrors('doctor_id');
});

it('lets the same slot be booked again after the patient cancels (no 500 / duplicate key)', function () {
    [, $doctor] = makeDoctor();
    $slot = makeSlot($doctor);
    $first = makePatient();

    $this->actingAs($first)->post(route('appointments.store'), bookingPayload($doctor));
    $appointment = Appointment::first();

    $this->actingAs($first)->delete(route('appointments.destroy', $appointment));

    expect($appointment->fresh()->status)->toBe('cancelled');
    expect($slot->fresh()->is_booked)->toBeFalse();

    $this->actingAs(makePatient())
        ->post(route('appointments.store'), bookingPayload($doctor))
        ->assertSessionHasNoErrors();

    expect(Appointment::count())->toBe(2);
    expect(Appointment::where('status', 'pending')->count())->toBe(1);
});

it('frees the slot when the DOCTOR cancels an appointment', function () {
    [$doctorUser, $doctor] = makeDoctor();
    $slot = makeSlot($doctor);

    $this->actingAs(makePatient())->post(route('appointments.store'), bookingPayload($doctor));
    $appointment = Appointment::first();

    $this->actingAs($doctorUser)
        ->patch(route('doctor.appointments.updateStatus', $appointment), ['status' => 'cancelled'])
        ->assertRedirect();

    expect($slot->fresh()->is_booked)->toBeFalse();
});

it('does not let a cancelled appointment be confirmed again', function () {
    [$doctorUser, $doctor] = makeDoctor();
    makeSlot($doctor);

    $this->actingAs(makePatient())->post(route('appointments.store'), bookingPayload($doctor));
    $appointment = Appointment::first();

    $this->actingAs($doctorUser)->patch(route('doctor.appointments.updateStatus', $appointment), ['status' => 'cancelled']);
    $this->actingAs($doctorUser)->patch(route('doctor.appointments.updateStatus', $appointment), ['status' => 'confirmed']);

    expect($appointment->fresh()->status)->toBe('cancelled');
});

it('does not let a patient cancel a completed appointment or re-open its slot', function () {
    [$doctorUser, $doctor] = makeDoctor();
    $slot = makeSlot($doctor);
    $patient = makePatient();

    $this->actingAs($patient)->post(route('appointments.store'), bookingPayload($doctor));
    $appointment = Appointment::first();
    $this->actingAs($doctorUser)->patch(route('doctor.appointments.updateStatus', $appointment), ['status' => 'completed']);

    $this->actingAs($patient)->delete(route('appointments.destroy', $appointment));

    expect($appointment->fresh()->status)->toBe('completed');
    expect($slot->fresh()->is_booked)->toBeTrue();
});

it('does not let a patient cancel someone else\'s appointment', function () {
    [, $doctor] = makeDoctor();
    makeSlot($doctor);

    $this->actingAs(makePatient())->post(route('appointments.store'), bookingPayload($doctor));

    $this->actingAs(makePatient())
        ->delete(route('appointments.destroy', Appointment::first()))
        ->assertForbidden();
});

/*
|--------------------------------------------------------------------------
| Admin
|--------------------------------------------------------------------------
*/

it('deactivates instead of deleting a doctor who has appointments', function () {
    [, $doctor] = makeDoctor();
    makeSlot($doctor);
    $this->actingAs(makePatient())->post(route('appointments.store'), bookingPayload($doctor));

    $admin = User::create(['name' => 'Admin', 'email' => 'a@a.test', 'password' => 'password', 'role' => 'admin']);

    $this->actingAs($admin)->delete(route('admin.doctors.destroy', $doctor))->assertRedirect();

    expect(Doctor::find($doctor->id))->not->toBeNull();
    expect($doctor->fresh()->is_active)->toBeFalse();
    expect(Appointment::count())->toBe(1);
});

it('deletes a doctor with no appointments together with the login account', function () {
    [$doctorUser, $doctor] = makeDoctor();
    $admin = User::create(['name' => 'Admin', 'email' => 'a@a.test', 'password' => 'password', 'role' => 'admin']);

    $this->actingAs($admin)->delete(route('admin.doctors.destroy', $doctor));

    expect(Doctor::find($doctor->id))->toBeNull();
    expect(User::find($doctorUser->id))->toBeNull();
});

/*
|--------------------------------------------------------------------------
| Registration & contact
|--------------------------------------------------------------------------
*/

it('registers a doctor against an existing hospital, inactive until approved', function () {
    $hospital = makeHospital();

    $this->post(route('register.doctor'), [
        'name' => 'Dr New', 'email' => 'new@doc.test', 'phone' => '9800000001',
        'specialization' => 'Neuro', 'license_number' => 'NMC-999',
        'hospital_id' => $hospital->id,
        'password' => 'Password1', 'password_confirmation' => 'Password1',
        'terms' => '1',
    ])->assertRedirect(route('doctor.appointments.index'));

    $doctor = Doctor::where('license_number', 'NMC-999')->first();

    expect($doctor)->not->toBeNull();
    expect($doctor->hospital_id)->toBe($hospital->id);
    expect($doctor->is_active)->toBeFalse();
    expect(Hospital::count())->toBe(1); // no stray hospital was created
});

it('refuses doctor registration for a hospital that does not exist', function () {
    $this->post(route('register.doctor'), [
        'name' => 'Dr New', 'email' => 'new@doc.test', 'phone' => '9800000001',
        'specialization' => 'Neuro', 'license_number' => 'NMC-999',
        'hospital_id' => 999,
        'password' => 'Password1', 'password_confirmation' => 'Password1',
        'terms' => '1',
    ])->assertSessionHasErrors('hospital_id');

    expect(Doctor::count())->toBe(0);
});

it('shows the flash error message on the page (layouts render session error)', function () {
    [$doctorUser, $doctor] = makeDoctor();
    makeSlot($doctor);
    $patient = makePatient();

    $this->actingAs($patient)->post(route('appointments.store'), bookingPayload($doctor));
    $appointment = Appointment::first();
    $this->actingAs($doctorUser)->patch(route('doctor.appointments.updateStatus', $appointment), ['status' => 'completed']);

    $this->actingAs($patient)->delete(route('appointments.destroy', $appointment));

    $this->actingAs($patient)
        ->get(route('appointments.index'))
        ->assertOk()
        ->assertSee('Only pending or confirmed appointments can be cancelled.', false);
});
