@extends('layouts.dashboard')

@section('content')

<div class="max-w-6xl mx-auto space-y-6">

    <div>
        <h1 class="text-2xl font-bold text-slate-900">
            My Appointment Availability
        </h1>

        <p class="text-sm text-slate-500 mt-1">
            Add the dates and times when you are available for patient appointments.
        </p>
    </div>

    @if(session('success'))
        <div class="rounded-lg bg-green-50 border border-green-200 px-4 py-3 text-green-700">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="rounded-lg bg-red-50 border border-red-200 px-4 py-3 text-red-700">
            {{ session('error') }}
        </div>
    @endif

    @if($errors->any())
        <div class="rounded-lg bg-red-50 border border-red-200 px-4 py-3 text-red-700">
            <ul class="list-disc ml-5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">

        <h2 class="text-lg font-semibold text-slate-900 mb-4">
            Add Available Appointment Time
        </h2>

        <form
            method="POST"
            action="{{ route('doctor.availability.store') }}"
            class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end"
        >

            @csrf

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">
                    Date
                </label>

                <input
                    type="date"
                    name="available_date"
                    value="{{ old('available_date') }}"
                    min="{{ now()->toDateString() }}"
                    required
                    class="w-full rounded-lg border-slate-300"
                >
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">
                    Time
                </label>

                <input
                    type="time"
                    name="available_time"
                    value="{{ old('available_time') }}"
                    required
                    class="w-full rounded-lg border-slate-300"
                >
            </div>

            <button
                type="submit"
                class="rounded-lg bg-slate-900 px-5 py-2.5 text-white font-medium hover:bg-slate-800"
            >
                Add Available Time
            </button>

        </form>

    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200">

        <div class="p-6 border-b border-slate-200">
            <h2 class="text-lg font-semibold text-slate-900">
                My Available Times
            </h2>
        </div>

        <div class="overflow-x-auto">

            <table class="min-w-full divide-y divide-slate-200">

                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">
                            Date
                        </th>

                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">
                            Time
                        </th>

                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">
                            Status
                        </th>

                        <th class="px-6 py-3 text-right text-xs font-semibold text-slate-500 uppercase">
                            Action
                        </th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-200">

                    @forelse($availabilities as $availability)

                        <tr>

                            <td class="px-6 py-4 text-sm text-slate-900">
                                {{ $availability->available_date->format('d M Y') }}
                            </td>

                            <td class="px-6 py-4 text-sm text-slate-900">
                                {{ \Carbon\Carbon::parse($availability->available_time)->format('h:i A') }}
                            </td>

                            <td class="px-6 py-4">

                                @if($availability->is_booked)

                                    <span class="inline-flex rounded-full bg-blue-100 px-3 py-1 text-xs font-medium text-blue-700">
                                        Booked
                                    </span>

                                @else

                                    <span class="inline-flex rounded-full bg-green-100 px-3 py-1 text-xs font-medium text-green-700">
                                        Available
                                    </span>

                                @endif

                            </td>

                            <td class="px-6 py-4 text-right">

                                @if(!$availability->is_booked)

                                    <form
                                        method="POST"
                                        action="{{ route('doctor.availability.destroy', $availability) }}"
                                        onsubmit="return confirm('Remove this available time?')"
                                        class="inline"
                                    >

                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="text-red-600 hover:text-red-800 text-sm font-medium"
                                        >
                                            Remove
                                        </button>

                                    </form>

                                @else

                                    <span class="text-sm text-slate-400">
                                        Appointment booked
                                    </span>

                                @endif

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="4"
                                class="px-6 py-10 text-center text-sm text-slate-500"
                            >
                                No appointment times added yet.

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection