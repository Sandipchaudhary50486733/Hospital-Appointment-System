<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'Admin — Meridian Health')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-slate-50 text-slate-900 font-sans h-screen overflow-hidden">

    {{-- =========================================================
         FLASH MESSAGES
    ========================================================== --}}
    @if (session('status') || session('error') || $errors->any())
        <div
            id="flash-data"
            @if(session('status'))
                data-status="{{ session('status') }}"
            @endif
            @if(session('error') || $errors->any())
                data-error="{{ session('error') ?? $errors->first() }}"
            @endif
            class="hidden">
        </div>
    @endif


    {{-- =========================================================
         MAIN FULL SCREEN CONTAINER
    ========================================================== --}}
    <div class="flex h-screen overflow-hidden">


        {{-- =====================================================
             SIDEBAR
        ====================================================== --}}
        <aside
            class="w-72 h-screen bg-slate-900 text-slate-300 flex flex-col shrink-0">

            {{-- =================================================
                 LOGO
            ================================================== --}}
            <a
                href="{{ route('admin.dashboard') }}"
                class="flex items-center gap-3 px-6 py-5 border-b border-slate-800 shrink-0">

                <i class="fa-solid fa-hospital text-brand-500 text-2xl"></i>

                <span class="font-extrabold text-white text-lg">
                    Meridian Health
                </span>

            </a>


            {{-- =================================================
                 NAVIGATION
            ================================================== --}}
            <nav class="flex-1 px-3 py-6 overflow-y-auto">

                {{-- Dashboard --}}
                <a
                    href="{{ route('admin.dashboard') }}"
                    class="flex items-center gap-4 px-4 py-3 rounded-xl text-sm font-semibold transition
                    {{ request()->routeIs('admin.dashboard')
                        ? 'bg-brand-500 text-white shadow-md'
                        : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">

                    <i class="fa-solid fa-gauge w-5 text-center text-base"></i>

                    <span>Dashboard</span>
                </a>


                {{-- Doctors --}}
                <a
                    href="{{ route('admin.doctors.index') }}"
                    class="flex items-center gap-4 px-4 py-3 rounded-xl text-sm font-semibold transition mt-1
                    {{ request()->routeIs('admin.doctors.*')
                        ? 'bg-brand-500 text-white shadow-md'
                        : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">

                    <i class="fa-solid fa-user-doctor w-5 text-center text-base"></i>

                    <span>Doctors</span>
                </a>


                {{-- Users --}}
                <a
                    href="{{ route('admin.users.index') }}"
                    class="flex items-center gap-4 px-4 py-3 rounded-xl text-sm font-semibold transition mt-1
                    {{ request()->routeIs('admin.users.*')
                        ? 'bg-brand-500 text-white shadow-md'
                        : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">

                    <i class="fa-solid fa-users w-5 text-center text-base"></i>

                    <span>Users</span>
                </a>


                {{-- Hospitals --}}
                <a
                    href="{{ route('admin.hospitals.index') }}"
                    class="flex items-center gap-4 px-4 py-3 rounded-xl text-sm font-semibold transition mt-1
                    {{ request()->routeIs('admin.hospitals.*')
                        ? 'bg-brand-500 text-white shadow-md'
                        : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">

                    <i class="fa-solid fa-hospital w-5 text-center text-base"></i>

                    <span>Hospitals</span>
                </a>


                {{-- Appointments --}}
                <a
                    href="{{ route('admin.appointments.index') }}"
                    class="flex items-center gap-4 px-4 py-3 rounded-xl text-sm font-semibold transition mt-1
                    {{ request()->routeIs('admin.appointments.*')
                        ? 'bg-brand-500 text-white shadow-md'
                        : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">

                    <i class="fa-solid fa-calendar-check w-5 text-center text-base"></i>

                    <span>Appointments</span>
                </a>


                {{-- Messages (contact form inbox) --}}
                @php $unreadMessages = \App\Models\ContactMessage::whereNull('read_at')->count(); @endphp
                <a
                    href="{{ route('admin.messages.index') }}"
                    class="flex items-center gap-4 px-4 py-3 rounded-xl text-sm font-semibold transition mt-1
                    {{ request()->routeIs('admin.messages.*')
                        ? 'bg-brand-500 text-white shadow-md'
                        : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">

                    <i class="fa-solid fa-envelope w-5 text-center text-base"></i>

                    <span>Messages</span>

                    @if ($unreadMessages > 0)
                        <span class="ml-auto bg-red-500 text-white text-xs font-bold rounded-full px-2 py-0.5">{{ $unreadMessages }}</span>
                    @endif
                </a>


                {{-- Divider --}}
                <div class="border-t border-slate-800 my-5"></div>


                {{-- Settings --}}
                <a
                    href="{{ route('admin.settings.edit') }}"
                    class="flex items-center gap-4 px-4 py-3 rounded-xl text-sm font-semibold transition
                    {{ request()->routeIs('admin.settings.*')
                        ? 'bg-brand-500 text-white shadow-md'
                        : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">

                    <i class="fa-solid fa-gear w-5 text-center text-base"></i>

                    <span>Settings</span>
                </a>


                {{-- Back to Website --}}
                <a
                    href="{{ route('public.home') }}"
                    class="flex items-center gap-4 px-4 py-3 rounded-xl text-sm font-semibold
                           text-slate-300 hover:bg-slate-800 hover:text-white transition mt-1">

                    <i class="fa-solid fa-arrow-left w-5 text-center text-base"></i>

                    <span>Back to Website</span>
                </a>

            </nav>


            {{-- =================================================
                 SIGN OUT
            ================================================== --}}
            <div class="px-3 py-4 border-t border-slate-800 shrink-0">

                <form
                    method="POST"
                    action="{{ route('logout') }}">

                    @csrf

                    <button
                        type="submit"
                        class="w-full flex items-center gap-4 px-4 py-3 rounded-xl
                               text-sm font-semibold text-red-400
                               hover:bg-red-500/10 hover:text-red-300
                               transition">

                        <i class="fa-solid fa-arrow-right-from-bracket w-5 text-center text-base"></i>

                        <span>Sign out</span>

                    </button>

                </form>

            </div>

        </aside>


        {{-- =====================================================
             RIGHT SIDE / MAIN AREA
        ====================================================== --}}
        <div class="flex-1 flex flex-col min-w-0 h-screen overflow-hidden">


            {{-- =================================================
                 TOP HEADER
            ================================================== --}}
            <header
                class="bg-white border-b border-slate-100 px-8 py-4 flex items-center justify-between shrink-0">

                {{-- Left --}}
                <span class="text-base font-semibold text-slate-500">
                    Admin Panel
                </span>


                {{-- Right --}}
                <div class="flex items-center gap-2">

                    <i class="fa-solid fa-circle-user text-brand-500 text-lg"></i>

                    <span class="text-sm font-semibold text-slate-700">
                        {{ auth()->user()->name }}
                    </span>

                </div>

            </header>


            {{-- =================================================
                 MAIN CONTENT
                 ONLY THIS SECTION SCROLLS
            ================================================== --}}
            <main
                class="flex-1 min-h-0 overflow-y-auto overflow-x-auto">

                @yield('content')

            </main>

        </div>

    </div>


</body>
</html>