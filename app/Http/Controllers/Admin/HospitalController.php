<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Hospital;
use App\Models\District;
use App\Models\Province;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HospitalController extends Controller
{
    public function index(): View
    {
        $hospitals = Hospital::withCount('doctors')
            ->orderBy('name')
            ->get();

        return view('admin.hospitals.index', compact('hospitals'));
    }

    public function create(): View
    {
        $provinces = Province::orderBy('name')->get();

        return view('admin.hospitals.create', compact('provinces'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'province_id' => ['required', 'exists:provinces,id'],
            'district_id' => ['required', 'exists:districts,id'],
            'name'        => ['required', 'string', 'max:255'],
            'address'     => ['nullable', 'string', 'max:255'],
            'phone'       => ['nullable', 'string', 'max:20'],
            'email'       => ['nullable', 'email', 'max:255'],
            'description' => ['nullable', 'string'],
            'is_active'   => ['sometimes', 'boolean'],
        ]);

        // Make sure the chosen district actually belongs to the chosen
        // province, not just that both IDs individually exist.
        $request->validate([
            'district_id' => [
                function ($attribute, $value, $fail) use ($request) {
                    $belongsToProvince = District::where('id', $value)
                        ->where('province_id', $request->province_id)
                        ->exists();

                    if (! $belongsToProvince) {
                        $fail('The selected district does not belong to the selected province.');
                    }
                },
            ],
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        Hospital::create($validated);

        return redirect()
            ->route('admin.hospitals.index')
            ->with('status', 'Hospital created successfully.');
    }

    public function edit(Hospital $hospital): View
    {
        $provinces = Province::orderBy('name')->get();

        $districts = District::where('province_id', $hospital->province_id)->orderBy('name')->get();

        return view('admin.hospitals.edit', compact('hospital', 'provinces', 'districts'));
    }

    public function update(Request $request, Hospital $hospital): RedirectResponse
    {
        $validated = $request->validate([
            'province_id' => ['required', 'exists:provinces,id'],
            'district_id' => ['required', 'exists:districts,id'],
            'name'        => ['required', 'string', 'max:255'],
            'address'     => ['nullable', 'string', 'max:255'],
            'phone'       => ['nullable', 'string', 'max:20'],
            'email'       => ['nullable', 'email', 'max:255'],
            'description' => ['nullable', 'string'],
            'is_active'   => ['sometimes', 'boolean'],
        ]);

        $request->validate([
            'district_id' => [
                function ($attribute, $value, $fail) use ($request) {
                    $belongsToProvince = District::where('id', $value)
                        ->where('province_id', $request->province_id)
                        ->exists();

                    if (! $belongsToProvince) {
                        $fail('The selected district does not belong to the selected province.');
                    }
                },
            ],
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        $hospital->update($validated);

        return redirect()
            ->route('admin.hospitals.index')
            ->with('status', 'Hospital updated successfully.');
    }

    public function destroy(Hospital $hospital): RedirectResponse
    {
        // Keep the doctors, but remove their hospital assignment.
        $hospital->doctors()->update([
            'hospital_id' => null,
        ]);

        $hospital->delete();

        return redirect()
            ->route('admin.hospitals.index')
            ->with('status', 'Hospital deleted successfully.');
    }

    public function doctors(Hospital $hospital): View
    {
        $doctors = $hospital->doctors()
            ->orderBy('name')
            ->get();

        return view('admin.hospitals.doctors', compact('hospital', 'doctors'));
    }
}
