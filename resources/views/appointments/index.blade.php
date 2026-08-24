@extends('layouts.dashboard')

@section('title', 'Book an Appointment — Meridian Health')

@section('content')
<main class="max-w-5xl mx-auto px-6 py-10">

    <h1 class="text-2xl font-extrabold mb-1">Book an appointment</h1>
    <p class="text-sm text-slate-500 mb-8">Fill in your visit details and we'll confirm your slot shortly.</p>

    <div class="grid md:grid-cols-2 gap-6 items-start">
        <div class="card">
            <h2 class="text-lg font-extrabold mb-4">New appointment</h2>
            <form method="POST" action="{{ route('appointments.store') }}" class="space-y-4">
                @csrf

                <div>
                    <label class="field-label">Patient name</label>
                    <input type="text" value="{{ auth()->user()->name }}" disabled class="field-input bg-slate-100 text-slate-500">
                </div>

                <div>
                    <label class="field-label">Contact number</label>
                    <input type="tel" name="contact_number" value="{{ old('contact_number', auth()->user()->phone) }}"
                           placeholder="(555) 123-4567" class="field-input">
                </div>

                <div>
                    <label class="field-label">Department / Doctor</label>
                    <select name="doctor_id" class="field-input">
                        <option value="" disabled {{ old('doctor_id') ? '' : 'selected' }}>Select a doctor</option>
                        @foreach ($doctors as $doctor)
                            <option value="{{ $doctor->id }}" @selected((string) old('doctor_id') === (string) $doctor->id)>
                                {{ $doctor->name }} — {{ $doctor->specialization }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="field-label">Preferred date</label>
                        <input type="date" name="appointment_date" value="{{ old('appointment_date') }}" min="{{ now()->toDateString() }}" class="field-input">
                    </div>
                    <div>
                        <label class="field-label">Preferred time</label>
                        <input type="time" name="appointment_time" value="{{ old('appointment_time') }}" class="field-input">
                    </div>
                </div>

                <div>
                    <label class="field-label">Appointment type</label>
                    <select name="type" class="field-input">
                        <option value="new_patient" @selected(old('type', 'new_patient') === 'new_patient')>New patient visit</option>
                        <option value="follow_up" @selected(old('type') === 'follow_up')>Follow-up visit</option>
                    </select>
                </div>

                <div>
                    <label class="field-label">Reason for visit / symptoms</label>
                    <textarea name="reason" rows="3" placeholder="Briefly describe your symptoms or reason for the visit" class="field-input">{{ old('reason') }}</textarea>
                </div>

                <button type="submit" class="btn-primary w-full py-3">Request appointment</button>
            </form>
        </div>

        <div class="card">
            <h2 class="text-lg font-extrabold mb-4">Your appointments</h2>

            @forelse ($appointments as $appointment)
                <div class="border-b border-slate-100 last:border-0 py-3.5">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="font-bold text-sm">{{ $appointment->doctor->name }}</p>
                            <p class="text-xs text-slate-500">{{ $appointment->doctor->specialization }}</p>
                            <p class="text-xs font-semibold mt-1">{{ $appointment->appointment_date->format('M j, Y') }} at {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('g:i A') }}</p>
                            <p class="text-xs text-slate-400 capitalize">{{ str_replace('_', ' ', $appointment->type) }}</p>
                        </div>
                        <div class="text-right">
                            <span class="status-pill status-{{ $appointment->status }}">{{ $appointment->status }}</span>
                            @if (in_array($appointment->status, ['pending', 'confirmed']))
                                <form method="POST" action="{{ route('appointments.destroy', $appointment) }}" class="mt-2"
                                      data-confirm="Cancel this appointment?" data-confirm-button="Yes, cancel it">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-outline">Cancel</button>
                                </form>
                            @endif
                        </div>
                    </div>

                    {{-- Rating section: only shown for completed appointments --}}
                    @if ($appointment->status === 'completed')
                        @if ($appointment->rating)
                            {{-- Already rated: just show what they gave --}}
                            <div class="mt-2 flex items-center gap-2">
                                <span class="text-xs text-slate-500">You rated this visit:</span>
                                <x-star-rating :rating="$appointment->rating->rating" :count="1" />
                            </div>
                        @else
                            {{-- Not rated yet: show the star-picker form --}}
                            <form method="POST" action="{{ route('appointments.rating.store', $appointment) }}" class="mt-3 bg-slate-50 rounded-lg p-3">
                                @csrf
                                <p class="text-xs font-semibold text-slate-600 mb-2">How was your visit?</p>

                                <div class="flex flex-row-reverse justify-end gap-1 star-picker">
                                    @for ($i = 5; $i >= 1; $i--)
                                        <input type="radio" name="rating" id="rating-{{ $appointment->id }}-{{ $i }}" value="{{ $i }}" class="hidden" required>
                                        <label for="rating-{{ $appointment->id }}-{{ $i }}" class="text-2xl cursor-pointer text-slate-300">&#9733;</label>
                                    @endfor
                                </div>

                                <textarea name="comment" rows="2" placeholder="Optional comment..." class="field-input mt-2 text-xs"></textarea>

                                <button type="submit" class="btn-ghost mt-2 text-xs">Submit rating</button>
                            </form>
                        @endif
                    @endif
                </div>
            @empty
                <p class="text-sm text-slate-500 text-center py-6">You have no appointments yet. Book one using the form on the left.</p>
            @endforelse
        </div>
    </div>
</main>

{{-- Pure CSS star-picker: highlights this star and every star to its
     left when selected. Works with radio inputs rendered in reverse
     order (5,4,3,2,1) combined with flex-row-reverse, so visually they
     still read left-to-right as 1,2,3,4,5. --}}
<style>
.star-picker input:checked ~ label,
.star-picker label:hover ~ label,
.star-picker label:hover {
    color: #fbbf24;
}
</style>
@endsection
