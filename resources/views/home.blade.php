@extends('layouts.dashboard')

@section('title', 'Home — Meridian Health')

@section('content')
<main class="max-w-5xl mx-auto px-6 py-10">

    <div class="bg-gradient-to-br from-[#005bea] to-[#00c6fb] rounded-3xl p-10 text-white mb-8 relative overflow-hidden">
        <h1 class="text-3xl font-bold mb-2">Welcome back, {{ explode(' ', auth()->user()->name)[0] }}</h1>
        <p class="text-white/85 max-w-lg mb-6">Book a new appointment, check what's coming up, or review your visit history — all in one place.</p>
        <a href="{{ route('appointments.index') }}" class="inline-block bg-white text-brand-700 font-bold text-sm rounded-lg px-6 py-3 hover:bg-slate-100">
            Book an appointment &rarr;
        </a>
    </div>

    {{-- Prompt to rate the most recent completed, unrated visit --}}
    @if ($pendingRating)
        <div class="bg-amber-50 border border-amber-200 rounded-2xl p-5 mb-8 flex items-center justify-between flex-wrap gap-3">
            <div>
                <p class="font-bold text-sm text-amber-800">How was your visit with {{ $pendingRating->doctor->name }}?</p>
                <p class="text-xs text-amber-700 mt-0.5">Your feedback helps other patients choose the right doctor.</p>
            </div>
            <a href="{{ route('appointments.index') }}" class="btn-primary text-xs px-4 py-2">Rate this visit</a>
        </div>
    @endif

    <div class="grid grid-cols-3 gap-5 mb-8">
        <div class="card"><div class="text-3xl font-extrabold">{{ $upcomingCount }}</div><div class="text-xs font-semibold text-slate-500 mt-1">Upcoming appointments</div></div>
        <div class="card"><div class="text-3xl font-extrabold">{{ $pastCount }}</div><div class="text-xs font-semibold text-slate-500 mt-1">Completed visits</div></div>
        <div class="card"><div class="text-2xl font-extrabold">{{ auth()->user()->phone ?? '—' }}</div><div class="text-xs font-semibold text-slate-500 mt-1">Contact on file</div></div>
    </div>

    <div class="card">
        <h2 class="text-lg font-extrabold mb-4">Your next appointment</h2>

        @if ($nextAppointment)
            <div class="flex items-center justify-between">
                <div>
                    <p class="font-bold">{{ $nextAppointment->doctor->name }}</p>
                    <p class="text-xs text-slate-500">{{ $nextAppointment->doctor->specialization }}</p>
                    <p class="text-sm font-semibold mt-1">{{ $nextAppointment->appointment_date->format('l, M j, Y') }} at {{ \Carbon\Carbon::parse($nextAppointment->appointment_time)->format('g:i A') }}</p>
                </div>
                <span class="status-pill status-{{ $nextAppointment->status }}">{{ $nextAppointment->status }}</span>
            </div>
        @else
            <p class="text-sm text-slate-500 text-center py-4">No upcoming appointments. <a href="{{ route('appointments.index') }}" class="text-brand-600 font-semibold">Book one now &rarr;</a></p>
        @endif
    </div>
</main>
@endsection
