@extends(auth()->check() ? 'layouts.dashboard' : 'layouts.site')

@section('title', 'Find a Doctor — Meridian Health')

@section('content')
<section class="max-w-6xl mx-auto px-6 py-16">
    <h1 class="text-3xl font-extrabold mb-1">Find a Doctor</h1>
    <p class="text-slate-500 mb-8">Search by name or specialty.</p>

    <form method="GET" action="{{ route('public.doctors') }}" class="mb-10 max-w-md">
        <div class="relative">
            <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
            <input type="text" name="q" value="{{ $search }}" placeholder="Search doctors or specialties..." class="field-input pl-11 py-3">
        </div>
    </form>

    <div class="grid md:grid-cols-3 gap-6">
        @forelse ($doctors as $doctor)
        <div class="card text-center">
            @if ($doctor->image_url)
            <img src="{{ $doctor->image_url }}" alt="{{ $doctor->name }}" class="w-16 h-16 rounded-full object-cover mx-auto mb-3">
            @else
            <div class="w-16 h-16 rounded-full bg-brand-50 flex items-center justify-center mx-auto mb-3">
                <i class="fa-solid fa-user-doctor text-2xl text-brand-500"></i>
            </div>
            @endif
            <h3 class="font-bold">{{ $doctor->name }}</h3>
            <p class="text-xs text-brand-700 font-semibold mt-1">{{ $doctor->specialization }}</p>
            
            @if ($doctor->hospital)
            <p class="text-xs text-slate-500 mt-2">
                <i class="fa-solid fa-hospital text-brand-500 mr-1"></i>
                {{ $doctor->hospital->name }}
            </p>
            @else
            <p class="text-xs text-slate-400 mt-2">
                Hospital not assigned
            </p>
            @endif

            <div class="flex justify-center mt-2">
                <x-star-rating :rating="$doctor->average_rating" :count="$doctor->ratings_count" />
            </div>

            @if ($doctor->bio)
            <p class="text-xs text-slate-500 mt-2.5 leading-relaxed">{{ Str::limit($doctor->bio, 100) }}</p>
            @endif
            <a href="{{ route('register') }}" class="btn-ghost mt-4 inline-block">Book with {{ explode(' ', $doctor->name)[1] ?? $doctor->name }}</a>

            <a href="{{ route('public.doctor', $doctor) }}" class="btn-ghost mt-4 inline-block">View Profile</a>
        </div>
        @empty
        <p class="text-slate-500 col-span-3">No doctors match your search.</p>
        @endforelse
    </div>

    <div class="mt-9">{{ $doctors->links() }}</div>
</section>
@endsection
