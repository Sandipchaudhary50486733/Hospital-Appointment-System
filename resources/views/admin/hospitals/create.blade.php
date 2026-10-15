@extends('layouts.admin')

@section('title', 'Add Hospital — Meridian Health')

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
        Add a hospital
    </h1>

    <p class="text-sm text-slate-500 mb-8">
        Create a hospital that can be assigned to doctors.
    </p>

    <div class="card">

        <form method="POST"
              action="{{ route('admin.hospitals.store') }}"
              class="space-y-4">

            @csrf

            {{-- Province --}}
            <div>
                <label for="province_id" class="field-label">
                    Province
                </label>

                <select
                    name="province_id"
                    id="province_id"
                    class="field-input"
                    required
                >
                    <option value="">Select Province</option>

                    @foreach($provinces as $province)
                        <option
                            value="{{ $province->id }}"
                            @selected(old('province_id') == $province->id)
                        >
                            {{ $province->name }}
                        </option>
                    @endforeach
                </select>

                @error('province_id')
                    <p class="mt-1 text-sm text-red-600">
                        {{ $message }}
                    </p>
                @enderror
            </div>


            {{-- District --}}
            <div>
                <label for="district_id" class="field-label">
                    District
                </label>

                <select
                    name="district_id"
                    id="district_id"
                    class="field-input"
                    required
                    disabled
                >
                    <option value="">Select District</option>
                </select>

                @error('district_id')
                    <p class="mt-1 text-sm text-red-600">
                        {{ $message }}
                    </p>
                @enderror
            </div>


            {{-- Hospital Name --}}
            <div>
                <label for="name" class="field-label">
                    Hospital name
                </label>

                <input
                    type="text"
                    id="name"
                    name="name"
                    value="{{ old('name') }}"
                    placeholder="Nobel Medical College Teaching Hospital"
                    class="field-input"
                    required
                >

                @error('name')
                    <p class="text-red-600 text-xs mt-1">
                        {{ $message }}
                    </p>
                @enderror
            </div>


            {{-- Address --}}
            <div>
                <label for="address" class="field-label">
                    Address
                </label>

                <input
                    type="text"
                    id="address"
                    name="address"
                    value="{{ old('address') }}"
                    placeholder="Biratnagar, Nepal"
                    class="field-input"
                >

                @error('address')
                    <p class="text-red-600 text-xs mt-1">
                        {{ $message }}
                    </p>
                @enderror
            </div>


            {{-- Phone and Email --}}
            <div class="grid grid-cols-2 gap-3">

                <div>
                    <label for="phone" class="field-label">
                        Phone
                    </label>

                    <input
                        type="text"
                        id="phone"
                        name="phone"
                        value="{{ old('phone') }}"
                        placeholder="+977 21 123456"
                        class="field-input"
                    >

                    @error('phone')
                        <p class="text-red-600 text-xs mt-1">
                            {{ $message }}
                        </p>
                    @enderror
                </div>


                <div>
                    <label for="email" class="field-label">
                        Email
                    </label>

                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email') }}"
                        placeholder="hospital@example.com"
                        class="field-input"
                    >

                    @error('email')
                        <p class="text-red-600 text-xs mt-1">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

            </div>


            {{-- Description --}}
            <div>
                <label for="description" class="field-label">
                    Description
                </label>

                <textarea
                    name="description"
                    id="description"
                    rows="5"
                    placeholder="Short description about the hospital..."
                    class="field-input"
                >{{ old('description') }}</textarea>

                @error('description')
                    <p class="text-red-600 text-xs mt-1">
                        {{ $message }}
                    </p>
                @enderror
            </div>


            {{-- Active --}}
            <label class="flex items-center gap-2 font-semibold text-sm cursor-pointer">

                <input
                    type="checkbox"
                    name="is_active"
                    value="1"
                    {{ old('is_active', true) ? 'checked' : '' }}
                    class="rounded text-brand-500"
                >

                Active hospital

            </label>


            {{-- Submit --}}
            <button
                type="submit"
                class="btn-primary w-full py-3"
            >
                <i class="fa-solid fa-plus mr-1.5"></i>
                Create hospital
            </button>

        </form>

    </div>


    {{-- Back --}}
    <a
        href="{{ route('admin.hospitals.index') }}"
        class="btn-ghost inline-block mt-4"
    >
        &larr; Back to hospitals
    </a>

</main>


<script>
document.addEventListener('DOMContentLoaded', function () {

    const provinceSelect = document.getElementById('province_id');
    const districtSelect = document.getElementById('district_id');

    /*
     * Get all districts from the database.
     *
     * IMPORTANT:
     * There must be NO spaces inside:
     * \App\Models\District
     *
     * and:
     * ->
     */
    const districts = @json(
        \App\Models\District::orderBy('name')
            ->get(['id', 'province_id', 'name'])
    );


    function loadDistricts(provinceId, selectedDistrict = '') {

        // Reset district dropdown
        districtSelect.innerHTML =
            '<option value="">Select District</option>';

        // If no province is selected
        if (!provinceId) {
            districtSelect.disabled = true;
            return;
        }


        // Filter districts according to selected province
        const filteredDistricts = districts.filter(function (district) {

            return String(district.province_id) === String(provinceId);

        });


        // Add districts to dropdown
        filteredDistricts.forEach(function (district) {

            const option = document.createElement('option');

            option.value = district.id;
            option.textContent = district.name;

            if (
                String(district.id) ===
                String(selectedDistrict)
            ) {
                option.selected = true;
            }

            districtSelect.appendChild(option);

        });


        // Enable district dropdown
        districtSelect.disabled = false;

    }


    // When province changes
    provinceSelect.addEventListener('change', function () {

        loadDistricts(this.value);

    });


    // Load districts when page initially opens
    loadDistricts(
        provinceSelect.value,
        @json(old('district_id'))
    );

});
</script>

@endsection