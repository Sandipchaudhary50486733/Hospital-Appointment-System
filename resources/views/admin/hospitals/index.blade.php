@extends('layouts.admin')

@section('title', 'Hospitals — Meridian Health')

@section('content')
<main class="max-w-6xl mx-auto px-8 py-10">

    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-extrabold mb-1">Hospitals</h1>
            <p class="text-sm text-slate-500">
                Manage hospitals and their assigned doctors.
            </p>
        </div>

        <a href="{{ route('admin.hospitals.create') }}" class="btn-primary">
            <i class="fa-solid fa-plus mr-1.5"></i>
            Add hospital
        </a>
    </div>

    <div class="card overflow-x-auto">
        <table class="w-full text-sm">

            <thead>
                <tr class="text-left text-xs uppercase tracking-wide text-slate-500 border-b border-slate-100">
                    <th class="py-2.5 pr-3">Hospital</th>
                    <th class="py-2.5 pr-3">Address</th>
                    <th class="py-2.5 pr-3">Phone</th>
                    <th class="py-2.5 pr-3">Doctors</th>
                    <th class="py-2.5 pr-3">Status</th>
                    <th class="py-2.5">Actions</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($hospitals as $hospital)

                <tr class="border-b border-slate-50 last:border-0">

                    <td class="py-4 pr-3">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-brand-50 text-brand-600 flex items-center justify-center shrink-0">
                                <i class="fa-solid fa-hospital"></i>
                            </div>

                            <div>
                                <div class="font-bold text-slate-800">
                                    {{ $hospital->name }}
                                </div>

                                @if ($hospital->email)
                                <div class="text-xs text-slate-400 mt-0.5">
                                    {{ $hospital->email }}
                                </div>
                                @endif
                            </div>
                        </div>
                    </td>

                    <td class="py-4 pr-3 text-slate-600">
                        {{ $hospital->address ?? '—' }}
                    </td>

                    <td class="py-4 pr-3 text-slate-600">
                        {{ $hospital->phone ?? '—' }}
                    </td>

                    <td class="py-3 pr-3">
                        <a href="{{ route('admin.hospitals.doctors', $hospital) }}" class="font-bold text-brand-600 hover:text-brand-700">
                            {{ $hospital->doctors_count }}
                        </a>
                    </td>

                    <td class="py-4 pr-3">
                        @if ($hospital->is_active)
                        <span class="status-pill status-confirmed">
                            Active
                        </span>
                        @else
                        <span class="status-pill status-cancelled">
                            Inactive
                        </span>
                        @endif
                    </td>

                    <td class="py-4">
                        <div class="flex items-center gap-2">

                            <a href="{{ route('admin.hospitals.edit', $hospital) }}" class="btn-ghost">
                                <i class="fa-solid fa-pen-to-square mr-1"></i>
                                Edit
                            </a>

                            {{-- @if ($hospital->doctors_count === 0) --}}
                            <form method="POST" action="{{ route('admin.hospitals.destroy', $hospital) }}" class="inline" data-confirm="Delete this hospital?" data-confirm-text="This hospital will be permanently deleted." data-confirm-button="Delete hospital">

                                @csrf
                                @method('DELETE')

                                <button type="submit" class="btn-outline text-red-600">
                                    <i class="fa-solid fa-trash mr-1"></i>
                                    Delete
                                </button>
                            </form>
                            {{-- @endif --}}

                        </div>
                    </td>

                </tr>

                @empty

                <tr>
                    <td colspan="6" class="text-center py-12">

                        <div class="text-slate-400 mb-3">
                            <i class="fa-solid fa-hospital text-4xl"></i>
                        </div>

                        <p class="font-semibold text-slate-600">
                            No hospitals yet
                        </p>

                        <p class="text-sm text-slate-400 mt-1">
                            Add your first hospital to get started.
                        </p>

                        <a href="{{ route('admin.hospitals.create') }}" class="btn-primary mt-4">
                            Add hospital
                        </a>

                    </td>
                </tr>

                @endforelse
            </tbody>

        </table>
    </div>

</main>
@endsection
