@extends('layouts.dashboard')

@section('title', 'Doctor Appointments — Meridian Health')

@section('content')

<div class="max-w-6xl mx-auto px-6 py-10">

    {{-- Page Header --}}
    <div class="mb-8">
        <h1 class="text-2xl font-extrabold text-slate-900">
            Doctor Appointments
        </h1>

        <p class="mt-1 text-sm text-slate-500">
            Manage your appointment availability and patient appointments.
        </p>
    </div>


    {{-- Flash Success Message --}}
    @if (session('status'))
        <div class="mb-6 rounded-xl border border-green-200 bg-green-50 px-5 py-4">
            <p class="text-sm font-medium text-green-700">
                {{ session('status') }}
            </p>
        </div>
    @endif


    {{-- Validation / Error Messages --}}
    @if ($errors->any())
        <div class="mb-6 rounded-xl border border-red-200 bg-red-50 px-5 py-4">
            <p class="mb-2 text-sm font-semibold text-red-700">
                Please fix the following:
            </p>

            <ul class="list-disc list-inside space-y-1 text-sm text-red-600">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif


    {{-- Doctor Information --}}
    <div class="mb-8 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">

        <div class="flex flex-col gap-4 sm:flex-row sm:items-center">

            {{-- Doctor Image --}}
            <div class="h-16 w-16 shrink-0 overflow-hidden rounded-full bg-slate-100">

                @if ($doctor->image_url)
                    <img
                        src="{{ $doctor->image_url }}"
                        alt="{{ $doctor->name }}"
                        class="h-full w-full object-cover"
                    >
                @else
                    <div class="flex h-full w-full items-center justify-center text-xl font-bold text-slate-500">
                        {{ strtoupper(substr($doctor->name, 0, 1)) }}
                    </div>
                @endif

            </div>

            {{-- Doctor Details --}}
            <div>
                <h2 class="text-lg font-bold text-slate-900">
                    Dr. {{ $doctor->name }}
                </h2>

                <p class="text-sm text-slate-500">
                    {{ $doctor->specialization }}
                </p>

                @if ($doctor->hospital)
                    <p class="mt-1 text-sm text-slate-500">
                        {{ $doctor->hospital->name }}
                    </p>
                @endif
            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- DOCTOR AVAILABILITY --}}
    {{-- ========================================================= --}}

    <div class="mb-8 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">

        {{-- Heading --}}
        <div class="mb-6">

            <h2 class="text-xl font-bold text-slate-900">
                Set Your Availability
            </h2>

            <p class="mt-1 text-sm text-slate-500">
                Add the date and time when you are available for patient appointments.
            </p>

        </div>


        {{-- Add Availability Form --}}
        <form
            method="POST"
            action="{{ route('doctor.availability.store') }}"
            class="grid grid-cols-1 gap-5 md:grid-cols-3"
        >

            @csrf


            {{-- Available Date --}}
            <div>

                <label
                    for="available_date"
                    class="mb-2 block text-sm font-semibold text-slate-700"
                >
                    Available Date
                </label>

                <input
                    type="date"
                    id="available_date"
                    name="available_date"
                    value="{{ old('available_date') }}"
                    min="{{ now()->format('Y-m-d') }}"
                    required
                    class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3
                           text-sm text-slate-900 outline-none
                           focus:border-slate-500 focus:ring-2 focus:ring-slate-200"
                >

                @error('available_date')
                    <p class="mt-1 text-xs text-red-600">
                        {{ $message }}
                    </p>
                @enderror

            </div>


            {{-- Available Time --}}
            <div>

                <label
                    for="available_time"
                    class="mb-2 block text-sm font-semibold text-slate-700"
                >
                    Available Time
                </label>

                <input
                    type="time"
                    id="available_time"
                    name="available_time"
                    value="{{ old('available_time') }}"
                    required
                    class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3
                           text-sm text-slate-900 outline-none
                           focus:border-slate-500 focus:ring-2 focus:ring-slate-200"
                >

                @error('available_time')
                    <p class="mt-1 text-xs text-red-600">
                        {{ $message }}
                    </p>
                @enderror

            </div>


            {{-- Submit Button --}}
            <div class="flex items-end">

                <button
                    type="submit"
                    class="w-full rounded-xl bg-slate-900 px-5 py-3
                           text-sm font-semibold text-white
                           transition hover:bg-slate-800
                           focus:outline-none focus:ring-2
                           focus:ring-slate-400"
                >
                    + Add Availability
                </button>

            </div>

        </form>


        {{-- Existing Availability --}}
        <div class="mt-8">

            <div class="mb-4 flex items-center justify-between">

                <div>
                    <h3 class="text-base font-bold text-slate-900">
                        Your Available Time Slots
                    </h3>

                    <p class="mt-1 text-xs text-slate-500">
                        Patients can book the available slots listed below.
                    </p>
                </div>

            </div>


            @forelse ($availabilities as $availability)

                <div
                    class="mb-3 flex flex-col gap-4 rounded-xl border
                           border-slate-200 bg-slate-50 p-4
                           sm:flex-row sm:items-center sm:justify-between"
                >

                    {{-- Date and Time --}}
                    <div>

                        <p class="font-semibold text-slate-900">

                            {{ $availability->available_date->format('M d, Y') }}

                        </p>

                        <p class="mt-1 text-sm text-slate-500">

                            {{ \Carbon\Carbon::parse($availability->available_time)->format('h:i A') }}

                        </p>

                    </div>


                    {{-- Status / Remove --}}
                    <div>

                        @if ($availability->is_booked)

                            <span
                                class="inline-flex rounded-full bg-green-100
                                       px-3 py-1.5 text-xs font-semibold
                                       text-green-700"
                            >
                                Booked
                            </span>

                        @else

                            <form
                                method="POST"
                                action="{{ route('doctor.availability.destroy', $availability) }}"
                            >

                                @csrf

                                @method('DELETE')

                                <button
                                    type="submit"
                                    onclick="return confirm('Are you sure you want to remove this availability?')"
                                    class="rounded-lg border border-red-200
                                           bg-white px-4 py-2 text-sm
                                           font-semibold text-red-600
                                           transition hover:bg-red-50"
                                >
                                    Remove
                                </button>

                            </form>

                        @endif

                    </div>

                </div>

            @empty

                <div
                    class="rounded-xl border border-dashed
                           border-slate-300 bg-slate-50 p-8 text-center"
                >

                    <p class="text-sm font-medium text-slate-600">
                        No availability added yet.
                    </p>

                    <p class="mt-1 text-xs text-slate-400">
                        Add your first available date and time using the form above.
                    </p>

                </div>

            @endforelse

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- PATIENT APPOINTMENTS --}}
    {{-- ========================================================= --}}

    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">

        {{-- Header --}}
        <div class="border-b border-slate-200 p-6">

            <h2 class="text-xl font-bold text-slate-900">
                Patient Appointments
            </h2>

            <p class="mt-1 text-sm text-slate-500">
                View and manage appointments booked with you.
            </p>

        </div>


        {{-- Appointment List --}}
        <div class="divide-y divide-slate-200">

            @forelse ($appointments as $appointment)

                <div class="p-6">

                    <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">

                        {{-- Patient Information --}}
                        <div class="min-w-0">

                            <div class="flex items-center gap-3">

                                <div
                                    class="flex h-11 w-11 shrink-0
                                           items-center justify-center
                                           rounded-full bg-slate-100
                                           text-sm font-bold text-slate-600"
                                >
                                    {{ strtoupper(substr($appointment->user->name ?? 'P', 0, 1)) }}
                                </div>

                                <div>

                                    <p class="font-bold text-slate-900">
                                        {{ $appointment->user->name ?? 'Patient' }}
                                    </p>

                                    @if ($appointment->user->email)
                                        <p class="text-sm text-slate-500">
                                            {{ $appointment->user->email }}
                                        </p>
                                    @endif

                                </div>

                            </div>

                        </div>


                        {{-- Appointment Details --}}
                        <div class="grid grid-cols-1 gap-3 text-sm sm:grid-cols-3">

                            {{-- Date --}}
                            <div>

                                <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                                    Date
                                </p>

                                <p class="mt-1 font-semibold text-slate-800">

                                    @if ($appointment->appointment_date)
                                        {{ $appointment->appointment_date->format('M d, Y') }}
                                    @else
                                        Not set
                                    @endif

                                </p>

                            </div>


                            {{-- Time --}}
                            <div>

                                <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                                    Time
                                </p>

                                <p class="mt-1 font-semibold text-slate-800">

                                    @if ($appointment->appointment_time)
                                        {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}
                                    @else
                                        Not set
                                    @endif

                                </p>

                            </div>


                            {{-- Type --}}
                            <div>

                                <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                                    Type
                                </p>

                                <p class="mt-1 font-semibold capitalize text-slate-800">
                                    {{ str_replace('_', ' ', $appointment->type ?? 'Appointment') }}
                                </p>

                            </div>

                        </div>


                        {{-- Status and Actions --}}
                        <div class="flex flex-wrap items-center gap-2">

                            {{-- Current Status --}}
                            @if ($appointment->status === 'pending')

                                <span
                                    class="rounded-full bg-amber-100
                                           px-3 py-1.5 text-xs font-semibold
                                           text-amber-700"
                                >
                                    Pending
                                </span>

                            @elseif ($appointment->status === 'confirmed')

                                <span
                                    class="rounded-full bg-blue-100
                                           px-3 py-1.5 text-xs font-semibold
                                           text-blue-700"
                                >
                                    Confirmed
                                </span>

                            @elseif ($appointment->status === 'completed')

                                <span
                                    class="rounded-full bg-green-100
                                           px-3 py-1.5 text-xs font-semibold
                                           text-green-700"
                                >
                                    Completed
                                </span>

                            @elseif ($appointment->status === 'cancelled')

                                <span
                                    class="rounded-full bg-red-100
                                           px-3 py-1.5 text-xs font-semibold
                                           text-red-700"
                                >
                                    Cancelled
                                </span>

                            @else

                                <span
                                    class="rounded-full bg-slate-100
                                           px-3 py-1.5 text-xs font-semibold
                                           text-slate-600"
                                >
                                    {{ ucfirst($appointment->status) }}
                                </span>

                            @endif


                            {{-- Pending Actions --}}
                            @if ($appointment->status === 'pending')

                                {{-- Confirm --}}
                                <form
                                    method="POST"
                                    action="{{ route('doctor.appointments.updateStatus', $appointment) }}"
                                >

                                    @csrf
                                    @method('PATCH')

                                    <input
                                        type="hidden"
                                        name="status"
                                        value="confirmed"
                                    >

                                    <button
                                        type="submit"
                                        class="rounded-lg bg-slate-900
                                               px-4 py-2 text-xs
                                               font-semibold text-white
                                               transition hover:bg-slate-800"
                                    >
                                        Confirm
                                    </button>

                                </form>


                                {{-- Cancel --}}
                                <form
                                    method="POST"
                                    action="{{ route('doctor.appointments.updateStatus', $appointment) }}"
                                >

                                    @csrf
                                    @method('PATCH')

                                    <input
                                        type="hidden"
                                        name="status"
                                        value="cancelled"
                                    >

                                    <button
                                        type="submit"
                                        class="rounded-lg border
                                               border-red-200 bg-white
                                               px-4 py-2 text-xs
                                               font-semibold text-red-600
                                               transition hover:bg-red-50"
                                    >
                                        Cancel
                                    </button>

                                </form>

                            @endif


                            {{-- Confirmed Actions --}}
                            @if ($appointment->status === 'confirmed')

                                {{-- Complete --}}
                                <form
                                    method="POST"
                                    action="{{ route('doctor.appointments.updateStatus', $appointment) }}"
                                >

                                    @csrf
                                    @method('PATCH')

                                    <input
                                        type="hidden"
                                        name="status"
                                        value="completed"
                                    >

                                    <button
                                        type="submit"
                                        class="rounded-lg bg-green-600
                                               px-4 py-2 text-xs
                                               font-semibold text-white
                                               transition hover:bg-green-700"
                                    >
                                        Complete
                                    </button>

                                </form>


                                {{-- Cancel --}}
                                <form
                                    method="POST"
                                    action="{{ route('doctor.appointments.updateStatus', $appointment) }}"
                                >

                                    @csrf
                                    @method('PATCH')

                                    <input
                                        type="hidden"
                                        name="status"
                                        value="cancelled"
                                    >

                                    <button
                                        type="submit"
                                        class="rounded-lg border
                                               border-red-200 bg-white
                                               px-4 py-2 text-xs
                                               font-semibold text-red-600
                                               transition hover:bg-red-50"
                                    >
                                        Cancel
                                    </button>

                                </form>

                            @endif

                        </div>

                    </div>


                    {{-- Reason --}}
                    @if ($appointment->reason)

                        <div class="mt-5 rounded-xl bg-slate-50 p-4">

                            <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                                Reason for Visit
                            </p>

                            <p class="mt-1 text-sm leading-6 text-slate-700">
                                {{ $appointment->reason }}
                            </p>

                        </div>

                    @endif

                </div>

            @empty

                <div class="p-12 text-center">

                    <div
                        class="mx-auto mb-4 flex h-14 w-14
                               items-center justify-center
                               rounded-full bg-slate-100"
                    >
                        <span class="text-xl text-slate-400">
                            📅
                        </span>
                    </div>

                    <h3 class="font-bold text-slate-900">
                        No appointments yet
                    </h3>

                    <p class="mt-1 text-sm text-slate-500">
                        Patient appointments will appear here once they are booked.
                    </p>

                </div>

            @endforelse

        </div>

    </div>

</div>

@endsection