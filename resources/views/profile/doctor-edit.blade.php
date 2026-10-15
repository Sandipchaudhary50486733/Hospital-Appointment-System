@extends('layouts.dashboard')

@section('content')

<div class="max-w-4xl mx-auto">

    <div class="mb-6">

        <h1 class="text-2xl font-extrabold text-slate-900">
            Edit Doctor Profile
        </h1>

        <p class="text-sm text-slate-500 mt-1">
            Update your professional profile information.
        </p>

    </div>


    @if($errors->any())

        <div class="bg-red-50 border border-red-200
                    text-red-700 rounded-xl p-4 mb-6">

            <ul class="list-disc list-inside text-sm space-y-1">

                @foreach($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif


    <form
        method="POST"
        action="{{ route('doctor.profile.update') }}"
        enctype="multipart/form-data">

        @csrf
        @method('PUT')


        <div class="card space-y-6">


            {{-- Profile Image --}}
            <div>

                <label class="field-label">
                    Profile Image
                </label>

                <div class="flex items-center gap-5">

                    <img
                        id="doctorImagePreview"
                        src="{{ $doctor->image_url ?? 'https://ui-avatars.com/api/?name='.urlencode($doctor->name ?: 'Doctor').'&background=eef4ff&color=0d6efd' }}"
                        class="w-24 h-24 rounded-2xl object-cover
                               border border-slate-200"
                        alt="Doctor profile"
                    >


                    <div>

                        <input
                            type="file"
                            name="image"
                            id="doctorImage"
                            accept="image/jpeg,image/png,image/webp"
                            class="block text-sm text-slate-600"
                        >

                        <p class="text-xs text-slate-500 mt-2">
                            JPG, PNG or WEBP. Maximum 2MB.
                        </p>

                    </div>

                </div>

            </div>


            {{-- Name --}}
            <div>

                <label class="field-label">
                    Doctor Name
                </label>

                <input
                    type="text"
                    name="name"
                    value="{{ old('name', $doctor->name) }}"
                    class="field-input"
                    required
                >

            </div>


            {{-- Email --}}
            <div>

                <label class="field-label">
                    Email Address
                </label>

                <input
                    type="email"
                    name="email"
                    value="{{ old('email', $doctor->email) }}"
                    class="field-input"
                    required
                >

            </div>


            {{-- Phone --}}
            <div>

                <label class="field-label">
                    Phone Number
                </label>

                <input
                    type="text"
                    name="phone"
                    value="{{ old('phone', $user->phone) }}"
                    class="field-input"
                >

            </div>


            {{-- Specialization --}}
            <div>

                <label class="field-label">
                    Specialization
                </label>

                <input
                    type="text"
                    name="specialization"
                    value="{{ old('specialization', $doctor->specialization) }}"
                    class="field-input"
                    required
                >

            </div>


            {{-- License --}}
            <div>

                <label class="field-label">
                    License Number
                </label>

                <input
                    type="text"
                    name="license_number"
                    value="{{ old('license_number', $doctor->license_number) }}"
                    class="field-input"
                >

            </div>


            {{-- Hospital --}}
            <div>

                <label class="field-label">
                    Hospital
                </label>

                <input
                    type="text"
                    value="{{ $doctor->hospital?->name ?? 'Not assigned' }}"
                    class="field-input bg-gray-100"
                    readonly
                >

                <p class="text-xs text-slate-500 mt-1">
                    Hospital assignment is managed by the administrator.
                </p>

            </div>


            {{-- Biography --}}
            <div>

                <label class="field-label">
                    Biography
                </label>

                <textarea
                    name="bio"
                    rows="6"
                    class="field-input"
                    placeholder="Write a short professional biography..."
                >{{ old('bio', $doctor->bio) }}</textarea>

            </div>


            {{-- Buttons --}}
            <div class="flex flex-wrap gap-3 pt-3">

                <button
                    type="submit"
                    class="btn-primary">

                    <i class="fa-solid fa-check mr-2"></i>
                    Save Changes

                </button>


                <a
                    href="{{ route('doctor.profile') }}"
                    class="btn-ghost">

                    Cancel

                </a>

            </div>

        </div>

    </form>

</div>


<script>

document.getElementById('doctorImage')?.addEventListener('change', function (event) {

    const file = event.target.files[0];

    if (!file) {
        return;
    }

    const reader = new FileReader();

    reader.onload = function (e) {
        document.getElementById('doctorImagePreview').src = e.target.result;
    };

    reader.readAsDataURL(file);

});

</script>

@endsection