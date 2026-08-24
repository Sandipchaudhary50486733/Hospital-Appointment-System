@extends(auth()->check() ? 'layouts.dashboard' : 'layouts.site')

@section('title', 'Contact Us — Meridian Health')

@section('content')
<section class="max-w-6xl mx-auto px-6 py-16">
    <div class="grid md:grid-cols-2 gap-14">
        <div>
            <h1 class="text-3xl font-extrabold mb-4">Get in Touch</h1>
            <p class="text-slate-500 leading-relaxed mb-8">Questions about a visit or billing? Send us a message and our team will respond within one business day.</p>

            <div class="flex flex-col gap-5">
                <div class="flex gap-3.5 items-start">
                    <i class="fa-solid fa-phone text-brand-500 mt-0.5"></i>
                    <div><p class="font-bold text-sm">Phone</p><p class="text-sm text-slate-500">(555) 010-2345</p></div>
                </div>
                <div class="flex gap-3.5 items-start">
                    <i class="fa-solid fa-envelope text-brand-500 mt-0.5"></i>
                    <div><p class="font-bold text-sm">Email</p><p class="text-sm text-slate-500">care@meridianhealth.test</p></div>
                </div>
                <div class="flex gap-3.5 items-start">
                    <i class="fa-solid fa-location-dot text-brand-500 mt-0.5"></i>
                    <div><p class="font-bold text-sm">Address</p><p class="text-sm text-slate-500">120 Wellness Ave, Springfield</p></div>
                </div>
            </div>

            <div class="mt-8 bg-red-50 border border-red-200 rounded-xl p-4 text-xs text-red-700">
                For medical emergencies, call 911 or go to your nearest emergency room.
            </div>
        </div>

        <div class="card">
            <form method="POST" action="{{ route('contact.store') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="field-label">Name</label>
                    <input type="text" name="name" value="{{ old('name') }}" required class="field-input">
                </div>
                <div>
                    <label class="field-label">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required class="field-input">
                </div>
                <div>
                    <label class="field-label">Subject (optional)</label>
                    <input type="text" name="subject" value="{{ old('subject') }}" class="field-input">
                </div>
                <div>
                    <label class="field-label">Message</label>
                    <textarea name="message" rows="4" required class="field-input">{{ old('message') }}</textarea>
                </div>
                <button type="submit" class="btn-primary w-full py-3">Send Message</button>
            </form>
        </div>
    </div>
</section>
@endsection
