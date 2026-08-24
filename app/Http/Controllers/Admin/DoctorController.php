<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\DoctorManageRequest;
use App\Models\Doctor;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class DoctorController extends Controller
{
    public function index(): View
    {
        $doctors = Doctor::withCount('appointments')->orderBy('name')->get();

        return view('admin.doctors.index', compact('doctors'));
    }

    public function create(): View
    {
        return view('admin.doctors.form', ['doctor' => new Doctor()]);
    }

    public function store(DoctorManageRequest $request): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            // Stored under storage/app/public/doctors — only the relative
            // path (e.g. "doctors/xyz.jpg") is saved to the database.
            $data['image'] = $request->file('image')->store('doctors', 'public');
        }

        Doctor::create($data);

        return redirect()->route('admin.doctors.index')->with('status', 'Doctor profile created.');
    }

    public function edit(Doctor $doctor): View
    {
        return view('admin.doctors.form', compact('doctor'));
    }

    public function update(DoctorManageRequest $request, Doctor $doctor): RedirectResponse
    {
        $data = $request->validated();
        $data['is_active'] = $request->boolean('is_active');

        if ($request->hasFile('image')) {
            // Replace the old file (if any) so storage doesn't accumulate orphans.
            if ($doctor->image) {
                Storage::disk('public')->delete($doctor->image);
            }

            $data['image'] = $request->file('image')->store('doctors', 'public');
        }

        $doctor->update($data);

        return redirect()->route('admin.doctors.index')->with('status', 'Doctor profile updated.');
    }

    public function destroy(Doctor $doctor): RedirectResponse
    {
        abort_if($doctor->user_id !== null, 422, 'Cannot delete a doctor with an active login account. Deactivate instead.');

        if ($doctor->image) {
            Storage::disk('public')->delete($doctor->image);
        }

        $doctor->delete();

        return redirect()->route('admin.doctors.index')->with('status', 'Doctor profile removed.');
    }
}
