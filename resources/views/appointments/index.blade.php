@extends('layouts.dashboard')

@section('title', 'Book an Appointment — Meridian Health')

@section('content')

<main class="max-w-5xl mx-auto px-6 py-10">

    <h1 class="text-2xl font-extrabold mb-1">
        Book an appointment
    </h1>

    <p class="text-sm text-slate-500 mb-8">
        Select a doctor and date to see the appointment times that the doctor has made available.
    </p>


    <div class="grid md:grid-cols-2 gap-6 items-start">


        {{-- =========================================================
             BOOK APPOINTMENT
        ========================================================== --}}

        <div class="card">

            <h2 class="text-lg font-extrabold mb-4">
                New appointment
            </h2>


            <form
                method="POST"
                action="{{ route('appointments.store') }}"
                class="space-y-4"
            >

                @csrf


                {{-- Patient name --}}
                <div>

                    <label class="field-label">
                        Patient name
                    </label>

                    <input
                        type="text"
                        value="{{ auth()->user()->name }}"
                        disabled
                        class="field-input bg-slate-100 text-slate-500"
                    >

                </div>


                {{-- Contact number --}}
                <div>

                    <label class="field-label">
                        Contact number
                    </label>

                    <input
                        type="tel"
                        name="contact_number"
                        value="{{ old('contact_number', auth()->user()->phone) }}"
                        placeholder="(555) 123-4567"
                        class="field-input"
                        required
                    >

                    @error('contact_number')
                        <p class="mt-1 text-sm text-red-600">
                            {{ $message }}
                        </p>
                    @enderror

                </div>


                {{-- Province --}}
                <div>

                    <label
                        for="province_id"
                        class="field-label"
                    >
                        Province
                    </label>

                    <select
                        id="province_id"
                        class="field-input"
                        required
                    >

                        <option value="">
                            Select a province
                        </option>

                        @foreach ($provinces as $province)

                            <option
                                value="{{ $province->id }}"
                                @selected(old('province_id') == $province->id)
                            >
                                {{ $province->name }}
                            </option>

                        @endforeach

                    </select>

                </div>


                {{-- District --}}
                <div>

                    <label
                        for="district_id"
                        class="field-label"
                    >
                        District
                    </label>

                    <select
                        id="district_id"
                        class="field-input"
                        required
                        disabled
                    >

                        <option value="">
                            Select a district
                        </option>

                    </select>

                </div>


                {{-- Hospital --}}
                <div>

                    <label
                        for="hospital_id"
                        class="field-label"
                    >
                        Hospital
                    </label>

                    <select
                        name="hospital_id"
                        id="hospital_id"
                        class="field-input"
                        required
                        disabled
                    >

                        <option value="">
                            Select a hospital
                        </option>

                        @foreach ($hospitals as $hospital)

                            <option
                                value="{{ $hospital->id }}"
                                data-province-id="{{ $hospital->province_id }}"
                                data-district-id="{{ $hospital->district_id }}"
                            >
                                {{ $hospital->name }}
                            </option>

                        @endforeach

                    </select>

                    @error('hospital_id')

                        <p class="mt-1 text-sm text-red-600">
                            {{ $message }}
                        </p>

                    @enderror

                </div>


                {{-- Doctor --}}
                <div>

                    <label
                        for="doctor_id"
                        class="field-label"
                    >
                        Doctor
                    </label>

                    <select
                        name="doctor_id"
                        id="doctor_id"
                        class="field-input"
                        required
                        disabled
                    >

                        <option value="">
                            Select a doctor
                        </option>

                        @foreach ($doctors as $doctor)

                            <option
                                value="{{ $doctor->id }}"
                                data-hospital-id="{{ $doctor->hospital_id }}"
                            >
                                {{ $doctor->name }} — {{ $doctor->specialization }}
                            </option>

                        @endforeach

                    </select>

                    @error('doctor_id')

                        <p class="mt-1 text-sm text-red-600">
                            {{ $message }}
                        </p>

                    @enderror

                </div>


                {{-- Date --}}
                <div>

                    <label
                        for="appointment_date"
                        class="field-label"
                    >
                        Appointment date
                    </label>

                    <input
                        type="date"
                        name="appointment_date"
                        id="appointment_date"
                        value="{{ old('appointment_date') }}"
                        min="{{ now()->toDateString() }}"
                        class="field-input"
                        required
                    >

                    @error('appointment_date')

                        <p class="mt-1 text-sm text-red-600">
                            {{ $message }}
                        </p>

                    @enderror

                </div>


                {{-- Available time --}}
                <div>

                    <label
                        for="appointment_time"
                        class="field-label"
                    >
                        Available appointment time
                    </label>

                    <select
                        name="appointment_time"
                        id="appointment_time"
                        class="field-input"
                        required
                    >

                        <option value="">
                            Select doctor and date first
                        </option>

                    </select>

                    <p
                        id="availability-message"
                        class="mt-1 text-xs text-slate-500"
                    >
                        Select a doctor and date to see available times.
                    </p>

                    @error('appointment_time')

                        <p class="mt-1 text-sm text-red-600">
                            {{ $message }}
                        </p>

                    @enderror

                </div>


                {{-- Appointment type --}}
                <div>

                    <label class="field-label">
                        Appointment type
                    </label>

                    <select
                        name="type"
                        class="field-input"
                        required
                    >

                        <option
                            value="new_patient"
                            @selected(old('type', 'new_patient') === 'new_patient')
                        >
                            New patient visit
                        </option>

                        <option
                            value="follow_up"
                            @selected(old('type') === 'follow_up')
                        >
                            Follow-up visit
                        </option>

                    </select>

                    @error('type')

                        <p class="mt-1 text-sm text-red-600">
                            {{ $message }}
                        </p>

                    @enderror

                </div>


                {{-- Reason --}}
                <div>

                    <label class="field-label">
                        Reason for visit / symptoms
                    </label>

                    <textarea
                        name="reason"
                        rows="3"
                        placeholder="Briefly describe your symptoms or reason for the visit"
                        class="field-input"
                        required
                    >{{ old('reason') }}</textarea>

                    @error('reason')

                        <p class="mt-1 text-sm text-red-600">
                            {{ $message }}
                        </p>

                    @enderror

                </div>


                {{-- Submit --}}
                <button
                    type="submit"
                    class="btn-primary w-full py-3"
                >
                    Request appointment
                </button>

            </form>

        </div>


        {{-- =========================================================
             PATIENT APPOINTMENTS
        ========================================================== --}}

        <div class="card">

            <h2 class="text-lg font-extrabold mb-4">
                Your appointments
            </h2>


            @forelse ($appointments as $appointment)

                <div class="border-b border-slate-100 last:border-0 py-3.5">

                    <div class="flex items-start justify-between">

                        <div>

                            <p class="font-bold text-sm">
                                {{ $appointment->doctor->name }}
                            </p>


                            <p class="text-sm text-slate-500">

                                {{ $appointment->doctor->specialization }}

                                @if ($appointment->doctor->hospital)
                                    — {{ $appointment->doctor->hospital->name }}
                                @endif

                            </p>


                            {{-- Hospital --}}
                            @if ($appointment->doctor->hospital)

                                <p class="text-xs text-slate-500 mt-1">

                                    <i class="fa-solid fa-hospital text-brand-500 mr-1"></i>

                                    {{ $appointment->doctor->hospital->name }}

                                </p>

                            @else

                                <p class="text-xs text-slate-400 mt-1">
                                    Hospital not assigned
                                </p>

                            @endif


                            {{-- Date and time --}}
                            <p class="text-xs font-semibold mt-1">

                                {{ $appointment->appointment_date->format('M j, Y') }}

                                at

                                {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('g:i A') }}

                            </p>


                            {{-- Type --}}
                            <p class="text-xs text-slate-400 capitalize">

                                {{ str_replace('_', ' ', $appointment->type) }}

                            </p>

                        </div>


                        {{-- Status --}}
                        <div class="text-right">

                            <span
                                class="status-pill status-{{ $appointment->status }}"
                            >
                                {{ $appointment->status }}
                            </span>


                            @if (in_array($appointment->status, ['pending', 'confirmed']))

                                <form
                                    method="POST"
                                    action="{{ route('appointments.destroy', $appointment) }}"
                                    class="mt-2"
                                    data-confirm="Cancel this appointment?"
                                    data-confirm-button="Yes, cancel it"
                                >

                                    @csrf

                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        class="btn-outline"
                                    >
                                        Cancel
                                    </button>

                                </form>

                            @endif

                        </div>

                    </div>


                    {{-- =================================================
                         Rating
                    ================================================== --}}

                    @if ($appointment->status === 'completed')

                        @if ($appointment->rating)

                            <div class="mt-2 flex items-center gap-2">

                                <span class="text-xs text-slate-500">
                                    You rated this visit:
                                </span>

                                <x-star-rating
                                    :rating="$appointment->rating->rating"
                                    :count="1"
                                />

                            </div>

                        @else

                            <form
                                method="POST"
                                action="{{ route('appointments.rating.store', $appointment) }}"
                                class="mt-3 bg-slate-50 rounded-lg p-3"
                            >

                                @csrf

                                <p class="text-xs font-semibold text-slate-600 mb-2">
                                    How was your visit?
                                </p>


                                <div class="flex flex-row-reverse justify-end gap-1 star-picker">

                                    @for ($i = 5; $i >= 1; $i--)

                                        <input
                                            type="radio"
                                            name="rating"
                                            id="rating-{{ $appointment->id }}-{{ $i }}"
                                            value="{{ $i }}"
                                            class="hidden"
                                            required
                                        >

                                        <label
                                            for="rating-{{ $appointment->id }}-{{ $i }}"
                                            class="text-2xl cursor-pointer text-slate-300"
                                        >
                                            &#9733;
                                        </label>

                                    @endfor

                                </div>


                                <textarea
                                    name="comment"
                                    rows="2"
                                    placeholder="Optional comment..."
                                    class="field-input mt-2 text-xs"
                                ></textarea>


                                <button
                                    type="submit"
                                    class="btn-ghost mt-2 text-xs"
                                >
                                    Submit rating
                                </button>

                            </form>

                        @endif

                    @endif

                </div>

            @empty

                <p class="text-sm text-slate-500 text-center py-6">
                    You have no appointments yet. Book one using the form on the left.
                </p>

            @endforelse

        </div>

    </div>

</main>


{{-- =========================================================
     STAR PICKER
========================================================== --}}

<style>

    .star-picker input:checked ~ label,
    .star-picker label:hover ~ label,
    .star-picker label:hover {
        color: #fbbf24;
    }

</style>


<script>

document.addEventListener('DOMContentLoaded', function () {

    /*
    |--------------------------------------------------------------------------
    | Existing Province → District → Hospital → Doctor system
    |--------------------------------------------------------------------------
    */

    const provinceSelect =
        document.getElementById('province_id');

    const districtSelect =
        document.getElementById('district_id');

    const hospitalSelect =
        document.getElementById('hospital_id');

    const doctorSelect =
        document.getElementById('doctor_id');


    /*
    |--------------------------------------------------------------------------
    | Appointment date/time elements
    |--------------------------------------------------------------------------
    */

    const appointmentDate =
        document.getElementById('appointment_date');

    const appointmentTime =
        document.getElementById('appointment_time');

    const availabilityMessage =
        document.getElementById('availability-message');


    if (
        !provinceSelect ||
        !districtSelect ||
        !hospitalSelect ||
        !doctorSelect
    ) {
        return;
    }


    /*
    |--------------------------------------------------------------------------
    | District data from Laravel
    |--------------------------------------------------------------------------
    */

    const districts = {!! $districts->map(function ($district) {

        return [
            'id' => $district->id,
            'province_id' => $district->province_id,
            'name' => $district->name,
        ];

    })->values()->toJson() !!};


    /*
    |--------------------------------------------------------------------------
    | Save original hospital options
    |--------------------------------------------------------------------------
    */

    const hospitalOptions = Array.from(
        hospitalSelect.options
    ).map(option => ({

        value: option.value,

        text: option.text,

        provinceId: option.dataset.provinceId,

        districtId: option.dataset.districtId

    }));


    /*
    |--------------------------------------------------------------------------
    | Save original doctor options
    |--------------------------------------------------------------------------
    */

    const doctorOptions = Array.from(
        doctorSelect.options
    ).map(option => ({

        value: option.value,

        text: option.text,

        hospitalId: option.dataset.hospitalId

    }));


    /*
    |--------------------------------------------------------------------------
    | Load districts
    |--------------------------------------------------------------------------
    */

    function loadDistricts() {

        const provinceId =
            provinceSelect.value;


        districtSelect.innerHTML =
            '<option value="">Select a district</option>';

        hospitalSelect.innerHTML =
            '<option value="">Select a hospital</option>';

        doctorSelect.innerHTML =
            '<option value="">Select a doctor</option>';


        districtSelect.disabled =
            !provinceId;

        hospitalSelect.disabled =
            true;

        doctorSelect.disabled =
            true;


        resetAvailability();


        if (!provinceId) {
            return;
        }


        districts
            .filter(district =>
                String(district.province_id) ===
                String(provinceId)
            )
            .forEach(district => {

                const option =
                    document.createElement('option');

                option.value =
                    district.id;

                option.textContent =
                    district.name;

                districtSelect.appendChild(option);

            });

    }


    /*
    |--------------------------------------------------------------------------
    | Load hospitals
    |--------------------------------------------------------------------------
    */

    function loadHospitals() {

        const provinceId =
            provinceSelect.value;

        const districtId =
            districtSelect.value;


        hospitalSelect.innerHTML =
            '<option value="">Select a hospital</option>';

        doctorSelect.innerHTML =
            '<option value="">Select a doctor</option>';


        hospitalSelect.disabled =
            true;

        doctorSelect.disabled =
            true;


        resetAvailability();


        if (!provinceId || !districtId) {
            return;
        }


        hospitalOptions

            .filter(hospital =>

                String(hospital.provinceId) ===
                    String(provinceId)

                &&

                String(hospital.districtId) ===
                    String(districtId)

            )

            .forEach(hospital => {

                const option =
                    document.createElement('option');

                option.value =
                    hospital.value;

                option.textContent =
                    hospital.text;

                hospitalSelect.appendChild(option);

            });


        hospitalSelect.disabled =
            hospitalSelect.options.length <= 1;

    }


    /*
    |--------------------------------------------------------------------------
    | Load doctors
    |--------------------------------------------------------------------------
    */

    function loadDoctors() {

        const hospitalId =
            hospitalSelect.value;


        doctorSelect.innerHTML =
            '<option value="">Select a doctor</option>';

        doctorSelect.disabled =
            true;


        resetAvailability();


        if (!hospitalId) {
            return;
        }


        doctorOptions

            .filter(doctor =>

                String(doctor.hospitalId) ===
                String(hospitalId)

            )

            .forEach(doctor => {

                const option =
                    document.createElement('option');

                option.value =
                    doctor.value;

                option.textContent =
                    doctor.text;

                doctorSelect.appendChild(option);

            });


        doctorSelect.disabled =
            doctorSelect.options.length <= 1;

    }


    /*
    |--------------------------------------------------------------------------
    | Reset appointment times
    |--------------------------------------------------------------------------
    */

    function resetAvailability() {

        appointmentTime.innerHTML =
            '<option value="">Select doctor and date first</option>';

        appointmentTime.disabled =
            true;

        availabilityMessage.textContent =
            'Select a doctor and date to see available times.';

    }


    /*
    |--------------------------------------------------------------------------
    | Load doctor's available appointment times
    |--------------------------------------------------------------------------
    */

    function loadAvailableTimes() {

        const doctorId =
            doctorSelect.value;

        const date =
            appointmentDate.value;


        appointmentTime.innerHTML =
            '<option value="">Loading available times...</option>';

        appointmentTime.disabled =
            true;


        if (!doctorId || !date) {

            resetAvailability();

            return;
        }


        availabilityMessage.textContent =
            'Checking the doctor\'s available times...';


        const url =
            '{{ route("appointments.available-times") }}'
            + '?doctor_id='
            + encodeURIComponent(doctorId)
            + '&date='
            + encodeURIComponent(date);


        fetch(url, {

            method: 'GET',

            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }

        })

        .then(response => {

            if (!response.ok) {
                throw new Error(
                    'Unable to load available times.'
                );
            }

            return response.json();

        })

        .then(times => {

            appointmentTime.innerHTML = '';


            if (!times.length) {

                appointmentTime.innerHTML =
                    '<option value="">No available times</option>';

                appointmentTime.disabled =
                    true;

                availabilityMessage.textContent =
                    'The doctor has not added any available appointment times for this date.';

                return;
            }


            const defaultOption =
                document.createElement('option');

            defaultOption.value =
                '';

            defaultOption.textContent =
                'Select an available time';

            appointmentTime.appendChild(
                defaultOption
            );


            times.forEach(slot => {

                const option =
                    document.createElement('option');


                /*
                |--------------------------------------------------------------------------
                | MySQL TIME may return HH:MM:SS
                |--------------------------------------------------------------------------
                */

                const timeValue =
                    String(slot.available_time)
                        .substring(0, 5);


                option.value =
                    timeValue;


                /*
                |--------------------------------------------------------------------------
                | Convert 24-hour time to AM/PM
                |--------------------------------------------------------------------------
                */

                const parts =
                    timeValue.split(':');

                let hour =
                    parseInt(parts[0], 10);

                const minute =
                    parts[1];

                const period =
                    hour >= 12
                        ? 'PM'
                        : 'AM';


                hour =
                    hour % 12 || 12;


                option.textContent =
                    `${hour}:${minute} ${period}`;


                appointmentTime.appendChild(
                    option
                );

            });


            appointmentTime.disabled =
                false;


            availabilityMessage.textContent =
                'Select one of the available times provided by the doctor.';

        })

        .catch(error => {

            console.error(error);


            appointmentTime.innerHTML =
                '<option value="">Unable to load available times</option>';

            appointmentTime.disabled =
                true;


            availabilityMessage.textContent =
                'Unable to load the doctor\'s available times. Please try again.';

        });

    }


    /*
    |--------------------------------------------------------------------------
    | Events
    |--------------------------------------------------------------------------
    */

    provinceSelect.addEventListener(
        'change',
        loadDistricts
    );


    districtSelect.addEventListener(
        'change',
        loadHospitals
    );


    hospitalSelect.addEventListener(
        'change',
        loadDoctors
    );


    doctorSelect.addEventListener(
        'change',
        loadAvailableTimes
    );


    appointmentDate.addEventListener(
        'change',
        loadAvailableTimes
    );


    /*
    |--------------------------------------------------------------------------
    | Initial state
    |--------------------------------------------------------------------------
    */

    appointmentTime.disabled = true;

});

</script>

@endsection
