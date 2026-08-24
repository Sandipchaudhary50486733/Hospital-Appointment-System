@extends(auth()->check() ? 'layouts.dashboard' : 'layouts.site')

@section('title', 'Meridian Health — Compassionate, Modern Healthcare')

@section('content')

<section class="bg-gradient-to-br from-[#005bea] to-[#00c6fb] text-white py-20 px-6">
    <div class="max-w-6xl mx-auto grid md:grid-cols-2 gap-12 items-center">
        <div>
            <h1 class="text-4xl md:text-5xl font-extrabold leading-tight mb-5">Your health, on your schedule.</h1>
            <p class="text-white/90 leading-relaxed mb-8 max-w-md">Book appointments with trusted doctors, manage your records, and stay on top of your care — all from one simple patient portal.</p>
            <div class="flex gap-3 flex-wrap">
                <a href="{{ route('register') }}" class="bg-white text-brand-700 font-bold text-sm rounded-lg px-6 py-3.5 hover:bg-slate-100">Book an Appointment</a>
                <a href="{{ route('public.doctors') }}" class="border border-white text-white font-bold text-sm rounded-lg px-6 py-3.5 hover:bg-white/10">Find a Doctor</a>
            </div>
        </div>
        <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTQodEeSXly0r7UR6npCohqtBt9SNBhmp_1cTUOLOn-Gg&s=10"
             alt="Doctor with patient" class="w-full h-80 object-cover rounded-3xl shadow-2xl">
    </div>
</section>

<section class="max-w-6xl mx-auto px-6 py-16">
    <div class="grid md:grid-cols-3 gap-6">
        <div class="card"><i class="fa-solid fa-calendar-check text-2xl text-brand-500"></i><h3 class="font-bold mt-3 mb-2">Easy Booking</h3><p class="text-sm text-slate-500 leading-relaxed">Choose a doctor, pick a time, and request your appointment in under a minute.</p></div>
        <div class="card"><i class="fa-solid fa-notes-medical text-2xl text-brand-500"></i><h3 class="font-bold mt-3 mb-2">Digital Records</h3><p class="text-sm text-slate-500 leading-relaxed">View your appointment history and status, whenever you need it.</p></div>
        <div class="card"><i class="fa-solid fa-shield-heart text-2xl text-brand-500"></i><h3 class="font-bold mt-3 mb-2">Secure &amp; Private</h3><p class="text-sm text-slate-500 leading-relaxed">Your health information is protected at every step, always.</p></div>
    </div>
</section>

<section class="bg-white py-16 px-6">
    <div class="max-w-6xl mx-auto">
        <h2 class="text-2xl font-extrabold mb-1">Meet a few of our doctors</h2>
        <p class="text-slate-500 mb-8">Experienced, board-certified physicians across a range of specialties.</p>

        <div class="grid md:grid-cols-3 gap-6">
            @forelse ($doctors as $doctor)
                <div class="border border-slate-100 rounded-2xl p-6 text-center">
                    @if ($doctor->image_url)
                        <img src="{{ $doctor->image_url }}" alt="{{ $doctor->name }}" class="w-16 h-16 rounded-full object-cover mx-auto mb-3">
                    @else
                        <div class="w-16 h-16 rounded-full bg-brand-50 flex items-center justify-center mx-auto mb-3">
                            <i class="fa-solid fa-user-doctor text-2xl text-brand-500"></i>
                        </div>
                    @endif
                    <h3 class="font-bold">{{ $doctor->name }}</h3>
                    <p class="text-xs text-slate-500 mt-1">{{ $doctor->specialization }}</p>
                    <div class="flex justify-center mt-2">
                        <x-star-rating :rating="$doctor->average_rating" :count="$doctor->ratings_count" />
                    </div>
                </div>
            @empty
                <p class="text-slate-500">Doctor profiles will appear here once added.</p>
            @endforelse
        </div>

        <div class="text-center mt-8">
            <a href="{{ route('public.doctors') }}" class="btn-ghost">View all doctors</a>
        </div>
    </div>
</section>

<section class="py-16 px-6 text-center">
    <h2 class="text-2xl font-extrabold mb-3">Ready to book your visit?</h2>
    <p class="text-slate-500 mb-6">Creating an account takes less than a minute.</p>
    <a href="{{ route('register') }}" class="btn-primary">Create your patient account</a>
</section>

@endsection
