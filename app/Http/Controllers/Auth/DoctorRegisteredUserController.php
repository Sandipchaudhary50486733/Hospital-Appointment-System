<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\DoctorRegisterRequest;
use App\Models\Doctor;
use App\Models\User;
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
        return view('auth.register-doctor');
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
                'name'            => $validated['name'],
                'specialization'  => $validated['specialization'],
                'license_number'  => $validated['license_number'],
                'email'           => $validated['email'],
            ]);

            return $user;
        });

        event(new Registered($user));

        Auth::login($user);

        return redirect()->route('doctor.appointments.index');
    }
}
