@extends(auth()->check() ? 'layouts.dashboard' : 'layouts.site')

@section('title', 'Services — Meridian Health')

@section('content')
<section class="max-w-6xl mx-auto px-6 py-16">
    <h1 class="text-3xl font-extrabold mb-1">Our Services</h1>
    <p class="text-slate-500 mb-10">A full range of specialties under one roof.</p>

    <div class="grid md:grid-cols-3 gap-6">
        @php
            $services = [
                ['icon' => 'fa-heart-pulse', 'title' => 'Cardiology', 'desc' => 'Comprehensive heart health, from screenings to interventional care.'],
                ['icon' => 'fa-child-reaching', 'title' => 'Pediatrics', 'desc' => 'Preventive and developmental care for infants through teens.'],
                ['icon' => 'fa-user-nurse', 'title' => 'Dermatology', 'desc' => 'Skin health, cosmetic dermatology, and chronic condition management.'],
                ['icon' => 'fa-bone', 'title' => 'Orthopedics', 'desc' => 'Sports injuries, joint care, and post-surgical rehabilitation.'],
                ['icon' => 'fa-stethoscope', 'title' => 'General Medicine', 'desc' => 'Primary care, annual checkups, and everyday health concerns.'],
                ['icon' => 'fa-flask', 'title' => 'Lab & Diagnostics', 'desc' => 'On-site testing with fast, accurate results shared through your portal.'],
            ];
        @endphp

        @foreach ($services as $service)
            <div class="card">
                <i class="fa-solid {{ $service['icon'] }} text-2xl text-brand-500"></i>
                <h3 class="font-bold mt-3 mb-2">{{ $service['title'] }}</h3>
                <p class="text-sm text-slate-500 leading-relaxed">{{ $service['desc'] }}</p>
            </div>
        @endforeach
    </div>

    <div class="text-center mt-12">
        <a href="{{ route('public.doctors') }}" class="btn-primary">Find a specialist</a>
    </div>
</section>
@endsection
