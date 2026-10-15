<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | PATIENT PROFILE
    |--------------------------------------------------------------------------
    */

    public function patient()
    {
        $user = auth()->user();

        return view('profile.patient', compact('user'));
    }


    /*
    |--------------------------------------------------------------------------
    | PATIENT EDIT PROFILE PAGE
    |--------------------------------------------------------------------------
    */

    public function editPatient()
    {
        $user = auth()->user();

        return view('profile.patient-edit', compact('user'));
    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE PATIENT PROFILE
    |--------------------------------------------------------------------------
    */

    public function updatePatient(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')
                    ->ignore($user->id),
            ],

            'phone' => [
                'nullable',
                'string',
                'max:20',
            ],

            'gender' => [
                'nullable',
                'string',
                'max:20',
            ],

            'date_of_birth' => [
                'nullable',
                'date',
            ],

            'image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | UPDATE PATIENT INFORMATION
        |--------------------------------------------------------------------------
        */

        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'gender' => $validated['gender'] ?? null,
            'date_of_birth' => $validated['date_of_birth'] ?? null,
        ]);


        /*
        |--------------------------------------------------------------------------
        | UPDATE PATIENT PROFILE IMAGE
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('image')) {

            // Delete previous image if it exists
            if (
                !empty($user->image) &&
                Storage::disk('public')->exists($user->image)
            ) {
                Storage::disk('public')->delete($user->image);
            }

            // Store new image
            $imagePath = $request
                ->file('image')
                ->store('profiles', 'public');

            // Save image path
            $user->update([
                'image' => $imagePath,
            ]);
        }


        return redirect()
            ->route('patient.profile')
            ->with(
                'status',
                'Your profile has been updated successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | DOCTOR PROFILE
    |--------------------------------------------------------------------------
    */

    public function doctor()
    {
        $user = auth()->user();

        $doctor = $user->doctor()
            ->with('hospital')
            ->first();

        abort_unless(
            $doctor,
            403,
            'No doctor profile is linked to this account.'
        );

        return view(
            'profile.doctor',
            compact('user', 'doctor')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | DOCTOR EDIT PROFILE PAGE
    |--------------------------------------------------------------------------
    */

    public function editDoctor()
    {
        $user = auth()->user();

        $doctor = $user->doctor()
            ->with('hospital')
            ->first();

        abort_unless(
            $doctor,
            403,
            'No doctor profile is linked to this account.'
        );

        return view(
            'profile.doctor-edit',
            compact('user', 'doctor')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE DOCTOR PROFILE
    |--------------------------------------------------------------------------
    */

    public function updateDoctor(Request $request)
    {
        $user = $request->user();

        /*
        |--------------------------------------------------------------------------
        | FIND DOCTOR PROFILE
        |--------------------------------------------------------------------------
        */

        $doctor = $user->doctor;

        abort_unless(
            $doctor,
            403,
            'No doctor profile is linked to this account.'
        );


        /*
        |--------------------------------------------------------------------------
        | VALIDATION
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')
                    ->ignore($user->id),
            ],

            'phone' => [
                'nullable',
                'string',
                'max:20',
            ],

            'specialization' => [
                'required',
                'string',
                'max:255',
            ],

            'license_number' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('doctors', 'license_number')
                    ->ignore($doctor->id),
            ],

            'bio' => [
                'nullable',
                'string',
            ],

            'image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | UPDATE USER TABLE
        |--------------------------------------------------------------------------
        */

        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
        ]);


        /*
        |--------------------------------------------------------------------------
        | UPDATE DOCTOR TABLE
        |--------------------------------------------------------------------------
        */

        $doctor->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'specialization' => $validated['specialization'],
            'license_number' => $validated['license_number'] ?? null,
            'bio' => $validated['bio'] ?? null,
        ]);


        /*
        |--------------------------------------------------------------------------
        | UPDATE DOCTOR PROFILE IMAGE
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('image')) {

            // Delete previous doctor image
            if (
                !empty($doctor->image) &&
                Storage::disk('public')->exists($doctor->image)
            ) {
                Storage::disk('public')->delete($doctor->image);
            }

            // Store new doctor image
            $imagePath = $request
                ->file('image')
                ->store('doctors', 'public');

            // Save new image path
            $doctor->update([
                'image' => $imagePath,
            ]);
        }


        return redirect()
            ->route('doctor.profile')
            ->with(
                'status',
                'Your doctor profile has been updated successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | PATIENT CHANGE PASSWORD PAGE
    |--------------------------------------------------------------------------
    */

    public function editPatientPassword()
    {
        return view('profile.patient-password');
    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE PATIENT PASSWORD
    |--------------------------------------------------------------------------
    */

    public function updatePatientPassword(Request $request)
    {

        $request->validate([
            'current_password' => [
                'required',
                'current_password',
            ],

            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
            ],
        ]);


        $request->user()->update([
            'password' => Hash::make($request->password),
        ]);


        return redirect()
            ->route('patient.profile')
            ->with(
                'status',
                'Your password has been changed successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | DOCTOR CHANGE PASSWORD PAGE
    |--------------------------------------------------------------------------
    */

    public function editDoctorPassword()
    {
        return view('profile.doctor-password');
    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE DOCTOR PASSWORD
    |--------------------------------------------------------------------------
    */

    public function updateDoctorPassword(Request $request)
    {
        $request->validate([
            'current_password' => [
                'required',
                'current_password',
            ],

            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
            ],
        ]);


        $request->user()->update([
            'password' => Hash::make($request->password),
        ]);


        return redirect()
            ->route('doctor.profile')
            ->with(
                'status',
                'Your password has been changed successfully.'
            );
    }
}

