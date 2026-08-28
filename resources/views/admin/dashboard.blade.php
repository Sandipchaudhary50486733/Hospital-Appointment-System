@extends('layouts.dashboard')

@section('title', 'Admin Dashboard — Meridian Health')

@section('content')
<main class="max-w-6xl mx-auto px-6 py-10">

    @if (session('status'))
        <div class="banner">{{ session('status') }}</div>
    @endif

    <h1 class="text-2xl font-extrabold mb-1">Admin Dashboard</h1>
    <p class="text-sm text-slate-500 mb-8">A quick look at what's happening across Meridian Health.</p>

    <div class="grid grid-cols-4 gap-5 mb-8">
        <div class="card"><div class="text-3xl font-extrabold">{{ $stats['patients'] }}</div><div class="text-xs font-semibold text-slate-500 mt-1">Patients</div></div>
        <div class="card"><div class="text-3xl font-extrabold">{{ $stats['doctors'] }}</div><div class="text-xs font-semibold text-slate-500 mt-1">Doctors</div></div>
        <div class="card"><div class="text-3xl font-extrabold">{{ $stats['pending'] }}</div><div class="text-xs font-semibold text-slate-500 mt-1">Pending appts</div></div>
        <div class="card"><div class="text-3xl font-extrabold">{{ $stats['todayAppointments'] }}</div><div class="text-xs font-semibold text-slate-500 mt-1">Today's appts</div></div>
    </div>

    {{-- Doctors / Users / Appointments now live here instead of the top nav --}}
    <div class="grid grid-cols-3 gap-5 mb-8">
        <a href="{{ route('admin.doctors.index') }}" class="card hover:border-brand-300 hover:shadow-md transition block">
            <i class="fa-solid fa-user-doctor text-2xl text-brand-500"></i>
            <h3 class="font-bold mt-3 mb-1">Doctors</h3>
            <p class="text-xs text-slate-500">Add, edit, or deactivate doctor profiles.</p>
        </a>
        <a href="{{ route('admin.users.index') }}" class="card hover:border-brand-300 hover:shadow-md transition block">
            <i class="fa-solid fa-users text-2xl text-brand-500"></i>
            <h3 class="font-bold mt-3 mb-1">Users</h3>
            <p class="text-xs text-slate-500">Browse all registered patients and doctors.</p>
        </a>
        <a href="{{ route('admin.appointments.index') }}" class="card hover:border-brand-300 hover:shadow-md transition block">
            <i class="fa-solid fa-calendar-check text-2xl text-brand-500"></i>
            <h3 class="font-bold mt-3 mb-1">Appointments</h3>
            <p class="text-xs text-slate-500">View every appointment, filterable by status.</p>
        </a>
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
