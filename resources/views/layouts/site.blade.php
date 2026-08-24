<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>@yield('title', 'Meridian Health')</title>
@vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-50 text-slate-900 font-sans">

@if (session('status') || $errors->any())
    <div id="flash-data"
         @if(session('status')) data-status="{{ session('status') }}" @endif
         @if($errors->any()) data-error="{{ $errors->first() }}" @endif
         class="hidden"></div>
@endif

<header class="bg-white border-b border-slate-100 sticky top-0 z-20">
    <div class="max-w-6xl mx-auto px-6 py-4 flex items-center justify-between">
        <a href="{{ route('public.home') }}" class="flex items-center gap-2 font-extrabold text-lg text-slate-900">
            <i class="fa-solid fa-hospital text-brand-500 text-xl"></i> Meridian Health
        </a>
        <nav class="hidden md:flex items-center gap-1 text-sm font-semibold">
            <a href="{{ route('public.home') }}" class="px-3.5 py-2 rounded-lg {{ request()->routeIs('public.home') ? 'bg-brand-50 text-brand-700' : 'text-slate-600 hover:bg-slate-50' }}">Home</a>
            <a href="{{ route('public.about') }}" class="px-3.5 py-2 rounded-lg {{ request()->routeIs('public.about') ? 'bg-brand-50 text-brand-700' : 'text-slate-600 hover:bg-slate-50' }}">About</a>
            <a href="{{ route('public.services') }}" class="px-3.5 py-2 rounded-lg {{ request()->routeIs('public.services') ? 'bg-brand-50 text-brand-700' : 'text-slate-600 hover:bg-slate-50' }}">Services</a>
            <a href="{{ route('public.doctors') }}" class="px-3.5 py-2 rounded-lg {{ request()->routeIs('public.doctors') ? 'bg-brand-50 text-brand-700' : 'text-slate-600 hover:bg-slate-50' }}">Doctors</a>
            <a href="{{ route('public.contact') }}" class="px-3.5 py-2 rounded-lg {{ request()->routeIs('public.contact') ? 'bg-brand-50 text-brand-700' : 'text-slate-600 hover:bg-slate-50' }}">Contact Us</a>
        </nav>
        <div class="flex items-center gap-3">
            @auth
                @php $u = auth()->user(); @endphp
                <a href="{{ $u->isAdmin() ? route('admin.dashboard') : ($u->isDoctor() ? route('doctor.appointments.index') : route('home')) }}" class="btn-primary">My Dashboard</a>
            @else
                <a href="{{ route('login') }}" class="text-brand-500 border border-brand-500 hover:bg-brand-50 font-semibold text-sm rounded-lg px-4 py-2.5">Sign In</a>
                <a href="{{ route('register') }}" class="btn-primary">Register</a>
            @endauth
        </div>
    </div>
</header>

@yield('content')

<footer class="bg-slate-900 text-slate-300 mt-16">
    <div class="max-w-6xl mx-auto px-6 py-14 grid md:grid-cols-3 gap-10">
        <div>
            <h4 class="text-white font-bold mb-3">Meridian Health</h4>
            <p class="text-sm text-slate-400 leading-relaxed">Compassionate, modern healthcare for you and your family. Book appointments online and manage your care in one place.</p>
        </div>
        <div>
            <h4 class="text-white font-bold mb-3">Quick Links</h4>
            <div class="flex flex-col gap-2 text-sm">
                <a href="{{ route('public.about') }}" class="text-slate-400 hover:text-white">About Us</a>
                <a href="{{ route('public.services') }}" class="text-slate-400 hover:text-white">Services</a>
                <a href="{{ route('public.doctors') }}" class="text-slate-400 hover:text-white">Find a Doctor</a>
                <a href="{{ route('public.contact') }}" class="text-slate-400 hover:text-white">Contact Us</a>
            </div>
        </div>
        <div>
            <h4 class="text-white font-bold mb-3">Contact</h4>
            <div class="flex flex-col gap-2 text-sm text-slate-400">
                <span><i class="fa-solid fa-phone mr-2"></i>(555) 010-2345</span>
                <span><i class="fa-solid fa-envelope mr-2"></i>care@meridianhealth.test</span>
                <span><i class="fa-solid fa-location-dot mr-2"></i>120 Wellness Ave, Springfield</span>
            </div>
        </div>
    </div>
    <div class="border-t border-slate-800 text-center text-xs text-slate-500 py-5">
        &copy; {{ date('Y') }} Meridian Health. For medical emergencies, call 911.
    </div>
</footer>

</body>
</html>