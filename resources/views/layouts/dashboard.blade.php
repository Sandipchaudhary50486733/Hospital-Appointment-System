<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Meridian Health')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-50 text-slate-900 font-sans min-h-screen">

    @php $u = auth()->user(); @endphp

    @if (session('status') || $errors->any())
    <div id="flash-data" @if(session('status')) data-status="{{ session('status') }}" @endif @if($errors->any()) data-error="{{ $errors->first() }}" @endif
        class="hidden"></div>
    @endif

    <header class="bg-white border-b border-slate-100 sticky top-0 z-20">
        <div class="px-6 py-3.5 flex items-center justify-between">
            <a href="{{ $u->isAdmin() ? route('admin.dashboard') : ($u->isDoctor() ? route('doctor.appointments.index') : route('home')) }}" class="flex items-center gap-2 font-extrabold text-lg text-slate-900">
                <i class="fa-solid fa-hospital text-brand-500 text-xl"></i> Meridian Health
            </a>

            <nav class="hidden md:flex items-center gap-1 text-sm font-semibold">
                @if ($u->isPatient())
                {{-- Patient's own dashboard home, plus the public marketing pages --}}
                <a href="{{ route('home') }}" class="px-3.5 py-2 rounded-lg {{ request()->routeIs('home') ? 'bg-brand-50 text-brand-700' : 'text-slate-600 hover:bg-slate-50' }}">Home</a>
                <a href="{{ route('public.about') }}" class="px-3.5 py-2 rounded-lg {{ request()->routeIs('public.about') ? 'bg-brand-50 text-brand-700' : 'text-slate-600 hover:bg-slate-50' }}">About</a>
                <a href="{{ route('public.services') }}" class="px-3.5 py-2 rounded-lg {{ request()->routeIs('public.services') ? 'bg-brand-50 text-brand-700' : 'text-slate-600 hover:bg-slate-50' }}">Services</a>
                <a href="{{ route('public.doctors') }}" class="px-3.5 py-2 rounded-lg {{ request()->routeIs('public.doctors') ? 'bg-brand-50 text-brand-700' : 'text-slate-600 hover:bg-slate-50' }}">Doctors</a>
                <a href="{{ route('appointments.index') }}" class="px-3.5 py-2 rounded-lg {{ request()->routeIs('appointments.*') ? 'bg-brand-50 text-brand-700' : 'text-slate-600 hover:bg-slate-50' }}">Appointments</a>
                <a href="{{ route('public.contact') }}" class="px-3.5 py-2 rounded-lg {{ request()->routeIs('public.contact') ? 'bg-brand-50 text-brand-700' : 'text-slate-600 hover:bg-slate-50' }}">Contact Us</a>

                @elseif ($u->isDoctor())
                {{-- Public marketing pages, then the doctor's own dashboard link --}}
                <a href="{{ route('public.home') }}" class="px-3.5 py-2 rounded-lg {{ request()->routeIs('public.home') ? 'bg-brand-50 text-brand-700' : 'text-slate-600 hover:bg-slate-50' }}">Home</a>
                <a href="{{ route('public.about') }}" class="px-3.5 py-2 rounded-lg {{ request()->routeIs('public.about') ? 'bg-brand-50 text-brand-700' : 'text-slate-600 hover:bg-slate-50' }}">About</a>
                <a href="{{ route('public.services') }}" class="px-3.5 py-2 rounded-lg {{ request()->routeIs('public.services') ? 'bg-brand-50 text-brand-700' : 'text-slate-600 hover:bg-slate-50' }}">Services</a>
                <a href="{{ route('public.contact') }}" class="px-3.5 py-2 rounded-lg {{ request()->routeIs('public.contact') ? 'bg-brand-50 text-brand-700' : 'text-slate-600 hover:bg-slate-50' }}">Contact Us</a>
                <a href="{{ route('doctor.appointments.index') }}" class="px-3.5 py-2 rounded-lg {{ request()->routeIs('doctor.appointments.*') ? 'bg-brand-50 text-brand-700' : 'text-slate-600 hover:bg-slate-50' }}">My Appointments</a>

                @elseif ($u->isAdmin())
                {{-- Public marketing pages, then all the existing admin sections --}}
                <a href="{{ route('public.home') }}" class="px-3.5 py-2 rounded-lg {{ request()->routeIs('public.home') ? 'bg-brand-50 text-brand-700' : 'text-slate-600 hover:bg-slate-50' }}">Home</a>
                <a href="{{ route('public.about') }}" class="px-3.5 py-2 rounded-lg {{ request()->routeIs('public.about') ? 'bg-brand-50 text-brand-700' : 'text-slate-600 hover:bg-slate-50' }}">About</a>
                <a href="{{ route('public.services') }}" class="px-3.5 py-2 rounded-lg {{ request()->routeIs('public.services') ? 'bg-brand-50 text-brand-700' : 'text-slate-600 hover:bg-slate-50' }}">Services</a>
                <a href="{{ route('public.contact') }}" class="px-3.5 py-2 rounded-lg {{ request()->routeIs('public.contact') ? 'bg-brand-50 text-brand-700' : 'text-slate-600 hover:bg-slate-50' }}">Contact Us</a>
                <a href="{{ route('admin.dashboard') }}" class="px-3.5 py-2 rounded-lg {{ request()->routeIs('admin.dashboard') ? 'bg-brand-50 text-brand-700' : 'text-slate-600 hover:bg-slate-50' }}">Dashboard</a>
                <a href="{{ route('admin.doctors.index') }}" class="px-3.5 py-2 rounded-lg {{ request()->routeIs('admin.doctors.*') ? 'bg-brand-50 text-brand-700' : 'text-slate-600 hover:bg-slate-50' }}">Doctors</a>
                <a href="{{ route('admin.appointments.index') }}" class="px-3.5 py-2 rounded-lg {{ request()->routeIs('admin.appointments.*') ? 'bg-brand-50 text-brand-700' : 'text-slate-600 hover:bg-slate-50' }}">Appointments</a>
                <a href="{{ route('admin.users.index') }}" class="px-3.5 py-2 rounded-lg {{ request()->routeIs('admin.users.*') ? 'bg-brand-50 text-brand-700' : 'text-slate-600 hover:bg-slate-50' }}">Users</a>
                @endif
            </nav>

            <div class="flex items-center gap-3.5">
                <span class="text-sm font-semibold text-slate-600">
                    {{ $u->name }}
                    @if ($u->isDoctor())<span class="bg-brand-50 text-brand-700 text-[11px] px-2 py-0.5 rounded-full ml-1">Provider</span>@endif
                    @if ($u->isAdmin())<span class="bg-brand-50 text-brand-700 text-[11px] px-2 py-0.5 rounded-full ml-1">Admin</span>@endif
                </span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="border border-slate-200 hover:bg-red-50 hover:border-red-200 hover:text-red-600 text-xs font-semibold rounded-full px-4 py-2">Sign out</button>
                </form>
            </div>
        </div>
    </header>

    @yield('content')

</body>
</html>
