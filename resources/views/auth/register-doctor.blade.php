<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Provider Registration — Meridian Health</title>
@vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-50 font-sans min-h-screen flex items-center justify-center p-6">

@if ($errors->any())
    <div id="flash-data" data-error="{{ $errors->first() }}" class="hidden"></div>
@endif

<div class="w-full max-w-5xl grid md:grid-cols-2 bg-white rounded-3xl overflow-hidden shadow-2xl shadow-slate-300/50">

    <div class="hidden md:flex flex-col p-12 bg-gradient-to-br from-brand-700 to-[#005bea] text-white">
        <h1 class="text-3xl font-bold mb-4">Join Our Care Team</h1>
        <p class="leading-relaxed mb-8 text-white/90">Register as a provider to manage your schedule and the appointments patients book with you.</p>

        <img src="https://images.unsplash.com/photo-1622253692010-333f2da6031d?q=80&w=1170&auto=format&fit=crop&ixlib=rb-4.1.0"
             alt="Doctor reviewing chart" class="w-full h-56 object-cover rounded-2xl mb-8">

        <div class="flex flex-col gap-5">
            <div class="flex gap-4 items-start">
                <i class="fa-solid fa-calendar-days text-2xl"></i>
                <div><h3 class="font-semibold">Manage Your Schedule</h3><p class="text-sm text-white/80">Confirm, complete, or reschedule visits.</p></div>
            </div>
            <div class="flex gap-4 items-start">
                <i class="fa-solid fa-id-badge text-2xl"></i>
                <div><h3 class="font-semibold">Verified Profile</h3><p class="text-sm text-white/80">Your credentials are reviewed by our admin team.</p></div>
            </div>
        </div>
    </div>

    <div class="p-10 md:p-12 overflow-y-auto max-h-screen">
        <h2 class="text-3xl font-bold text-brand-700 text-center mb-1">Provider Registration</h2>
        <p class="text-center text-sm text-slate-500 mb-8">Create your clinician account.</p>

        <form method="POST" action="{{ route('register.doctor') }}" class="space-y-4">
            @csrf

            <div class="relative">
                <i class="fa-solid fa-user-doctor absolute left-4 top-1/2 -translate-y-1/2 text-brand-500"></i>
                <input type="text" name="name" value="{{ old('name') }}" placeholder="Full Name (e.g. Dr. Jane Doe)"
                       autocomplete="name" required class="field-input pl-11 py-3.5 @error('name') border-red-400 @enderror">
            </div>
            @error('name')<p class="text-red-600 text-xs -mt-2">{{ $message }}</p>@enderror

            <div class="grid grid-cols-2 gap-3">
                <div class="relative">
                    <i class="fa-solid fa-stethoscope absolute left-4 top-1/2 -translate-y-1/2 text-brand-500"></i>
                    <input type="text" name="specialization" value="{{ old('specialization') }}" placeholder="Specialization"
                           required class="field-input pl-11 py-3.5 @error('specialization') border-red-400 @enderror">
                </div>
                <div class="relative">
                    <i class="fa-solid fa-id-card absolute left-4 top-1/2 -translate-y-1/2 text-brand-500"></i>
                    <input type="text" name="license_number" value="{{ old('license_number') }}" placeholder="License Number"
                           required class="field-input pl-11 py-3.5 @error('license_number') border-red-400 @enderror">
                </div>
            </div>
            @error('specialization')<p class="text-red-600 text-xs -mt-2">{{ $message }}</p>@enderror
            @error('license_number')<p class="text-red-600 text-xs -mt-2">{{ $message }}</p>@enderror

            <div class="relative">
                <i class="fa-solid fa-envelope absolute left-4 top-1/2 -translate-y-1/2 text-brand-500"></i>
                <input type="email" name="email" value="{{ old('email') }}" placeholder="Email Address"
                       autocomplete="email" required class="field-input pl-11 py-3.5 @error('email') border-red-400 @enderror">
            </div>
            @error('email')<p class="text-red-600 text-xs -mt-2">{{ $message }}</p>@enderror

            <div class="relative">
                <i class="fa-solid fa-phone absolute left-4 top-1/2 -translate-y-1/2 text-brand-500"></i>
                <input type="tel" name="phone" value="{{ old('phone') }}" placeholder="Phone Number"
                       autocomplete="tel" required class="field-input pl-11 py-3.5 @error('phone') border-red-400 @enderror">
            </div>
            @error('phone')<p class="text-red-600 text-xs -mt-2">{{ $message }}</p>@enderror

            <div class="relative">
                <i class="fa-solid fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-brand-500"></i>
                <input type="password" id="password" name="password" placeholder="Password"
                       autocomplete="new-password" minlength="8" required class="field-input pl-11 pr-11 py-3.5 @error('password') border-red-400 @enderror">
                <i class="fa-solid fa-eye absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 cursor-pointer" onclick="togglePassword('password', this)"></i>
            </div>
            @error('password')<p class="text-red-600 text-xs -mt-2">{{ $message }}</p>@enderror

            <div class="relative">
                <i class="fa-solid fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-brand-500"></i>
                <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Confirm Password"
                       autocomplete="new-password" minlength="8" required class="field-input pl-11 pr-11 py-3.5">
                <i class="fa-solid fa-eye absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 cursor-pointer" onclick="togglePassword('password_confirmation', this)"></i>
            </div>

            <div class="bg-brand-50 border border-brand-100 rounded-xl p-3.5 text-xs text-slate-600 leading-relaxed">
                <label class="flex items-start gap-2 cursor-pointer">
                    <input type="checkbox" name="terms" value="1" required class="mt-0.5 rounded text-brand-500">
                    I confirm the credentials above are accurate and I agree to Meridian Health's Provider Terms and Privacy Policy.
                </label>
            </div>

            <button type="submit" class="btn-primary w-full py-3.5 text-base">Create Provider Account</button>

            <p class="text-center text-sm text-slate-600">Are you a patient? <a href="{{ route('register') }}" class="text-brand-500 font-bold">Register here</a> instead.</p>
            <p class="text-center text-sm text-slate-600">Already registered? <a href="{{ route('login') }}" class="text-brand-500 font-bold">Sign in</a></p>
        </form>
    </div>
</div>

<script>
function togglePassword(id, el) {
    const input = document.getElementById(id);
    input.type = input.type === 'password' ? 'text' : 'password';
    el.classList.toggle('fa-eye');
    el.classList.toggle('fa-eye-slash');
}
</script>

</body>
</html>
