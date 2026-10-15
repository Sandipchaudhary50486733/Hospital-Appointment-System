@extends('layouts.dashboard')

@section('content')

<div class="max-w-5xl mx-auto space-y-6">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-slate-900">
                My Profile
            </h1>

            <p class="text-sm text-slate-500 mt-1">
                View your personal and account information.
            </p>
        </div>

        <div class="flex gap-2">
            <a href="{{ route('patient.profile.edit') }}" class="btn-primary">
                <i class="fa-solid fa-pen mr-2"></i>
                Edit Profile
            </a>

            <a href="{{ route('patient.profile.password') }}" class="btn-ghost">
                <i class="fa-solid fa-lock mr-2"></i>
                Change Password
            </a>
        </div>
    </div>


    {{-- Success Message --}}
    @if(session('status'))
    <div class="bg-green-50 border border-green-200 text-green-700 rounded-xl px-4 py-3 text-sm">
        <i class="fa-solid fa-circle-check mr-2"></i>
        {{ session('status') }}
    </div>
    @endif


    {{-- Profile Card --}}
    <div class="card">

        <div class="flex flex-col sm:flex-row items-center sm:items-start gap-6">

            {{-- Avatar --}}
            <div class="w-28 h-28 rounded-full overflow-hidden
            border-4 border-white shadow
            flex-shrink-0">

                @if($user->image)

                <img src="{{ $user->image_url }}" alt="{{ $user->name }}" class="w-full h-full object-cover">

                @else

                <div class="w-full h-full
                    bg-brand-100 text-brand-700
                    flex items-center justify-center
                    text-4xl font-extrabold">

                    {{ strtoupper(substr($user->name, 0, 1)) }}

                </div>

                @endif

            </div>


            {{-- Basic Information --}}
            <div class="text-center sm:text-left">

                <h2 class="text-2xl font-extrabold text-slate-900">
                    {{ $user->name }}
                </h2>

                <p class="text-sm text-slate-500 mt-1">
                    {{ $user->email }}
                </p>

                <div class="mt-3">
                    <span class="inline-flex items-center px-3 py-1 rounded-full
                                 bg-brand-100 text-brand-700 text-xs font-bold">
                        <i class="fa-solid fa-user mr-1.5"></i>
                        Patient
                    </span>
                </div>

            </div>

        </div>

    </div>


    {{-- Personal Information --}}
    <div class="card">

        <h2 class="text-lg font-bold text-slate-900 mb-5">
            Personal Information
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

            <div>
                <p class="field-label">Full Name</p>
                <p class="text-sm text-slate-900 font-medium">
                    {{ $user->name }}
                </p>
            </div>

            <div>
                <p class="field-label">Email Address</p>
                <p class="text-sm text-slate-900 font-medium">
                    {{ $user->email }}
                </p>
            </div>

            <div>
                <p class="field-label">Phone Number</p>
                <p class="text-sm text-slate-900 font-medium">
                    {{ $user->phone ?: 'Not provided' }}
                </p>
            </div>

            <div>
                <p class="field-label">Gender</p>
                <p class="text-sm text-slate-900 font-medium">
                    {{ $user->gender ?: 'Not provided' }}
                </p>
            </div>

            <div>
                <p class="field-label">Date of Birth</p>
                <p class="text-sm text-slate-900 font-medium">
                    {{ $user->date_of_birth?->format('F d, Y') ?? 'Not provided' }}
                </p>
            </div>

            <div>
                <p class="field-label">Role</p>
                <p class="text-sm text-slate-900 font-medium">
                    Patient
                </p>
            </div>

        </div>

    </div>


    {{-- Account Information --}}
    <div class="card">

        <h2 class="text-lg font-bold text-slate-900 mb-5">
            Account Information
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

            <div>
                <p class="field-label">Account Created</p>

                <p class="text-sm text-slate-900 font-medium">
                    {{ $user->created_at?->format('F d, Y') }}
                </p>
            </div>

            <div>
                <p class="field-label">Account Type</p>

                <p class="text-sm text-slate-900 font-medium">
                    Patient Account
                </p>
            </div>

        </div>

    </div>

</div>

@endsection
