@extends('layouts.admin')

@section('title', 'All Appointments — Meridian Health')

@section('content')

<div class="max-w-6xl mx-auto px-8 py-10">

    <h1 class="text-2xl font-extrabold mb-1">
        All appointments
    </h1>

    <p class="text-sm text-slate-500 mb-6">
        Every appointment booked across the system.
    </p>

    {{-- Status Filters --}}
    <div class="flex gap-2 mb-6 flex-wrap">

        <a
            href="{{ route('admin.appointments.index') }}"
            class="text-xs font-bold rounded-full px-4 py-2
            {{ !$status
                ? 'bg-brand-500 text-white'
                : 'bg-white border border-slate-200 text-slate-600' }}"
        >
            All
        </a>

        <a
            href="{{ route('admin.appointments.index', ['status' => 'pending']) }}"
            class="text-xs font-bold rounded-full px-4 py-2
            {{ $status === 'pending'
                ? 'bg-brand-500 text-white'
                : 'bg-white border border-slate-200 text-slate-600' }}"
        >
            Pending
        </a>

        <a
            href="{{ route('admin.appointments.index', ['status' => 'confirmed']) }}"
            class="text-xs font-bold rounded-full px-4 py-2
            {{ $status === 'confirmed'
                ? 'bg-brand-500 text-white'
                : 'bg-white border border-slate-200 text-slate-600' }}"
        >
            Confirmed
        </a>

        <a
            href="{{ route('admin.appointments.index', ['status' => 'completed']) }}"
            class="text-xs font-bold rounded-full px-4 py-2
            {{ $status === 'completed'
                ? 'bg-brand-500 text-white'
                : 'bg-white border border-slate-200 text-slate-600' }}"
        >
            Completed
        </a>

        <a
            href="{{ route('admin.appointments.index', ['status' => 'cancelled']) }}"
            class="text-xs font-bold rounded-full px-4 py-2
            {{ $status === 'cancelled'
                ? 'bg-brand-500 text-white'
                : 'bg-white border border-slate-200 text-slate-600' }}"
        >
            Cancelled
        </a>

    </div>

    {{-- Appointments Table --}}
    <div class="card overflow-x-auto">

        <table class="w-full text-sm">

            <thead>
                <tr class="text-left text-xs uppercase tracking-wide text-slate-500 border-b border-slate-100">

                    <th class="py-2.5 pr-3">
                        Patient
                    </th>

                    <th class="py-2.5 pr-3">
                        Doctor
                    </th>

                    <th class="py-2.5 pr-3">
                        Hospital
                    </th>

                    <th class="py-2.5 pr-3">
                        Date &amp; time
                    </th>

                    <th class="py-2.5 pr-3">
                        Type
                    </th>

                    <th class="py-2.5">
                        Status
                    </th>

                </tr>
            </thead>

            <tbody>

                @forelse ($appointments as $appointment)

                    <tr class="border-b border-slate-50 last:border-0">

                        {{-- Patient --}}
                        <td class="py-3 pr-3 font-semibold">
                            {{ $appointment->user->name ?? '—' }}
                        </td>

                        {{-- Doctor --}}
                        <td class="py-3 pr-3 text-slate-600">

                            @if ($appointment->doctor)

                                <div class="flex items-center gap-2">

                                    <i class="fa-solid fa-user-doctor text-brand-500"></i>

                                    <span>
                                        {{ $appointment->doctor->name }}
                                    </span>

                                </div>

                            @else

                                <span class="text-slate-400">
                                    Not assigned
                                </span>

                            @endif

                        </td>

                        {{-- Hospital --}}
                        <td class="py-3 pr-3 text-slate-600">

                            @if ($appointment->doctor && $appointment->doctor->hospital)

                                <div class="flex items-center gap-2">

                                    <i class="fa-solid fa-hospital text-brand-500"></i>

                                    <span>
                                        {{ $appointment->doctor->hospital->name }}
                                    </span>

                                </div>

                            @else

                                <span class="text-slate-400">
                                    Not assigned
                                </span>

                            @endif

                        </td>

                        {{-- Date & Time --}}
                        <td class="py-3 pr-3 text-xs whitespace-nowrap">

                            {{ $appointment->appointment_date?->format('M j, Y') ?? '—' }}

                            @if ($appointment->appointment_time)
                                at
                                {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('g:i A') }}
                            @endif

                        </td>

                        {{-- Appointment Type --}}
                        <td class="py-3 pr-3 text-xs capitalize">

                            {{ str_replace('_', ' ', $appointment->type ?? '—') }}

                        </td>

                        {{-- Status --}}
                        <td class="py-3">

                            <span class="status-pill status-{{ $appointment->status }}">
                                {{ $appointment->status }}
                            </span>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td
                            colspan="6"
                            class="text-center text-sm text-slate-500 py-8"
                        >
                            No appointments found.
                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

        <div class="mt-4">
            {{ $appointments->links() }}
        </div>

    </div>

</div>

@endsection