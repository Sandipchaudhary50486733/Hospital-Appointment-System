@extends('layouts.admin')

@section('title', 'Edit Hospital — Meridian Health')

@section('content')
<main class="max-w-2xl mx-auto px-8 py-10">

    @if ($errors->any())
    <div class="banner error mb-6">
        @foreach ($errors->all() as $error)
        {{ $error }}<br>
        @endforeach
    </div>
    @endif

    <h1 class="text-2xl font-extrabold mb-1">
        Edit hospital
    </h1>

    <p class="text-sm text-slate-500 mb-8">
        Update this hospital's information.
    </p>

    <div class="card">

        <form method="POST" action="{{ route('admin.hospitals.update', $hospital) }}" class="space-y-4">

            @csrf
            @method('PUT')

            {{-- Province --}}
            <div>
                <label for="province_id" class="field-label">Province</label>

                <select name="province_id" id="province_id" class="field-input" required>
                    <option value="">Select Province</option>

                    @foreach($provinces as $province)
                    <option value="{{ $province->id }}" @selected(old('province_id')==$province->id)>
                        {{ $province->name }}
                    </option>
                    @endforeach
                </select>

                @error('province_id')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- District --}}
            <div>
                <label for="district_id" class="field-label">District</label>

                <select name="district_id" id="district_id" class="field-input" required disabled>
                    <option value="">Select District</option>
                </select>

                @error('district_id')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="field-label">
                    Hospital name
                </label>

                <input type="text" name="name" value="{{ old('name', $hospital->name) }}" class="field-input">

                @error('name')
                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="field-label">
                    Address
                </label>

                <input type="text" name="address" value="{{ old('address', $hospital->address) }}" class="field-input">

                @error('address')
                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-2 gap-3">

                <div>
                    <label class="field-label">
                        Phone
                    </label>

                    <input type="text" name="phone" value="{{ old('phone', $hospital->phone) }}" class="field-input">

                    @error('phone')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="field-label">
                        Email
                    </label>

                    <input type="email" name="email" value="{{ old('email', $hospital->email) }}" class="field-input">

                    @error('email')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

            </div>

            <div>
                <label class="field-label">
                    Description
                </label>

                <textarea name="description" rows="5" class="field-input">{{ old('description', $hospital->description) }}</textarea>

                @error('description')
                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <label class="flex items-center gap-2 font-semibold text-sm cursor-pointer">

                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $hospital->is_active) ? 'checked' : '' }} class="rounded text-brand-500">

                Active hospital

            </label>

            <button type="submit" class="btn-primary w-full py-3">

                <i class="fa-solid fa-floppy-disk mr-1.5"></i>
                Save changes

            </button>

        </form>

    </div>

    <a href="{{ route('admin.hospitals.index') }}" class="btn-ghost inline-block mt-4">

        &larr; Back to hospitals

    </a>

</main>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const provinceSelect = document.getElementById('province_id');
        const districtSelect = document.getElementById('district_id');

        const districts = @json(\App\ Models\ District::orderBy('name') - > get(['id', 'province_id', 'name']));

        function loadDistricts(provinceId, selectedDistrict = '') {
            districtSelect.innerHTML =
                '<option value="">Select District</option>';

            if (!provinceId) {
                districtSelect.disabled = true;
                return;
            }

            districts
                .filter(district =>
                    String(district.province_id) === String(provinceId)
                )
                .forEach(function(district) {
                    const option = document.createElement('option');

                    option.value = district.id;
                    option.textContent = district.name;

                    if (String(district.id) === String(selectedDistrict)) {
                        option.selected = true;
                    }

                    districtSelect.appendChild(option);
                });

            districtSelect.disabled = false;
        }

        provinceSelect.addEventListener('change', function() {
            loadDistricts(this.value);
        });

        loadDistricts(
            provinceSelect.value
            , @json(old('district_id', $hospital - > district_id))
        );
    });

</script>
@endsection
