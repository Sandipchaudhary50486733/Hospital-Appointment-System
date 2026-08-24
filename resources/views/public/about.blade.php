@extends(auth()->check() ? 'layouts.dashboard' : 'layouts.site')

@section('title', 'About Us — Meridian Health')

@section('content')
<section class="max-w-3xl mx-auto px-6 py-16">
    <h1 class="text-3xl font-extrabold mb-4">About Meridian Health</h1>
    <p class="text-slate-500 leading-relaxed mb-5">Meridian Health has been serving our community for over two decades, combining experienced medical staff with a modern, patient-first approach to care. We believe healthcare should be accessible, transparent, and easy to navigate — which is why we built a portal that puts booking and communication with your care team all in one place.</p>
    <p class="text-slate-500 leading-relaxed mb-10">Our team of board-certified physicians spans cardiology, pediatrics, dermatology, orthopedics, and general medicine, supported by a dedicated administrative staff focused on making every visit as smooth as possible.</p>

    <div class="grid grid-cols-3 gap-5 mb-12">
        <div class="card text-center"><div class="text-3xl font-extrabold text-brand-500">20+</div><div class="text-xs text-slate-500 mt-1">Years of service</div></div>
        <div class="card text-center"><div class="text-3xl font-extrabold text-brand-500">15k+</div><div class="text-xs text-slate-500 mt-1">Patients cared for</div></div>
        <div class="card text-center"><div class="text-3xl font-extrabold text-brand-500">5</div><div class="text-xs text-slate-500 mt-1">Specialties covered</div></div>
    </div>

    <h2 class="text-xl font-extrabold mb-3">Our mission</h2>
    <p class="text-slate-500 leading-relaxed">To deliver compassionate, high-quality care that respects every patient's time and dignity — supported by technology that makes healthcare simpler, not more complicated.</p>
</section>
@endsection
