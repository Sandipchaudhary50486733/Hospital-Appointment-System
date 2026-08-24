<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Create Account — Meridian Health</title>
@vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-50 font-sans min-h-screen flex items-center justify-center p-6">

@if ($errors->any())
    <div id="flash-data" data-error="{{ $errors->first() }}" class="hidden"></div>
@endif

<div class="w-full max-w-5xl grid md:grid-cols-2 bg-white rounded-3xl overflow-hidden shadow-2xl shadow-slate-300/50">

    <div class="hidden md:flex flex-col p-12 bg-gradient-to-br from-[#005bea] to-[#00c6fb] text-white">
        <h1 class="text-4xl font-bold mb-4">Welcome to Meridian</h1>
        <p class="leading-relaxed mb-8 text-white/90">Create your patient account and book appointments with experienced doctors anytime.</p>

        <img src="https://images.unsplash.com/photo-1585421514738-01798e348b17?q=80&w=1170&auto=format&fit=crop&ixlib=rb-4.1.0"
             alt="Doctor" class="w-full h-64 object-cover rounded-2xl mb-8">

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

    <div class="p-10 md:p-12 overflow-y-auto max-h-screen">
        <h2 class="text-3xl font-bold text-brand-700 text-center mb-8">Create Account</h2>

        <form method="POST" action="{{ route('register') }}" class="space-y-4">
            @csrf

            <div class="relative">
                <i class="fa-solid fa-user absolute left-4 top-1/2 -translate-y-1/2 text-brand-500"></i>
                <input type="text" name="fullname" value="{{ old('fullname') }}" placeholder="Full Name"
                       autocomplete="name" required class="field-input pl-11 py-3.5 @error('fullname') border-red-400 @enderror">
            </div>
            @error('fullname')<p class="text-red-600 text-xs -mt-2">{{ $message }}</p>@enderror

            <div class="relative">
                <i class="fa-solid fa-envelope absolute left-4 top-1/2 -translate-y-1/2 text-brand-500"></i>
                <input type="email" name="email" value="{{ old('email') }}" placeholder="Email Address"
                       autocomplete="email" required class="field-input pl-11 py-3.5 @error('email') border-red-400 @enderror">
            </div>
            @error('email')<p class="text-red-600 text-xs -mt-2">{{ $message }}</p>@enderror

            <div class="relative">
                <i class="fa-solid fa-calendar absolute left-4 top-1/2 -translate-y-1/2 text-brand-500"></i>
                <input type="date" name="date_of_birth" value="{{ old('date_of_birth') }}" max="{{ now()->toDateString() }}"
                       autocomplete="bday" required class="field-input pl-11 py-3.5 @error('date_of_birth') border-red-400 @enderror">
            </div>
            @error('date_of_birth')<p class="text-red-600 text-xs -mt-2">{{ $message }}</p>@enderror

            <div class="relative">
                <i class="fa-solid fa-venus-mars absolute left-4 top-1/2 -translate-y-1/2 text-brand-500"></i>
                <select name="gender" required class="field-input pl-11 py-3.5 @error('gender') border-red-400 @enderror">
                    <option value="" disabled {{ old('gender') ? '' : 'selected' }}>Select Gender</option>
                    <option value="male" @selected(old('gender') === 'male')>Male</option>
                    <option value="female" @selected(old('gender') === 'female')>Female</option>
                    <option value="other" @selected(old('gender') === 'other')>Other</option>
                </select>
            </div>
            @error('gender')<p class="text-red-600 text-xs -mt-2">{{ $message }}</p>@enderror

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
            <p id="matchWarning" class="text-red-600 text-xs -mt-2 hidden">Passwords do not match.</p>

            <button type="submit" class="btn-primary w-full py-3.5 text-base">Create Account</button>

            <p class="text-center text-sm text-slate-600">Already have an account? <a href="{{ route('login') }}" class="text-brand-500 font-bold">Login</a></p>
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

document.querySelector('form').addEventListener('submit', function (e) {
    const password = document.getElementById('password').value;
    const confirm = document.getElementById('password_confirmation').value;
    const warning = document.getElementById('matchWarning');

    if (password !== confirm) {
        e.preventDefault();
        warning.classList.remove('hidden');
        if (window.Swal) {
            Swal.fire({ icon: 'warning', title: 'Passwords do not match', confirmButtonColor: '#0d6efd' });
        }
    } else {
        warning.classList.add('hidden');
    }
});
</script>

</body>
</html>
