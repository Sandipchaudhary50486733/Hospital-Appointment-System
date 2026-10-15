<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'Meridian Health')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-slate-50 text-slate-900 font-sans min-h-screen">

    @php
    $u = auth()->user();

    /*
    |--------------------------------------------------------------------------
    | Profile Image
    |--------------------------------------------------------------------------
    | Patient image:
    | users.image
    |
    | Doctor image:
    | doctors.image
    */
    $profileImage = null;

    if ($u->isPatient()) {
    $profileImage = $u->image;
    } elseif ($u->isDoctor() && $u->doctor) {
    $profileImage = $u->doctor->image;
    }
    @endphp


    {{-- =========================================================
         FLASH MESSAGE
    ========================================================== --}}

    @if (session('status') || session('error') || $errors->any())

    <div id="flash-data" @if(session('status')) data-status="{{ session('status') }}" @endif @if(session('error') || $errors->any())
        data-error="{{ session('error') ?? $errors->first() }}"
        @endif

        class="hidden">
    </div>

    @endif


    {{-- =========================================================
         HEADER / NAVBAR
    ========================================================== --}}

    <header class="bg-white border-b border-slate-100 sticky top-0 z-20">

        <div class="px-6 py-3.5 flex items-center justify-between">


            {{-- =================================================
                 LOGO
            ================================================== --}}

            <a href="{{ $u->isAdmin()
                    ? route('public.home')
                    : ($u->isDoctor()
                        ? route('doctor.appointments.index')
                        : route('home')) }}" class="flex items-center gap-2 font-extrabold text-lg text-slate-900">

                <i class="fa-solid fa-hospital text-brand-500 text-xl"></i>

                Meridian Health

            </a>


            {{-- =================================================
                 MAIN NAVIGATION
                 PROFILE LINK REMOVED FROM PATIENT + DOCTOR
            ================================================== --}}

            <nav class="hidden md:flex items-center gap-1 text-sm font-semibold">

                {{-- =================================================
                     PATIENT NAVIGATION
                ================================================== --}}

                @if ($u->isPatient())

                <a href="{{ route('home') }}" class="px-3.5 py-2 rounded-lg
                        {{ request()->routeIs('home')
                            ? 'bg-brand-50 text-brand-700'
                            : 'text-slate-600 hover:bg-slate-50' }}">
                    Home
                </a>


                <a href="{{ route('public.about') }}" class="px-3.5 py-2 rounded-lg
                        {{ request()->routeIs('public.about')
                            ? 'bg-brand-50 text-brand-700'
                            : 'text-slate-600 hover:bg-slate-50' }}">
                    About
                </a>


                <a href="{{ route('public.services') }}" class="px-3.5 py-2 rounded-lg
                        {{ request()->routeIs('public.services')
                            ? 'bg-brand-50 text-brand-700'
                            : 'text-slate-600 hover:bg-slate-50' }}">
                    Services
                </a>


                <a href="{{ route('public.doctors') }}" class="px-3.5 py-2 rounded-lg
                        {{ request()->routeIs('public.doctors')
                            ? 'bg-brand-50 text-brand-700'
                            : 'text-slate-600 hover:bg-slate-50' }}">
                    Doctors
                </a>


                <a href="{{ route('appointments.index') }}" class="px-3.5 py-2 rounded-lg
                        {{ request()->routeIs('appointments.*')
                            ? 'bg-brand-50 text-brand-700'
                            : 'text-slate-600 hover:bg-slate-50' }}">
                    Appointments
                </a>


                {{-- Profile link intentionally removed --}}


                <a href="{{ route('public.contact') }}" class="px-3.5 py-2 rounded-lg
                        {{ request()->routeIs('public.contact')
                            ? 'bg-brand-50 text-brand-700'
                            : 'text-slate-600 hover:bg-slate-50' }}">
                    Contact Us
                </a>


                {{-- =================================================
                     DOCTOR NAVIGATION
                ================================================== --}}

                @elseif ($u->isDoctor())

                <a href="{{ route('public.home') }}" class="px-3.5 py-2 rounded-lg
                        {{ request()->routeIs('public.home')
                            ? 'bg-brand-50 text-brand-700'
                            : 'text-slate-600 hover:bg-slate-50' }}">
                    Home
                </a>


                <a href="{{ route('public.about') }}" class="px-3.5 py-2 rounded-lg
                        {{ request()->routeIs('public.about')
                            ? 'bg-brand-50 text-brand-700'
                            : 'text-slate-600 hover:bg-slate-50' }}">
                    About
                </a>


                <a href="{{ route('public.services') }}" class="px-3.5 py-2 rounded-lg
                        {{ request()->routeIs('public.services')
                            ? 'bg-brand-50 text-brand-700'
                            : 'text-slate-600 hover:bg-slate-50' }}">
                    Services
                </a>


                <a href="{{ route('public.contact') }}" class="px-3.5 py-2 rounded-lg
                        {{ request()->routeIs('public.contact')
                            ? 'bg-brand-50 text-brand-700'
                            : 'text-slate-600 hover:bg-slate-50' }}">
                    Contact Us
                </a>


                <a href="{{ route('doctor.appointments.index') }}" class="px-3.5 py-2 rounded-lg
                        {{ request()->routeIs('doctor.appointments.*')
                            ? 'bg-brand-50 text-brand-700'
                            : 'text-slate-600 hover:bg-slate-50' }}">
                    My Appointments
                </a>

                <a href="{{ route('doctor.availability.index') }}" class="px-3.5 py-2 rounded-lg
    {{ request()->routeIs('doctor.availability.*')
        ? 'bg-brand-50 text-brand-700'
        : 'text-slate-600 hover:bg-slate-50' }}">
                    Appointment Availability
                </a>


                {{-- Profile link intentionally removed --}}


                {{-- =================================================
                     ADMIN NAVIGATION
                ================================================== --}}

                @elseif ($u->isAdmin())

                <a href="{{ route('public.home') }}" class="px-3.5 py-2 rounded-lg
                        {{ request()->routeIs('public.home')
                            ? 'bg-brand-50 text-brand-700'
                            : 'text-slate-600 hover:bg-slate-50' }}">
                    Home
                </a>


                <a href="{{ route('public.about') }}" class="px-3.5 py-2 rounded-lg
                        {{ request()->routeIs('public.about')
                            ? 'bg-brand-50 text-brand-700'
                            : 'text-slate-600 hover:bg-slate-50' }}">
                    About
                </a>


                <a href="{{ route('public.services') }}" class="px-3.5 py-2 rounded-lg
                        {{ request()->routeIs('public.services')
                            ? 'bg-brand-50 text-brand-700'
                            : 'text-slate-600 hover:bg-slate-50' }}">
                    Services
                </a>


                <a href="{{ route('public.contact') }}" class="px-3.5 py-2 rounded-lg
                        {{ request()->routeIs('public.contact')
                            ? 'bg-brand-50 text-brand-700'
                            : 'text-slate-600 hover:bg-slate-50' }}">
                    Contact Us
                </a>

                @endif

            </nav>


            {{-- =================================================
                 RIGHT SIDE
            ================================================== --}}

            <div class="flex items-center gap-3.5">


                {{-- =================================================
                     ADMIN PROFILE
                ================================================== --}}

                @if ($u->isAdmin())

                <div class="relative">

                    <button type="button" data-dropdown-toggle="#admin-profile-menu-2" class="flex items-center gap-2 bg-white
                                   border border-slate-200
                                   hover:bg-slate-50
                                   rounded-lg px-4 py-2.5
                                   text-sm font-semibold text-slate-700">

                        <i class="fa-solid fa-user-circle text-brand-500"></i>

                        {{ $u->name }}

                        <i class="fa-solid fa-chevron-down text-xs text-slate-400"></i>

                    </button>


                    <div id="admin-profile-menu-2" data-dropdown-menu class="hidden absolute right-0 mt-2 w-48
                                   bg-white border border-slate-200
                                   rounded-lg shadow-lg py-1.5 z-30">

                        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2 px-4 py-2.5
                                       text-sm text-slate-700 hover:bg-slate-50
                                       {{ request()->routeIs('admin.dashboard')
                                            ? 'bg-brand-50 text-brand-700'
                                            : '' }}">

                            <i class="fa-solid fa-gauge w-4 text-slate-400"></i>

                            Dashboard

                        </a>


                        <a href="{{ route('admin.settings.edit') }}" class="flex items-center gap-2 px-4 py-2.5
                                       text-sm text-slate-700 hover:bg-slate-50
                                       {{ request()->routeIs('admin.settings.*')
                                            ? 'bg-brand-50 text-brand-700'
                                            : '' }}">

                            <i class="fa-solid fa-gear w-4 text-slate-400"></i>

                            Settings

                        </a>


                        <div class="border-t border-slate-100 my-1"></div>


                        <form method="POST" action="{{ route('logout') }}">

                            @csrf

                            <button type="submit" class="w-full text-left flex items-center gap-2
                                           px-4 py-2.5 text-sm text-red-600
                                           hover:bg-red-50">

                                <i class="fa-solid fa-arrow-right-from-bracket w-4"></i>

                                Sign out

                            </button>

                        </form>

                    </div>

                </div>


                {{-- =================================================
                     PATIENT / DOCTOR PROFILE
                ================================================== --}}

                @else

                <div class="relative">


                    {{-- =================================================
                             PROFILE BUTTON
                        ================================================== --}}

                    <button type="button" data-dropdown-toggle="#user-profile-menu" class="flex items-center gap-3 bg-white
                                   border border-slate-200
                                   hover:bg-slate-50
                                   rounded-xl px-2.5 py-2
                                   transition">


                        {{-- PROFILE PHOTO --}}

                        <div class="w-10 h-10 rounded-full overflow-hidden
                                       bg-brand-100 text-brand-700
                                       flex items-center justify-center
                                       flex-shrink-0 border border-slate-200">

                            @if ($profileImage)

                            <img src="{{ route('media', ['path' => $profileImage]) }}" alt="{{ $u->name }}" class="w-full h-full object-cover">

                            @else

                            <span class="font-bold text-sm">
                                {{ strtoupper(substr($u->name, 0, 1)) }}
                            </span>

                            @endif

                        </div>


                        {{-- NAME AND ROLE --}}

                        <div class="hidden sm:block text-left">

                            <div class="text-sm font-bold text-slate-800 leading-tight">

                                {{ $u->name }}

                            </div>


                            <div class="text-xs text-slate-500 mt-0.5">

                                {{ $u->isDoctor() ? 'Doctor' : 'Patient' }}

                            </div>

                        </div>


                        {{-- ARROW --}}

                        <i class="fa-solid fa-chevron-down
                                       text-xs text-slate-400 mr-1"></i>


                    </button>


                    {{-- =================================================
                             DROPDOWN MENU
                        ================================================== --}}

                    <div id="user-profile-menu" data-dropdown-menu class="hidden absolute right-0 mt-2 w-60
                                   bg-white border border-slate-200
                                   rounded-xl shadow-lg py-2 z-30">


                        {{-- PROFILE HEADER --}}

                        <div class="px-4 py-3 border-b border-slate-100">

                            <div class="flex items-center gap-3">


                                <div class="w-11 h-11 rounded-full overflow-hidden
                                               bg-brand-100 text-brand-700
                                               flex items-center justify-center
                                               flex-shrink-0">

                                    @if ($profileImage)

                                    <img src="{{ route('media', ['path' => $profileImage]) }}" alt="{{ $u->name }}" class="w-full h-full object-cover">

                                    @else

                                    <span class="font-bold">
                                        {{ strtoupper(substr($u->name, 0, 1)) }}
                                    </span>

                                    @endif

                                </div>


                                <div class="min-w-0">

                                    <p class="text-sm font-bold text-slate-800 truncate">

                                        {{ $u->name }}

                                    </p>


                                    <p class="text-xs text-slate-500 truncate">

                                        {{ $u->email }}

                                    </p>

                                </div>

                            </div>

                        </div>


                        {{-- =================================================
                                 VIEW PROFILE
                            ================================================== --}}

                        <a href="{{ $u->isDoctor()
                                    ? route('doctor.profile')
                                    : route('patient.profile') }}" class="flex items-center gap-3 px-4 py-3
                                       text-sm font-semibold text-slate-700
                                       hover:bg-brand-50 hover:text-brand-700">

                            <i class="fa-solid fa-user w-5 text-slate-400"></i>

                            <span>View Profile</span>

                        </a>


                        {{-- =================================================
                                 EDIT PROFILE
                            ================================================== --}}

                        <a href="{{ $u->isDoctor()
                                    ? route('doctor.profile.edit')
                                    : route('patient.profile.edit') }}" class="flex items-center gap-3 px-4 py-3
                                       text-sm font-semibold text-slate-700
                                       hover:bg-slate-50">

                            <i class="fa-solid fa-pen w-5 text-slate-400"></i>

                            <span>Edit Profile</span>

                        </a>


                        {{-- DIVIDER --}}

                        <div class="border-t border-slate-100 my-1"></div>


                        {{-- =================================================
                                 SIGN OUT
                            ================================================== --}}

                        <form method="POST" action="{{ route('logout') }}">

                            @csrf

                            <button type="submit" class="w-full text-left flex items-center
                                           gap-3 px-4 py-3
                                           text-sm font-semibold
                                           text-red-600 hover:bg-red-50">

                                <i class="fa-solid fa-arrow-right-from-bracket w-5"></i>

                                <span>Sign out</span>

                            </button>

                        </form>


                    </div>

                </div>

                @endif

            </div>

        </div>

    </header>


    {{-- =========================================================
         PAGE CONTENT
    ========================================================== --}}

    @yield('content')


</body>
</html>
