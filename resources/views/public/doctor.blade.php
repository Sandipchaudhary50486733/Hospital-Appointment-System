@extends(auth()->check() ? 'layouts.dashboard' : 'layouts.site')

@section('title', $doctor->name . ' — Meridian Health')

@section('content')

<section class="max-w-5xl mx-auto px-6 py-16">

    {{-- Back to doctors --}}
    <a href="{{ route('public.doctors') }}"
       class="text-sm font-semibold text-brand-600 hover:text-brand-700">
        <i class="fa-solid fa-arrow-left mr-1"></i>
        Back to Doctors
    </a>

    {{-- Doctor profile card --}}
    <div class="card mt-6">

        <div class="grid md:grid-cols-3 gap-8 items-start">

            {{-- Doctor photo --}}
            <div class="text-center">

                @if ($doctor->image_url)

                    <img src="{{ $doctor->image_url }}"
                         alt="{{ $doctor->name }}"
                         class="w-40 h-40 rounded-full object-cover mx-auto border border-slate-200">

                @else

                    <div class="w-40 h-40 rounded-full bg-brand-50
                                flex items-center justify-center mx-auto">
                        <i class="fa-solid fa-user-doctor text-5xl text-brand-500"></i>
                    </div>

                @endif

            </div>


            {{-- Doctor information --}}
            <div class="md:col-span-2">

                {{-- Name --}}
                <h1 class="text-3xl font-extrabold">
                    {{ $doctor->name }}
                </h1>

                {{-- Specialization --}}
                <p class="text-brand-700 font-semibold mt-2">
                    {{ $doctor->specialization }}
                </p>


                {{-- Hospital --}}
                @if ($doctor->hospital)

                    <div class="flex items-start gap-3 mt-5">

                        <i class="fa-solid fa-hospital text-brand-500 mt-1"></i>

                        <div>
                            <p class="font-bold text-sm">
                                Hospital
                            </p>

                            <p class="text-sm text-slate-500">
                                {{ $doctor->hospital->name }}
                            </p>

                        </div>

                    </div>

                @else

                    <div class="flex items-start gap-3 mt-5">

                        <i class="fa-solid fa-hospital text-slate-400 mt-1"></i>

                        <div>
                            <p class="font-bold text-sm text-slate-500">
                                Hospital
                            </p>

                            <p class="text-sm text-slate-400">
                                Hospital not assigned
                            </p>
                        </div>

                    </div>

                @endif


                {{-- Rating --}}
                <div class="mt-5">

                    <x-star-rating
                        :rating="$doctor->average_rating"
                        :count="$doctor->ratings_count"
                    />

                </div>


                {{-- Bio --}}
                @if ($doctor->bio)

                    <div class="mt-6">

                        <h2 class="font-bold text-lg mb-2">
                            About the doctor
                        </h2>

                        <p class="text-sm text-slate-500 leading-relaxed">
                            {{ $doctor->bio }}
                        </p>

                    </div>

                @endif


                {{-- Email --}}
                @if ($doctor->email)

                    <div class="mt-5 text-sm text-slate-500">

                        <i class="fa-solid fa-envelope text-brand-500 mr-2"></i>

                        {{ $doctor->email }}

                    </div>

                @endif


                {{-- License number --}}
                @if ($doctor->license_number)

                    <div class="mt-3 text-sm text-slate-500">

                        <i class="fa-solid fa-id-card text-brand-500 mr-2"></i>

                        License: {{ $doctor->license_number }}

                    </div>

                @endif


                {{-- Book appointment --}}
                <div class="mt-7">

                    <a href="{{ route('register') }}"
                       class="btn-primary inline-block">

                        <i class="fa-solid fa-calendar-check mr-2"></i>

                        Book an Appointment

                    </a>

                </div>

            </div>

        </div>

    </div>

</section>

@endsection