<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class DoctorRegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'            => ['required', 'string', 'max:150'],
            'email'           => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'phone'           => ['required', 'string', 'max:20'],
            'specialization'  => ['required', 'string', 'max:100'],
            'license_number'  => ['required', 'string', 'max:50', 'unique:doctors,license_number'],
            // Must be an existing, active hospital (admins manage the list),
            // so registrations can no longer create stray hospital records.
            'hospital_id'     => ['required', Rule::exists('hospitals', 'id')->where('is_active', true)],
            'password'        => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()],
            'terms'           => ['accepted'],
        ];
    }

    public function messages(): array
    {
        return [
            'hospital_id.required' => 'Please select your hospital.',
            'hospital_id.exists'   => 'Please select a hospital from the list.',
            'terms.accepted' => 'You must confirm your credentials and agree to the provider terms.',
        ];
    }
}
