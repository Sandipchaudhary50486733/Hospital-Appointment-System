@extends('layouts.dashboard')

@section('content')

<div class="max-w-5xl mx-auto space-y-6">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">

        <div>
            <h1 class="text-2xl font-extrabold text-slate-900">
                My Doctor Profile
            </h1>

            <p class="text-sm text-slate-500 mt-1">
                View your professional and account information.
            </p>
        </div>


        <div class="flex gap-2">

            <a href="{{ route('doctor.profile.edit') }}" class="btn-primary">

                <i class="fa-solid fa-pen mr-2"></i>
                Edit Profile

            </a>


            <a href="{{ route('doctor.profile.password') }}" class="btn-ghost">

                <i class="fa-solid fa-lock mr-2"></i>
                Change Password

            </a>

        </div>

    </div>


    {{-- Success --}}
    @if(session('status'))

    <div class="bg-green-50 border border-green-200
                    text-green-700 rounded-xl px-4 py-3 text-sm">

        <i class="fa-solid fa-circle-check mr-2"></i>

        {{ session('status') }}

    </div>

    @endif


    {{-- Doctor Header --}}
    <div class="card">

        <div class="flex flex-col sm:flex-row
                    items-center sm:items-start gap-6">


            {{-- Doctor Image --}}
            <div>

                <img src="{{ $doctor->image_url ?? 'https://ui-avatars.com/api/?name='.urlencode($doctor->name ?: 'Doctor').'&background=eef4ff&color=0d6efd' }}" alt="{{ $doctor->name }}" class="w-32 h-32 rounded-2xl object-cover
                           border border-slate-200 shadow-sm">

            </div>


            {{-- Doctor Basic Information --}}
            <div class="text-center sm:text-left flex-1">

                <h2 class="text-2xl font-extrabold text-slate-900">
                    {{ $doctor->name }}
                </h2>

                <p class="text-brand-600 font-semibold mt-1">
                    {{ $doctor->specialization }}
                </p>

                <p class="text-sm text-slate-500 mt-2">
                    {{ $doctor->email }}
                </p>


                {{-- Status --}}
                <div class="mt-4">

                    @if($doctor->is_active)

                    <span class="inline-flex items-center
                                     px-3 py-1 rounded-full
                                     bg-green-100 text-green-700
                                     text-xs font-bold">

                        <span class="w-2 h-2 rounded-full
                                         bg-green-500 mr-2"></span>

                        Active

                    </span>

                    @else

                    <span class="inline-flex items-center
                                     px-3 py-1 rounded-full
                                     bg-red-100 text-red-700
                                     text-xs font-bold">

                        <span class="w-2 h-2 rounded-full
                                         bg-red-500 mr-2"></span>

                        Inactive

                    </span>

                    @endif

                </div>

            </div>

        </div>

    </div>


    {{-- Professional Information --}}
    <div class="card">

        <h2 class="text-lg font-bold text-slate-900 mb-5">
            Professional Information
        </h2>


        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">


            <div>
                <p class="field-label">
                    Doctor Name
                </p>

                <p class="text-sm text-slate-900 font-medium">
                    {{ $doctor->name }}
                </p>
            </div>


            <div>
                <p class="field-label">
                    Email Address
                </p>

                <p class="text-sm text-slate-900 font-medium">
                    {{ $doctor->email }}
                </p>
            </div>


            <div>
                <p class="field-label">
                    Phone Number
                </p>

                <p class="text-sm text-slate-900 font-medium">
                    {{ $user->phone ?: 'Not provided' }}
                </p>
            </div>


            <div>
                <p class="field-label">
                    Specialization
                </p>

                <p class="text-sm text-slate-900 font-medium">
                    {{ $doctor->specialization }}
                </p>
            </div>


            <div>
                <p class="field-label">
                    License Number
                </p>

                <p class="text-sm text-slate-900 font-medium">
                    {{ $doctor->license_number ?: 'Not provided' }}
                </p>
            </div>


            <div>
                <p class="field-label">
                    Hospital
                </p>

                <p class="text-sm text-slate-900 font-medium">
                    {{ $doctor->hospital?->name ?? 'Not assigned' }}
                </p>
            </div>


            <div>
                <p class="field-label">
                    Account Status
                </p>

                <p class="text-sm font-semibold
                    {{ $doctor->is_active ? 'text-green-600' : 'text-red-600' }}">

                    {{ $doctor->is_active ? 'Active' : 'Inactive' }}

                </p>
            </div>


            <div>
                <p class="field-label">
                    Account Created
                </p>

                <p class="text-sm text-slate-900 font-medium">
                    {{ $user->created_at?->format('F d, Y') }}
                </p>
            </div>

        </div>

    </div>


    {{-- Biography --}}
    <div class="card">

        <h2 class="text-lg font-bold text-slate-900 mb-4">
            Biography
        </h2>

        @if($doctor->bio)

        <p class="text-sm text-slate-600 leading-7 whitespace-pre-line">
            {{ $doctor->bio }}
        </p>

        @else

        <p class="text-sm text-slate-400">
            No biography has been added yet.
        </p>

        @endif

    </div>

    @if($doctor->image)
    <img src="{{ $doctor->image_url }}" alt="{{ $doctor->name }}" class="w-24 h-24 rounded-full object-cover border-4 border-white shadow">
    @else
    <div class="w-24 h-24 rounded-full
               bg-brand-100 text-brand-700
               flex items-center justify-center
               text-3xl font-extrabold
               border-4 border-white shadow">
        {{ strtoupper(substr($doctor->name, 0, 1)) }}
    </div>
    @endif

    {{-- Rating --}}
    <div class="card">

        <h2 class="text-lg font-bold text-slate-900 mb-5">
            Doctor Rating
        </h2>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">

            <div>
                <p class="field-label">
                    Average Rating
                </p>

                <p class="text-2xl font-extrabold text-slate-900">
                    {{ $doctor->average_rating ?? '—' }}
                </p>
            </div>

            <div>
                <p class="field-label">
                    Total Reviews
                </p>

                <p class="text-2xl font-extrabold text-slate-900">
                    {{ $doctor->ratings_count }}
                </p>
            </div>

        </div>

    </div>

</div>

@endsection
