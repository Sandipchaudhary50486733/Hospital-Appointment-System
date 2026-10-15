@extends('layouts.admin')

@section('title', ($doctor->exists ? 'Edit' : 'Add') . ' Doctor — Meridian Health')

@section('content')
<main class="max-w-2xl mx-auto px-8 py-10">

    @if ($errors->any())
    <div class="banner error">@foreach ($errors->all() as $error){{ $error }}<br>@endforeach</div>
    @endif

    <h1 class="text-2xl font-extrabold mb-1">{{ $doctor->exists ? 'Edit doctor' : 'Add a doctor' }}</h1>
    <p class="text-sm text-slate-500 mb-8">{{ $doctor->exists ? "Update this provider's profile." : 'Create a doctor profile visible on the public site.' }}</p>

    <div class="card">
        <form method="POST" action="{{ $doctor->exists ? route('admin.doctors.update', $doctor) : route('admin.doctors.store') }}" enctype="multipart/form-data" class="space-y-4">
            @csrf
            @if ($doctor->exists) @method('PUT') @endif

            <div class="flex items-center gap-5">
                <img id="imagePreview" src="{{ $doctor->image_url ?? 'https://ui-avatars.com/api/?name='.urlencode($doctor->name ?: 'Doctor').'&background=eef4ff&color=0d6efd' }}" alt="Preview" class="w-20 h-20 rounded-full object-cover border border-slate-200">
                <div class="flex-1">
                    <label class="field-label">Profile photo</label>
                    <input type="file" name="image" accept="image/*" data-preview-target="#imagePreview" class="text-sm file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-brand-50 file:text-brand-700 file:font-semibold file:text-xs hover:file:bg-brand-100">
                    <p class="text-xs text-slate-400 mt-1">JPG, PNG, or WEBP. Max 2MB.</p>
                </div>
            </div>
            @error('image')<p class="text-red-600 text-xs">{{ $message }}</p>@enderror

            <div>
                <label class="field-label">Full name</label>
                <input type="text" name="name" value="{{ old('name', $doctor->name) }}" placeholder="Dr. Jane Doe" class="field-input">
                @error('name')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="field-label">Hospital</label>

                <select name="hospital_id" class="field-input">
                    <option value="">Select a hospital</option>

                    @foreach ($hospitals as $hospital)
                    <option value="{{ $hospital->id }}" {{ old('hospital_id', $doctor->hospital_id) == $hospital->id ? 'selected' : '' }}>
                        {{ $hospital->name }}
                    </option>
                    @endforeach
                </select>

                @error('hospital_id')
                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="field-label">Specialization</label>
                    <input type="text" name="specialization" value="{{ old('specialization', $doctor->specialization) }}" placeholder="Cardiology" class="field-input">
                    @error('specialization')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="field-label">License number</label>
                    <input type="text" name="license_number" value="{{ old('license_number', $doctor->license_number) }}" placeholder="MD-123456" class="field-input">
                    @error('license_number')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <div>
                <label class="field-label">Email</label>
                <input type="email" name="email" value="{{ old('email', $doctor->email) }}" placeholder="doctor@meridianhealth.test" class="field-input">
            </div>

            <div>
                <label class="field-label">Bio</label>
                <textarea name="bio" rows="4" placeholder="Short public bio shown on the doctor directory" class="field-input">{{ old('bio', $doctor->bio) }}</textarea>
            </div>

            @if ($doctor->exists)
            <label class="flex items-center gap-2 font-semibold text-sm cursor-pointer">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $doctor->is_active) ? 'checked' : '' }} class="rounded text-brand-500">
                Active (visible for booking)
            </label>
            @endif

            <button type="submit" class="btn-primary w-full py-3">{{ $doctor->exists ? 'Save changes' : 'Create doctor' }}</button>
        </form>
    </div>

    <a href="{{ route('admin.doctors.index') }}" class="btn-ghost inline-block mt-4">&larr; Back to doctors</a>
</main>
@endsection
