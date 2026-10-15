@extends('layouts.dashboard')

@section('title', 'Provider Dashboard — Meridian Health')

@section('content')
<main class="max-w-4xl mx-auto px-6 py-10">

    <h1 class="text-2xl font-extrabold mb-1">Your appointments</h1>
    <p class="text-sm text-slate-500 mb-8">{{ $doctor->name }} — {{ $doctor->specialization }}</p>

    @unless ($doctor->is_active)
    <div class="mb-6 rounded-xl border border-amber-200 bg-amber-50 p-4 text-sm text-amber-800">
        <i class="fa-solid fa-hourglass-half mr-1"></i>
        Your profile is <strong>pending admin approval</strong>. Patients cannot see or book you until an administrator activates it.
    </div>
    @endunless

    <div class="card">
        @forelse ($appointments as $appointment)
        <div class="flex items-start justify-between border-b border-slate-100 last:border-0 py-4">
            <div>
                <p class="font-bold text-sm">{{ $appointment->user->name }}</p>
                <p class="text-xs text-slate-500">{{ $appointment->user->email }} &middot; {{ $appointment->contact_number }}</p>
                <p class="text-xs font-semibold mt-1">{{ $appointment->appointment_date->format('M j, Y') }} at {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('g:i A') }}
                    <span class="text-slate-400 font-normal capitalize">&middot; {{ str_replace('_', ' ', $appointment->type) }}</span>
                </p>
                @if ($appointment->reason)
                <p class="text-xs text-slate-400 mt-1">"{{ $appointment->reason }}"</p>
                @endif

                @if ($appointment->rating)
                <div class="flex items-center gap-1 mt-2">
                    <span class="text-xs text-slate-500">Patient rating:</span>
                    <x-star-rating :rating="$appointment->rating->rating" :count="1" />
                </div>
                @endif
            </div>

            <div class="text-right">
                <span class="status-pill status-{{ $appointment->status }}">{{ $appointment->status }}</span>

                @if (in_array($appointment->status, ['pending', 'confirmed']))
                <div class="flex gap-1.5 justify-end mt-2 flex-wrap">
                    @if ($appointment->status === 'pending')
                    <form method="POST" action="{{ route('doctor.appointments.updateStatus', $appointment) }}">
                        @csrf @method('PATCH')
                        <input type="hidden" name="status" value="confirmed">
                        <button type="submit" class="btn-primary text-xs px-3 py-1.5">Confirm</button>
                    </form>
                    @endif
                    <form method="POST" action="{{ route('doctor.appointments.updateStatus', $appointment) }}">
                        @csrf @method('PATCH')
                        <input type="hidden" name="status" value="completed">
                        <button type="submit" class="btn-ghost">Mark complete</button>
                    </form>
                    <form method="POST" action="{{ route('doctor.appointments.updateStatus', $appointment) }}" data-confirm="Cancel this appointment?" data-confirm-button="Yes, cancel it">
                        @csrf @method('PATCH')
                        <input type="hidden" name="status" value="cancelled">
                        <button type="submit" class="btn-outline">Cancel</button>
                    </form>
                </div>
                @endif
            </div>
        </div>
        @empty
        <p class="text-sm text-slate-500 text-center py-8">No appointments have been booked with you yet.</p>
        @endforelse
    </div>
</main>
@endsection
