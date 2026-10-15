<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\DoctorManageRequest;
use App\Models\Doctor;
use App\Models\Hospital;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class DoctorController extends Controller
{
    
public function index(): View
{
    $doctors = Doctor::with('hospital')
        ->withCount('appointments')
        ->orderBy('name')
        ->get();

    return view('admin.doctors.index', compact('doctors'));
}

    public function create()
{
    return view('admin.doctors.form', [
        'doctor' => new Doctor(['is_active' => true]),
        'hospitals' => Hospital::where('is_active', true)->orderBy('name')->get(),
    ]);
}
    public function store(DoctorManageRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['is_active'] = $request->boolean('is_active');

        if ($request->hasFile('image')) {
            // Stored under storage/app/public/doctors — only the relative
            // path (e.g. "doctors/xyz.jpg") is saved to the database.
            $data['image'] = $request->file('image')->store('doctors', 'public');
        }

        Doctor::create($data);

        return redirect()->route('admin.doctors.index')->with('status', 'Doctor profile created.');
    }

    public function edit(Doctor $doctor)
{
    return view('admin.doctors.form', [
        'doctor' => $doctor,
        'hospitals' => Hospital::where('is_active', true)->orderBy('name')->get(),
    ]);
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
        // A doctor with appointment history must not be hard-deleted: the
        // database cascades would erase every patient's appointment and rating.
        // Deactivate instead - the doctor disappears from the public site and
        // from booking, but the history is kept.
        if ($doctor->appointments()->exists()) {
            $doctor->update(['is_active' => false]);

            return redirect()
                ->route('admin.doctors.index')
                ->with('status', 'This doctor has appointment history, so the profile was deactivated instead of deleted.');
        }

        DB::transaction(function () use ($doctor) {
            if ($doctor->image) {
                Storage::disk('public')->delete($doctor->image);
            }

            $userId = $doctor->user_id;

            $doctor->delete();

            // Remove the doctor's login account too, if there is one.
            if ($userId) {
                User::whereKey($userId)->delete();
            }
        });

        return redirect()
            ->route('admin.doctors.index')
            ->with('status', 'Doctor removed successfully.');
    }
}
