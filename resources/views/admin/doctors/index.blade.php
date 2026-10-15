@extends('layouts.admin')

@section('title', 'Manage Doctors — Meridian Health')

@section('content')
<main class="max-w-6xl mx-auto px-8 py-10">

    @if (session('status'))
    <div class="banner">{{ session('status') }}</div>
    @endif
    @if ($errors->any())
    <div class="banner error">@foreach ($errors->all() as $error){{ $error }}<br>@endforeach</div>
    @endif

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
                    <th class="py-2.5 pr-3">Hospital</th>
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
                    <td>
                        @if ($doctor->hospital)
                        <span class="font-semibold text-slate-700">
                            {{ $doctor->hospital->name }}
                        </span>
                        @else
                        <span class="text-slate-400">
                            Not assigned
                        </span>
                        @endif
                    </td>
                    <td class="py-3 pr-3">
                        <x-star-rating :rating="$doctor->average_rating" :count="$doctor->ratings_count" />
                    </td>
                    <td class="py-3 pr-3">{{ $doctor->appointments_count }}</td>
                    <td class="py-3 pr-3">
                        <span class="status-pill {{ $doctor->is_active ? 'status-confirmed' : 'status-cancelled' }}">{{ $doctor->is_active ? 'Active' : 'Inactive' }}</span>
                    </td>
                    <td class="py-3 pr-3 text-xs text-slate-500">{{ $doctor->user_id ? 'Registered' : '— (seeded)' }}</td>

                    <td class="py-3 text-right whitespace-nowrap">

                        <a href="{{ route('admin.doctors.edit', $doctor) }}" class="btn-ghost">
                            <i class="fa-solid fa-pen-to-square mr-1"></i>
                            Edit
                        </a>

                        <form method="POST" action="{{ route('admin.doctors.destroy', $doctor) }}" class="inline" data-confirm="Delete this doctor?" data-confirm-button="Yes, delete it">

                            @csrf
                            @method('DELETE')

                            <button type="submit" class="btn-outline text-red-600">
                                <i class="fa-solid fa-trash mr-1"></i>
                                Delete
                            </button>

                        </form>

                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center text-sm text-slate-500 py-8">No doctors yet.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</main>
@endsection
