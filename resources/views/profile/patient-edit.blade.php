@extends('layouts.dashboard')

@section('content')

<div class="max-w-4xl mx-auto">

    {{-- Page Header --}}
    <div class="mb-6">

        <h1 class="text-2xl font-extrabold text-slate-900">
            Edit Profile
        </h1>

        <p class="text-sm text-slate-500 mt-1">
            Update your personal information.
        </p>

    </div>


    {{-- Validation Errors --}}
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


    {{-- Success Message --}}
    @if(session('status'))

        <div class="bg-green-50 border border-green-200
                    text-green-700 rounded-xl p-4 mb-6">

            {{ session('status') }}

        </div>

    @endif


    {{-- Patient Edit Form --}}
    <form
        method="POST"
        action="{{ route('patient.profile.update') }}"
        enctype="multipart/form-data"
    >

        @csrf
        @method('PUT')


        <div class="card space-y-6">


            {{-- Profile Image --}}
            <div>

                <label class="field-label">
                    Profile Photo
                </label>

                <div class="flex flex-col sm:flex-row
                            sm:items-center gap-5 mt-2">


                    {{-- Current Image --}}
                    <div id="patientImageContainer">

                        @if($user->image)

                            <img
                                id="patientImagePreview"
                                src="{{ $user->image_url }}"
                                alt="{{ $user->name }}"
                                class="w-24 h-24 rounded-full
                                       object-cover
                                       border-4 border-white
                                       shadow"
                            >

                        @else

                            <div
                                id="patientImagePreview"
                                class="w-24 h-24 rounded-full
                                       bg-brand-100
                                       text-brand-700
                                       flex items-center
                                       justify-center
                                       text-3xl
                                       font-extrabold
                                       border-4
                                       border-white
                                       shadow"
                            >

                                {{ strtoupper(substr($user->name, 0, 1)) }}

                            </div>

                        @endif

                    </div>


                    {{-- Upload Input --}}
                    <div>

                        <input
                            type="file"
                            name="image"
                            id="patientImage"
                            accept="image/jpeg,image/png,image/webp"
                            class="block w-full text-sm
                                   text-slate-600
                                   file:mr-4
                                   file:py-2
                                   file:px-4
                                   file:rounded-lg
                                   file:border-0
                                   file:text-sm
                                   file:font-semibold
                                   file:bg-brand-100
                                   file:text-brand-700
                                   hover:file:bg-brand-200"
                        >

                        <p class="text-xs text-slate-500 mt-2">
                            JPG, JPEG, PNG or WEBP.
                            Maximum size: 2MB.
                        </p>

                        @error('image')

                            <p class="text-sm text-red-600 mt-1">
                                {{ $message }}
                            </p>

                        @enderror

                    </div>

                </div>

            </div>


            {{-- Personal Information Header --}}
            <div>

                <h2 class="text-lg font-bold text-slate-900">
                    Personal Information
                </h2>

                <p class="text-sm text-slate-500 mt-1">
                    Update the information associated with your account.
                </p>

            </div>


            {{-- Name --}}
            <div>

                <label class="field-label">
                    Full Name
                </label>

                <input
                    type="text"
                    name="name"
                    value="{{ old('name', $user->name) }}"
                    class="field-input"
                    required
                >

                @error('name')

                    <p class="text-sm text-red-600 mt-1">
                        {{ $message }}
                    </p>

                @enderror

            </div>


            {{-- Email --}}
            <div>

                <label class="field-label">
                    Email Address
                </label>

                <input
                    type="email"
                    name="email"
                    value="{{ old('email', $user->email) }}"
                    class="field-input"
                    required
                >

                @error('email')

                    <p class="text-sm text-red-600 mt-1">
                        {{ $message }}
                    </p>

                @enderror

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
                    placeholder="Enter your phone number"
                >

                @error('phone')

                    <p class="text-sm text-red-600 mt-1">
                        {{ $message }}
                    </p>

                @enderror

            </div>


            {{-- Gender --}}
            <div>

                <label class="field-label">
                    Gender
                </label>

                <select
                    name="gender"
                    class="field-input"
                >

                    <option value="">
                        Select Gender
                    </option>

                    <option
                        value="Male"
                        {{ old('gender', $user->gender) === 'Male'
                            ? 'selected'
                            : '' }}
                    >
                        Male
                    </option>

                    <option
                        value="Female"
                        {{ old('gender', $user->gender) === 'Female'
                            ? 'selected'
                            : '' }}
                    >
                        Female
                    </option>

                    <option
                        value="Other"
                        {{ old('gender', $user->gender) === 'Other'
                            ? 'selected'
                            : '' }}
                    >
                        Other
                    </option>

                </select>

                @error('gender')

                    <p class="text-sm text-red-600 mt-1">
                        {{ $message }}
                    </p>

                @enderror

            </div>


            {{-- Date of Birth --}}
            <div>

                <label class="field-label">
                    Date of Birth
                </label>

                <input
                    type="date"
                    name="date_of_birth"
                    value="{{ old(
                        'date_of_birth',
                        $user->date_of_birth?->format('Y-m-d')
                    ) }}"
                    class="field-input"
                >

                @error('date_of_birth')

                    <p class="text-sm text-red-600 mt-1">
                        {{ $message }}
                    </p>

                @enderror

            </div>


            {{-- Role --}}
            <div>

                <label class="field-label">
                    Role
                </label>

                <input
                    type="text"
                    value="Patient"
                    class="field-input bg-gray-100"
                    readonly
                >

            </div>


            {{-- Buttons --}}
            <div class="flex flex-wrap gap-3 pt-3">

                <button
                    type="submit"
                    class="btn-primary"
                >

                    <i class="fa-solid fa-check mr-2"></i>

                    Save Changes

                </button>


                <a
                    href="{{ route('patient.profile') }}"
                    class="btn-ghost"
                >

                    Cancel

                </a>

            </div>

        </div>

    </form>

</div>


{{-- Image Preview --}}
<script>

    const patientImage =
        document.getElementById('patientImage');

    if (patientImage) {

        patientImage.addEventListener(
            'change',
            function(event) {

                const file =
                    event.target.files[0];

                if (!file) {
                    return;
                }


                const reader =
                    new FileReader();


                reader.onload =
                    function(e) {

                        const container =
                            document.getElementById(
                                'patientImageContainer'
                            );


                        const preview =
                            document.getElementById(
                                'patientImagePreview'
                            );


                        if (!container) {
                            return;
                        }


                        /*
                        |--------------------------------------------------------------------------
                        | If preview is already an image
                        |--------------------------------------------------------------------------
                        */

                        if (
                            preview &&
                            preview.tagName === 'IMG'
                        ) {

                            preview.src =
                                e.target.result;

                            return;
                        }


                        /*
                        |--------------------------------------------------------------------------
                        | If there is no existing image,
                        | replace avatar with image
                        |--------------------------------------------------------------------------
                        */

                        container.innerHTML = `
                            <img
                                id="patientImagePreview"
                                src="${e.target.result}"
                                alt="Profile Preview"
                                class="w-24 h-24 rounded-full
                                       object-cover
                                       border-4 border-white
                                       shadow"
                            >
                        `;

                    };


                reader.readAsDataURL(file);

            }
        );

    }

</script>

@endsection
