<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Sign In — Meridian Health</title>
@vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-50 font-sans min-h-screen flex items-center justify-center p-6">

@if (session('status') || $errors->any())
    <div id="flash-data"
         @if(session('status')) data-status="{{ session('status') }}" @endif
         @if($errors->any()) data-error="{{ $errors->first() }}" @endif
         class="hidden"></div>
@endif

<div class="w-full max-w-5xl grid md:grid-cols-2 bg-white rounded-3xl overflow-hidden shadow-2xl shadow-slate-300/50">

    <div class="hidden md:flex flex-col p-12 bg-gradient-to-br from-[#005bea] to-[#00c6fb] text-white">
        <h1 class="text-4xl font-bold mb-4">Welcome Back</h1>
        <p class="leading-relaxed mb-8 text-white/90">Sign in to book appointments, view your medical records, and manage your care with Meridian Health.</p>

        <img src="https://images.unsplash.com/photo-1576091160399-112ba8d25d1d?q=80&w=1170&auto=format&fit=crop&ixlib=rb-4.1.0"
             alt="Hospital reception" class="w-full h-64 object-cover rounded-2xl mb-8">

        <div class="flex flex-col gap-5">
            <div class="flex gap-4 items-start">
                <i class="fa-solid fa-shield-heart text-2xl"></i>
                <div><h3 class="font-semibold">Secure &amp; Private</h3><p class="text-sm text-white/80">Your information is protected.</p></div>
            </div>
            <div class="flex gap-4 items-start">
                <i class="fa-solid fa-calendar-check text-2xl"></i>
                <div><h3 class="font-semibold">Easy Appointment</h3><p class="text-sm text-white/80">Book in just a few clicks.</p></div>
            </div>
            <div class="flex gap-4 items-start">
                <i class="fa-solid fa-user-doctor text-2xl"></i>
                <div><h3 class="font-semibold">Expert Doctors</h3><p class="text-sm text-white/80">Experienced medical professionals.</p></div>
            </div>
        </div>
    </div>

    <div class="p-10 md:p-14 flex flex-col justify-center">
        <h2 class="text-3xl font-bold text-brand-700 text-center mb-8">Sign In</h2>

        <form method="POST" action="{{ route('login') }}" class="space-y-5">
            @csrf

            <div class="relative">
                <i class="fa-solid fa-envelope absolute left-4 top-1/2 -translate-y-1/2 text-brand-500"></i>
                <input type="email" name="email" value="{{ old('email') }}" placeholder="Email Address"
                       autocomplete="email" autofocus required
                       class="field-input pl-11 py-3.5">
            </div>

            <div class="relative">
                <i class="fa-solid fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-brand-500"></i>
                <input type="password" id="password" name="password" placeholder="Password"
                       autocomplete="current-password" required
                       class="field-input pl-11 pr-11 py-3.5">
                <i class="fa-solid fa-eye absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 cursor-pointer"
                   onclick="togglePassword('password', this)"></i>
            </div>

            <div class="flex items-center justify-between text-sm">
                <label class="flex items-center gap-2 text-slate-600 cursor-pointer">
                    <input type="checkbox" name="remember" class="rounded text-brand-500"> Remember me
                </label>
                <a href="#" class="text-brand-500 font-semibold">Forgot password?</a>
            </div>

            <button type="submit" class="btn-primary w-full py-3.5 text-base">Sign In</button>

            <p class="text-center text-sm text-slate-600">No account yet? <a href="{{ route('register') }}" class="text-brand-500 font-bold">Register here</a></p>
            <p class="text-center text-sm text-slate-600">Are you a provider? <a href="{{ route('register.doctor') }}" class="text-brand-500 font-bold">Register as a doctor</a></p>
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
