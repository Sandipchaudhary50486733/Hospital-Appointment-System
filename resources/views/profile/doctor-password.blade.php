@extends('layouts.dashboard')

@section('content')

<div class="max-w-2xl mx-auto">

    <div class="mb-6">

        <h1 class="text-2xl font-extrabold text-slate-900">
            Change Password
        </h1>

        <p class="text-sm text-slate-500 mt-1">
            Update your password to keep your doctor account secure.
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
        action="{{ route('doctor.profile.password.update') }}">

        @csrf
        @method('PUT')


        <div class="card space-y-6">


            {{-- Current Password --}}
            <div>

                <label class="field-label">
                    Current Password
                </label>

                <input
                    type="password"
                    name="current_password"
                    class="field-input"
                    required
                >

            </div>


            {{-- New Password --}}
            <div>

                <label class="field-label">
                    New Password
                </label>

                <input
                    type="password"
                    name="password"
                    class="field-input"
                    required
                >

                <p class="text-xs text-slate-500 mt-1">
                    Password must contain at least 8 characters.
                </p>

            </div>


            {{-- Confirm Password --}}
            <div>

                <label class="field-label">
                    Confirm New Password
                </label>

                <input
                    type="password"
                    name="password_confirmation"
                    class="field-input"
                    required
                >

            </div>


            {{-- Buttons --}}
            <div class="flex gap-3 pt-2">

                <button
                    type="submit"
                    class="btn-primary">

                    <i class="fa-solid fa-lock mr-2"></i>
                    Change Password

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

@endsection