<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\DoctorRegisterRequest;
use App\Models\Doctor;
use App\Models\User;
use App\Models\Hospital;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class DoctorRegisteredUserController extends Controller
{
    /**
     * Show the doctor registration form.
     */
    public function create(): View
    {
        $hospitals = Hospital::where('is_active', true)
            ->with(['province', 'district'])
            ->orderBy('name')
            ->get();
        return view('auth.register-doctor', compact('hospitals'));
    }

    /**
     * Handle an incoming doctor registration request.
     */
    public function store(DoctorRegisterRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $user = DB::transaction(function () use ($validated) {
            $user = User::create([
                'name'     => $validated['name'],
                'email'    => $validated['email'],
                'phone'    => $validated['phone'],
                'role'     => 'doctor',
                'password' => Hash::make($validated['password']),
            ]);

            Doctor::create([
                'user_id'         => $user->id,
                'hospital_id'     => $validated['hospital_id'],
                'name'            => $validated['name'],
                'specialization'  => $validated['specialization'],
                'license_number'  => $validated['license_number'],
                'email'           => $validated['email'],
                // New providers stay hidden from patients until an admin
                // reviews their credentials and activates the profile.
                'is_active'       => false,
            ]);

            return $user;
        });

        event(new Registered($user));

        Auth::login($user);

        return redirect()
            ->route('doctor.appointments.index')
            ->with('status', 'Welcome! Your account was created. Our admin team will review your credentials and activate your profile - then patients can book with you.');
    }
}
