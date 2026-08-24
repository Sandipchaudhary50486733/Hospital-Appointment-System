@extends('layouts.dashboard')

@section('title', 'Admin Dashboard — Meridian Health')

@section('content')
<main class="max-w-6xl mx-auto px-6 py-10">

    <h1 class="text-2xl font-extrabold mb-1">Admin Dashboard</h1>
    <p class="text-sm text-slate-500 mb-8">A quick look at what's happening across Meridian Health.</p>

    <div class="grid grid-cols-5 gap-5 mb-8">
        <div class="card"><div class="text-3xl font-extrabold">{{ $stats['patients'] }}</div><div class="text-xs font-semibold text-slate-500 mt-1">Patients</div></div>
        <div class="card"><div class="text-3xl font-extrabold">{{ $stats['doctors'] }}</div><div class="text-xs font-semibold text-slate-500 mt-1">Doctors</div></div>
        <div class="card"><div class="text-3xl font-extrabold">{{ $stats['pending'] }}</div><div class="text-xs font-semibold text-slate-500 mt-1">Pending appts</div></div>
        <div class="card"><div class="text-3xl font-extrabold">{{ $stats['todayAppointments'] }}</div><div class="text-xs font-semibold text-slate-500 mt-1">Today's appts</div></div>
        <div class="card">
            <div class="text-3xl font-extrabold flex items-center gap-1.5">
                {{ $averageRating ?? '—' }}
                @if ($averageRating)<i class="fa-solid fa-star text-amber-400 text-xl"></i>@endif
            </div>
            <div class="text-xs font-semibold text-slate-500 mt-1">Avg. doctor rating</div>
        </div>
    </div>

    <div class="card">
        <h2 class="text-lg font-extrabold mb-4">Recent appointments</h2>
        @forelse ($recentAppointments as $appointment)
            <div class="flex items-center justify-between border-b border-slate-100 last:border-0 py-3 text-sm">
                <span class="font-bold w-1/3">{{ $appointment->user->name }}</span>
                <span class="text-slate-500 w-1/3">{{ $appointment->doctor->name }} — {{ $appointment->doctor->specialization }}</span>
                <span class="text-xs w-1/6">{{ $appointment->appointment_date->format('M j, Y') }}</span>
                <span class="status-pill status-{{ $appointment->status }}">{{ $appointment->status }}</span>
            </div>
        @empty
            <p class="text-sm text-slate-500 text-center py-6">No appointments yet.</p>
        @endforelse
        <div class="mt-4">
            <a href="{{ route('admin.appointments.index') }}" class="btn-ghost">View all appointments</a>
        </div>
    </div>
</main>
@endsection
