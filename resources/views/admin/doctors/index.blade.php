@extends('layouts.dashboard')

@section('title', 'Manage Doctors — Meridian Health')

@section('content')
<main class="max-w-6xl mx-auto px-6 py-10">

    <div class="flex items-center justify-between mb-1">
        <h1 class="text-2xl font-extrabold">Doctors</h1>
        <a href="{{ route('admin.doctors.create') }}" class="btn-primary">+ Add Doctor</a>
    </div>
    <p class="text-sm text-slate-500 mb-8">Manage provider profiles shown on the public site and booking form.</p>

    <div class="card overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-xs uppercase tracking-wide text-slate-500 border-b border-slate-100">
                    <th class="py-2.5 pr-3">Photo</th>
                    <th class="py-2.5 pr-3">Name</th>
                    <th class="py-2.5 pr-3">Specialization</th>
                    <th class="py-2.5 pr-3">Rating</th>
                    <th class="py-2.5 pr-3">Appointments</th>
                    <th class="py-2.5 pr-3">Status</th>
                    <th class="py-2.5 pr-3">Login</th>
                    <th class="py-2.5"></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($doctors as $doctor)
                    <tr class="border-b border-slate-50 last:border-0">
                        <td class="py-3 pr-3">
                            @if ($doctor->image_url)
                                <img src="{{ $doctor->image_url }}" alt="{{ $doctor->name }}" class="w-10 h-10 rounded-full object-cover">
                            @else
                                <div class="w-10 h-10 rounded-full bg-brand-50 flex items-center justify-center text-brand-500">
                                    <i class="fa-solid fa-user-doctor"></i>
                                </div>
                            @endif
                        </td>
                        <td class="py-3 pr-3 font-bold">{{ $doctor->name }}</td>
                        <td class="py-3 pr-3">{{ $doctor->specialization }}</td>
                        <td class="py-3 pr-3"><x-star-rating :rating="$doctor->average_rating" :count="$doctor->ratings_count" /></td>
                        <td class="py-3 pr-3">{{ $doctor->appointments_count }}</td>
                        <td class="py-3 pr-3">
                            <span class="status-pill {{ $doctor->is_active ? 'status-confirmed' : 'status-cancelled' }}">{{ $doctor->is_active ? 'Active' : 'Inactive' }}</span>
                        </td>
                        <td class="py-3 pr-3 text-xs text-slate-500">{{ $doctor->user_id ? 'Registered' : '— (seeded)' }}</td>
                        <td class="py-3 text-right whitespace-nowrap">
                            <a href="{{ route('admin.doctors.edit', $doctor) }}" class="btn-ghost">Edit</a>
                            @if (! $doctor->user_id)
                                <form method="POST" action="{{ route('admin.doctors.destroy', $doctor) }}" class="inline"
                                      data-confirm="Delete this doctor profile?" data-confirm-button="Yes, delete it">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-outline">Delete</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="text-center text-sm text-slate-500 py-8">No doctors yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</main>
@endsection
