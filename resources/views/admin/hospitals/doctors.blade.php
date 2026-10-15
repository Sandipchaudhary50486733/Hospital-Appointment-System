@extends('layouts.admin')

@section('title', $hospital->name . ' — Doctors')

@section('content')

<main class="max-w-6xl mx-auto px-8 py-10">

    <a href="{{ route('admin.hospitals.index') }}"
       class="text-sm font-semibold text-brand-600 hover:text-brand-700">
        <i class="fa-solid fa-arrow-left mr-1"></i>
        Back to Hospitals
    </a>

    <div class="flex items-center justify-between mt-5 mb-6">
        <div>
            <h1 class="text-2xl font-extrabold">
                {{ $hospital->name }}
            </h1>

            <p class="text-sm text-slate-500 mt-1">
                Doctors assigned to this hospital
            </p>
        </div>
    </div>

    <div class="card overflow-x-auto">

        <table class="w-full text-sm">

            <thead>
                <tr class="text-left text-xs uppercase tracking-wide text-slate-500 border-b border-slate-100">
                    <th class="py-3 pr-3">Name</th>
                    <th class="py-3 pr-3">Specialization</th>
                    <th class="py-3 pr-3">Email</th>
                    <th class="py-3">Status</th>
                </tr>
            </thead>

            <tbody>

                @forelse ($doctors as $doctor)

                    <tr class="border-b border-slate-50 last:border-0">

                        <td class="py-3 pr-3 font-bold">
                            {{ $doctor->name }}
                        </td>

                        <td class="py-3 pr-3">
                            {{ $doctor->specialization }}
                        </td>

                        <td class="py-3 pr-3">
                            {{ $doctor->email ?? '—' }}
                        </td>

                        <td class="py-3">
                            <span class="status-pill {{ $doctor->is_active ? 'status-confirmed' : 'status-cancelled' }}">
                                {{ $doctor->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="4"
                            class="text-center text-sm text-slate-500 py-8">
                            No doctors are assigned to this hospital.
                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</main>

@endsection